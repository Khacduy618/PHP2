<?php
namespace App\Middleware;

use Core\Controller;

class AuthMiddleWare extends Controller
{
    public function handleAdminAuth()
    {
        if (!isset($_SESSION['isLogin_Admin'])) {
            // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // Lưu URL hiện tại để redirect sau khi đăng nhập
            setcookie('msg1', 'Vui lòng đăng nhập để tiếp tục!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
            exit();
        }

        // Kiểm tra role admin (giả sử role 1 là admin)
        if ($_SESSION['user']['user_role'] > 1 ) {
            setcookie('msg1', 'Bạn không có quyền truy cập trang này!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/');
            exit();
        }
    }

    public function handleUserAuth()
    {
        if (!isset($_SESSION['isLogin'])) {
            // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            setcookie('msg1', 'Vui lòng đăng nhập để tiếp tục!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
            exit();
        }
    }

    public function handleEmployeeAuth()
    {
        if (!isset($_SESSION['isLogin_Admin'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            setcookie('msg1', 'Vui lòng đăng nhập admin để tiếp tục!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
            exit();
        }

        // Kiểm tra role employee (giả sử role 2, 3, 4 là employee)
        if ($_SESSION['user']['user_role'] = 0 ) {
            setcookie('msg1', 'Bạn không có quyền truy cập trang này!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/');
            exit();
        }
    }

    public function handleGuestOnly()
    {
        // Dành cho các trang chỉ khách mới được truy cập (như trang đăng nhập, đăng ký)
        if (isset($_SESSION['isLogin']) || isset($_SESSION['isLogin_Admin'])) {
            header('Location: ' . _WEB_ROOT . '/');
            exit();
        }
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['isLogin']) || isset($_SESSION['isLogin_Admin']);
    }

    public function isAdmin()
    {
        return isset($_SESSION['isLogin_Admin']) && $_SESSION['user']['user_role'] == 1;
    }

    public function isEmployee()
    {
        return isset($_SESSION['isLogin_Admin']) && ($_SESSION['user']['user_role'] > 1);
    }
}
