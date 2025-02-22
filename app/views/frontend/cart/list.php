<div class="page-header text-center">
    <div class="container">
        <h1 class="page-title">Shopping Cart<span>Shop</span></h1>
    </div><!-- End .container -->
</div><!-- End .page-header -->
<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?=_WEB_ROOT?>/trang-chu">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </div><!-- End .container -->
</nav><!-- End .breadcrumb-nav -->
<?php if(isset($_COOKIE['msg1'])): ?>
<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
    <strong><?php echo htmlspecialchars($_COOKIE['msg1']); ?></strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="page-content">
    <div class="cart">
        <div class="container">
            <div class="checkout-discount">
                <form action="" method="post">
                    <input type="text" class="form-control" name="coupon_name" required id="checkout-discount-input"
                        value="<?=isset($_POST['coupon_name']) ? $_POST['coupon_name'] : ''?>">
                    <label for="checkout-discount-input" class="text-truncate"id="coupon-label">
                        <?php if(isset($coupon)): ?>
                            <?=$coupon['coupon_name']?>
                        <?php else: ?>
                            Have a coupon? <span>Click here to enter your code</span>
                        <?php endif; ?>
                    </label>
                </form>
            </div>
            <div class="row">
            
                <div class="col-lg-8">

                    <table class="table table-cart table-mobile">
                        <form id="cartForm" action="?act=checkout" method="POST">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="select-all-checkbox" onchange="selectAllCheckboxes()" name="select-all"
                                            id="select-all"></th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
							if (!empty($cart_list)) {
                                $tong = 0;
                                $shipping = 20000;
                                $_SESSON['shipping'] = $shipping;
                                $discount = 0;
                                $total = 0;
                                
								foreach ($cart_list as $value) {
                                    $ttien=$value['product_price']*$value['quantity'];
                                    $tong+=$ttien; 
                            ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="cart_items[]" id="<?=$value['cart_item_id']?>"
                                            class="checkboxes" value="<?=$value['cart_item_id']?>"
                                            data-price="<?=$value['product_price']?>"
                                            data-quantity="<?=$value['quantity']?>">
                                    </td>
                                    <td class="product-col">
                                        <label for="<?=$value['cart_item_id']?>">
                                            <div class="product">
                                                <figure class="product-media">
                                                    <div class="product-image">
                                                    <img src="<?=_WEB_ROOT?>/public/uploads/products/<?=$value['product_img']?>" alt="Product image">
                                                    </div>
                                                </figure>

                                                <h3 class="product-title">
                                                    <a
                                                        href="?act=product&id=<?=$value['pro_id']?>"><?= $value['product_name'] ?></a>
                                                </h3><!-- End .product-title -->
                                            </div><!-- End .product -->
                                        </label>
                                    </td>
                                    <td class="price-col"><label
                                            for="<?=$value['cart_item_id']?>"><?=number_format($value['product_price'],0,",",".")?>
                                            đ</label></td>
                                    <td class="quantity-col">
                                        <div class="cart-product-quantity">
                                            <input type="number" class="form-control quantity-input" value="<?= $value['quantity'] ?>"
                                                min="1" max="10" step="1" data-decimals="0" required>
                                        </div><!-- End .cart-product-quantity -->
                                    </td>
                                    <td class="total-col"><label
                                            for="<?=$value['cart_item_id']?>"><?=number_format($ttien,0,",",".")?>
                                            đ</label></td>
                                    <td class="remove-col"><a class="btn-remove"
                                            href="?act=cart&xuli=delete&product_id=<?= $value['pro_id'] ?>"><i
                                                class="icon-close"></i></a>
                                    </td>
                                </tr>
                                <?php }
							} else{?>
                                <tr>
                                    <td colspan="5" class="text-center">No products in the cart.</td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                    </table><!-- End .table table-wishlist -->

                    <div class="cart-bottom">
                        <a href="?act=cart&xuli=deleteall" class="btn btn-outline-danger" 
                           onclick="return confirm('Are you sure you want to delete all items from cart?')">
                            <i class="bi bi-trash"></i> Delete All Cart
                        </a>
                    </div>
                    
                </div><!-- End .col-lg-9 -->
                <aside class="col-lg-4">
                    <div class="summary summary-cart">
                        <h3 class="summary-title">Cart Total</h3><!-- End .summary-title -->

                        <table class="table table-summary">
                            <tbody>
                                <tr class="summary-subtotal">
                                    <td>Subtotal:</td>
                                    <td colspan="2" class="subtotal-amount"><?=number_format($tong,0,",",".")?> đ</td>
                                </tr><!-- End .summary-subtotal -->
                                <tr class="summary-shipping">
                                    <td>Shipping:</td>
                                    <td colspan="2"><?=number_format($shipping,0,",",".")?> đ</td>
                                </tr>
                                <?php
                                    if (isset($coupon)) {
                                        $discount = intval($tong * ($coupon['coupon_discount'] / 100));
                                    }
                                    $total = $tong + $shipping - $discount;
                                    ?>
                                <tr class="summary-shipping-estimate">
                                    <td><label for="address_id">Select Shipping Address: </label></td>
                                    <td colspan="2">
                                        <select class="form-select" id="address_id" name="address_id">
                                            <?php foreach($addresses as $addr): ?>
                                                <option value="<?=$addr['address_id']?>">
                                                    <?=$addr['address_name']?> - <?=$addr['address_city']?>, <?=$addr['address_street']?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr><!-- End .summary-shipping-estimate -->
                                <?php
                                if(isset($coupon)){
                                ?>
                                <tr class="summary-coupon">
                                    <td>Coupon:</td>
                                    <td><?=$coupon['coupon_name']?></td>
                                    <td class="discount-amount"><?=number_format($discount,0,",",".")?> đ</td>
                                </tr>
                                <?php } ?>
                                <tr class="summary-total">
                                    <td>Total:</td>
                                    <td colspan="2" class="total-amount"><?=number_format($total,0,",",".")?> đ</td>
                                </tr><!-- End .summary-total -->
                            </tbody>
                        </table><!-- End .table table-summary -->
                        <input type="hidden" name="coupon" value="<?=$coupon['coupon_name']?>" >
                        <input type="hidden" name="total" value="<?=$total?>">
                        <input type="hidden" name="shipping" value="<?=$shipping?>">
                        <button type='submit' class="btn btn-outline-primary-2 btn-order btn-block">PROCEED TO
                            CHECKOUT</button>
                        </form>
                       
                    </div><!-- End .summary -->
                    
                    <a href="<?=_WEB_ROOT?>/product" class="btn btn-outline-dark-2 btn-block mb-3"><span>CONTINUE
                            SHOPPING</span><i class="icon-refresh"></i></a>
                </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End .cart -->
</div><!-- End .page-content -->

<!-- Add this temporarily to debug -->
<?php
    echo "Subtotal: " . $tong . "<br>";
    echo "Shipping: " . $shipping . "<br>";
    echo "Discount: " . $discount . "<br>";
    
$total = $tong + $shipping - $discount;
    echo "Total: " . $total . "<br>";
?>