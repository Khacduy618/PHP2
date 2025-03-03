<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h1 class="text-uppercase m-0">Bill Details #<?= isset($billDetails['bill_var_id']) ? $billDetails['bill_var_id'] : 'Not Found'; ?></h1>
        <div>
            <?php if (isset($billDetails['deleted']) && $billDetails['deleted'] == 1): ?>
            <a href="<?=_WEB_ROOT?>/delete-bill/<?=$billDetails['bill_id']?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Deleted Bills List
            </a>
            <?php else: ?>
            <a href="<?=_WEB_ROOT?>/bill" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Bills List
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($billDetails) && is_array($billDetails)): ?>
    <div class="row">
        <!-- Customer Information -->
        <div class="col-md-5 me-auto mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user-circle me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted">Full Name:</label>
                        <p class="fw-bold"><?=$billDetails['user_name']; ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Email:</label>
                        <p class="fw-bold"><?=$billDetails['bill_userEmail']; ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Phone:</label>
                        <p class="fw-bold"><?=$billDetails['user_phone']; ?></p>
                    </div>
                    <div>
                        <label class="text-muted">Delivery Address:</label>
                        <p class="fw-bold"><?=$billDetails['address_name'] . '- ' . $billDetails['address_street'] . ', ' . $billDetails['address_city'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted">Order Time:</label>
                        <p class="fw-bold"><?=$billDetails['bill_time']; ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Status:</label>
                        <?php
                        $statusMapping = [
                            1 => ['Unpaid', 'danger'],
                            2 => ['Paid', 'success'],
                            3 => ['Processing', 'warning'],
                            4 => ['Confirmed', 'info'],
                            5 => ['Delivering', 'primary'],
                            6 => ['Delivered', 'success'],
                            7 => ['Completed', 'success'],
                            8 => ['Archived', 'secondary']
                        ];
                        $status = $statusMapping[$billDetails['bill_status']];
                        ?>
                        <p><span class="badge bg-<?=$status[1]?> fs-6"><?=$status[0]?></span></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Coupon:</label>
                        <p class="fw-bold"><?=$billDetails['coupon_name'] ?: 'None'; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-shopping-cart me-2"></i>Product Details</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($billDetails['products'])): 
                                    $products = explode(", ", $billDetails['products']);
                                    $quantities = explode(", ", $billDetails['quantities']);
                                    $prices = explode(", ", $billDetails['prices']);
                                    for ($i = 0; $i < count($products); $i++): 
                                        $subtotal = $quantities[$i] * $prices[$i];
                                ?>
                                <tr>
                                    <td><?=$products[$i]?></td>
                                    <td class="text-center"><?=$quantities[$i]?></td>
                                    <td class="text-end"><?=number_format($prices[$i], 0, ',', '.')?> đ</td>
                                    <td class="text-end"><?=number_format($subtotal, 0, ',', '.')?> đ</td>
                                </tr>
                                <?php endfor; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No products found in this bill</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total -->
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span class="fw-bold"><?=number_format($billDetails['bill_price'],0,',','.')?> đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping Fee:</span>
                                    <span class="fw-bold"><?=number_format($billDetails['bill_priceDelivery'],0,',','.')?> đ</span>
                                </div>
                                <?php if ($billDetails['coupon_name'] != 'Không có'): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discount:</span>
                                    <span class="fw-bold text-danger">-<?=number_format($billDetails['bill_price'] + $billDetails['bill_priceDelivery'] - $billDetails['total_price'],0,',','.')?> đ</span>
                                </div>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                    <span class="fw-bold">Total:</span>
                                    <span class="fw-bold text-primary fs-5"><?=number_format($billDetails['total_price'],0,',','.')?> đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>Bill information not found
    </div>
    <?php endif; ?>
</div>