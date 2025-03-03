<?php
namespace App\Models;

use Core\Model;
class AddressModel extends Model
{
    protected $table ;
    protected $status ;
    protected $contents ;

    public function __construct(){
        parent::__construct();
        $this->table = "address";
        $this->status = "address_status";
        $this->contents = "address_userEmail";
    }

    public function getAllUserAddresses($userEmail) {
        $sql = "SELECT * FROM $this->table WHERE $this->contents = ?";
        return $this->pdo_query_all($sql, [$userEmail]);
    }

    public function getUserAddress($userEmail, $addressId) {
        $sql = "SELECT * FROM $this->table WHERE $this->contents =? AND address_id =?";
        return $this->pdo_query_one($sql, [$userEmail, $addressId]);
    }

    function getOneAddress($userEmail) {
        $sql = "SELECT * FROM $this->table WHERE $this->contents = ? AND $this->status = 0";
        return $this->pdo_query_one($sql, $userEmail);
    }

    public function getOneAddressById($addressId) {
        $sql = "SELECT * FROM address WHERE address_id = ?";
        return $this->pdo_query_one($sql, [$addressId]);
    }
}
