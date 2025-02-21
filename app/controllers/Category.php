<?php
class Category extends Controller
{
    public $data =[];
    public $category_model;

    public function __construct()
    {
        if (!isset($_SESSION['isLogin_Admin'])) {
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
            exit();
        }
        $this->category_model = $this->model('CategoryModel');
    }

    public function list_category() {
        $title = 'Category List';
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists();
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'backend/categories/list';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function add_new() {
        $title = 'Add new a category';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists();
        $this->data['content'] = 'backend/categories/add';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['category_name']) || empty($_POST['category_desc']) || empty($_FILES['category_img']['name']))  {
                $_SESSION['msg'] = 'Fill in all required fields!';
                header('Location: ' . _WEB_ROOT . '/add-new-category');
                exit;
            }
            $file = $_FILES['category_img'];
            $category_img = $file['name'];
            $tmp_name = $file['tmp_name'];
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($category_img, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $file["size"] < 2 * 1024 * 1024) {
                // Tạo tên file mới
                $new_filename = uniqid() . '.' . $ext;
                // Tạo đường dẫn đầy đủ đến thư mục uploads
                $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                $upload_path = $base_path . '/public/uploads/categories/';
                $this->handleUpload($base_path,$upload_path,$tmp_name,$new_filename);
            }else{
                $_SESSION['msg'] = 'Định dạng file không hợp lệ (chỉ chấp nhận: jpg, jpeg, png - Dung lượng dưới 2MB)';
                header('Location: ' . _WEB_ROOT . '/add-new-category');
                exit;
            }
                $category_name = $_POST['category_name'];
                $category_desc = $_POST['category_desc'];
                $parent_id = (!empty($_POST['parent_id'])) ? $_POST['parent_id'] : NULL;
                $category_status = $_POST['category_status'];
                
                
                $data = array(
                    'category_name' => $category_name,
                    'category_desc' => $category_desc,
                    'parent_id' => $parent_id,
                    'category_status'  =>   $category_status,
                    'category_img'  =>   $new_filename
                );
                
                // Xử lý ký tự đặc biệt
                foreach ($data as $key => $value) {
                    if (strpos($value, "'") != false) {
                        $value = str_replace("'", "\'", $value);
                        $data[$key] = $value;
                    }
                }
                $status = $this->category_model->store($data);
            
                if ($status) {
                    $_SESSION['msg'] = 'Category added successfully!';
                    header('Location: '._WEB_ROOT.'/category');
                } else {
                    setcookie('msg1', 'Failed to add category!', time() + 5, '/');
                    header('Location: ' . _WEB_ROOT . '/add-new-category');
                }
                exit();
            
        }
    }

    public function edit($id=0) {
        $title = 'Update a category';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists();
        $this->data['sub_content']['category'] = $this->category_model->findbyId($id);
        $this->data['content'] = 'backend/categories/edit';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function update() {
        
        $id = $_POST['category_id'];
        $category_name = $_POST['category_name'];
        $category_desc = $_POST['category_desc'];
        $parent_id = (!empty($_POST['parent_id'])) ? $_POST['parent_id'] : NULL;
        $category_status = $_POST['category_status'];

        if (!empty($_FILES['category_img']['name'])) {
            //check img
            $file = $_FILES['category_img'];
            $category_img = $file['name'];
            $tmp_name = $file['tmp_name'];
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($category_img, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $file["size"] < 2 * 1024 * 1024) {
                // Tạo tên file mới
                $new_filename = uniqid() . '.' . $ext;
                // Tạo đường dẫn đầy đủ đến thư mục uploads
                $base_path = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
                $upload_path = $base_path . '/public/uploads/categories/';
                $this->handleUpload($base_path,$upload_path,$tmp_name,$new_filename);
            }else{
                $_SESSION['msg'] = 'Định dạng file không hợp lệ (chỉ chấp nhận: jpg, jpeg, png - Dung lượng dưới 2MB: 1920px x 1080px)';
                header('Location: ' . _WEB_ROOT . '/edit-category' . '/'.$id);
                exit;
            }
        } else {
            $current_cate = $this->category_model->findbyId($id);
            $new_filename = $current_cate['category_img'];
        }
        
        $data = array(
            'category_name' => $category_name,
            'category_desc' => $category_desc,
            'parent_id' => $parent_id,
            'category_status'  =>   $category_status,
            'category_img'  =>   $new_filename
        );
        
        // Xử lý ký tự đặc biệt
        foreach ($data as $key => $value) {
            if (strpos($value, "'") != false) {
                $value = str_replace("'", "\'", $value);
                $data[$key] = $value;
            }
        }
        $status = $this->category_model->update($data, $id);
            
        if ($status) {
            $_SESSION['msg'] = 'Category updated successfully!';
            header('Location: '._WEB_ROOT.'/category');
        } else {
            setcookie('msg1', 'Failed to update category!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/edit-category');
        }
        exit();

    }

    public function delete($id=0) {
        if($this->category_model->delete($id)){
            $_SESSION['msg'] = 'Category deleted successfully!';
            header('Location: '._WEB_ROOT.'/category');
        }else{
            setcookie('msg1', 'Failed to delete category!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/category');
        }
        exit();
    }

}
