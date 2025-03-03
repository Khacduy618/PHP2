<?php
namespace App\Models;

use Core\Model;

class CartModel extends Model
{

        
    public function getCartItems($userEmail,$limit = 0) {
        $sql = "SELECT ci.*, p.product_name, p.product_price, p.product_img, p.product_discount 
                FROM cart_item ci
                JOIN cart c ON ci.cart_id = c.cart_id
                JOIN products p ON ci.pro_id = p.product_id
                WHERE c.cart_userEmail = ?";
        if ($limit > 0) {
            $sql.= " LIMIT ". $limit;
        }
        return $this->pdo_query_all($sql, [$userEmail]);
    }

    public function addToCart($userEmail, $productId, $quantity)
   {
    // Kiểm tra xem người dùng đã có cart chưa
    $checkCartSql = "SELECT cart_id FROM cart WHERE cart_userEmail = ?";
    $cart = $this->pdo_query_one($checkCartSql, [$userEmail]);

    if (!$cart) {
        $createCartSql = "INSERT INTO cart (cart_userEmail) VALUES (?)";
        $this->pdo_execute($createCartSql, $userEmail);

        $cart = $this->pdo_query_one($checkCartSql, [$userEmail]);
    }

    $cartId = $cart['cart_id'];

    // Kiểm tra sản phẩm
    $checkProductSql = "SELECT * FROM cart_item WHERE cart_id = ? AND pro_id = ?";
    $existingItem = $this->pdo_query_one($checkProductSql, [$cartId, $productId]);

    if ($existingItem) {
        // Debug
        var_dump("Updating quantity:", $quantity, $cartId, $productId);

        $updateQuantitySql = "UPDATE cart_item SET quantity = quantity + ? WHERE cart_id = ? AND pro_id = ?";
        $updated = $this->pdo_execute($updateQuantitySql, [$quantity, $cartId, $productId]);

        if (!$updated) {
            throw new Exception("Update quantity failed!");
        }

        return $updated;
        } else {
            $insertProductSql = "INSERT INTO cart_item (cart_id, pro_id, quantity) VALUES (?, ?, ?)";
            $inserted = $this->pdo_execute($insertProductSql, [$cartId, $productId, $quantity]);

            if (!$inserted) {
                throw new Exception("Insert product failed!");
            }

            return $inserted;
        }
    }
    
     public function updateQuantity($userEmail, $productId, $quantity)
    {
        $sql = "UPDATE cart_item 
                SET quantity = ? 
                WHERE cart_id = (SELECT cart_id FROM cart WHERE cart_userEmail = ?) 
                AND pro_id = ?";
         $this->pdo_execute($sql, [$quantity, $userEmail, $productId]);
    }

    public function removeFromCart($userEmail, $productId)
    {
        $sql = "DELETE FROM cart_item WHERE cart_id = (SELECT cart_id FROM cart WHERE cart_userEmail = ?) AND pro_id = ?";
        $this->pdo_execute($sql, [$userEmail, $productId]);
    }

    public function clearCart($userEmail)
    {
        $sql = "DELETE FROM cart_item WHERE cart_id = (SELECT cart_id FROM cart WHERE cart_userEmail = ?)";
        $this->pdo_execute($sql, $userEmail);
    }
    public function coupon($name) {
        try {
            $sql = "SELECT * FROM coupons WHERE coupon_name = ? AND coupon_count > 0 AND coupon_expiredate >= NOW()";
            $result = $this->pdo_query_one($sql, [$name]);
            
            // Add debug
            error_log('Coupon query result: ' . print_r($result, true));
            
            return $result;
        } catch (Exception $e) {
            error_log('Error in coupon method: ' . $e->getMessage());
            return null;
        }
    }
    
       public function coupon_update( $coupon_id){
          $sql = "UPDATE coupons SET coupon_count = coupon_count - 1 WHERE coupon_id =?";
          return $this->pdo_execute($sql, [$coupon_id]);
       }   

    public function deleteCartItemById($cartItemId) {
        $sql = "DELETE FROM cart_item WHERE cart_item_id = ?";
        return $this->pdo_execute($sql, $cartItemId);
    }

    public function deleteSelectedCartItems($userEmail, $selectedItemIds) {
        // Kiểm tra nếu $selectedItemIds không phải là mảng hoặc rỗng
        if (!is_array($selectedItemIds) || empty($selectedItemIds)) {
            return false;
        }

        try {
            // Tạo mảng params với userEmail là phần tử đầu tiên
            $params = array_merge([$userEmail], $selectedItemIds);
            
            // Tạo placeholders cho IN clause
            $placeholders = str_repeat('?,', count($selectedItemIds) - 1) . '?';
            
            $sql = "DELETE FROM cart_item 
                    WHERE cart_id = (SELECT cart_id FROM cart WHERE cart_userEmail = ?) 
                    AND cart_item_id IN ($placeholders)";
            
            // Debug
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            
            return $this->pdo_execute($sql, $params);
            
        } catch (Exception $e) {
            error_log("Error in deleteSelectedCartItems: " . $e->getMessage());
            throw new Exception("Không thể xóa các mục đã chọn: " . $e->getMessage());
        }
    }

    public function coupon_by_id($coupon_id) {
        $sql = "SELECT * FROM coupons WHERE coupon_id = ?";
        return $this->pdo_query_one($sql, [$coupon_id]);
    }
}
