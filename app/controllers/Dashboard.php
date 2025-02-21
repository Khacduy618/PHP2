<?php
class Dashboard extends Controller
{
    public $data =[];
    public $dashboard_model;

    public function __construct()
    {
        //kiemtra admin
        if (!isset($_SESSION['isLogin_Admin'])) {
            header('Location: ' . _WEB_ROOT . '/dang-nhap');
            exit();
        }
        $this->dashboard_model = $this->model('DashboardModel');
    }

    public function index() {
        
        $title = 'Dashboard';
        // $this->data['sub_content']['product_list'] = $dataproduct;
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = 'Dashboard';
        $this->data['content'] = 'backend/dashboard/index';
       $this->render('layouts/admin_layout', $this->data);
    }
    
}

?>