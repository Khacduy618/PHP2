<div class="order-complete py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <i class="fas fa-check-circle text-success" style="font-size: 72px;"></i>
                <h2 class="mt-4 mb-3">Order Placed Successfully!</h2>
                <p class="lead text-muted">Thank you for your order. We will process your order as soon as possible.</p>
            </div>
        </div>

        <?php if(isset($_SESSION['order_complete'])): ?>
        <div class="order-details mt-5">
            <h3 class="mb-4">Order Details #<?php echo $_SESSION['order_complete']['bill_var_id']; ?></h3>
            
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <!-- Shipping Information -->
                    <div class="customer-info mb-5">
                        <h4 class="border-bottom pb-2">Shipping Information</h4>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p><strong>Full Name:</strong> <?php echo $_SESSION['order_complete']['bill_name']; ?></p>
                                <p><strong>Phone:</strong> <?php echo $_SESSION['order_complete']['bill_phone']; ?></p>
                                <p><strong>Email:</strong> <?php echo $_SESSION['order_complete']['bill_userEmail']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Address:</strong> <?php echo $_SESSION['order_complete']['bill_address']; ?></p>
                                <p><strong>Payment Method:</strong> <?php echo $_SESSION['order_complete']['bill_payment']; ?></p>
                                <p><strong>Order Date:</strong> <?php echo $_SESSION['order_complete']['bill_date']; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items">
                        <h4 class="border-bottom pb-2">Ordered Products</h4>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($_SESSION['order_complete']['items'] as $item): ?>
                                        <tr>
                                            <td class="align-middle"><?php echo $item['product_name']; ?></td>
                                            <td class="text-center align-middle"><?php echo $item['quantity']; ?></td>
                                            <td class="text-center align-middle"><?=number_format($item['price'],0,",",".")?> đ</td>
                                            <td class="text-right align-middle"><?=number_format($item['total'],0,",",".")?> đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                        <td class="text-right"><strong><?=number_format($_SESSION['order_complete']['total_amount'],0,",",".")?> đ</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Shipping Fee:</strong></td>
                                        <td class="text-right"><strong><?=number_format($_SESSION['order_complete']['shipping_fee'],0,",",".")?> đ</strong></td>
                                    </tr>
                                    <tr class="border-top">
                                        <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                                        <td class="text-right"><h5 class="mb-0"><?=number_format($_SESSION['order_complete']['final_total'],0,",",".")?> đ</h5></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="<?=_WEB_ROOT?>/product" class="btn btn-primary btn-lg px-5">Continue Shopping</a>
                <a href="<?=_WEB_ROOT?>/order_history" class="btn btn-outline-primary btn-lg px-5 ml-3">View Order History</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
