<?php
namespace Core;

use PDO;
use PDOException;

class Model extends Database{
    protected $db;
    protected $table;
    protected $status;
    protected $contents;
    public function __construct() {
        parent::__construct();
        $this->db = $this->getConnection();
    }

    protected function pdo_query($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $params = is_array($params) ? $params : [$params];
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            error_log('Query execution failed: ' . $e->getMessage());
            file_put_contents('db_errors.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            throw $e;
        }
    }

    protected function pdo_query_all($sql, $params = array()) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            // Debug PDO errors if any
            if ($stmt->errorCode() !== '00000') {
                echo "<pre>";
                echo "PDO Error Info:\n";
                print_r($stmt->errorInfo());
                echo "</pre>";
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "<pre>";
            echo "PDO Exception:\n";
            echo $e->getMessage();
            echo "</pre>";
            return array();
        }
    }

    protected function pdo_query_one($sql, $params = []) {
        try {
            $stmt = $this->pdo_query($sql, $params);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    protected function pdo_query_value($sql, $params = []) {
        try {
            $stmt = $this->pdo_query($sql, $params);
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    protected function pdo_execute_id($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $params = is_array($params) ? $params : [$params];
            $stmt->execute($params);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log('Query execution failed: ' . $e->getMessage());
            file_put_contents('db_errors.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            throw $e;
        }
    }

    protected function pdo_execute($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $params = is_array($params) ? $params : [$params];
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            throw $e;
        }
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
        $sql = "SELECT * FROM $this->table WHERE $this->contents = ?";
        return $this->pdo_query_one($sql, [$id]);
    }

    public function update($data, $id) {
        if (!empty($data)) {
            $fields = "";
            foreach ($data as $key => $value) {
                $fields .= "$key = '$value',";
            }
            $fields = trim($fields, ",");
            $sql = "UPDATE $this->table SET $fields WHERE $this->contents = ?";
            return $this->pdo_execute($sql, $id);
        }
    }
    
    public function delete($id){
        $sql = "UPDATE $this->table SET $this->status = 0 WHERE $this->contents = ?";
        return $this->pdo_execute($sql, $id);
    }

   
}