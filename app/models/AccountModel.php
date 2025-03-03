<?php
namespace App\Models;

use Core\Model;
    class AccountModel extends Model
    {
        protected $table = 'user';

        public function login_action($user_email, $user_password)
        {
            $sql = "SELECT * FROM $this->table WHERE user_email = ? AND user_password = ? LIMIT 1";
            return $this->pdo_query_one($sql, [$user_email, $user_password]);
        }
       
        function check_account($email)
        {
            $sql = "SELECT user_email FROM $this->table WHERE user_email = ? LIMIT 1";
            $result = $this->pdo_query_one($sql, [$email]);
            return $result ? true : false;
        }
          function register_action($data)
        {
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
    
        function account($user_email)
        {
            $query = "SELECT u.*, a.address_name, a.address_city, a.address_street
                  FROM $this->table u
                  LEFT JOIN address a ON u.user_email = a.address_userEmail
                  WHERE u.user_email = ?";
            return $this->pdo_query_one($query, $user_email);
        }


        public function dangky_google($google_id, $user_name, $user_full_name, $user_email, $user_images, $user_password)
        {
            $query = "INSERT INTO user (google_id, user_name, user_full_name, user_email, user_images, user_password) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            return $this->pdo_execute($query, $google_id, $user_name, $user_full_name, $user_email, $user_images, $user_password);
        }
        

        public function updatePassword($access_token, $new_password)
        {
            $sql = "UPDATE $this->table SET user_password = ? WHERE access_token = ?";
            return $this->pdo_query($sql, [$new_password, $access_token]);
        }

        public function accessToken($access_token = '', $email) {
            $sql = "UPDATE $this->table SET access_token = ? WHERE user_email = ?";
            return $this->pdo_execute($sql, [$access_token, $email]);
        }

        public function getAccessToken($access_token) {
            $sql = "SELECT user_email FROM $this->table WHERE access_token = ?";
            $result = $this->pdo_query_one($sql, [$access_token]);
            return $result ? $result['user_email'] : false;
        }

    
    }
    
?>