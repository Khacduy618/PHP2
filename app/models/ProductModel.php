<?php

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

    public function getProductLists($isAdmin = false) {
        if($isAdmin == true){
            $sql = "SELECT p.product_id, p.product_img, p.product_name, p.product_price, p.product_discount, p.product_count, p.product_status";
        }else{
            $sql = "SELECT p.*";
        }
       $sql .= " , c.category_name FROM $this->table p LEFT JOIN categories c ON p.product_cat = c.category_id WHERE 1";
        return $this->pdo_query_all($sql);
    }

    
    public function getDetail($id){
        $sql = "SELECT screen_cam, os, gpu, cpu, pin, colors, sizes, ram, rom, bluetooth FROM $this->table WHERE $this->contents = ?";
        return $this->pdo_query_one($sql, [$id]);
    }

    
}
