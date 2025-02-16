<?php
class CategoryModel extends Model
{
    protected $_table = 'categories';
    
    public function getCategoryLists() {
       $sql = "SELECT * FROM $this->_table WHERE 1";
       return $this->pdo_query_all($sql);
    }

    public function getDetail($id) {
        $sql = "SELECT * FROM $this->_table WHERE category_id = ?";
        return $this->pdo_query_one($sql, [$id]);
    }
}

?>