<?php
namespace Core;
class Database 
{
     /** @var \PDO */
     protected $__conn;
    
     public function __construct() {
         global $config;
         $this->__conn = Connection::getInstance($config['database']);
     }
 
     /**
      * Get the PDO connection instance
      * @return \PDO
      */
     public function getConnection() {
         return $this->__conn;
     }
}

?>