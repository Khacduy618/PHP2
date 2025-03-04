<?php
namespace App\Models;

use Core\Model;
class ProductModel extends Model{
    
    protected $table ;
    protected $status ;
    protected $contents ; 

    public function __construct(){
        parent::__construct();
        $this->table = "products";
        $this->status = "product_status";
        $this->contents = "product_id";
    }

   
    public function getProductLists($keyword = "", $product_cat = 0, $sort = "popularity", $page = 1, $item_per_page = 12) {
        $offset = ($page - 1) * $item_per_page;
        
        $sql = "SELECT p.*, 
                       c.category_name, 
                       c.category_id,
                       COALESCE((SELECT COUNT(*) FROM reviews r WHERE r.pro_id = p.product_id), 0) as review_count,
                       COALESCE((SELECT AVG(helpful) FROM reviews r WHERE r.pro_id = p.product_id), 0) as avg_rating,
                       COALESCE((SELECT SUM(bd.pro_count) 
                                FROM bill_details bd 
                                WHERE bd.pro_id = p.product_id), 0) as total_sold
                FROM $this->table p 
                LEFT JOIN categories c ON c.category_id = p.product_cat
                WHERE 1";
        
        $params = [];
        if(!isset($_SESSION['isLogin_Admin'])){
            $sql.= " AND p.product_status = 1";
        }
        // Handle category filter
        if($product_cat > 0){
            $sql .= " AND p.product_cat = ?";
            $params[] = $product_cat;
        }

        // Handle search
        if(!empty($keyword) && $keyword !== '/' && $keyword !== ''){
            $sql .= " AND (p.product_name LIKE ? OR c.category_name LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        // Add sorting conditions
        switch(strtolower($sort)) {
            case 'active':
                $sql .= " AND p.product_status = 1";
                break;
            case 'inactive':
                $sql .= " AND p.product_status = 0";
                break;
            case 'price-high':
                $sql .= " ORDER BY p.product_price DESC";
                break;
            case 'price-low':
                $sql .= " ORDER BY p.product_price ASC";
                break;
            case 'rating':
                $sql .= " ORDER BY avg_rating DESC, review_count DESC";
                break;
            case 'date':
                $sql .= " ORDER BY p.created_at DESC";
                break;
            case 'popularity':
            default:
                $sql .= " ORDER BY total_sold DESC";
        }
        
        if($item_per_page > 0) {
            $sql .= " LIMIT " . (int)$item_per_page;
        }
        
        if($offset > 0) {
            $sql.= " OFFSET ". (int)$offset;
        }
        
        return $this->pdo_query_all($sql, $params);
    }

    public function getTotalProducts($keyword = "", $product_cat = 0) {
        $sql = "SELECT COUNT(*) as total 
                FROM $this->table p
                LEFT JOIN categories c ON c.category_id = p.product_cat
                WHERE 1";
                
        
        $params = [];
        

        if(!isset($_SESSION['isLogin_Admin'])){
            $sql.= " AND p.product_status = 1";
        }

        
        if($product_cat > 0){
            $sql .= " AND p.product_cat = ?";
            $params[] = $product_cat;
        }

        if(!empty($keyword) && $keyword !== '/'){
            $sql .= " AND (p.product_name LIKE ? OR c.category_name LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        $result = $this->pdo_query_one($sql, $params);
        return $result['total'];
    }

    public function getDetail($id){
        $sql = "SELECT screen_cam, os, gpu, cpu, pin, colors, sizes, ram, rom, bluetooth FROM $this->table WHERE $this->contents = ?";
        return $this->pdo_query_one($sql, [$id]);
    }

    public function related_product($cat, $id) {
        $sql = "SELECT p.product_id, p.product_name, p.product_price, p.product_img, p.product_status, p.product_count, p.product_discount, p.product_cat, c.category_name, p.created_at
                FROM products p
                JOIN categories c ON p.product_cat = c.category_id 
                WHERE product_cat = ? AND product_id != ?";
        return $this->pdo_query_all($sql, [$cat, $id]);
    }
}
