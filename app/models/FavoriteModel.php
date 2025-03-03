<?php
namespace App\Models;

use Core\Model;

class FavoriteModel extends Model
{
    protected $table;
    protected $status;
    protected $contents;

    public function __construct(){
        parent::__construct();
        $this->table = "favorites";
        $this->status = "";
        $this->contents = "favorite_id";
    }

    function findByUser($userEmail){
        $sql = "SELECT f.favorite_id, f.favorite_userEmail, f.favorite_proid,
                p.product_id, p.product_name, p.product_price, p.product_img, p.product_count 
                FROM $this->table f 
                JOIN products p ON f.favorite_proid = p.product_id 
                WHERE f.favorite_userEmail = ?";
        return $this->pdo_query($sql,[$userEmail]);
    }

    function findByUserAndProduct($userEmail, $productId){
        $sql = "SELECT * FROM $this->table 
                WHERE favorite_userEmail = ? AND favorite_proid = ?";
        return $this->pdo_query_one($sql, [$userEmail, $productId]);
    }

    function getRecentFavorites($userEmail, $limit = 3) {
        $sql = "SELECT f.*, p.* FROM $this->table f 
                JOIN products p ON f.favorite_proid = p.product_id 
                WHERE f.favorite_userEmail = ? 
                ORDER BY f.$this->contents DESC 
                LIMIT $limit";
        return $this->pdo_query($sql, [$userEmail]);
    }

    function countFavorites($userEmail) {
        $sql = "SELECT COUNT(*) as total FROM $this->table WHERE favorite_userEmail = ?";
        $result = $this->pdo_query_one($sql, [$userEmail]);
        return $result['total'];
    }

    function isProductFavorited($userEmail, $productId) {
        $sql = "SELECT COUNT(*) as count FROM $this->table 
                WHERE favorite_userEmail = ? AND favorite_proid = ?";
        $result = $this->pdo_query_one($sql, [$userEmail, $productId]);
        return $result['count'] > 0;
    }
}
