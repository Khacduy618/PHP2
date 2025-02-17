<?php

class ProductModel extends Model{
    
    protected $_table = 'products';
    
    public function store($data) {
        $f = "";
        $v = "";
        foreach ($data as $key => $value) {
            $f .= $key . ",";
            $v .= "'" . $value . "',";
        }
        $f = trim($f, ",");
        $v = trim($v, ",");
        $query = "INSERT INTO $this->_table($f) VALUES ($v);";

        return $this->pdo_execute($query);
    }

    public function getProductLists($isAdmin = false) {
        if($isAdmin == true){
            $sql = "SELECT p.product_id, p.product_img, p.product_name, p.product_price, p.product_discount, p.product_count, p.product_status";
        }else{
            $sql = "SELECT p.*";
        }
       $sql .= " , c.category_name FROM $this->_table p LEFT JOIN categories c ON p.product_cat = c.category_id WHERE 1";
        return $this->pdo_query_all($sql);
    }

    
    public function getDetail($id){
        $sql = "SELECT screen_cam, os, gpu, cpu, pin, colors, sizes, ram, rom, bluetooth FROM $this->_table WHERE product_id = ?";
        return $this->pdo_query_one($sql, [$id]);
    }

    public function findbyId($id) {
        $sql = "SELECT * FROM $this->_table WHERE product_id = ?";
        return $this->pdo_query_one($sql, [$id]);
    }

    public function update($data, $id) {
        if (!empty($data)) {
            $fields = "";
            foreach ($data as $key => $value) {
                $fields .= "$key = '$value',";
            }
            $fields = trim($fields, ",");
            $sql = "UPDATE $this->_table SET $fields WHERE product_id = ?";
            return $this->pdo_execute($sql, $id);
        }
    }

    public function delete($id){
        $sql = "UPDATE $this->_table SET product_status = 0 WHERE product_id = ?";
        return $this->pdo_execute($sql, $id);
    }
}
