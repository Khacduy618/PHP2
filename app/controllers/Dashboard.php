<?php
namespace App\Controllers;
use Core\Controller;

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
        $this->data['sub_content']['limit'] = isset($_GET['allPro']) ? $_GET['allPro'] : '';
        $this->data['sub_content']['offset'] = '';
        $this->data['sub_content']['days'] = $this->dashboard_model->getDaysInCurrentMonth();
        $this->data['sub_content']['months'] =  $this->dashboard_model->getMonthsInYear();
        $this->data['sub_content']['years'] =  $this->dashboard_model->get_years_for_report();
        $this->data['sub_content']['annual_revenue']  = $this->dashboard_model->calculate_month_revenue();
        $this->data['sub_content']['doanhthu_ngay'] = $this->dashboard_model->calculate_current_month_daily_revenue();
        $this->data['sub_content']['doanhthu_nam'] = $this->dashboard_model->calculate_annual_revenue();
        $this->data['sub_content']['total_revenue']  = $this->dashboard_model->calculate_revenue();
        $this->data['sub_content']['revenue'] =  $this->data['sub_content']['total_revenue']['revenue'];
        $this->data['sub_content']['total_dh'] = $this->dashboard_model->calculate_total_orders();
        $this->data['sub_content']['total_user'] =   $this->dashboard_model->get_total_users();
        $this->data['sub_content']['total_blogs'] =   $this->dashboard_model->get_total_blogs();
        $this->data['sub_content']['product_top'] =   $this->dashboard_model->product_top();
        $this->data['sub_content']['product_nonSell'] =   $this->dashboard_model->product_notIn_bill();
        $this->data['sub_content']['product_nonSell_5'] =  $this->dashboard_model->product_notIn_bill(5);
        $this->data['sub_content']['product_top_5'] =  $this->dashboard_model->product_top(5);
        $this->data['sub_content']['category_revenue_report'] =  $this->dashboard_model->generate_category_revenue_report();
        $title = 'Dashboard';
        // $this->data['sub_content']['product_list'] = $dataproduct;
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = 'Dashboard';
        $this->data['content'] = 'backend/dashboard/index';
       $this->render('layouts/admin_layout', $this->data);
    }
    
}

?>