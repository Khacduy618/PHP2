<?php
namespace App\Controllers;
use Core\Controller;

class Address extends Controller
{
    public $data =[];
    public $user_model;
    public $address_model;
    public $auth;


    public function __construct()
    {
        $this->auth = new \App\Middleware\AuthMiddleWare();
        $this->user_model = $this->model('UserModel');
        $this->address_model = $this->model('AddressModel');
    }

    public function list_address($userEmail=''){
        $this->auth->handleEmployeeAuth();
        $title = 'Address Management';
        $this->data['sub_content']['addresses'] = $this->address_model->getAllUserAddresses($userEmail);
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['content'] = 'backend/address/list';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function add_new($address_userEmail = '') {
        $this->auth->handleEmployeeAuth();
        $title = 'Add new address';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['address_userEmail'] = $address_userEmail;
        $this->data['sub_content']['user'] = $this->user_model->findbyId($address_userEmail);
        $this->data['content'] = 'backend/address/add';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function store(){
        $address_userEmail = $_POST['address_userEmail'];
        $address_street = $_POST['address_street'];
        $address_city = $_POST['address_city'];
        $address_province = $_POST['address_province'];
        $data = array(
            'address_userEmail' => $address_userEmail,
            'address_street' => $address_street,
            'address_city' => $address_city,
            'address_province' => $address_province
        );
        
        // Xử lý ký tự đặc biệt
        foreach ($data as $key => $value) {
            if (strpos($value, "'") != false) {
                $value = str_replace("'", "\'", $value);
                $data[$key] = $value;
            }
        }
        $status = $this->address_model->store($data);
    
        if ($status) {
            $_SESSION['msg'] = 'Address added successfully!';
            header('Location: '._WEB_ROOT.'/address');
        } else {
            setcookie('msg1', 'Failed to add address!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT . '/add-new-address');
        }
        exit();
    }
    public function edit($address_id = '') {
        $title = 'Edit address';
        $this->data['sub_content']['title'] = $title;
        $this->data['page_title'] = $title;
        $this->data['sub_content']['address'] = $this->model('AddressModel')->findbyId($address_id);
        $this->data['content'] = 'backend/address/edit';
        $this->render('layouts/admin_layout', $this->data);
    }

    public function update(){
        $address_id = $_POST['address_id'];
        $address_userEmail = $_POST['address_userEmail'];
        $address_street = $_POST['address_street'];
        $address_city = $_POST['address_city'];
        $address_province = $_POST['address_province'];
        $address_country = $_POST['address_country'];
        $data = array(
            'address_userEmail' => $address_userEmail,
            'address_street' => $address_street,
            'address_city' => $address_city,
            'address_province' => $address_province,
            'address_country' => $address_country
        );
        
        // Xử lý ký tự đặc biệt
        foreach ($data as $key => $value) {
            if (strpos($value, "'")!= false) {
                $value = str_replace("'", "\'", $value);
                $data[$key] = $value;
            }
        }
        $status = $this->address_model->update($address_id, $data);
        
        if ($status) {
            $_SESSION['msg'] = 'Address updated successfully!';
            header('Location: '._WEB_ROOT.'/address');
        } else {
            setcookie('msg1', 'Failed to update address!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT. '/edit-address');
        }
        exit();

    }

    public function delete($id=0) {
        $this->auth->handleAdminAuth();
        if($this->address_model->delete($id)){
            $_SESSION['msg'] = 'Address deleted successfully!';
            header('Location: '._WEB_ROOT.'/address');
        } else{
            setcookie('msg1', 'Failed to delete address!', time() + 5, '/');
            header('Location: ' . _WEB_ROOT. '/address');
            exit();
        }
    }
}
