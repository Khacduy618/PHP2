<?php
namespace App\Controllers;
use Core\Controller;
class Category extends Controller
{
    public $data =[];
    public $category_model;
    private $productCounts = [];
    public $auth;

    public function __construct()
    {
        $this->category_model = $this->model('CategoryModel');
        $this->auth = new \App\Middleware\AuthMiddleWare();
        
    }

    public function list_category() {
        // Get URL parts
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path_parts = explode('/', trim($path, '/'));
        
        // Remove 'php2' and 'category' from parts if they exist
        if(isset($path_parts[0]) && $path_parts[0] == 'php2') {
            array_shift($path_parts);
        }
        if(isset($path_parts[0]) && $path_parts[0] == 'category') {
            array_shift($path_parts);
        }
        
        // Get search and status parameters
        $search = isset($path_parts[0]) && $path_parts[0] !== '' ? urldecode($path_parts[0]) : '';
        $status = isset($path_parts[1]) ? $path_parts[1] : 'all';
        
        // Convert URL-friendly search term back to normal
        $search = str_replace('-', ' ', $search);
        
        // Store search keyword in session if not empty
        if (!empty($search)) {
            $_SESSION['search_keyword'] = $search;
        } else {
            unset($_SESSION['search_keyword']);
        }

        $title = 'Category Management';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        
        // Get filtered categories
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists($search, $status);
        
        $this->data['content'] = 'backend/categories/list';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function add_new() {
        $this->auth->handleEmployeeAuth();
        $title = 'Add new a category';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists();
        $this->data['content'] = 'backend/categories/add';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function store() {
        $this->auth->handleEmployeeAuth();
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
        $this->auth->handleEmployeeAuth();
        $title = 'Update a category';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['category_list'] = $this->category_model->getCategoryLists();
        $this->data['sub_content']['category'] = $this->category_model->findbyId($id);
        $this->data['content'] = 'backend/categories/edit';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function update() {
        $this->auth->handleEmployeeAuth();
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
        $this->auth->handleEmployeeAuth();
        if($this->category_model->delete($id)){
            $_SESSION['msg'] = 'Category deleted successfully!';
            header('Location: '._WEB_ROOT.'/category');
        }else{
            setcookie('msg1', 'Failed to delete category!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/category');
        }
        exit();
    }
    
    private function calculateTotalProducts($data, $category_id) {
        
        // Nếu đã tính toán trước đó, trả về kết quả từ cache
        if (isset($this->productCounts[$category_id])) {
            return $this->productCounts[$category_id];
        }

        $total = 0;
        foreach ($data as $item) {
            // Đếm sản phẩm của chính category này
            if ($item['category_id'] == $category_id) {
                $total = $item['product_count'];
                break;
            }
        }

        // Đếm sản phẩm từ tất cả subcategories
        foreach ($data as $item) {
            if ($item['parent_id'] == $category_id) {
                $total += $this->calculateTotalProducts($data, $item['category_id']);
            }
        }

        // Lưu kết quả vào cache
        $this->productCounts[$category_id] = $total;
        return $total;
    }

    // Tìm tất cả subcategories của một category
    private function findSubCategories($data, $parent_id) {
        $subs = [];
        foreach ($data as $item) {
            if ($item['parent_id'] == $parent_id) {
                $subs[] = $item;
                $subs = array_merge($subs, $this->findSubCategories($data, $item['category_id']));
            }
        }
        return $subs;
    }

    function menu($data, $parent_id = NULL) {
        $output = '';
        
    

        foreach ($data as $value) {
            if ($value['parent_id'] == $parent_id) {
                // Reset cache for new calculations
                $this->productCounts = [];
                
                // Calculate total products including all subcategories
                $totalProducts = $this->calculateTotalProducts($data, $value['category_id']);
                
                // Check if category has children
                $hasChildren = false;
                foreach ($data as $child) {
                    if ($child['parent_id'] == $value['category_id'] || $value['parent_id'] === 0) {
                        $hasChildren = true;
                        break;
                    }
                }

                if ($hasChildren) {
                    // Parent category with subcategories
                    $output .= '<div class="filter-item">';
                    $output .= '<div class="custom-control  parent-category">';
                              '" name="category[]" value="' . $value['category_id'] . '">';
                    $output .= '<label class="custom-control-label" for="cat-' . $value['category_id'] . '">' . 
                              ucfirst($value['category_name']) . '</label>';
                    $output .= '<span class="Categories-count">' . $totalProducts . '</span>';
                    $output .= '<p class="toggle-icon"></p>';
                    $output .= '</div>';
                    
                    // Sub categories container
                    $output .= '<div class="sub-categories">';
                    $output .= $this->menu($data, $value['category_id']);
                    $output .= '</div>';
                    $output .= '</div>';
                } else {
                    // Single category without subcategories
                    $output .= '<div class="filter-item sub-cat">';
                    $output .= '<div class="custom-control">';
                    
                    $output .= '<a href="'._WEB_ROOT.'/product'.'/' . $value['category_id'] . '" class="category-link">';
                    $output .= '<label class="custom-control-label" for="cat-' . $value['category_id'] . '">' . 
                            ucfirst($value['category_name']) . '</label>';
                    $output .= '<span class="subCategories-count">' . $value['product_count'] . '</span>';
                    $output .= '</a>';
                    $output .= '</div>';
                    $output .= '</div>';

                }
            }
        }
        return $output;
    }
    public function list_cat() {
        $data = $this->category_model->list();
        return $this->menu($data);
    }

    //menu cate home

    private function findSubCategories_home($data, $parent_id) {
        $subs = [];
        foreach ($data as $item) {
            if ($item['parent_id'] == $parent_id) {
                $subs[] = $item;
                $subs = array_merge($subs, $this->findSubCategories($data, $item['category_id']));
            }
        }
        return $subs;
    }

    function menu_home($data, $parent_id = NULL) {
        $output = '';

        foreach ($data as $value) {
            
            if ($value['parent_id'] == $parent_id) {
                // Check if category has children
                $hasChildren = false;
                foreach ($data as $child) {
                    if ($child['parent_id'] == $value['category_id']) {
                        $hasChildren = true;
                        break;
                    }
                }

                // Start list item
                $output .= '<li class="' . ($hasChildren ? 'has-submenu' : '') . '">';
                $output .= '<a ';
                // Add category link
                if($value['parent_id'] != NULL){
                $output .= 'href="'._WEB_ROOT.'/product'.'/' . $value['category_id'] . '"';
                }
                $output .= '>';
                $output .= ucfirst($value['category_name']);
                if ($hasChildren) {
                    $output .= '<i class="icon-angle-right"></i>';
                }
                $output .= '</a>';

                // If has children, add submenu
                if ($hasChildren) {
                    $output .= '<ul>';
                    $output .= $this->menu_home($data, $value['category_id']);
                    $output .= '</ul>';
                }

                $output .= '</li>';
            }
        }
        
        return $output;
    }
    public function list_cat_home() {
        $data = $this->category_model->list();
        return $this->menu_home($data);
    }
}
