<?php

class ProductModel extends Model{
    
    protected $_table = 'products';
    
    public function getProductLists() {
       $sql = "SELECT * FROM $this->_table WHERE 1";
       return $this->pdo_query_all($sql);
    }

    public function getDetail($id) {
        $sql = "SELECT * FROM $this->_table WHERE product_id = ?";
        return $this->pdo_query_one($sql, [$id]);
    }
}
