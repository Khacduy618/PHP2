<?php

class Bill extends Controller
{
    public $data =[];
    public $bill_model;
    public $cartModel;
    public $address_model;


    public function __construct()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
            exit();
        }
        $this->cartModel = $this->model('CartModel');
        $this->bill_model = $this->model('BillModel');
        $this->address_model = $this->model('AddressModel');
    }

    public function check_info() {
        if (isset($_SESSION['user'])) {
            $userEmail = $_SESSION['user']['user_email'];


            if (isset($_POST['coupon'])) {
                $coupon_name = $_POST['coupon'];
                $coupon = $this->cartModel->coupon($coupon_name);
            }  
            if (isset($_POST['address_id'])) {
                $address_id = $_POST['address_id'];
                $address = $this->address_model->getOneAddressById($address_id);
            } 
            $selectedItemsJson = $_POST['selected_items'] ?? '';
            $selectedItems = json_decode($selectedItemsJson, true);
            
            if (empty($selectedItems)) {
                setcookie('msg1', 'Please select items to checkout', time() + 5);
                header('Location: ' . _WEB_ROOT . '/cart');
                exit;
            }

            if (isset($selectedItems)) {

                $this->data['sub_content']['cartItems'] = $selectedItems; // Đổi tên biến
                $this->data['sub_content']['address'] = $address;
                $this->data['sub_content']['shipping'] = $_POST['shipping'] ?? 0;
                $this->data['sub_content']['coupon'] = $coupon;
                
                $this->data['sub_content']['title'] = 'Checkout Information';
                $this->data['page_title'] = 'Checkout Information';
                $this->data['content'] = 'frontend/checkout/checkout';

                // Debug
                // error_log('Selected Items: ' . print_r($selectedItems, true));
            
                $this->render('layouts/client_layout', $this->data);
            } else {
                setcookie('msg1', 'Vui lòng chọn ít nhất 1 sản phẩm để thanh toán', time() + 5);
                header('Location: ' . _WEB_ROOT . '/cart');
                return;
            }

            require_once 'Views/index.php';
        } else {
            header('location: ?act=taikhoan');
        }

        try {
            // Lấy và validate dữ liệu từ form
            $selectedItemsJson = $_POST['selected_items'] ?? '';
            $selectedItems = json_decode($selectedItemsJson, true);
            
            if (empty($selectedItems)) {
                setcookie('msg1', 'Please select items to checkout', time() + 5);
                header('Location: ' . _WEB_ROOT . '/cart');
                exit;
            }

            // Lấy thông tin địa chỉ
            $userEmail = $_SESSION['user']['user_email'];
            // $address = $this->address_model->getOneAddress($_POST['address_id']);

            // Chuẩn bị dữ liệu cho view
            $this->data['sub_content']['cartItems'] = $selectedItems; // Đổi tên biến
            // $this->data['sub_content']['address'] = $address;
            $this->data['sub_content']['shipping'] = $_POST['shipping'] ?? 0;
            $this->data['sub_content']['coupon'] = $this->cartModel->coupon($name);
            
            $this->data['sub_content']['title'] = 'Checkout Information';
            $this->data['page_title'] = 'Checkout Information';
            $this->data['content'] = 'frontend/checkout/checkout';

            // Debug
            error_log('Selected Items: ' . print_r($selectedItems, true));
            
            $this->render('layouts/client_layout', $this->data);
            
        } catch (Exception $e) {
            error_log("Checkout error: " . $e->getMessage());
            setcookie('msg1', 'An error occurred during checkout', time() + 5);
            header('Location: ' . _WEB_ROOT . '/cart');
            exit;
        }
    }

    public function save_order(){
        if (isset($_SESSION['user']) && isset($_POST['bill_payment'])) {
            try {
                $userEmail = $_SESSION['user']['user_email'];
                $user_name = $_SESSION['user']['user_name'];

                // Kiểm tra session cart_items thay vì POST
                if (!isset($_SESSION['cart_items']) || empty($_SESSION['cart_items'])) {
                    setcookie('msg1', 'Vui lòng chọn sản phẩm để thanh toán', time() + 5);
                    header('Location: ' . _WEB_ROOT . '/cart');
                    return;
                }

                // Lấy cart_item_ids từ session cart_items
                $selectedItemIds = array_map(function($item) {
                    return $item['cart_item_id'];
                }, $_SESSION['cart_items']);

                // Kiểm tra số lượng sản phẩm trước khi đặt hàng
                foreach ($_SESSION['cart_items'] as $item) {
                    if (!$this->bill_model->checkProductQuantity($item['pro_id'], $item['quantity'])) {
                        setcookie('msg1', 'Sản phẩm ' . $item['product_name'] . ' không đủ số lượng', time() + 5);
                        header('Location: ' . _WEB_ROOT . '/cart');
                        return;
                    }
                }

                // Tạo bill_var_id unique
                $bill_var_id = 'Tede-' . $user_name . '-' . date('YmdHis');
                
                // Lấy các giá trị từ POST
                $address_id = isset($_POST['address_id']) ? (int)$_POST['address_id'] : null;
                $total = isset($_POST['total']) ? (int)$_POST['total'] : 0;
                $tong = isset($_POST['tong']) ? (int)$_POST['tong'] : 0;
                $shipping = isset($_POST['shipping']) ? (int)$_POST['shipping'] : 0;
                $coupon_id = !empty($_POST['coupon_id']) ? (int)$_POST['coupon_id'] : null;
            
                // Thêm kiểm tra mã giảm giá
                if ($coupon_id !== null) {
                    // Kiểm tra xem mã giảm giá có tồn tại không
                    $coupon = $this->cartModel->coupon_by_id($coupon_id);
                    if (!$coupon) {
                        setcookie('msg1', 'Mã giảm giá không hợp lệ', time() + 5);
                        header('Location: ' . _WEB_ROOT . '/cart');
                        return;
                    }
                }
                $bill_payment = (int)$_POST['bill_payment'];
                $bill_status = $bill_payment == 1 ? 1 : 2;

                $address = $this->address_model->getOneAddressById($address_id);
                if($coupon_id){
                    $this->cartModel-> coupon_update($coupon_id);
                }
                // Thêm hóa đơn và lấy ID
                $bill_id = $this->bill_model->bill_insert_id(
                    $bill_var_id,
                    $userEmail,
                    $_SESSION['login']['user_phone'] ?? '',
                    $address_id,
                    $shipping,
                    $tong,
                    $total,
                    $coupon_id,
                    $bill_payment,
                    $bill_status
                );

                if ($bill_id) {
                    // Tạo chuỗi values cho chi tiết hóa đơn
                    $values_string = "";
                    $ordered_items = [];
                    foreach ($_SESSION['cart_items'] as $key => $item) {
                        // Debug thông tin
                        error_log("Processing item: " . print_r($item, true));
                        
                        $values_string .= "(" . 
                            $bill_id . ", '" . 
                            $bill_var_id . "', " . 
                            (int)$item['pro_id'] . ", " . 
                            (int)$item['product_price'] . ", " . 
                            (int)$item['quantity'] . ")";
                        
                        if ($key !== array_key_last($_SESSION['cart_items'])) {
                            $values_string .= ", ";
                        }

                        $ordered_items[] = [
                            'product_name' => $item['product_name'],
                            'quantity' => $item['quantity'],
                            'price' => $item['product_price'],
                            'total' => $item['product_price'] * $item['quantity']
                        ];
                    }

                    error_log("Final values string: " . $values_string);

                    try {
                        // Thêm chi tiết hóa đơn và cập nhật số lượng sản phẩm
                        $detail_result = $this->bill_model->insert_bill_detail($values_string);

                        if ($detail_result) {
                            // Xóa các cart item đã chọn từ database
                            $this->cartModel->deleteSelectedCartItems($userEmail, $selectedItemIds);
                            
                            $_SESSION['order_complete'] = [
                                'bill_var_id' => $bill_var_id,
                                'bill_name' => $user_name,
                                'bill_address' => $address['address_name']. '-' . $address['address_street']. ' ' .$address['address_city'],
                                'bill_phone' => $_SESSION['login']['user_phone'],
                                'bill_userEmail' => $userEmail,
                                'bill_payment' => $bill_payment == 1 ? 'Cash on delivery' : 
                                                ($bill_payment == 2 ? 'Direct bank transfer' : 
                                                ($bill_payment == 3 ? 'PayPal' : 'Credit Card (Stripe)')),
                                'bill_date' => date('Y-m-d H:i:s'),
                                'total_amount' => $total,
                                'shipping_fee' => $shipping,
                                'final_total' => $tong,
                                'items' => $ordered_items
                            ];

                            // Xóa giỏ hàng sau khi đặt hàng thành công
                            unset($_SESSION['cart_items']);
                            header('Location: ?act=checkout&xuli=checkout_complete');
                        } else {
                            setcookie('msg1', 'Lỗi khi lưu chi tiết đơn hàng', time() + 5);
                            header('Location: ?act=checkout');
                        }
                    } catch (Exception $e) {
                        error_log("Error in save(): " . $e->getMessage());
                        setcookie('msg1', $e->getMessage(), time() + 5);
                        header('Location: ' . _WEB_ROOT . '/cart');
                        return;
                    }
                } else {
                    setcookie('msg1', 'Đặt hàng không thành công', time() + 5);
                    header('Location: ?act=checkout');
                }
            } catch (Exception $e) {
                setcookie('msg1', $e->getMessage(), time() + 5);
                header('Location: ' . _WEB_ROOT . '/cart');
                return;
            }
        } else {
            setcookie('msg1', 'Vui lòng đăng nhập và chọn phương thức thanh toán', time() + 5);
            header('Location: ' . _WEB_ROOT . '/cart');
        }
    }
}
