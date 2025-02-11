<?php
    class Profile extends Controller
    {
        public $data =[];
        public $profile_model;

        public function __construct()
        {
            // Kiểm tra đăng nhập trước khi cho phép truy cập profile
            if (!isset($_SESSION['user'])) {
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }
            $this->profile_model = $this->model('ProfileModel');
        }

        public function showProfile()
        {   
            $user_email = $_SESSION['user']['user_email'];
            $dataProfile = $this->profile_model->getProfile($user_email);
            $title = 'Profile';
            $this->data['sub_content']['title'] = $title;
            $this->data['page_title'] = $title;
            $this->data['sub_content']['profile'] = $dataProfile;
            $this->data['content'] = 'frontend/profile/profile';
            $this->render('layouts/client_layout', $this->data);
        }

        public function update_avatar()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['user_image'])) {
                $file = $_FILES['user_image'];
                $user_email = $_SESSION['user']['user_email'];
                
                // Kiểm tra file
                if ($file['error'] === 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    $filename = $file['name'];
                    $tmp_name = $file['tmp_name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed)) {
                        // Tạo tên file mới
                        $new_filename = uniqid() . '.' . $ext;
                        
                        // Tạo đường dẫn đầy đủ đến thư mục uploads
                        $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                        $upload_path = $base_path . '/public/uploads/avatar/';
                        
                        // Tạo các thư mục nếu chưa tồn tại
                        if (!file_exists($base_path . '/public')) {
                            mkdir($base_path . '/public', 0777, true);
                        }
                        if (!file_exists($base_path . '/public/uploads')) {
                            mkdir($base_path . '/public/uploads', 0777, true);
                        }
                        if (!file_exists($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }
                        
                        // Upload file
                        if (move_uploaded_file($tmp_name, $upload_path . $new_filename)) {
                            // Lấy và xóa ảnh cũ trước khi cập nhật DB
                            $old_image = $this->profile_model->get_old_avatar($user_email);
                            
                            // Cập nhật tên file mới vào database
                            if ($this->profile_model->update_avatar($user_email, $new_filename)) {
                                // Xóa ảnh cũ sau khi cập nhật DB thành công
                                if ($old_image && $old_image !== 'default-avatar.jpg' && file_exists($upload_path . $old_image)) {
                                    unlink($upload_path . $old_image);
                                }
                                
                                setcookie('msg', 'Cập nhật ảnh đại diện thành công', time() + 5);
                            } else {
                                // Xóa file mới nếu cập nhật DB thất bại
                                if (file_exists($upload_path . $new_filename)) {
                                    unlink($upload_path . $new_filename);
                                }
                                setcookie('msg1', 'Không thể cập nhật ảnh đại diện', time() + 5);
                            }
                        } else {
                            setcookie('msg1', 'Không thể upload ảnh', time() + 5);
                        }
                    } else {
                        setcookie('msg1', 'Định dạng file không hợp lệ (chỉ chấp nhận: jpg, jpeg, png, gif)', time() + 5);
                    }
                } else {
                    setcookie('msg1', 'Có lỗi xảy ra khi upload file', time() + 5);
                }
                
                header('Location: ' . _WEB_ROOT . '/profile_user');
                exit();
            }
        }

    }
    
?>