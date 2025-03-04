<?php
namespace App\Controllers;
use Core\Controller;

class Product extends Controller
{
    public $data =[];
    public $product_model;
    public $category_model;
    public $auth;

    public function __construct()
    {
        $this->product_model = $this->model('ProductModel');
        $this->category_model = $this->model('CategoryModel');
        $this->auth = new \App\Middleware\AuthMiddleWare();
    }

    public function list_product($category_id = 0, $search = '', $sort = 'popularity', $perpage = 12, $page = 1) {
        $valid_sorts = ['price-high', 'price-low', 'popularity', 'rating', 'date', 'active', 'inactive'];
    

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path_parts = explode('/', trim($path, '/'));
        
        array_shift($path_parts); 
        array_shift($path_parts); 
        
        
        $category_id = (empty($path_parts[0]) || $path_parts[0] === '0') ? 0 : intval($path_parts[0]);
        $search = isset($path_parts[1]) && $path_parts[1] !== '' ? urldecode($path_parts[1]) : '';
        $sort = isset($path_parts[2]) && in_array($path_parts[2], $valid_sorts) ? $path_parts[2] : 'popularity';
        $page = isset($path_parts[3]) ? max(1, intval($path_parts[3])) : 1;
        
        $search = str_replace('-', ' ', $search);
        
        if (!empty($search)) {
            $_SESSION['search_keyword'] = $search;
        }

        $total_products = $this->product_model->getTotalProducts($search, $category_id);
        $total_pages = ceil($total_products / $perpage);
        
        $page = min($page, $total_pages);
        $page = max(1, $page);
        // Debug output
        // echo "Final parameters:\n";
        // echo "category_id: $category_id\n";
        // echo "search: " . ($search === '' ? '(empty)' : $search) . "\n";
        // echo "sort: $sort\n";
        // echo "perpage: $perpage\n";
        // echo "page: $page\n";
        if(isset($_SESSION['isLogin_Admin'])) {
            $this->auth->handleEmployeeAuth();
            $title = 'Product Management';
            $this->data['sub_content']['title'] = $title;
            $this->data['page_title'] = $title;
            $this->data['sub_content']['category_list'] = $this->category_model->getCatFill();
            $this->data['sub_content']['product_list'] = $this->product_model->getProductLists($search, $category_id, $sort, $page, $perpage);
            $this->data['sub_content']['total_products'] = $total_products;
            $this->data['sub_content']['current_page'] = $page;
            $this->data['sub_content']['total_pages'] = $total_pages;
            $this->data['content'] = 'backend/products/list';
            $this->render('layouts/admin_layout', $this->data);
        } else {
            $title = 'Product List';
            $this->data['sub_content']['title'] = $title;
            $this->data['page_title'] = $title;
            $this->data['sub_content']['product_list'] = $this->product_model->getProductLists($search, $category_id, $sort, $page, $perpage);
            $this->data['sub_content']['total_products'] = $total_products;
            $this->data['sub_content']['current_page'] = $page;
            $this->data['sub_content']['total_pages'] = $total_pages;
            $this->data['content'] = 'frontend/products/list';
            $this->render('layouts/client_layout', $this->data);
        }
    }

    public function add_new() {
        $this->auth->handleEmployeeAuth();
        
        $title = 'Add new a product';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists();
        $this->data['content'] = 'backend/products/add';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function store() {
        $this->auth->handleEmployeeAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate required fields
            if (empty($_POST['product_name']) || empty($_POST['product_price']) || 
                empty($_POST['product_cat']) || empty($_FILES['product_img']['name'])) {
                $_SESSION['msg1'] = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
                exit();
            }

            // Validate product name
            if(strlen($_POST['product_name']) < 5 || strlen($_POST['product_name']) > 100) {
                $_SESSION['msg1'] = 'Tên sản phẩm phải từ 5-100 ký tự!';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
                exit();
            }

            // Validate price
            if(!is_numeric($_POST['product_price']) || $_POST['product_price'] <= 0) {
                $_SESSION['msg1'] = 'Giá sản phẩm phải là số dương!';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
                exit();
            }

           

            // Validate stock quantity
            if(!is_numeric($_POST['product_count']) || $_POST['product_count'] < 0) {
                $_SESSION['msg1'] = 'Số lượng tồn kho không hợp lệ!';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
                exit();
            }

            // Validate image
            $file = $_FILES['product_img'];
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($ext, $allowed)) {
                $_SESSION['msg1'] = 'Chỉ chấp nhận file ảnh định dạng: jpg, jpeg, png!';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
                exit();
            }

            if ($file['size'] > 2 * 1024 * 1024) {
                $_SESSION['msg1'] = 'Kích thước file không được vượt quá 2MB!';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
                exit();
            }

            // Process image upload
            $new_filename = uniqid() . '.' . $ext;
            $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
            $upload_path = $base_path . '/public/uploads/products/';
            
            if (!$this->handleUpload($base_path, $upload_path, $file['tmp_name'], $new_filename)) {
                $_SESSION['msg1'] = 'Không thể tải lên file ảnh!';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
                exit();
            }

            // Prepare data
            $data = array(
                'product_name' => trim($_POST['product_name']),
                'product_price' => (float)$_POST['product_price'],
                'product_discount' => (int)$_POST['product_discount'],
                'product_count' => (int)$_POST['product_count'],
                'product_cat' => (int)$_POST['product_cat'],
                'product_status' => isset($_POST['product_status']) ? 1 : 0,
                'screen_cam' => trim($_POST['screen_cam'] ?? ''),
                'os' => trim($_POST['os'] ?? ''),
                'gpu' => trim($_POST['gpu'] ?? ''),
                'cpu' => trim($_POST['cpu'] ?? ''),
                'pin' => trim($_POST['pin'] ?? ''),
                'colors' => trim($_POST['colors'] ?? ''),
                'sizes' => trim($_POST['sizes'] ?? ''),
                'ram' => trim($_POST['ram'] ?? ''),
                'rom' => trim($_POST['rom'] ?? ''),
                'bluetooth' => trim($_POST['bluetooth'] ?? ''),
                'product_img' => $new_filename
            );

            // Handle special characters
            foreach ($data as $key => $value) {
                if (is_string($value) && strpos($value, "'") !== false) {
                    $data[$key] = str_replace("'", "\'", $value);
                }
            }

            // Store product
            if ($this->product_model->store($data)) {
                $_SESSION['msg'] = 'Thêm sản phẩm thành công!';
                header('Location: ' . _WEB_ROOT . '/product');
            } else {
                $_SESSION['msg1'] = 'Thêm sản phẩm thất bại!';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
            }
            exit();
        }
    }

    public function detail($id=0) {
        $title = 'Detail';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        if(isset($_SESSION['isLogin_Admin'])){
            $this->data['sub_content']['info'] = $this->product_model->getDetail($id);
            $this->data['content'] = 'backend/products/list';
            $this->render('layouts/admin_layout', $this->data);
        }else {
            $this->data['sub_content']['specifications'] = $this->product_model->getDetail($id);
            $product = $this->data['sub_content']['info'] =  $this->product_model->findbyId($id);
            $cat = $product['product_cat'];
            $this->data['sub_content']['related_product'] = $this->product_model->related_product($cat, $id);
            $this->data['content'] = 'frontend/products/detail';
            $this->render('layouts/client_layout', $this->data);
        }
        
    }

    public function delete($id=0) {
        $this->auth->handleAdminAuth();
        if($this->product_model->delete($id)){
            $_SESSION['msg'] = 'Product deleted successfully!';
            header('Location: '._WEB_ROOT.'/product');
        }else{
            $_SESSION['msg1'] = 'Failed to delete product!';

            header('Location: ' . _WEB_ROOT . '/product');
        }
        exit();
    }

    public function edit($id=0) {
        $this->auth->handleEmployeeAuth();
        if (!isset($_SESSION['isLogin_Admin'])) {
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
            exit();
        }
        $title = 'Update a product';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists();
        $this->data['sub_content']['item'] = $this->product_model->findbyId($id);
        $this->data['content'] = 'backend/products/edit';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function update() {
        $this->auth->handleEmployeeAuth();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['product_id'];

            // Validate required fields
            if (empty($_POST['product_name']) || empty($_POST['product_price']) || empty($_POST['product_cat'])) {
                $_SESSION['msg1'] = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
                header('Location: ' . _WEB_ROOT . '/edit-product/' . $id);
                exit();
            }

            // Validate product name
            if(strlen($_POST['product_name']) < 5 || strlen($_POST['product_name']) > 100) {
                $_SESSION['msg1'] = 'Tên sản phẩm phải từ 5-100 ký tự!';
                header('Location: ' . _WEB_ROOT . '/edit-product/' . $id);
                exit();
            }

            // Validate price
            if(!is_numeric($_POST['product_price']) || $_POST['product_price'] <= 0) {
                $_SESSION['msg1'] = 'Giá sản phẩm phải là số dương!';
                header('Location: ' . _WEB_ROOT . '/edit-product/' . $id);
                exit();
            }

            

            // Validate stock quantity
            if(!is_numeric($_POST['product_count']) || $_POST['product_count'] < 0) {
                $_SESSION['msg1'] = 'Số lượng tồn kho không hợp lệ!';
                header('Location: ' . _WEB_ROOT . '/edit-product/' . $id);
                exit();
            }

            // Handle image if uploaded
            if (!empty($_FILES['product_img']['name'])) {
                $file = $_FILES['product_img'];
                $allowed = ['jpg', 'jpeg', 'png'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (!in_array($ext, $allowed)) {
                    $_SESSION['msg1'] = 'Chỉ chấp nhận file ảnh định dạng: jpg, jpeg, png!';
                    header('Location: ' . _WEB_ROOT . '/edit-product/' . $id);
                    exit();
                }

                if ($file['size'] > 2 * 1024 * 1024) {
                    $_SESSION['msg1'] = 'Kích thước file không được vượt quá 2MB!';
                    header('Location: ' . _WEB_ROOT . '/edit-product/' . $id);
                    exit();
                }

                $new_filename = uniqid() . '.' . $ext;
                $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                $upload_path = $base_path . '/public/uploads/products/';
                
                if (!$this->handleUpload($base_path, $upload_path, $file['tmp_name'], $new_filename)) {
                    $_SESSION['msg1'] = 'Không thể tải lên file ảnh!';
                    header('Location: ' . _WEB_ROOT . '/edit-product/' . $id);
                    exit();
                }
            } else {
                $current_product = $this->product_model->findbyId($id);
                $new_filename = $current_product['product_img'];
            }

            // Prepare data
            $data = array(
                'product_name' => trim($_POST['product_name']),
                'product_price' => (float)$_POST['product_price'],
                'product_discount' => (int)$_POST['product_discount'],
                'product_count' => (int)$_POST['product_count'],
                'product_cat' => (int)$_POST['product_cat'],
                'product_status' => isset($_POST['product_status']) ? 1 : 0,
                'screen_cam' => trim($_POST['screen_cam'] ?? ''),
                'os' => trim($_POST['os'] ?? ''),
                'gpu' => trim($_POST['gpu'] ?? ''),
                'cpu' => trim($_POST['cpu'] ?? ''),
                'pin' => trim($_POST['pin'] ?? ''),
                'colors' => trim($_POST['colors'] ?? ''),
                'sizes' => trim($_POST['sizes'] ?? ''),
                'ram' => trim($_POST['ram'] ?? ''),
                'rom' => trim($_POST['rom'] ?? ''),
                'bluetooth' => trim($_POST['bluetooth'] ?? ''),
                'product_img' => $new_filename
            );

            // Handle special characters
            foreach ($data as $key => $value) {
                if (is_string($value) && strpos($value, "'") !== false) {
                    $data[$key] = str_replace("'", "\'", $value);
                }
            }

            // Update product
            if ($this->product_model->update($data, $id)) {
                $_SESSION['msg'] = 'Cập nhật sản phẩm thành công!';
                header('Location: ' . _WEB_ROOT . '/product');
            } else {
                $_SESSION['msg1'] = 'Cập nhật sản phẩm thất bại!';
                header('Location: ' . _WEB_ROOT . '/edit-product/' . $id);
            }
            exit();
        }
    }
}
