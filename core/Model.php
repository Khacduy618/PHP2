<?php
class Model extends Database{
    protected $db;
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

    protected function pdo_query_all($sql, $params = []) {
        try {
            $stmt = $this->pdo_query($sql, $params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw $e;
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

    protected function pdo_execute($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $params = is_array($params) ? $params : [$params];
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}