<?php
namespace App\Controllers;
use Core\Controller;

    class Profile extends Controller
    {
        public $data =[];
        public $profile_model;
        public $auth;

        public function __construct()
        {
            $this->auth = new \App\Middleware\AuthMiddleWare();
            $this->auth->handleUserAuth(); // Yêu cầu đăng nhập cho mọi chức năng profile
            
            $this->profile_model = $this->model('ProfileModel');
        }

        public function index()
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
                    $allowed = ['jpg', 'jpeg', 'png'];
                    $filename = $file['name'];
                    $tmp_name = $file['tmp_name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed) && $file["size"] < 2 * 1024 * 1024) {
                        // Tạo tên file mới
                        $new_filename = uniqid() . '.' . $ext;
                        
                        // Tạo đường dẫn đầy đủ đến thư mục uploads
                        $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                        $upload_path = $base_path . '/public/uploads/avatar/';
                        
                        // Upload file
                        if ($this->handleUpload($base_path, $upload_path, $tmp_name, $new_filename)) {
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
                        setcookie('msg1', 'Định dạng file không hợp lệ (chỉ chấp nhận: jpg, jpeg, png - Dung lượng dưới 2MB: 1920px x 1080px)', time() + 5);
                    }
                } else {
                    setcookie('msg1', 'Có lỗi xảy ra khi upload file', time() + 5);
                }
                
                header('Location: ' . _WEB_ROOT . '/profile');
                exit();
            }
        }


        public function edit_profile() {
            $user_email = $_SESSION['user']['user_email'];
            $this->data['sub_content']['title'] = 'Sửa thông tin cá nhân';
            $this->data['page_title'] = 'Sửa thông tin cá nhân';
            $this->data['sub_content']['profile'] = $this->profile_model->getProfile($user_email);
            $this->data['content'] = 'frontend/profile/edit_profile';
            $this->render('layouts/client_layout', $this->data);
        }

        public function update_profile() {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                header('Location: ' . _WEB_ROOT . '/profile');
                exit();
            }

            $user_email = $_SESSION['user']['user_email'];

            $user_name = trim($_POST['user_name']);
            $user_phone = trim($_POST['user_phone']);
            
            if (strlen($user_name) < 2 || strlen($user_name) > 50) {
                setcookie('msg1', 'Họ tên phải từ 2 đến 50 ký tự', time() + 5);
                header('Location: ' . _WEB_ROOT . '/edit-profile');
                exit();
            }

            if (!preg_match('/^[0-9]{10,12}$/', $user_phone)) {
                setcookie('msg1', 'Số điện thoại phải từ 10 đến 12 số', time() + 5);
                header('Location: ' . _WEB_ROOT . '/edit-profile');
                exit();
            }

           
            $user_data = [
                'user_name' => $user_name,
                'user_phone' => $user_phone
            ];

            $address_data = [
                'address_name' => htmlspecialchars(trim($_POST['address_name'])),
                'address_province' => htmlspecialchars(trim($_POST['address_province'])),
                'address_city' => htmlspecialchars(trim($_POST['address_city'])),
                'address_street' => htmlspecialchars(trim($_POST['address_street']))
            ];

            if (empty($address_data['address_name']) || 
                empty($address_data['address_province']) || 
                empty($address_data['address_city']) || 
                empty($address_data['address_street'])) {
                setcookie('msg1', 'Vui lòng điền đầy đủ thông tin địa chỉ', time() + 5);
                header('Location: ' . _WEB_ROOT . '/edit-profile');
                exit();
            }

            $image_data = null;
            if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === 0) {
                $file = $_FILES['user_image'];
                $allowed = ['jpg', 'jpeg', 'png'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (!in_array($ext, $allowed)) {
                    setcookie('msg1', 'Chỉ chấp nhận file ảnh định dạng JPG, JPEG hoặc PNG', time() + 5);
                    header('Location: ' . _WEB_ROOT . '/edit-profile');
                    exit();
                }

                if ($file['size'] > 2 * 1024 * 1024) {
                    setcookie('msg1', 'Kích thước ảnh không được vượt quá 2MB', time() + 5);
                    header('Location: ' . _WEB_ROOT . '/edit-profile');
                    exit();
                }

                $new_filename = uniqid() . '.' . $ext;
                $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                $upload_path = $base_path . '/public/uploads/avatar/';

                if (move_uploaded_file($file['tmp_name'], $upload_path . $new_filename)) {
                    $image_data = [
                        'new_filename' => $new_filename,
                        'upload_path' => $upload_path
                    ];
                }
            }

            // Update profile
            if ($this->profile_model->update_profile($user_data, $address_data, $user_email, $image_data)) {
                setcookie('msg', 'Cập nhật thông tin thành công', time() + 5);
                header('Location: ' . _WEB_ROOT . '/profile');
            } else {
                setcookie('msg1', 'Có lỗi xảy ra khi cập nhật thông tin', time() + 5);
                header('Location: ' . _WEB_ROOT . '/edit-profile');
            }
            exit();
        }

    }
    
?>