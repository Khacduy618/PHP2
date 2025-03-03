<?php
namespace App\Controllers;

use Core\Controller;

class Home extends Controller
{
    public $data = [];
    public $home_model;

    public function __construct()
    {
        $this->home_model = $this->model('HomeModel');
    }

    public function index() {
        $title = 'Home';
        
        // Lấy 3 danh mục cha (Smartphones, Tablets, Laptops)
        $this->data['sub_content']['parent_categories'] = $this->home_model->getParentCategories(3);
        
        // Debug để kiểm tra dữ liệu
        // echo '<pre>';
        // print_r($this->data['sub_content']['parent_categories']);
        // echo '</pre>';
        
        $this->data['sub_content']['featured_products'] = $this->home_model->getFeaturedProducts(10);
        $this->data['sub_content']['sale_products'] = $this->home_model->getOnSaleProducts(10);
        $this->data['sub_content']['top_rated_products'] = $this->home_model->getTopRatedProducts(10);
        $this->data['sub_content']['deal_on'] = $this->home_model->getTopSellingAndOnSaleProducts(2);
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'frontend/home/index';
       $this->render('layouts/client_layout', $this->data);
    }

    public function getProductsByCategory() {
        if(isset($_POST['category_id'])) {
            $products = $this->home_model->getTrendingProducts($_POST['category_id']);
            // Debug AJAX response
            error_log('Products for category ' . $_POST['category_id'] . ': ' . print_r($products, true));
            echo json_encode($products);
        }
    }

    public function getTopSellingProducts() {
        // header('Content-Type: application/json');
        
        if(!isset($_POST['category_id'])) {
            echo json_encode(['error' => 'Category ID is required']);
            exit;
        }

            $categoryId = $_POST['category_id'];
            $products = $this->home_model->getTopSellingProductsByCategory($categoryId);
            
            // Debug
            error_log("Request received for category: " . $categoryId);
            error_log("Found " . count($products) . " products");
            
            echo json_encode($products);
       
        exit;
    }
}