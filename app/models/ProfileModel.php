<?php
    class ProfileModel extends Model
    {
        protected $table = 'user';

        public function getProfile($user_email)
        {
            $query = "SELECT u.*, a.address_name, a.address_city, a.address_street
                  FROM {$this->table} u
                  LEFT JOIN address a ON u.user_email = a.address_userEmail
                  WHERE u.user_email = ?";
            return $this->pdo_query_one($query, [$user_email]);
        }

        public function update_profile($data, $address_data, $user_email)
        {
            // Cập nhật thông tin user
            if (!empty($data)) {
                $fields = "";
                $params = [];
                foreach ($data as $key => $value) {
                    $fields .= "$key = ?,";
                    $params[] = $value;
                }
                $fields = trim($fields, ",");
                $query = "UPDATE {$this->table} SET $fields WHERE user_email = ?";
                $params[] = $user_email;
                $this->pdo_execute($query, $params);
            }
    
            // Cập nhật thông tin địa chỉ
            if (!empty($address_data)) {
                // Kiểm tra xem có địa chỉ nào tồn tại với user_email này không
                $check_address_query = "SELECT * FROM address WHERE address_userEmail = ?";
                $existing_address = $this->pdo_query_one($check_address_query, [$user_email]);
    
                if ($existing_address) {
                    // Nếu địa chỉ đã tồn tại, thực hiện cập nhật
                    $address_fields = "";
                    $params = [];
                    foreach ($address_data as $key => $value) {
                        $address_fields .= "$key = ?,";
                        $params[] = $value;
                    }
                    $address_fields = trim($address_fields, ",");
                    $query = "UPDATE address SET $address_fields WHERE address_userEmail = ?";
                    $params[] = $user_email;
                    $this->pdo_execute($query, $params);
                } else {
                    // Nếu địa chỉ chưa tồn tại, thực hiện thêm mới
                    $address_data['address_userEmail'] = $user_email;
                    $fields = implode(",", array_keys($address_data));
                    $placeholders = str_repeat('?,', count($address_data) - 1) . '?';
                    $query = "INSERT INTO address ($fields) VALUES ($placeholders)";
                    $this->pdo_execute($query, array_values($address_data));
                }
            }
    
            // Thông báo kết quả
            setcookie('msg', 'Update successfully', time() + 2);
        }

        public function update_avatar($user_email, $image_name)
        {
            try {
                $sql = "UPDATE $this->table SET user_images = ? WHERE user_email = ?";
                $result = $this->pdo_execute($sql, [$image_name, $user_email]);
                
                if ($result) {
                    return true;
                }
                return false;
            } catch (\PDOException $e) {
                error_log("Error updating avatar: " . $e->getMessage());
                return false;
            }
        }

        public function get_old_avatar($user_email)
        {
            $sql = "SELECT user_images FROM $this->table WHERE user_email = ?";
            $result = $this->pdo_query_one($sql, [$user_email]);
            return $result['user_images'] ?? null;
        }
    }
    
?>