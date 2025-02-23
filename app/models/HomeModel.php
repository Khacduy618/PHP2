<?php
class HomeModel extends Model{
    protected $table = 'products';
    public function getFeaturedProducts($limit = 0) {
        $sql = "SELECT p.product_id, p.product_name, p.product_img, 
                       p.product_price, p.product_discount, c.category_name,
                        (SELECT review_category 
                        FROM reviews r2 
                        WHERE r2.pro_id = p.product_id 
                        GROUP BY review_category 
                        ORDER BY COUNT(*) DESC 
                        LIMIT 1) as most_common_rating,
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
                        (SELECT review_category 
                        FROM reviews r2 
                        WHERE r2.pro_id = p.product_id 
                        GROUP BY review_category 
                        ORDER BY COUNT(*) DESC 
                        LIMIT 1) as most_common_rating,
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
                        (SELECT review_category 
                        FROM reviews r2 
                        WHERE r2.pro_id = p.product_id 
                        GROUP BY review_category 
                        ORDER BY COUNT(*) DESC 
                        LIMIT 1) as most_common_rating,
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
                ORDER BY review_count DESC 
                LIMIT $limit";
        return $this->pdo_query_all($sql);
    }

    public function getTopSellingAndOnSaleProducts($limit = 0) {
        $sql = "SELECT p.product_id, p.product_name, p.product_img, 
                   p.product_price, p.product_discount, c.category_name,
                   (SELECT review_category 
                        FROM reviews r2 
                        WHERE r2.pro_id = p.product_id 
                        GROUP BY review_category 
                        ORDER BY COUNT(*) DESC 
                        LIMIT 1) as most_common_rating,
                    COUNT(DISTINCT r.review_id) as review_count,
                    COUNT(DISTINCT rv.id) as vote_count,
                    COALESCE(SUM(bd.pro_count), 0) as total_sold
            FROM $this->table p 
            LEFT JOIN categories c ON p.product_cat = c.category_id
            LEFT JOIN reviews r ON p.product_id = r.pro_id
            LEFT JOIN review_votes rv ON r.review_id = rv.review_id
            LEFT JOIN bill_details bd ON p.product_id = bd.pro_id
            LEFT JOIN bills b ON bd.bill_id = b.bill_id
            WHERE p.product_status = 1 
            GROUP BY p.product_id, p.product_name, p.product_img, 
                     p.product_price, p.product_discount, c.category_name
            HAVING total_sold > 0 OR p.product_discount > 0
            ORDER BY total_sold DESC, p.product_discount DESC
            LIMIT $limit";
        return $this->pdo_query_all($sql);
    }
}