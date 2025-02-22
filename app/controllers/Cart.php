<?php
class Cart extends Controller
{
    public $data =[];
    public $cart_model;
    public $address_model;

    public function __construct()
    {
        if (!isset($_SESSION['isLogin'])) {
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
            exit();
        }
        $this->cart_model = $this->model('CartModel');
        $this->address_model = $this->model('AddressModel');
    }

    public function list_cart(){
        $title = 'Cart';
        $coupon_name = isset($_POST['coupon_name']) ? $_POST['coupon_name'] : '';
        $this->data['sub_content']['cart_list'] = $this->cart_model->getCartItems($_SESSION['user']['user_email']);
        $this->data['sub_content']['address'] = $this->address_model->getOneAddress($_SESSION['user']['user_email']);
        $this->data['sub_content']['addresses'] = $this->address_model->getAllUserAddresses($_SESSION['user']['user_email']);
        $this->data['sub_content']['coupon'] = $this->cart_model->coupon($coupon_name);
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'frontend/cart/list';
        $this->render('layouts/client_layout', $this->data);
    }

    public function add_cart()
    {
        // Kiểm tra dữ liệu đầu vào
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
        // Thêm sản phẩm vào giỏ hàng
        $status = $this->cart_model->addToCart($userEmail, $productId, $quantity);
        
        if ($status) {
            $_SESSION['msg'] = 'Cart item added successfully!';
            header('Location: '._WEB_ROOT.'/cart');
        } else {
            setcookie('msg1', 'Failed to add product to cart!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/cart');
        }
        exit();
    }

    public function update_cart()
    {
            // Kiểm tra dữ liệu đầu vào
            if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id']) || 
                !isset($_POST['quantity']) || !is_numeric($_POST['quantity'])) {
                header('location:'. _WEB_ROOT.'/cart');
                exit;
            }

            $userEmail = $_SESSION['user']['user_email'];
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            try {
                $this->cart_model->updateQuantity($userEmail, $productId, $quantity);
                header('location:' ._WEB_ROOT.'/cart');
            } catch (Exception $e) {
                // Xử lý lỗi
                echo "Lỗi: " . $e->getMessage();
            }
    }

    public function delete_cart()
    {
            $userEmail = $_SESSION['user']['user_email'];
            $productId = $_GET['product_id'];
            $this->cart_model->removeFromCart($userEmail, $productId);
            header('location:' ._WEB_ROOT.'/cart');
        
    }

    public function deleteall_cart()
    {
         
            $userEmail = $_SESSION['user']['user_email'];
            $this->cart_model->clearCart($userEmail);
            header('location:' ._WEB_ROOT.'/cart');
        
    }
}
