<?php
class UserModel extends Model
{
    protected $table = 'user';

    public function getListUser()
    {
        $sql = "SELECT u.*, a.address_name, a.address_city, a.address_street
              FROM {$this->table} u
              LEFT JOIN address a ON u.user_email = a.address_userEmail
              Order by user_role asc";
        return $this->pdo_query_all($sql);
    }
    
    public function store($data) {
        $f = "";
        $v = "";
        foreach ($data as $key => $value) {
            $f .= $key . ",";
            $v .= "'" . $value . "',";
        }
        $f = trim($f, ",");
        $v = trim($v, ",");
        $query = "INSERT INTO $this->table($f) VALUES ($v);";

        return $this->pdo_execute($query);
    }

    public function findbyId($id) {
        $sql = "SELECT * FROM $this->table WHERE user_email = ?";
        return $this->pdo_query_one($sql, [$id]);
    }

    public function update($data, $id) {
        if (!empty($data)) {
            $fields = "";
            foreach ($data as $key => $value) {
                $fields .= "$key = '$value',";
            }
            $fields = trim($fields, ",");
            $sql = "UPDATE $this->table SET $fields WHERE user_email = ?";
            return $this->pdo_execute($sql, $id);
        }
    }

    public function delete($id){
        $sql = "UPDATE $this->table SET user_status = 0 WHERE user_email = ?";
        return $this->pdo_execute($sql, $id);
    }
}
