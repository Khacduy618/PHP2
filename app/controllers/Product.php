<?php
class Product extends Controller
{
    public $data =[];
    public $product_model;

    public function __construct()
    {
        $this->product_model = $this->model('ProductModel');
    }
    public function list_product() {
        $dataProduct = $this->product_model->getProductLists();
        $title = 'Product List';
        $this->data['sub_content']['product_list'] = $dataProduct;
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = 'Product';
        $this->data['content'] = 'frontend/products/list';
        $this->render('layouts/client_layout', $this->data);
    }

    public function detail($id=0) {
        $title = 'Detail';
        $this->data['sub_content']['info'] =  $this->product_model->getDetail($id);
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = 'Detail';
        $this->data['content'] = 'frontend/products/detail';
        $this->render('layouts/client_layout', $this->data);
    }
}
