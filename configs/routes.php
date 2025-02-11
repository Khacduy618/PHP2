<?php
$routes['default_controller'] = 'home';

/*
*   duong dan ao => duong dan that
*/
//home
$routes['dash-board'] = 'dashboard';
$routes['trang-chu'] = 'home';
//product
$routes['san-pham'] = 'product/list_product';
$routes['product-detail'] = 'product/detail';
//login
$routes['dang-nhap'] = 'account/login';
$routes['log-out'] = 'account/logout';
//pass
$routes['reset_form'] = 'account/reset_password_form';
$routes['reset_password'] = 'account/reset_password';
$routes['check_email'] = 'account/check_email';
$routes['forgot_password'] = 'account/forgot_password';
//profiles
$routes['profile_user'] = 'profile/showProfile';
$routes['update_avatar'] = 'profile/update_avatar';
//anothers
$routes['tin-tuc/.+-(\d+).html'] = 'news/category/$1';
?>