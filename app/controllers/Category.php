<?php
class Category extends Controller
{
    public $data =[];
    public $category_model;

    public function __construct()
    {
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

    


}
