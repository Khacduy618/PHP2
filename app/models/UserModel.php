<?php
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

    public function getListUser()
    {
        $sql = "SELECT u.*, a.address_name, a.address_city, a.address_street
              FROM {$this->table} u
              LEFT JOIN address a ON u.user_email = a.address_userEmail
              Order by user_role asc";
        return $this->pdo_query_all($sql);
    }
    
    
}
