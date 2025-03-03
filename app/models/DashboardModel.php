<?php
namespace App\Models;

use Core\Model;
class DashboardModel extends Model
{
    
    function product_top($limit="") {
        $sql = "SELECT p.*, COALESCE(SUM(bd.pro_count), 0) as total_sold 
            FROM products p 
            JOIN bill_details bd ON p.product_id = bd.pro_id
            WHERE p.product_status = 1 AND p.product_count > 0 GROUP BY p.product_id ORDER BY total_sold DESC";
        if($limit){
            $sql.= " LIMIT $limit";
        }
        return $this->pdo_query_all($sql);
    }

    function getDaysInCurrentMonth() {
        $currentMonth = date('n');
        $currentYear = date('Y');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $days = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $days[] = $i;
        }
        return $days;
    }
    function getMonthsInYear() {

        $months = [];

        for ($i = 1; $i <= 12; $i++) {

            $months[] = date('F', mktime(0, 0, 0, $i, 1)); // Lấy tên tháng bằng hàm date

        }

        return $months;

    }

    function get_years_for_report() {
        $current_year = date('Y');
        $start_year = $current_year - 5;
        $years = range($start_year, $current_year);
        return $years;
    }
    function calculate_month_revenue() {
        $year = date('Y');
        $revenue = array();

        for ($month = 1; $month <= 12; $month++) {
            $sql = "SELECT SUM(bill_totalPrice) as revenue FROM bills 
                    WHERE MONTH(bill_time) = ? AND YEAR(bill_time) = ?";
            $result = $this->pdo_query_one($sql, [$month, $year]);
            $revenue[$month] = $result['revenue'];
        }

        return $revenue;
    }
    //tổng doanh thu hiện tại
    function calculate_revenue() {
        $sql = "SELECT SUM(bill_totalPrice) as revenue FROM bills";
        $result = $this->pdo_query_one($sql);
        return $result;
    }


    function calculate_total_orders() {
        $sql = "SELECT COUNT(*) as TotalOrders FROM bills";
        $stmt = $this->db->query($sql);
        $totalOrders = $stmt->fetchColumn();
        return $totalOrders;
    }

    function get_total_users() {
        $sql = "SELECT COUNT(*) as total_users FROM user where user_status = 1";
        
        $result = $this->pdo_query_one($sql);
        return $result['total_users'];
    }
    function get_total_blogs() {
        $sql = "SELECT COUNT(*) as total_blogs FROM blogs";
        
        $result = $this->pdo_query_one($sql);
        return $result['total_blogs'];
    }
    //tổng doanh thu của từng danh mục sản phẩm
    function generate_category_revenue_report(){
        $sql = "SELECT c.category_name, SUM((bd.pro_count*bd.pro_price)) AS total_revenue 
            FROM bill_details bd JOIN products p ON bd.pro_id = p.product_id 
            JOIN categories c ON p.product_cat = c.category_id GROUP BY c.category_id";
        return $this->pdo_query_all($sql);
    }
    function calculate_current_month_daily_revenue() {
        $current_month = date('n');
        $current_year = date('Y');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);
        $dailyRevenue = array();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $sql = "SELECT SUM(bill_totalPrice) as revenue FROM bills 
                    WHERE DAY(bill_time) = ? AND MONTH(bill_time) = ? AND YEAR(bill_time) = ?";
            $result = $this->pdo_query_one($sql, [$day, $current_month, $current_year]);
            $dailyRevenue[$day] = $result['revenue'];
        }

        return $dailyRevenue;
    }

    //doanh thu năm hiện tại trở về trước 5 năm
    function calculate_annual_revenue() {
        $current_year = date('Y');
        $start_year = $current_year - 5;
        $revenue = array();

        for ($year = $start_year; $year <= $current_year; $year++) {
            $sql = "SELECT SUM(bill_totalPrice) as revenue FROM bills 
                    WHERE YEAR(bill_time) = ?";
            $result = $this->pdo_query_one($sql, $year);
            $revenue[$year] = $result['revenue'];
        }

        return $revenue;
    }
    function product_notIn_bill($limit="") {
        $sql = "SELECT p.*
                FROM products p
                LEFT JOIN bill_details bd ON p.product_id = bd.pro_id WHERE p.product_status = 1
                GROUP BY p.product_id
                HAVING SUM(bd.pro_count) IS NULL OR SUM(bd.pro_count) = 0";
        if($limit){
            $sql.= " LIMIT $limit";
        }
        
        return $this->pdo_query_all($sql);
    }
}

?>