<?php
namespace App\Controllers;
use Core\Controller;

use App\Middleware\AuthMiddleWare;

class User extends Controller
{
    public $data =[];
    private $user_model;
    private $account_model;
    private $authMiddleware;

        public function __construct()
        {
            $this->authMiddleware = new AuthMiddleWare();
            $this->authMiddleware->handleAdminAuth();
            $this->user_model = $this->model('UserModel');
            $this->account_model = $this->model('AccountModel');
        }

    public function list_user($search = '', $status = '', $page = 1)
    {   
        // Get parameters from URL
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path_parts = explode('/', trim($path, '/'));
        
        array_shift($path_parts); // Remove 'php2'
        array_shift($path_parts); // Remove 'user'
        
        $search = isset($path_parts[0]) && $path_parts[0] !== '' ? urldecode($path_parts[0]) : '';
        $status = isset($path_parts[1]) ? $path_parts[1] : '';
        $page = isset($path_parts[2]) ? max(1, intval($path_parts[2])) : 1;
        
        // Convert URL-friendly search term back to normal
        $search = str_replace('-', ' ', $search);
        
        if (!empty($search)) {
            $_SESSION['search_keyword'] = $search;
        }

        $perpage = 12; // Fixed items per page
        $total_users = $this->user_model->getTotalUsers($search, $status);
        $total_pages = ceil($total_users / $perpage);
        
        // Ensure page is within valid range
        $page = min($page, max(1, $total_pages));

        $title = 'User Management';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['users'] = $this->user_model->getListUser($search, $status, $page, $perpage);
        $this->data['sub_content']['total_users'] = $total_users;
        $this->data['sub_content']['pagination'] = [
            'page' => $page,
            'total_pages' => $total_pages,
            'search' => $search,
            'status' => $status
        ];
        $this->data['content'] = 'backend/users/list';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function add_new() {
        $title = 'Add new a user';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'backend/users/add';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate required fields
            if (empty($_POST['user_email']) || empty($_POST['user_name']) || 
                empty($_POST['user_phone']) || empty($_FILES['user_images']['name'])) {
                setcookie('msg1', 'Vui lòng điền đầy đủ thông tin!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/add-new-user');
                exit();
            }
    
            // Validate email format
            if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
                setcookie('msg1', 'Email không hợp lệ!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/add-new-user');
                exit();
            }
    
            // Check if email exists
            if ($this->account_model->check_account($_POST['user_email'])) {
                setcookie('msg1', 'Email đã tồn tại trong hệ thống!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/add-new-user');
                exit();
            }
    
            // Validate phone number
            $phone = $_POST['user_phone'];
            if (!preg_match('/^[0-9]{10,12}$/', $phone)) {
                setcookie('msg1', 'Số điện thoại phải từ 10-12 số!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/add-new-user');
                exit();
            }
    
            // Validate name
            $user_name = trim($_POST['user_name']);
            if (strlen($user_name) < 2 || strlen($user_name) > 50) {
                setcookie('msg1', 'Tên phải từ 2-50 ký tự!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/add-new-user');
                exit();
            }
    
            // Validate image
            $file = $_FILES['user_images'];
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($ext, $allowed)) {
                setcookie('msg1', 'Chỉ chấp nhận file ảnh định dạng: jpg, jpeg, png!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/add-new-user');
                exit();
            }
    
            if ($file['size'] > 2 * 1024 * 1024) {
                setcookie('msg1', 'Kích thước file không được vượt quá 2MB!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/add-new-user');
                exit();
            }
    
            // Generate new filename
            $new_filename = uniqid() . '.' . $ext;
            $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
            $upload_path = $base_path . '/public/uploads/avatar/';
    
            // Handle upload
            if (!$this->handleUpload($base_path, $upload_path, $file['tmp_name'], $new_filename)) {
                setcookie('msg1', 'Không thể tải lên file ảnh!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/add-new-user');
                exit();
            }
    
            // Prepare data
            $data = array(
                'user_name' => $user_name,
                'user_phone' => $phone,
                'user_email' => $_POST['user_email'],
                'user_role' => $_POST['user_role'],
                'user_images' => $new_filename
            );
    
            // Handle special characters
            foreach ($data as $key => $value) {
                if (strpos($value, "'") !== false) {
                    $data[$key] = str_replace("'", "\'", $value);
                }
            }
    
            // Store user
            if ($this->user_model->store($data)) {
                setcookie('msg', 'Thêm người dùng thành công!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/user');
            } else {
                setcookie('msg1', 'Thêm người dùng thất bại!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/add-new-user');
            }
            exit();
        }
    }

    public function edit($id=0) {
        $title = 'Update a user';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['user'] = $this->user_model->findbyId($id);
        $this->data['content'] = 'backend/users/edit';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['user_name']) || empty($_POST['user_phone'])) {
                setcookie('msg1', 'Vui lòng điền đầy đủ thông tin!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/edit-user/' . $_POST['user_email']);
                exit();
            }

            $phone = $_POST['user_phone'];
            if (!preg_match('/^[0-9]+$/', $phone)) {
                setcookie('msg1', 'Số điện thoại chỉ được chứa các chữ số!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/edit-user/' . $_POST['user_email']);
                exit();
            }

            if (strlen($phone) < 10 || strlen($phone) > 12) {
                setcookie('msg1', 'Số điện thoại phải từ 10-12 số!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/edit-user/' . $_POST['user_email']);
                exit();
            }

            $user_name = trim($_POST['user_name']);
            if (strlen($user_name) < 2 || strlen($user_name) > 50) {
                setcookie('msg1', 'Tên phải từ 2-50 ký tự!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/edit-user/' . $_POST['user_email']);
                exit();
            }

            if (!empty($_FILES['user_images']['name'])) {
                $file = $_FILES['user_images'];
                $allowed = ['jpg', 'jpeg', 'png'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (!in_array($ext, $allowed)) {
                    setcookie('msg1', 'Chỉ chấp nhận file ảnh định dạng: jpg, jpeg, png!', time() + 5, '/');
                    header('Location: ' . _WEB_ROOT . '/edit-user/' . $_POST['user_email']);
                    exit();
                }

                if ($file['size'] > 2 * 1024 * 1024) {
                    setcookie('msg1', 'Kích thước file không được vượt quá 2MB!', time() + 5, '/');
                    header('Location: ' . _WEB_ROOT . '/edit-user/' . $_POST['user_email']);
                    exit();
                }

                // Generate new filename and handle upload
                $new_filename = uniqid() . '.' . $ext;
                $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                $upload_path = $base_path . '/public/uploads/avatar/';
                
                if (!$this->handleUpload($base_path, $upload_path, $file['tmp_name'], $new_filename)) {
                    setcookie('msg1', 'Không thể tải lên file ảnh!', time() + 5, '/');
                    header('Location: ' . _WEB_ROOT . '/edit-user/' . $_POST['user_email']);
                    exit();
                }
            } else {
                $current_user = $this->user_model->findbyId($_POST['user_email']);
                $new_filename = $current_user['user_images'];
            }

            // Prepare data for update
            $data = array(
                'user_name' => $user_name,
                'user_phone' => $phone,
                'user_email' => $_POST['user_email'],
                'user_role' => $_POST['user_role'],
                'user_status' => $_POST['user_status'],
                'user_images' => $new_filename
            );

            // Handle special characters
            foreach ($data as $key => $value) {
                if (strpos($value, "'") !== false) {
                    $data[$key] = str_replace("'", "\'", $value);
                }
            }

            // Update user
            if ($this->user_model->update($data, $_POST['user_email'])) {
                setcookie('msg', 'Cập nhật thông tin thành công!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/user');
            } else {
                setcookie('msg1', 'Cập nhật thông tin thất bại!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/edit-user/' . $_POST['user_email']);
            }
            exit();
        }
    }

    public function delete($id=0) {
        if($this->user_model->delete($id)){
            $_SESSION['msg'] = 'User deleted successfully!';
            header('Location: '._WEB_ROOT.'/user');
        }else{
            setcookie('msg1', 'Failed to delete product!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/user');
        }
        exit();
    }
}
