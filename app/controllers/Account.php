<?php

class Account extends Controller
{
    public $data =[];
    public $account_model;

    public function __construct()
    {
        $this->account_model = $this->model('AccountModel');
    }

    function login()
    {   
        $title = 'Login';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'frontend/login/login_client';
        $this->render('layouts/client_layout', $this->data);
    }

    function login_action()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
            $user_password = md5($_POST['user_password']);
            if (($this->account_model->check_account($user_email)) == false) {
                setcookie('msg1', 'Email không chính xác', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }
            if(strlen($_POST['user_password']) < 8){
                setcookie('msg1', 'Mật khẩu tối thiểu 8 kí tự', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }
            $login = $this->account_model->login_action($user_email, $user_password);

            if ($login) {
                // Lưu thông tin user vào session
                $_SESSION['user'] = $login;
                
                if ($login['user_role'] == 1) {
                    $_SESSION['isLogin_Admin'] = true;
                    setcookie('msg', 'Đăng nhập admin thành công', time() + 5, '/');
                    header('Location: ' . _WEB_ROOT . '/dash-board');
                } else {
                    $_SESSION['isLogin'] = true;
                    setcookie('msg', 'Đăng nhập thành công', time() + 5, '/');
                    header('Location: ' . _WEB_ROOT . '/trang-chu');
                }
            } else {
                setcookie('msg1', 'Email hoặc mật khẩu không chính xác', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
            }
            exit();
        }
    }

    function register_action() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['user_email'];
            
            // Kiểm tra email đã tồn tại
            if ($this->account_model->check_account($email)) {
                setcookie('msg1', 'Email đã tồn tại trong hệ thống', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            // Kiểm tra độ dài mật khẩu
            if (strlen($_POST['user_password']) < 8) {
                setcookie('msg1', 'Mật khẩu phải có ít nhất 8 ký tự', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            // Kiểm tra mật khẩu xác nhận
            if ($_POST['user_password'] != $_POST['check_password']) {
                setcookie('msg1', 'Mật khẩu xác nhận không khớp', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            $data = array(
                'user_name' =>    $_POST['user_name'],
                'user_password' => md5($_POST['user_password']),
                'user_email'  =>   $email,
                'user_images' => 'user.png',
            );
            
            // Xử lý ký tự đặc biệt
            foreach ($data as $key => $value) {
                if (strpos($value, "'") != false) {
                    $value = str_replace("'", "\'", $value);
                    $data[$key] = $value;
                }
            }

            $status = $this->account_model->register_action($data);
            
            if ($status) {
                setcookie('msg', 'Đăng ký tài khoản thành công', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
            } else {
                setcookie('msg1', 'Đăng ký tài khoản thất bại', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
            }
            exit();
        }
    }

    function logout()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            unset($_SESSION['isLogin']);
            unset($_SESSION['isLogin_Admin']);
        }
        header('Location: ' . _WEB_ROOT . '/dang-nhap');
        exit();
    }
    //doimatkhau
    public function check_email_reset()
    {
        $title = 'Kiểm tra email';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'frontend/account/reset_password/check_email_reset';
        $this->render('layouts/client_layout', $this->data);
    }

    public function check_email()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            
            // Kiểm tra email có tồn tại
            $user = $this->account_model->checkEmail($email);
            
            if ($user) {
                // Lưu email vào session để dùng ở bước tiếp theo
                $_SESSION['reset_email'] = $email;
                header('Location: ' . _WEB_ROOT . '/reset_form');
            } else {
                setcookie('msg1', 'Email không tồn tại trong hệ thống', time() + 5);
                header('Location: ' . _WEB_ROOT . '/check_email_reset');
            }
            exit();
        }
    }

    public function reset_password_form()
    {
        // Kiểm tra xem đã check email chưa
        if (!isset($_SESSION['reset_email'])) {
            header('Location: ' . _WEB_ROOT . '/check_email_reset');
            exit();
        }

        $title = 'Đặt lại mật khẩu';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'frontend/account/reset_password/reset_password_form';
        $this->render('layouts/client_layout', $this->data);
    }

    public function reset_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_SESSION['reset_email'])) {
                header('Location: ' . _WEB_ROOT . '/check_email_reset');
                exit();
            }

            $email = $_SESSION['reset_email'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($new_password === $confirm_password) {
                // Cập nhật mật khẩu mới
                $this->account_model->updatePassword($email, $new_password);
                // Xóa session
                unset($_SESSION['reset_email']);
                setcookie('msg', 'Đổi mật khẩu thành công', time() + 5);
                header('Location: ' . _WEB_ROOT . '/check_email_reset');
            } else {
                setcookie('msg1', 'Mật khẩu xác nhận không khớp', time() + 5);
                header('Location: ' . _WEB_ROOT . '/account/reset_password_form');
            }
            exit();
        }
    }

    //quenmatkhau
    public function check_email_form() {
        $title = 'Kiểm tra email';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'frontend/account/forgot_password/check_email_form';
        $this->render('layouts/client_layout', $this->data);
    }
}
