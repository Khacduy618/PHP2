<?php
class HomeModel extends Model{
    protected $table = 'products';
    public function getFeaturedProducts($limit = 0) {
        $sql = "SELECT p.product_id, p.product_name, p.product_img, 
                       p.product_price, p.product_discount, c.category_name,
                COALESCE(AVG(r.review_content), 0) as average_rating,
                COUNT(DISTINCT r.review_id) as review_count,
                COUNT(DISTINCT f.favorite_id) as favorite_count
                FROM $this->table p 
                LEFT JOIN categories c ON p.product_cat = c.category_id 
                LEFT JOIN reviews r ON p.product_id = r.pro_id
                LEFT JOIN favorites f ON p.product_id = f.favorite_proid
                WHERE p.product_status = 1 
                GROUP BY p.product_id, p.product_name, p.product_img, 
                         p.product_price, p.product_discount, c.category_name
                ORDER BY favorite_count DESC, p.product_id DESC 
                LIMIT $limit";
        return $this->pdo_query_all($sql);
    }

    public function getOnSaleProducts($limit = 0) {
        $sql = "SELECT p.product_id, p.product_name, p.product_img, 
                       p.product_price, p.product_discount, c.category_name,
                COALESCE(AVG(r.review_content), 0) as average_rating,
                COUNT(DISTINCT r.review_id) as review_count,
                COUNT(DISTINCT f.favorite_id) as favorite_count
                FROM $this->table p 
                LEFT JOIN categories c ON p.product_cat = c.category_id 
                LEFT JOIN reviews r ON p.product_id = r.pro_id
                LEFT JOIN favorites f ON p.product_id = f.favorite_proid
                WHERE p.product_discount > 0 AND p.product_status = 1
                GROUP BY p.product_id, p.product_name, p.product_img, 
                         p.product_price, p.product_discount, c.category_name
                ORDER BY p.product_discount DESC, favorite_count DESC 
                LIMIT $limit";
        return $this->pdo_query_all($sql);
    }

    public function getTopRatedProducts($limit = 0) {
        $sql = "SELECT p.product_id, p.product_name, p.product_img, 
                       p.product_price, p.product_discount, c.category_name,
                COALESCE(AVG(r.review_content), 0) as average_rating,
                COUNT(DISTINCT r.review_id) as review_count,
                COUNT(DISTINCT rv.id) as vote_count,
                COUNT(DISTINCT f.favorite_id) as favorite_count
                FROM $this->table p 
                LEFT JOIN categories c ON p.product_cat = c.category_id 
                LEFT JOIN reviews r ON p.product_id = r.pro_id
                LEFT JOIN review_votes rv ON r.review_id = rv.review_id
                LEFT JOIN favorites f ON p.product_id = f.favorite_proid
                WHERE p.product_status = 1
                GROUP BY p.product_id, p.product_name, p.product_img, 
                         p.product_price, p.product_discount, c.category_name
                HAVING review_count > 0
                ORDER BY average_rating DESC, vote_count DESC, favorite_count DESC 
                LIMIT $limit";
        return $this->pdo_query_all($sql);
    }
}