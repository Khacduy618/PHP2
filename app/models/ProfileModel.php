<?php
namespace App\Models;

use Core\Model;
use Exception;

    class ProfileModel extends Model
    {
        protected $table = 'user';

        public function getProfile($user_email)
        {
            $query = "SELECT u.*, a.*
                  FROM {$this->table} u
                  LEFT JOIN address a ON u.user_email = a.address_userEmail
                  WHERE u.user_email = ? AND (a.address_status = 0 OR a.address_status IS NULL)";
            return $this->pdo_query_one($query, [$user_email]);
        }

        public function update_profile($data, $address_data, $user_email, $image_data = null)
        {
            try {
                $this->db->beginTransaction();

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

                // Cập nhật ảnh đại diện nếu có
                if ($image_data) {
                    $old_image = $this->get_old_avatar($user_email);
                    $this->update_avatar($user_email, $image_data['new_filename']);
                    
                    // Xóa ảnh cũ
                    if ($old_image && $old_image !== 'default-avatar.jpg') {
                        $old_image_path = $image_data['upload_path'] . $old_image;
                        if (file_exists($old_image_path)) {
                            unlink($old_image_path);
                        }
                    }
                }

                // Cập nhật địa chỉ
                if (!empty($address_data)) {
                    // Kiểm tra địa chỉ hiện tại của user với status = 0
                    $existing_address = $this->pdo_query_one(
                        "SELECT * FROM address WHERE address_userEmail = ? AND address_status = 0", 
                        [$user_email]
                    );

                    if ($existing_address) {
                        // Update địa chỉ hiện tại
                        $address_fields = "";
                        $params = [];
                        foreach ($address_data as $key => $value) {
                            $address_fields .= "$key = ?,";
                            $params[] = $value;
                        }
                        $address_fields = trim($address_fields, ",");
                        $query = "UPDATE address SET $address_fields 
                                 WHERE address_userEmail = ? AND address_id = ? AND address_status = 0";
                        $params[] = $user_email;
                        $params[] = $existing_address['address_id'];
                        $this->pdo_execute($query, $params);
                    } else {
                        // Insert địa chỉ mới với status = 0
                        $address_data['address_userEmail'] = $user_email;
                        $address_data['address_status'] = 0;
                        $fields = implode(",", array_keys($address_data));
                        $placeholders = str_repeat('?,', count($address_data) - 1) . '?';
                        $query = "INSERT INTO address ($fields) VALUES ($placeholders)";
                        $this->pdo_execute($query, array_values($address_data));
                    }
                }

                $this->db->commit();
                return true;
            } catch (Exception $e) {
                $this->db->rollBack();
                error_log("Error updating profile: " . $e->getMessage());
                return false;
            }
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