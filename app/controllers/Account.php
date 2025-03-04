<?php
namespace App\Controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Core\Controller;

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
            $password = $_POST['user_password'];

            if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                setcookie('msg1', 'Email không hợp lệ', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            if(strlen($password) < 8){
                setcookie('msg1', 'Mật khẩu tối thiểu 8 kí tự', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            if (!$this->account_model->check_account($user_email)) {
                setcookie('msg1', 'Email không chính xác!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            $user_password = md5($password);
            $login = $this->account_model->login_action($user_email, $user_password);

            if ($login) {
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
            $email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
            $name = trim($_POST['user_name']);
            $password = $_POST['user_password'];
            $check_password = $_POST['check_password'];
            
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                setcookie('msg1', 'Email không hợp lệ', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            if ($this->account_model->check_account($email)) {
                setcookie('msg1', 'Email đã tài khoản sử dụng!', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            if (strlen($name) < 2 || strlen($name) > 50) {
                setcookie('msg1', 'Tên phải từ 2 đến 50 ký tự', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            if (strlen($password) < 8) {
                setcookie('msg1', 'Mật khẩu phải có ít nhất 8 ký tự', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
                setcookie('msg1', 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường và 1 số', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            if ($password !== $check_password) {
                setcookie('msg1', 'Mật khẩu xác nhận không khớp', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/dang-nhap');
                exit();
            }

            $data = array(
                'user_name' => $name,
                'user_password' => md5($password),
                'user_email' => $email,
                'user_images' => 'user.png',
            );
            
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
            session_destroy();
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
            $user = $this->account_model->check_account($email);
            
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

    //quenmatkhau
    public function check_email_form() {
        $title = 'Kiểm tra email';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'frontend/account/forgot_password/check_email_form';
        $this->render('layouts/client_layout', $this->data);
    }

    public function send_email() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                
                
                // Kiểm tra email có tồn tại
                $user = $this->account_model->check_account($email);
                if (!$user) {
                    setcookie('msg1', 'Email không tồn tại trong hệ thống', time() + 5, '/');
                    header('Location: ' . _WEB_ROOT . '/check_email_form');
                    exit();
                }

                // Tạo access token
                $access_token = md5($email . time());
                $this->account_model->accessToken($access_token, $email);

                // Cấu hình PHPMailer
                $mail = new PHPMailer(true);
                
                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                    
                    $mail->isSMTP();                                         
                    $mail->Host       = 'smtp.gmail.com';                    
                    $mail->SMTPAuth   = true;                               
                    $mail->Username   = 'khacduy54.55@gmail.com';                             
                    $mail->Password   = 'jbkw seit ixju fvmz';                          
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;       
                    $mail->Port       = 465;                                
                    $mail->CharSet    = 'UTF-8';

                    //Recipients
                    $mail->setFrom('khacduy54.55@gmail.com', 'Password Reset');
                    $mail->addAddress($email);     

                    //Content
                    $mail->isHTML(true);                                  
                    $mail->Subject = 'Đặt lại mật khẩu';
                    $mail->Body    = '
                        <h2>Xin chào,</h2>
                        <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu của bạn.</p>
                        <p>Vui lòng click vào link bên dưới để đặt lại mật khẩu:</p>
                        <p><a href="' . _WEB_ROOT . '/change_password_form' . '/' . $access_token . '">Đặt lại mật khẩu</a></p>
                        <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
                    ';

                    $mail->send();
                    setcookie('msg', 'Email đã được gửi thành công', time() + 5, '/');
                    header('Location: ' . _WEB_ROOT . '/dang-nhap');
                    exit();

                } catch (Exception $e) {
                    error_log("Mail Error: " . $mail->ErrorInfo);
                    setcookie('msg1', 'Không thể gửi email: ' . $mail->ErrorInfo, time() + 5, '/');
                    header('Location: ' . _WEB_ROOT . '/check_email_form');
                    exit();
                }

            } catch (Exception $e) {
                error_log("General Error: " . $e->getMessage());
                setcookie('msg1', 'Có lỗi xảy ra, vui lòng thử lại', time() + 5, '/');
                header('Location: ' . _WEB_ROOT . '/check_email_form');
                exit();
            }
        }
    }

    public function change_password_form($access_token = '')
    {   
        $title = 'Đặt lại mật khẩu';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['access_token'] = $access_token;
        $this->data['content'] = 'frontend/account/forgot_password/change_pass_form';
        $this->render('layouts/client_layout', $this->data);
    }

    public function change_password($access_token = '')
    {
        if (!$access_token || !$this->account_model->getAccessToken($access_token)) {
            setcookie('msg1', 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/check_email_form');
            exit();
        }

        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);
        $email = $this->account_model->getAccessToken($access_token);

        // Validate password length
        if (strlen($new_password) < 8) {
            setcookie('msg1', 'Mật khẩu phải có ít nhất 8 ký tự', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/change_password_form/' . $access_token);
            exit();
        }

        // Validate password strength (optional)
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $new_password)) {
            setcookie('msg1', 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường và 1 số', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/change_password_form/' . $access_token);
            exit();
        }
        
        // Validate password confirmation
        if ($new_password !== $confirm_password) {
            setcookie('msg1', 'Mật khẩu xác nhận không khớp', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/change_password_form/' . $access_token);
            exit();
        }

        // Update password
        if ($this->account_model->updatePassword($access_token, md5($new_password))) {
            $access_token = NULL;
            $this->account_model->accessToken($access_token, $email);
            
            setcookie('msg', 'Đổi mật khẩu thành công', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
        } else {
            setcookie('msg1', 'Có lỗi xảy ra, vui lòng thử lại', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/change_password_form/' . $access_token);
        }
        exit();
    }
}
