<?php
class User extends Controller
{
    public $data =[];
        public $user_model;

        public function __construct()
        {
            $this->user_model = $this->model('UserModel');
        }

        public function list_user()
        {   
            $dataUser = $this->user_model->getListUser();
            $title = 'User Management';
            $this->data['sub_content']['title'] = $title;
            $this->data['page_title'] = $title;
            $this->data['sub_content']['users'] = $dataUser;
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
                if (empty($_POST['user_email']) || empty($_POST['user_name']) || empty($_POST['user_phone']) || empty($_FILES['user_images']['name']))  {
                    $_SESSION['msg'] = 'Fill in all required fields!';
                    header('Location: ' . _WEB_ROOT . '/add-new-user');
                    exit;
                }
                if(strlen($_POST['user_phone']) > 12){
                    $_SESSION['msg'] = 'Số điện thoại của bạn đã quá 12 ký tự!';
                    header('Location: ' . _WEB_ROOT . '/add-new-user');
                    exit;
                }
                $file = $_FILES['user_images'];
                $user_images = $file['name'];
                $tmp_name = $file['tmp_name'];
                $allowed = ['jpg', 'jpeg', 'png'];
                $ext = strtolower(pathinfo($user_images, PATHINFO_EXTENSION));
                if (in_array($ext, $allowed) && $file["size"] < 2 * 1024 * 1024) {
                    // Tạo tên file mới
                    $new_filename = uniqid() . '.' . $ext;
                    // Tạo đường dẫn đầy đủ đến thư mục uploads
                    $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                    $upload_path = $base_path . '/public/uploads/avatar/';
                    $this->handleUpload($base_path,$upload_path,$tmp_name,$new_filename);
                }else{
                    $_SESSION['msg'] = 'Định dạng file không hợp lệ (chỉ chấp nhận: jpg, jpeg, png - Dung lượng dưới 2MB)';
                    header('Location: ' . _WEB_ROOT . '/add-new-user');
                    exit;
                }
                    $user_name = $_POST['user_name'];
                    $user_phone = $_POST['user_phone'];
                    $user_email = $_POST['user_email'];
                    $user_role = $_POST['user_role'];
                   
                    
                    $data = array(
                        'user_name' => $user_name,
                        'user_phone' => $user_phone,
                        'user_email' => $user_email,
                        'user_role'  =>   $user_role,
                        'user_images'  =>   $new_filename
                    );
                    
                    // Xử lý ký tự đặc biệt
                    foreach ($data as $key => $value) {
                        if (strpos($value, "'") != false) {
                            $value = str_replace("'", "\'", $value);
                            $data[$key] = $value;
                        }
                    }
                    $status = $this->user_model->store($data);
                
                    if ($status) {
                        $_SESSION['msg'] = 'Product added successfully!';
                        header('Location: '._WEB_ROOT.'/user');
                    } else {
                        setcookie('msg1', 'Failed to add product!', time() + 5, '/');
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
            if(strlen($_POST['user_phone']) > 12){
                $_SESSION['msg'] = 'Số điện thoại của bạn đã quá 12 ký tự!';
                header('Location: ' . _WEB_ROOT . '/add-new-user');
                exit;
            }
            $user_name = $_POST['user_name'];
            $user_phone = $_POST['user_phone'];
            $user_email = $_POST['user_email'];
            $user_role = $_POST['user_role'];
            $user_status = $_POST['user_status'];
    
            if (!empty($_FILES['user_images']['name'])) {
                //check img
                $file = $_FILES['user_images'];
                $user_images = $file['name'];
                $tmp_name = $file['tmp_name'];
                $allowed = ['jpg', 'jpeg', 'png'];
                $ext = strtolower(pathinfo($user_images, PATHINFO_EXTENSION));
                if (in_array($ext, $allowed) && $file["size"] < 2 * 1024 * 1024) {
                    // Tạo tên file mới
                    $new_filename = uniqid() . '.' . $ext;
                    // Tạo đường dẫn đầy đủ đến thư mục uploads
                    $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                    $upload_path = $base_path . '/public/uploads/avatar/';
                    $this->handleUpload($base_path,$upload_path,$tmp_name,$new_filename);
                }else{
                    $_SESSION['msg'] = 'Định dạng file không hợp lệ (chỉ chấp nhận: jpg, jpeg, png - Dung lượng dưới 2MB: 1920px x 1080px)';
                    header('Location: ' . _WEB_ROOT . '/edit-user' . '/'.$user_email);
                    exit;
                }
            } else {
                $current_product = $this->user_model->findbyId($user_email);
                $new_filename = $current_product['user_images'];
            }
            
            $data = array(
                'user_name' => $user_name,
                'user_phone' => $user_phone,
                'user_email' => $user_email,
                'user_role'  =>   $user_role,
                'user_status'  =>   $user_status,
                'user_images'  =>   $new_filename
            );
            
            // Xử lý ký tự đặc biệt
            foreach ($data as $key => $value) {
                if (strpos($value, "'") != false) {
                    $value = str_replace("'", "\'", $value);
                    $data[$key] = $value;
                }
            }
            $status = $this->user_model->update($data, $user_email);
                
            if ($status) {
                $_SESSION['msg'] = 'Product updated successfully!';
                header('Location: '._WEB_ROOT.'/user');
            } else {
                setcookie('msg1', 'Failed to update product!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/edit-user');
            }
            exit();
    
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
