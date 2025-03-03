<div class="order-details">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Product</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $subtotal = 0;
                foreach($data as $item): 
                    $subtotal += $item['total_price'];
                ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= _WEB_ROOT ?>/public/uploads/products/<?= htmlspecialchars($item['product_img']) ?>" 
                                     alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                     class="rounded"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                <div class="ms-3">
                                    <h6 class="mb-0"><?= htmlspecialchars($item['product_name']) ?></h6>
                                </div>
                            </div>
                        </td>
                        <td class="text-center text-primary">
                            <?= number_format($item['pro_price'], 0, ",", ".") ?> đ
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark">
                                <?= htmlspecialchars($item['pro_count']) ?>
                            </span>
                        </td>
                        <td class="text-end fw-bold text-success">
                            <?= number_format($item['total_price'], 0, ",", ".") ?> đ
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="border-top">
                <tr>
                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                    <td class="text-end fw-bold">
                        <?= number_format($subtotal, 0, ",", ".") ?> đ
                    </td>
                </tr>
                <tr><td colspan="3" class="text-end fw-bold">Shipping:</td>
                    <td class="text-end fw-bold">
                        <?= number_format(20000, 0, ",", ".") ?> đ
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total:</td>
                    <td class="text-end fw-bold">
                        <?= number_format($subtotal + 20000, 0, ",", ".") ?> đ
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<style>
.order-details {
    font-size: 14px;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
}

.table td {
    padding: 1rem;
}

.badge {
    padding: 6px 10px;
    font-weight: 500;
}

tfoot {
    background-color: #f8f9fa;
}

tfoot td {
    padding: 1rem !important;
}

@media (max-width: 576px) {
    .table td {
        padding: 0.75rem;
    }
    
    .product-img {
        width: 50px;
        height: 50px;
    }
}
</style>
                
