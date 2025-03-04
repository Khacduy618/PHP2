<?php
namespace App\Controllers;
use Core\Controller;

class Cart extends Controller
{
    public $data =[];
    public $cart_model;
    public $address_model;
    public $auth;

    public function __construct()
    {
        
        $this->cart_model = $this->model('CartModel');
        $this->address_model = $this->model('AddressModel');
        $this->auth = new \App\Middleware\AuthMiddleWare();

    }

    public function list_cart(){

        $this->auth->handleUserAuth();
        $title = 'Cart';
        
        // Add debug
        error_log('Session data: ' . print_r($_SESSION, true));
        
        $userEmail = $_SESSION['user']['user_email'];
        $this->data['sub_content']['cart_list'] = $this->cart_model->getCartItems($userEmail);
        
        // Khởi tạo biến coupon mặc định
        $this->data['sub_content']['coupon'] = null;
        
        // Kiểm tra coupon trong session và POST
        if(isset($_SESSION['coupon'])) {
            $this->data['sub_content']['coupon'] = $_SESSION['coupon'];
            error_log('Coupon from session: ' . print_r($_SESSION['coupon'], true));
        } elseif(isset($_POST['coupon_name'])) {
            $coupon = $this->cart_model->coupon($_POST['coupon_name']);
            if($coupon) {
                $_SESSION['coupon'] = $coupon;
                $this->data['sub_content']['coupon'] = $coupon;
                error_log('Coupon from POST: ' . print_r($coupon, true));
            }
        }
        
        $this->data['sub_content']['address'] = $this->address_model->getOneAddress($_SESSION['user']['user_email']);
        $this->data['sub_content']['addresses'] = $this->address_model->getAllUserAddresses($_SESSION['user']['user_email']);
        
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'frontend/cart/list';
        
        $this->render('layouts/client_layout', $this->data);
    }

    public function add_cart()
    
    {
        $this->auth->handleUserAuth();
        
        if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id']) || !isset($_POST['quantity']) || !is_numeric($_POST['quantity'])) {
            header('location:' . _WEB_ROOT.'/product');
            exit;
        }

        $userEmail = $_SESSION['user']['user_email'];
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        if($quantity < 1 ){
            header('location:' . _WEB_ROOT.'/product-detail/'. $productId);
            exit;
        }
        
        $status = $this->cart_model->addToCart($userEmail, $productId, $quantity);
        
        if ($status) {
            setcookie('msg', 'Successed to add product to cart!', time() + 5, '/');
            header('Location: '._WEB_ROOT.'/product');
        } else {
            setcookie('msg1', 'Failed to add product to cart!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/cart');
        }
        exit();
    }

    public function update_cart()
    {
        $this->auth->handleUserAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id']) || 
                !isset($_POST['quantity']) || !is_numeric($_POST['quantity'])) {
                header('location:'. _WEB_ROOT.'/cart');
                exit;
            }

            $userEmail = $_SESSION['user']['user_email'];
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            $this->cart_model->updateQuantity($userEmail, $productId, $quantity);
            header('location:' ._WEB_ROOT.'/cart');
        }
    }

    // Helper method to check if request is AJAX
    private function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function delete_cart($productId=0)
    {
        $this->auth->handleUserAuth();
        $userEmail = $_SESSION['user']['user_email'];
        $status = $this->cart_model->removeFromCart($userEmail, $productId);
        
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // AJAX request
            echo json_encode(['success' => true]);
            exit;
        } else {
            // Normal request
            header('location:' ._WEB_ROOT.'/cart');
        }
    }

    public function deleteall_cart()
    {
         
            $userEmail = $_SESSION['user']['user_email'];
            $this->cart_model->clearCart($userEmail);
            header('location:' ._WEB_ROOT.'/cart');
        
    }

    public function update_quantity() {
        if ($this->isAjax() && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $userEmail = $_SESSION['user']['user_email'];
            $productId = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
            
            if ($quantity >= 1 && $quantity <= 10) {
                $this->cart_model->updateQuantity($userEmail, $productId, $quantity);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
            }
            exit;
        }
    }

    public function apply_coupon() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $coupon_name = $_POST['coupon_name'] ?? '';
            
            if (empty($coupon_name)) {
                echo json_encode(['error' => 'Coupon code is required']);
                return;
            }

            $coupon = $this->cart_model->coupon($coupon_name);
            
            if ($coupon) {
                // Lưu vào session
                $_SESSION['coupon'] = $coupon;
                error_log('Applied coupon: ' . print_r($coupon, true));
                echo json_encode(['success' => true, 'coupon' => $coupon]);
            } else {
                echo json_encode(['error' => 'Invalid coupon code']);
            }
        }
    }
}
