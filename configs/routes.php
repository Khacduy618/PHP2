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
//cart

$routes['cart'] = 'cart/list_cart';
$routes['add-to-cart'] = 'cart/add_cart';
$routes['update-cart'] = 'cart/update_cart';
$routes['delete-cart-item'] = 'cart/delete_cart';
$routes['delete-all-cart'] = 'cart/deleteall_cart';

//backend
//dashboard
$routes['dash-board'] = 'dashboard';
//products
$routes['add-new-product'] = 'product/add_new';
$routes['store-product'] = 'product/store';
$routes['delete-product'] = 'product/delete';
$routes['edit-product'] = 'product/edit';
$routes['update-product'] = 'product/update';
//categories
$routes['category'] = 'category/list_category';
$routes['add-new-category'] = 'category/add_new';
$routes['store-category'] = 'category/store';
$routes['delete-category'] = 'category/delete';
$routes['edit-category'] = 'category/edit';
$routes['update-category'] = 'category/update';
//users
$routes['user'] = 'user/list_user';
$routes['store-user'] = 'user/store';
$routes['delete-user'] = 'user/delete';
$routes['edit-user'] = 'user/edit';
$routes['update-user'] = 'user/update';
$routes['add-new-user'] = 'user/add_new';
//address
$routes['address'] = 'address/list_address';
$routes['store-address'] = 'address/store';
$routes['delete-address'] = 'address/delete';
$routes['edit-address'] = 'address/edit';
$routes['update-address'] = 'address/update';
$routes['add-new-address'] = 'address/add_new';
?>