<?php
$routes['default_controller'] = 'home';

/*
*   duong dan ao => duong dan that
*/
//frontend
//home
$routes['trang-chu'] = 'home';
//product
$routes['product'] = 'product/list_product';
$routes['product-detail'] = 'product/detail';
//login
$routes['dang-nhap'] = 'account/login';
$routes['log-out'] = 'account/logout';
//pass
//forgot_pass
$routes['forgot_password'] = 'account/check_email_form';
//reset_pass
$routes['reset_form'] = 'account/reset_password_form';
$routes['reset_password'] = 'account/reset_password';
$routes['check_email'] = 'account/check_email';
$routes['edit-password'] = 'account/check_email_reset';
//profiles
$routes['profile_user'] = 'profile/showProfile';
$routes['update_avatar'] = 'profile/update_avatar';

//backend
//dashboard
$routes['dash-board'] = 'dashboard';
//product
$routes['add-new-product'] = 'product/add_new';
$routes['store-product'] = 'product/store';
$routes['delete-product'] = 'product/delete';
$routes['edit-product'] = 'product/edit';
$routes['update-product'] = 'product/update';
?>