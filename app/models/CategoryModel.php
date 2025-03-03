<?php
namespace App\Models;

use Core\Model;
class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $status ;
    protected $contents ;

    public function __construct(){
        parent::__construct();
        $this->status = "category_status";
        $this->contents = "category_id";
    }
    public function getCategoryLists($search = '', $status = 'all') {
        $sql = "SELECT c.*, pc.category_name as parent_name 
                FROM {$this->table} c 
                LEFT JOIN {$this->table} pc ON c.parent_id = pc.category_id 
                WHERE 1=1";
        $params = [];
        
        // Add search condition
        if (!empty($search) && $search !== '') {
            $sql .= " AND (c.category_name LIKE ? OR c.category_desc LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        // Add status filter
        if ($status === 'active') {
            $sql .= " AND c.category_status = 1";
        } elseif ($status === 'inactive') {
            $sql .= " AND c.category_status = 0";
        }
        
        $sql .= " ORDER BY c.category_id DESC";
        
        return $this->pdo_query_all($sql, $params);
    }

    public function getCatFill() {
            $sql = "SELECT * FROM $this->table WHERE $this->status = 1 AND parent_id  IS NOT NULL ORDER BY $this->contents ASC";
            return $this->pdo_query_all($sql);
    }

    public function getDetail($id) {
        $sql = "SELECT * FROM $this->table WHERE $this->contents = ?";
        return $this->pdo_query_one($sql, [$id]);
    }

    public function list() {
        $sql = "SELECT c.*,
                (SELECT COUNT(*) FROM products WHERE product_cat = c.$this->contents AND product_status = 1) as product_count
                FROM $this->table c WHERE c.$this->status = 1
                ORDER BY 
                    IF(c.parent_id = 0, c.$this->contents, c.parent_id),
                    c.$this->contents";
        return $this->pdo_query_all($sql);
    }
    
}

?>