<?php
class Home extends Controller{
    
    public $data =[];
    public $home_model;

    public function __construct()
    {
        $this->home_model = $this->model('HomeModel');
    }

    public function index() {
        $dataproduct = $this->home_model->getList();
        $title = 'Product List';
        $this->data['sub_content']['product_list'] = $dataproduct;
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = 'Home';
        $this->data['content'] = 'frontend/home/index';
       $this->render('layouts/client_layout', $this->data);
    }

    
}