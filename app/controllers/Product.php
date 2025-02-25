<?php
class Product extends Controller
{
    public $data =[];
    public $product_model;
    public $category_model;

    public function __construct()
    {
        $this->product_model = $this->model('ProductModel');
        $this->category_model = $this->model('CategoryModel');
    }

    public function list_product() {
        
        
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists();
        
        if(isset($_SESSION['isLogin_Admin'])){
            $title = 'Product Management';
            $this->data['sub_content']['title'] = $title;
            $this->data['page_title'] = $title;
            $dataProduct = $this->product_model->getProductLists($_SESSION['isLogin_Admin']);
            $this->data['sub_content']['product_list'] = $dataProduct;
            $this->data['content'] = 'backend/products/list';
            $this->render('layouts/admin_layout', $this->data);
        }else {
            $title = 'Product List';
            $this->data['sub_content']['title'] = $title;
            $this->data['page_title'] = $title;
            $dataProduct = $this->product_model->getProductLists();
            $this->data['sub_content']['product_list'] = $dataProduct;
            $this->data['content'] = 'frontend/products/list';
            $this->render('layouts/client_layout', $this->data);
        }
       
    }

    public function add_new() {
        if (!isset($_SESSION['isLogin_Admin'])) {
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
            exit();
        }
        $title = 'Add new a product';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists();
        $this->data['content'] = 'backend/products/add';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['product_name']) || empty($_POST['product_price']) || empty($_POST['product_cat']) || empty($_FILES['product_img']['name']))  {
                $_SESSION['msg'] = 'Fill in all required fields!';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
                exit;
            }
            $file = $_FILES['product_img'];
            $product_img = $file['name'];
            $tmp_name = $file['tmp_name'];
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($product_img, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $file["size"] < 2 * 1024 * 1024) {
                // Tạo tên file mới
                $new_filename = uniqid() . '.' . $ext;
                // Tạo đường dẫn đầy đủ đến thư mục uploads
                $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                $upload_path = $base_path . '/public/uploads/products/';
                $this->handleUpload($base_path,$upload_path,$tmp_name,$new_filename);
            }else{
                $_SESSION['msg'] = 'Định dạng file không hợp lệ (chỉ chấp nhận: jpg, jpeg, png - Dung lượng dưới 2MB)';
                header('Location: ' . _WEB_ROOT . '/add-new-product');
                exit;
            }
                $name = $_POST['product_name'];
                $price = $_POST['product_price'];
                $discount = $_POST['product_discount'];
                $count = $_POST['product_count'];
                $cat = $_POST['product_cat'];
                $status = isset($_POST['product_status']) ? 1 : 0;
                $screen_cam = isset($_POST['screen_cam']) ? $_POST['screen_cam'] : '';
                $os = isset($_POST['os']) ? $_POST['os'] : '';
                $gpu = isset($_POST['gpu']) ? $_POST['gpu'] : '';
                $cpu = isset($_POST['cpu']) ? $_POST['cpu'] : '';
                $pin = isset($_POST['pin']) ? $_POST['pin'] : '';
                $colors = isset($_POST['colors']) ? $_POST['colors'] : '';
                $sizes = isset($_POST['sizes']) ? $_POST['sizes'] : '';
                $ram = isset($_POST['ram']) ? $_POST['ram'] : '';
                $rom = isset($_POST['rom']) ? $_POST['rom'] : '';
                $bluetooth = isset($_POST['bluetooth']) ? $_POST['bluetooth'] : '';
                
                $data = array(
                    'product_name' => $name,
                    'product_price' => $price,
                    'product_discount' => $discount,
                    'product_count'  =>   $count,
                    'product_cat'  =>   $cat,
                    'product_status'  =>   $status,
                    'screen_cam'  =>   $screen_cam,
                    'os'  =>   $os,
                    'gpu'  =>   $gpu,
                    'pin'  =>   $pin,
                    'cpu'  =>   $cpu,
                    'colors'  =>   $colors,
                    'sizes'  =>   $sizes,
                    'ram'  =>   $ram,
                    'rom'  =>   $rom,
                    'bluetooth'  =>   $bluetooth,
                    'product_img'  =>   $new_filename
                );
                
                // Xử lý ký tự đặc biệt
                foreach ($data as $key => $value) {
                    if (strpos($value, "'") != false) {
                        $value = str_replace("'", "\'", $value);
                        $data[$key] = $value;
                    }
                }
                $status = $this->product_model->store($data);
            
                if ($status) {
                    $_SESSION['msg'] = 'Product added successfully!';
                    header('Location: '._WEB_ROOT.'/product');
                } else {
                    setcookie('msg1', 'Failed to add product!', time() + 5, '/');
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
            $this->data['sub_content']['info'] =  $this->product_model->findbyId($id);
            $this->data['content'] = 'frontend/products/detail';
            $this->render('layouts/client_layout', $this->data);
        }
        
    }

    public function delete($id=0) {
        if($this->product_model->delete($id)){
            $_SESSION['msg'] = 'Product deleted successfully!';
            header('Location: '._WEB_ROOT.'/product');
        }else{
            setcookie('msg1', 'Failed to delete product!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/product');
        }
        exit();
    }

    public function edit($id=0) {
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
        
        $id = $_POST['product_id'];
        $name = $_POST['product_name'];
        $price = $_POST['product_price'];
        $discount = $_POST['product_discount'];
        $count = $_POST['product_count'];
        $cat = $_POST['product_cat'];
        $status = isset($_POST['product_status']) ? 1 : 0;
        $screen_cam = isset($_POST['screen_cam']) ? $_POST['screen_cam'] : '';
        $os = isset($_POST['os']) ? $_POST['os'] : '';
        $gpu = isset($_POST['gpu']) ? $_POST['gpu'] : '';
        $cpu = isset($_POST['cpu']) ? $_POST['cpu'] : '';
        $pin = isset($_POST['pin']) ? $_POST['pin'] : '';
        $colors = isset($_POST['colors']) ? $_POST['colors'] : '';
        $sizes = isset($_POST['sizes']) ? $_POST['sizes'] : '';
        $ram = isset($_POST['ram']) ? $_POST['ram'] : '';
        $rom = isset($_POST['rom']) ? $_POST['rom'] : '';
        $bluetooth = isset($_POST['bluetooth']) ? $_POST['bluetooth'] : '';

        if (!empty($_FILES['product_img']['name'])) {
            //check img
            $file = $_FILES['product_img'];
            $product_img = $file['name'];
            $tmp_name = $file['tmp_name'];
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($product_img, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $file["size"] < 2 * 1024 * 1024) {
                // Tạo tên file mới
                $new_filename = uniqid() . '.' . $ext;
                // Tạo đường dẫn đầy đủ đến thư mục uploads
                $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                $upload_path = $base_path . '/public/uploads/products/';
                $this->handleUpload($base_path,$upload_path,$tmp_name,$new_filename);
            }else{
                $_SESSION['msg'] = 'Định dạng file không hợp lệ (chỉ chấp nhận: jpg, jpeg, png - Dung lượng dưới 2MB)';
                header('Location: ' . _WEB_ROOT . '/edit-product' . '/'.$id);
                exit;
            }
        } else {
            $current_product = $this->product_model->findbyId($id);
            $new_filename = $current_product['product_img'];
        }
        
        $data = array(
            'product_name' => $name,
            'product_price' => $price,
            'product_discount' => $discount,
            'product_count'  =>   $count,
            'product_cat'  =>   $cat,
            'product_status'  =>   $status,
            'screen_cam'  =>   $screen_cam,
            'os'  =>   $os,
            'gpu'  =>   $gpu,
            'pin'  =>   $pin,
            'cpu'  =>   $cpu,
            'colors'  =>   $colors,
            'sizes'  =>   $sizes,
            'ram'  =>   $ram,
            'rom'  =>   $rom,
            'bluetooth'  =>   $bluetooth,
            'product_img'  =>   $new_filename
        );
        
        // Xử lý ký tự đặc biệt
        foreach ($data as $key => $value) {
            if (strpos($value, "'") != false) {
                $value = str_replace("'", "\'", $value);
                $data[$key] = $value;
            }
        }
        $status = $this->product_model->update($data, $id);
            
        if ($status) {
            $_SESSION['msg'] = 'Product updated successfully!';
            header('Location: '._WEB_ROOT.'/product');
        } else {
            setcookie('msg1', 'Failed to update product!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/edit-product');
        }
        exit();

    }
}
