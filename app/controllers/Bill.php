<?php
namespace App\Controllers;
use Core\Controller;
use Exception;

class Bill extends Controller
{
    public $data =[];
    public $bill_model;
    public $cartModel;
    public $address_model;
    public $auth;


    public function __construct()
    {
        $this->auth = new \App\Middleware\AuthMiddleWare();
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
        } else {
            header('location:' . _WEB_ROOT . '/dang-nhap');
        }

    }

    public function save_order(){
        if (isset($_SESSION['user']) && isset($_POST['bill_payment'])) {
            try {
                // Validate required fields
                $required_fields = [
                    'user_name' => 'Full Name',
                    'user_email' => 'Email',
                    'user_phone' => 'Phone'
                ];

                foreach ($required_fields as $field => $label) {
                    if (empty($_POST[$field])) {
                        setcookie('msg1', $label . ' is required', time() + 5);
                        header('Location: ' . _WEB_ROOT . '/cart');
                        return;
                    }
                }

                $userEmail = !empty($_POST['user_email']) ? $_POST['user_email'] : $_SESSION['user']['user_email'] ; 
                $user_name = !empty($_POST['user_name'])? $_POST['user_name'] : $_SESSION['user']['user_name'] ; 
                $user_phone = !empty($_POST['user_phone'])? $_POST['user_phone'] : $_SESSION['user']['user_phone'] ; 

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
                    if (!$this->bill_model->checkProductQuantity($item['product_id'], $item['quantity'])) {
                        setcookie('msg1', 'Sản phẩm ' . $item['product_name'] . ' không đủ số lượng', time() + 5);
                        header('Location: ' . _WEB_ROOT . '/cart');
                        return;
                    }
                }

                // Handle address logic
                $bill_address = '';
                if (!empty($_POST['address_name']) && !empty($_POST['address_street']) && 
                    !empty($_POST['address_city']) && !empty($_POST['address_province'])) {
                    // If new address details are provided, concatenate them
                    $bill_address = $_POST['address_name'] . ' , ' . 
                                  $_POST['address_street'] . ' , ' . 
                                  $_POST['address_city'] . ' , ' . 
                                  $_POST['address_province'];
                    $address_id = null; // Set address_id to null since we're using manual address
                } else {
                    // Use existing address from address_id
                    $address_id = isset($_POST['address_id']) ? (int)$_POST['address_id'] : null;
                    if ($address_id) {
                        $address = $this->address_model->getOneAddressById($address_id);
                        if ($address) {
                            $bill_address = $address['address_name'] . ' - ' . 
                                          $address['address_street'] . ' - ' . 
                                          $address['address_city'] . ' - ' . 
                                          $address['address_province'];
                        }
                    }
                }

                if(empty($bill_address)) {
                    setcookie('msg1', 'Please provide a valid address', time() + 5);
                    header('Location: ' . _WEB_ROOT . '/cart');
                    return;
                }

                // Tạo bill_var_id unique
                $bill_var_id = 'Tede-' . $user_name . '-' . date('YmdHis');
                
                // Lấy các giá trị từ POST
                $bill_payment = (int)$_POST['bill_payment'];
                $bill_status = $bill_payment == 1 ? 1 : 2;

                // Tạo mảng dữ liệu cho bill
                $bill_data = [
                    'bill_var_id' => $bill_var_id,
                    'bill_userEmail' => $userEmail,
                    'bill_phone' => $user_phone,
                    'bill_address' => $bill_address,
                    'bill_priceDelivery' => $_POST['shipping'] ?? 0,
                    'bill_price' => $_POST['total'] ?? 0,
                    'bill_totalPrice' => $_POST['tong'] ?? 0,
                    'bill_coupon' => isset($_POST['coupon_id']) ? $_POST['coupon_id'] : null,
                    'bill_payment' => $bill_payment,
                    'bill_status' => $bill_status
                ];

                // Thêm hóa đơn và lấy ID
                $bill_id = $this->bill_model->bill_insert_id($bill_data);

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
                            (int)$item['product_id'] . ", " . 
                            (int)$item['price'] . ", " . 
                            (int)$item['quantity'] . ")";
                        
                        if ($key !== array_key_last($_SESSION['cart_items'])) {
                            $values_string .= ", ";
                        }

                        $ordered_items[] = [
                            'product_name' => $item['product_name'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'total' => $item['price'] * $item['quantity']
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
                                'bill_address' => $bill_address,
                                'bill_phone' => $user_phone,
                                'bill_userEmail' => $userEmail,
                                'bill_payment' => $bill_payment == 1 ? 'Cash on delivery' : 
                                                ($bill_payment == 2 ? 'Direct bank transfer' : 
                                                ($bill_payment == 3 ? 'PayPal' : 'Credit Card (Stripe)')),
                                'bill_date' => date('Y-m-d H:i:s'),
                                'total_amount' => $_POST['total'] ?? 0,
                                'shipping_fee' => $_POST['shipping'] ?? 0,
                                'final_total' => $_POST['tong'] ?? 0,
                                'items' => $ordered_items
                            ];

                            // Xóa giỏ hàng sau khi đặt hàng thành công
                            unset($_SESSION['cart_items']);
                            header('Location:' . _WEB_ROOT . '/checkout_complete');
                        } else {
                            setcookie('msg1', 'Lỗi khi lưu chi tiết đơn hàng', time() + 5);
                            header('Location: ' . _WEB_ROOT . '/check-info');
                        }
                    } catch (Exception $e) {
                        error_log("Error in save(): " . $e->getMessage());
                        setcookie('msg1', $e->getMessage(), time() + 5);
                        header('Location: ' . _WEB_ROOT . '/cart');
                        return;
                    }
                } else {
                    setcookie('msg1', 'Đặt hàng không thành công', time() + 5);
                    header('Location: ' . _WEB_ROOT . '/check-info');
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

    public function checkout_completed() {
        $this->data['sub_content']['title'] = 'Checkout Completed';
        $this->data['page_title'] = 'Checkout Completed';
        $this->data['content'] = 'frontend/bill/bill';
        $this->render('layouts/client_layout', $this->data);
    }

    public function order_history() {
        if(!isset($_SESSION['user'])) {
            header('Location:' . _WEB_ROOT . '/dang-nhap');
            return;
        }

        $userEmail = $_SESSION['user']['user_email'];
        
        // Lấy danh sách đơn hàng từ database
        $bill = $this->bill_model->getBillByUserEmail($userEmail);
        
        if (!is_array($bill)) {
            $bill = array();
        }
        
        $this->data['sub_content']['title'] = 'Order History';
        $this->data['page_title'] = 'Order History';
        $this->data['sub_content']['bill'] = $bill;
        $this->data['content'] = 'frontend/bill/order_history';
        $this->render('layouts/client_layout', $this->data);
    }
    public function order_detail() {
        if(!isset($_SESSION['user'])) {
            echo 'Vui lòng đăng nhập để xem chi tiết đơn hàng';
            return;
        }

        // Lấy ID từ URL parameter thay vì POST
        $url = explode('/', $_SERVER['REQUEST_URI']);
        $id = end($url);
        
        if(is_numeric($id)) {
            try {
                $data = $this->bill_model->getBillDetailsByIdBill($id);
                
                if($data) {
                    // Kiểm tra nếu là AJAX request
                    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        // Trả về view AJAX
                        require_once _DIR_ROOT . "/app/views/frontend/bill/order_detail.php";
                    } else {
                        echo 'Yêu cầu không hợp lệ';
                    }
                } else {
                    echo 'Không tìm thấy thông tin đơn hàng';
                }
            } catch (Exception $e) {
                error_log("Error in order_detail: " . $e->getMessage());
                echo 'Có lỗi xảy ra khi lấy thông tin đơn hàng';
            }
        } else {
            echo 'ID đơn hàng không hợp lệ';
        }
    }

    public function listBills()
    {
        $this->auth->handleEmployeeAuth();
        $bills = $this->bill_model->getAll();
        $this->data['sub_content']['title'] = 'Danh sách đơn hàng';
        $this->data['page_title'] = 'Danh sách đơn hàng';
        $this->data['sub_content']['bills'] = $bills;
        $this->data['content'] = 'backend/bills/list';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function detail($billId = 0)
    {
        $this->auth->handleEmployeeAuth();
        if ($billId) {
            $billDetails = $this->bill_model->details($billId);
            
            // Kiểm tra xem có dữ liệu không
            if (!$billDetails) {
                // Nếu không có dữ liệu, set một mảng trống
                $billDetails = [];
            }
            
            // Định nghĩa mapping trạng thái
            $statusMapping = [
                1 => ['Chưa thanh toán', 'danger'],
                2 => ['Đã thanh toán', 'success'],
                3 => ['Đang xử lý', 'warning'],
                4 => ['Đã xác nhận', 'info'],
                5 => ['Đang giao hàng', 'primary'],
                6 => ['Đã giao hàng', 'success'],
                7 => ['Hoàn thành', 'success'],
                8 => ['Đã lưu trữ', 'secondary']
            ];
            $this->data['sub_content']['title'] = 'Chi tiết đơn hàng';
            $this->data['page_title'] = 'Chi tiết đơn hàng';
            $this->data['sub_content']['billDetails'] = $billDetails;
            $this->data['sub_content']['statusMapping'] = $statusMapping;
            $this->data['content'] = 'backend/bills/details';
            $this->render('layouts/admin_layout', $this->data);
            
        } else {
            header('Location:'._WEB_ROOT.'/bill');
        }
    }

    public function archivedBills()
    {
        $archivedBills = $this->bill_model->getArchivedBills();
        
        $this->data['sub_content']['title'] = 'Danh sách đơn hàng đã lưu trữ';
        $this->data['page_title'] = 'Danh sách đơn hàng đã lưu trữ';
        $this->data['sub_content']['archivedBills'] = $archivedBills;
        $this->data['content'] = 'backend/bills/archived';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function deleteBill($billId)
    {
        $this->auth->handleAdminAuth();
        if ($billId) {
            $this->bill_model->softDelete($billId);
            header('Location:'._WEB_ROOT.'/bill');
            exit;
        }
    }

    public function restoreBillArchived($id)
    {
        if ($id) {
            
            $this->bill_model->updateStatus($id, 7); 
            header('Location:'._WEB_ROOT.'/bill');
            exit;
        } else {
            header('Location:'._WEB_ROOT.'/archivedBills-bill');
        }
    }

    public function status($billId=0, $newStatus=0)
    {
        $this->auth->handleEmployeeAuth();
            $this->bill_model->updateStatus($billId, $newStatus);

            if ($newStatus == 8) {
                header('Location:'._WEB_ROOT.'/archivedBills-bill');
            } else {
                header('Location:'._WEB_ROOT. '/bill');
            }
            exit;
        
    }

    public function edit_bill_status_ajax()
    {
        header('Content-Type: application/json');
        
        // Đọc raw POST data vì đang gửi FormData
        $billId = $_POST['bill_id'] ?? null;
        $newStatus = $_POST['bill_status'] ?? null;

        if (!$billId || !$newStatus) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        try {
            $this->bill_model->updateStatus_ajax($billId, $newStatus);
            echo json_encode(['success' => true, 'message' => 'Update status success']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
