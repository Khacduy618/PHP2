<?php
class Home extends Controller{
    
    public $data =[];
    public $home_model;

    public function __construct()
    {
        $this->home_model = $this->model('HomeModel');
    }

    public function index() {
        $title = 'Home';
        $this->data['sub_content']['featured_products'] = $this->home_model->getFeaturedProducts(10);
        $this->data['sub_content']['sale_products'] = $this->home_model->getOnSaleProducts(10);
        $this->data['sub_content']['top_rated_products'] = $this->home_model->getTopRatedProducts(10);
        $this->data['sub_content']['deal_on'] = $this->home_model->getTopSellingAndOnSaleProducts(2);
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'frontend/home/index';
       $this->render('layouts/client_layout', $this->data);
    }

    
}