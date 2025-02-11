<?php
    class AccountModel extends Model
    {
        protected $table = 'user';

        public function login_action($email, $password)
        {
            $sql = "SELECT * FROM $this->table WHERE user_email = ? AND user_password = ? LIMIT 1";
            $stmt = $this->pdo_query($sql, [$email, $password]);
            if ($stmt) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return false;
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
            $query = "INSERT INTO user($f) VALUES ($v);";

            return $this->pdo_execute($query);
        }
    
        function account($user_email)
        {
            $query = "SELECT u.*, a.address_name, a.address_city, a.address_street
                  FROM user u
                  LEFT JOIN address a ON u.user_email = a.address_userEmail
                  WHERE u.user_email = ?";
            return $this->pdo_query_one($query, $user_email);
        }

        public function checkEmail($email)
        {
            $sql = "SELECT * FROM $this->table WHERE user_email = ? LIMIT 1";
            $stmt = $this->pdo_query($sql, [$email]);
            if ($stmt) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }

        public function dangky_google($google_id, $user_name, $user_full_name, $user_email, $user_images, $user_password)
        {
            $query = "INSERT INTO user (google_id, user_name, user_full_name, user_email, user_images, user_password) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            return $this->pdo_execute($query, $google_id, $user_name, $user_full_name, $user_email, $user_images, $user_password);
        }
        

        public function updatePassword($email, $new_password)
        {
            $sql = "UPDATE $this->table SET user_password = ? WHERE user_email = ?";
            return $this->pdo_query($sql, [md5($new_password), $email]);
        }
    }
    
?>