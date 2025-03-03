<?php
$routes['default_controller'] = 'home';

/*
*   duong dan ao => duong dan that
*/
//frontend
//profiles
$routes['profile'] = 'profile';
$routes['update_avatar'] = 'profile/update_avatar';
$routes['edit-profile'] = 'profile/edit_profile';
$routes['update-profile'] = 'profile/update_profile';
//home
$routes['trang-chu'] = 'home';
$routes['trending'] = 'home/getProductsByCategory';
$routes['topSell'] = 'home/getTopSellingProducts';

//product
$routes['product'] = 'product/list_product';
$routes['product-detail'] = 'product/detail';
//login
$routes['dang-nhap'] = 'account/login';
$routes['log-out'] = 'account/logout';
//pass
//forgot_pass
$routes['forgot_password'] = 'account/check_email_form';
$routes['send_email'] = 'account/send_email';
$routes['change_password_form'] = 'account/change_password_form';
$routes['change_password'] = 'account/change_password';
//reset_pass
$routes['check_email'] = 'account/check_email';
$routes['edit-password'] = 'account/check_email_reset';

//cart

$routes['cart'] = 'cart/list_cart';
$routes['add-to-cart'] = 'cart/add_cart';
$routes['update-cart'] = 'cart/update_cart';
$routes['update-quantity'] = 'cart/update_quantity';
$routes['delete-cart-item'] = 'cart/delete_cart';
$routes['delete-all-cart'] = 'cart/deleteall_cart';
$routes['apply-coupon'] = 'cart/apply_coupon';


//checkout
$routes['check-info'] = 'bill/check_info';
$routes['checkout'] = 'bill/save_order';
$routes['checkout_complete'] = 'bill/checkout_completed';
$routes['order_history'] = 'bill/order_history';
$routes['order_detail'] = 'bill/order_detail';



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
//bill
$routes['bill'] = 'bill/listBills';
$routes['detail-bill'] = 'bill/detail';
$routes['delete-bill'] = 'bill/deleteBill';
$routes['archivedBill'] = 'bill/status';
$routes['restoreBillArchived-bill'] = 'bill/restoreBillArchived';
$routes['archivedBills-bill'] = 'bill/archivedBills';
$routes['status']= 'bill/status';
?>