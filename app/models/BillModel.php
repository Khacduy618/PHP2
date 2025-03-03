<?php
namespace App\Models;

use Core\Model;
class BillModel extends Model
{
    protected $table ;
    protected $status ;
    protected $contents ;

    public function __construct(){
        parent::__construct();
        $this->table = "bills";
        $this->status = "bill_status";
        $this->contents = "bill_id";
    }

    public function getBillByUserEmail($bill_userEmail) {
        if (!$bill_userEmail) {
            throw new Exception("Email không hợp lệ");
        }
        
        $sql = "SELECT b.*, 
                       CASE 
                           WHEN b.$this->status IN (1,2,3) THEN 'Pending'
                           WHEN b.$this->status = 4 THEN 'Approved'
                           WHEN b.$this->status = 5 THEN 'Delivering'
                           WHEN b.$this->status = 6 THEN 'Delivered'
                           WHEN b.$this->status = 7 THEN 'Completed'
                           WHEN b.$this->status = 8 THEN 'Cancelled'
                       END as status_name
                FROM $this->table b 
                WHERE b.bill_userEmail = ?
                ORDER BY b.bill_time DESC";
        $result = $this->pdo_query_all($sql, [$bill_userEmail]);
        
        if (empty($result)) {
            return []; 
        }
        
        return $result;
    }
    public function getBillDetailsByIdBill($id_bill) {
        if (!$id_bill) {
            throw new Exception("ID hóa đơn không hợp lệ");
        }

        $sql = "SELECT bd.*, p.product_name, p.product_img, 
                       (bd.pro_price * bd.pro_count) as total_price
                FROM bill_details bd 
                LEFT JOIN products p ON bd.pro_id = p.product_id 
                WHERE bd.id_bill = ?";
        $result = $this->pdo_query_all($sql, [$id_bill]);
        
        if (empty($result)) {
            return []; 
        }
        
        return $result;
    }

    public function updateBillStatus($bill_userEmail, $status) {
        if (!$bill_userEmail) {
            throw new Exception("Email không hợp lệ");
        }

        if (!in_array($status, [1, 2, 3, 4, 5, 6, 7, 8])) { // Giả sử các trạng thái hợp lệ là 1-5
            throw new Exception("Trạng thái đơn hàng không hợp lệ");
        }

        $sql = "UPDATE bills SET bill_status = ? WHERE bill_userEmail = ?";
            $result = $this->pdo_execute($sql, $status, $bill_userEmail);
        
        if (!$result) {
            throw new Exception("Không thể cập nhật trạng thái đơn hàng");
        }
        
        return true;
    }

    public function bill_insert_id($data) {
        try {
            if (empty($data['bill_coupon'])) {
                $data['bill_coupon'] = NULL; // Đặt là NULL nếu không có mã giảm giá
            }
            // Tạo câu SQL với các placeholder
            $sql = "INSERT INTO bills (bill_var_id, bill_userEmail, bill_phone, bill_address, 
                                     bill_priceDelivery, bill_price, bill_totalPrice, 
                                     bill_coupon, bill_payment, bill_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Tạo mảng params theo thứ tự của các fields
            $params = [
                $data['bill_var_id'],
                $data['bill_userEmail'],
                $data['bill_phone'],
                $data['bill_address'],
                $data['bill_priceDelivery'],
                $data['bill_price'],
                $data['bill_totalPrice'],
                $data['bill_coupon'],
                $data['bill_payment'],
                $data['bill_status']
            ];

            // Thực hiện insert và lấy ID
            return $this->pdo_execute_id($sql, $params);

        } catch (Exception $e) {
            error_log("Error in bill_insert_id: " . $e->getMessage());
            throw new Exception("Không thể tạo hóa đơn mới: " . $e->getMessage());
        }
    }

    public function insert_bill_detail($values_string) {
        if (empty($values_string)) {
            throw new Exception("Không có dữ liệu chi tiết đơn hàng");
        }

        try {
            $this->db->beginTransaction();

            preg_match_all('/\((\d+),\s*\'([^\']+)\',\s*(\d+),\s*(\d+),\s*(\d+)\)/', $values_string, $matches);
            
            if (empty($matches[0])) {
                throw new Exception("Không thể phân tích dữ liệu đơn hàng");
            }

            $sql = "INSERT INTO bill_details (id_bill, bill_id, pro_id, pro_price, pro_count) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            for ($i = 0; $i < count($matches[1]); $i++) {
                $params = [
                    $matches[1][$i], 
                    $matches[2][$i], 
                    $matches[3][$i],
                    $matches[4][$i], 
                    $matches[5][$i]   
                ];
                $stmt->execute($params);
                
                $pro_id = $matches[3][$i];
                $quantity = $matches[5][$i];
                
                $update_sql = "UPDATE products SET product_count = product_count - ? WHERE product_id = ?";
                $this->pdo_execute($update_sql, [$quantity, $pro_id]);
                
                error_log("Updating product $pro_id with quantity $quantity");
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            // Rollback nếu có lỗi
            $this->db->rollBack();
            error_log("Error in insert_bill_detail: " . $e->getMessage());
            throw new Exception("Lỗi khi xử lý đơn hàng: " . $e->getMessage());
        }
    }

    // Thêm hàm kiểm tra số lượng sản phẩm trước khi đặt hàng
    public function checkProductQuantity($pro_id, $quantity) {
        $sql = "SELECT product_count FROM products WHERE product_id = ?";
        $result = $this->pdo_query_one($sql, [$pro_id]);
        
        if ($result) {
            return $result['product_count'] >= $quantity;
        }else{ throw new Exception("Không tìm thấy sản phẩm");}
       
       
    }
 
    public function getAll(): array
    {
        $query = "SELECT b.bill_id, 
                         b.bill_var_id,
                         b.bill_userEmail,  
                         b.bill_totalPrice AS total_price, 
                         b.bill_status, 
                         b.bill_time
                  FROM bills b
                  LEFT JOIN user u ON b.bill_userEmail = u.user_email
                  WHERE b.bill_status != 8
                  ORDER BY b.bill_time DESC";

        return $this->pdo_query_all($query);
    }

    public function details($id)
    {
        $query = "SELECT b.bill_id, b.bill_var_id, b.bill_userEmail, u.user_name, u.user_phone ,
                     b.bill_totalPrice as total_price, b.bill_price, b.bill_priceDelivery, 
                     IFNULL(c.coupon_name, 'Không có') as coupon_name, b.bill_status, 
                     b.bill_time, b.bill_address,
                     a.*,
                     GROUP_CONCAT(p.product_name SEPARATOR ', ') as products, 
                     GROUP_CONCAT(bd.pro_count SEPARATOR ', ') as quantities, 
                     GROUP_CONCAT(bd.pro_price SEPARATOR ', ') as prices 
              FROM bills b 
              LEFT JOIN user u ON b.bill_userEmail = u.user_email 
              LEFT JOIN bill_details bd ON b.bill_id = bd.id_bill 
              LEFT JOIN products p ON bd.pro_id = p.product_id 
              LEFT JOIN coupons c ON b.bill_coupon = c.coupon_id 
              LEFT JOIN address a ON b.bill_address = a.address_id
              WHERE b.bill_id = ? 
              GROUP BY b.bill_id";

        return $this->pdo_query_one($query, $id);
    }

    public function getArchivedBills()
    {
        $query = "SELECT b.bill_id, 
                         b.bill_var_id,
                         b.bill_userEmail, 
                         b.bill_totalPrice AS total_price, 
                         b.bill_time
                  FROM bills b
                  LEFT JOIN user u ON b.bill_userEmail = u.user_email
                  WHERE b.bill_status = 8
                  ORDER BY b.bill_time DESC";
        return $this->pdo_query_all($query);
    }

    public function softDelete($id)
    {
        $query = "UPDATE $this->table SET deleted = 1 WHERE bill_id = ?";
        $this->pdo_execute($query, $id);
    }

    public function updateStatus($id, $newStatus)
    {
        $query = "UPDATE $this->table SET bill_status = ? WHERE bill_id = ?";
        $this->pdo_execute($query, [$newStatus, $id]);
    }

    public function updateStatus_ajax($id, $newStatus)
    {
        $query = "UPDATE $this->table SET bill_status = $newStatus WHERE bill_id = ?";
        $this->pdo_execute($query, $id);
    }
}
