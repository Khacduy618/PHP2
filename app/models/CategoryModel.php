<?php
class CategoryModel extends Model
{
    protected $table ;
    protected $status ;
    protected $contents ;

    public function __construct(){
        parent::__construct();
        $this->table = "categories";
        $this->status = "category_status";
        $this->contents = "category_id";
    }
    public function getCategoryLists() {
       $sql = "SELECT * FROM $this->table WHERE 1";
       return $this->pdo_query_all($sql);
    }

    public function getDetail($id) {
        $sql = "SELECT * FROM $this->table WHERE $this->contents = ?";
        return $this->pdo_query_one($sql, [$id]);
    }

    
}

?>