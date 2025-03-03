

<div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
    <div class="container">
        <h1 class="page-title">Checkout<span>Shop</span></h1>
    </div><!-- End .container -->
</div><!-- End .page-header -->
<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?act=home">Home</a></li>
            <li class="breadcrumb-item"><a href="?act=cart">Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </div><!-- End .container -->
</nav><!-- End .breadcrumb-nav -->
<?php if(isset($_COOKIE['msg'])): ?>
<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
    <strong><?= $_COOKIE['msg'] ?></strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<?php if(isset($_COOKIE['msg1'])): ?>
<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
    <strong><?= $_COOKIE['msg1'] ?></strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>
<div class="page-content">
    <div class="checkout">
        <div class="container">
        <div class="checkout-discount">
        <form id="coupon-form" action="javascript:void(0)">
            <input type="text" class="form-control" name="coupon_name" required id="checkout-discount-input"
                value="<?= isset($_POST['coupon_name']) ? $_POST['coupon_name'] : '' ?>">
            <label for="checkout-discount-input" class="text-truncate" id="coupon-label">
                <?php if(isset($coupon) && is_array($coupon) && isset($coupon['coupon_name'])): ?>
                    <?= $coupon['coupon_name'] ?>
                <?php else: ?>
                    Have a coupon? <span>Click here to enter your code</span>
                <?php endif; ?>
            </label>
        </form>
        </div>
            <form action="<?= _WEB_ROOT ?>/checkout" id='form_thanhtoan' method="POST">
                
                <div class="row">
                    <div class="col-lg-9">
                        <h2 class="checkout-title">Billing Details</h2><!-- End .checkout-title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Full Name *</label>
                                <input type="text" class="form-control" name="user_name"
                                    placeholder="<?=$_SESSION['user']['user_name']?>" value="<?=$_SESSION['user']['user_name']?>">
                            </div><!-- End .col-sm-6 -->

                        </div><!-- End .row -->
                        <label>Email address *</label>
                        <input type="email" class="form-control" name="user_email"
                            placeholder="<?=$_SESSION['user']['user_email']?>" value="<?=$_SESSION['user']['user_email']?>">
                        <label for="phone">Phone *</label>
                        <input type="text" class="form-control" name="user_phone"
                            placeholder="<?=isset($_SESSION['user']['user_phone']) ? $_SESSION['user']['user_phone'] : ''?>" value="<?=isset($_SESSION['user']['user_phone']) ? $_SESSION['user']['user_phone'] : ''?>">
                        
                        <label>Address Name</label>
                        <input type="text" class="form-control" name="address_name"
                            placeholder="<?=isset($address['address_name']) ? $address['address_name'] : ''?>" value="<?=isset($address['address_name']) ? $address['address_name'] : ''?>" required>

                        <label>Province *</label>
                        <input type="text" class="form-control" name="address_province"
                            placeholder="<?=isset($address['address_province']) ? $address['address_province'] : ''?>" value="<?=isset($address['address_province']) ? $address['address_province'] : ''?>" required>

                        <label>City *</label>
                        <input type="text" class="form-control" name="address_city"
                            placeholder="<?=isset($address['address_city']) ? $address['address_city'] : ''?>" value="<?=isset($address['address_city']) ? $address['address_city'] : ''?>" required>

                        <label>Street address *</label>
                        <input type="text" class="form-control" name="address_street"
                            placeholder="<?=isset($address['address_street']) ? $address['address_street'] :''?>" value="<?=isset($address['address_street']) ? $address['address_street'] :''?>" required>
                        <?php if(!isset($_SESSION['user'])) {
                        ?>
                        <div class="custom-control custom-checkbox">
                            <a href="?act=taikhoan&xuli=dangky" class="custom-control-label">Create an account?</a>
                        </div><!-- End .custom-checkbox -->
                        <?php }
                        ?>

                       
                    </div><!-- End .col-lg-9 -->
                    <aside class="col-lg-3">
                        <div class="summary">
                            <h3 class="summary-title">Your Order</h3><!-- End .summary-title -->

                            <table class="table table-summary">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="widthTH"></th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $tong = 0;
                                    $_SESSION['cart_items'] = $cartItems;
                                    if (!empty($cartItems) && is_array($cartItems)) {
                                        foreach ($cartItems as $item) {
                                            // Đảm bảo các key tồn tại
                                            $price = isset($item['price']) ? $item['price'] : 0;
                                            $quantity = isset($item['quantity']) ? $item['quantity'] : 0;
                                            $ttien = $price * $quantity;
                                            $tong += $ttien;
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?=_WEB_ROOT?>/product-detail/<?=$item['product_id']?>">
                                                <?=htmlspecialchars($item['product_name'])?>
                                            </a>
                                        </td>
                                        <td>x <?=$quantity?></td>
                                        <td><?=number_format($ttien,0,",",".")?> đ</td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                        echo '<tr><td colspan="3">No items selected</td></tr>';
                                    }
                                    ?>
                                    <?php
                                    $discount = 0;
                                    if (isset($coupon) && is_array($coupon) && isset($coupon['coupon_discount'])) {
                                        $discount = $tong * ($coupon['coupon_discount'] / 100);
                                    }
                                    $total = $tong + $shipping - $discount;
                                    ?>
                                    <tr class="summary-subtotal">
                                        <td>Subtotal:</td>
                                        <td></td>
                                        <td><?=number_format($tong,0,",",".")?> đ</td>
                                    </tr><!-- End .summary-subtotal -->
                                    <tr>
                                        <td>Shipping:</td>
                                        <td></td>
                                        <td><?=number_format($shipping,0,",",".")?>
                                            đ</td>
                                    </tr>
                                    <?php if(is_array($coupon) && isset($coupon['coupon_discount'])){?>
                                    <tr class="summary-coupon">
                                        <td>Coupon:</td>
                                        <td class="discount-name"><?=$coupon['coupon_name']?></td>
                                        <td class="discount-amount"><?=number_format($discount,0,",",".")?>
                                            đ</td>
                                
                                    </tr>
                                        <?php }
                                    ?>
                                    <tr class=" summary-total">
                                        <td>Total:</td>
                                        <td></td>
                                        <td><?=number_format($total,0,",",".")?>
                                            đ</td>
                                    </tr><!-- End .summary-total -->
                                </tbody>
                            </table><!-- End .table table-summary -->
                            <?php
                                                $paymentsMapping = [
                                                    1 => 'Cash on delivery',
                                                    2 => 'Direct bank transfer',
                                                    3 => 'PayPal',
                                                    4 => 'Credit Card (Stripe)'
                                                ];
                                        ?>
                            <div class="accordion-summary" id="accordion-payment">
                                <?php foreach ($paymentsMapping as $status => $statusText){?>
                                <div class="d-flex align-item-center">
                                    <input type="radio" id="bill_payment<?=$status?>" name="bill_payment"
                                        value="<?=$status?>">
                                    <label for="bill_payment<?=$status?>">
                                        <?=$statusText?>
                                    </label>
                                </div><!-- End .card -->
                                <?php } ?>
                            </div><!-- End .accordion -->
                            <input type="hidden" name="address_id" value="<?=$address['address_id']?>">
                            <input type="hidden" name="total" value="<?=$total?>">
                            <input type="hidden" name="tong" value="<?=$tong?>">
                            <input type="hidden" name="coupon_id" value="<?= isset($coupon['coupon_id']) ? $coupon['coupon_id'] : 0 ?>">
                            <input type="hidden" name="shipping" value="<?=$shipping?>">
                            <button type="submit" class="btn btn-outline-primary-2 btn-order btn-block">
                                <span class="btn-text">Place Order</span>
                                <span class="btn-hover-text">Proceed to Checkout</span>
                            </button>
                        </div><!-- End .summary -->
                    </aside><!-- End .col-lg-3 -->
                </div><!-- End .row -->
            </form>
        </div><!-- End .container -->
    </div><!-- End .checkout -->
</div><!-- End .page-content -->
