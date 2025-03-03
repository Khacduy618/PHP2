<?php
namespace App\Models;

use Core\Model;
class UserModel extends Model
{
    protected $table ;
    protected $status ;
    protected $contents ;

    public function __construct(){
        parent::__construct();
        $this->table = "user";
        $this->status = "user_status";
        $this->contents = "user_email";
    }

    public function getListUser($keyword = "", $status = "", $page = 1, $item_per_page = 12)
    {
        $offset = ($page - 1) * $item_per_page;
        
        $sql = "SELECT DISTINCT u.*, 
                (SELECT a.address_name FROM address a 
                 WHERE a.address_userEmail = u.user_email 
                 AND a.address_status = 0 
                 LIMIT 1) as address_name,
                (SELECT a.address_city FROM address a 
                 WHERE a.address_userEmail = u.user_email 
                 AND a.address_status = 0 
                 LIMIT 1) as address_city,
                (SELECT a.address_street FROM address a 
                 WHERE a.address_userEmail = u.user_email 
                 AND a.address_status = 0 
                 LIMIT 1) as address_street
                FROM {$this->table} u
                WHERE 1";
        
        $params = [];

        // Handle search
        if(!empty($keyword) && $keyword !== '/' && $keyword !== '') {
            $sql .= " AND (u.user_name LIKE ? OR u.user_email LIKE ? OR u.user_phone LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        // Handle status filter
        if($status !== '') {
            $sql .= " AND u.user_status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY u.user_role ASC";
        if($item_per_page > 0) {
            $sql .= " LIMIT " . (int)$item_per_page;
        }
        
        if($offset > 0) {
            $sql.= " OFFSET ". (int)$offset;
        }

        return $this->pdo_query_all($sql, $params);
    }

    public function getTotalUsers($keyword = "", $status = "") {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_role = 0";
        $params = [];

        if(!empty($keyword) && $keyword !== '/') {
            $sql .= " AND (user_name LIKE ? OR user_email LIKE ? OR user_phone LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }

        if($status !== '') {
            $sql .= " AND user_status = ?";
            $params[] = $status;
        }

        $result = $this->pdo_query_one($sql, $params);
        return $result['total'];
    }
}
