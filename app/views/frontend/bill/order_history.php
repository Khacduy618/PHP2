<div class="order-history py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold text-primary text-center mb-4">Your Order History</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="<?= _WEB_ROOT ?>" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order History</li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php if(isset($_SESSION['user']) && !empty($bill)): ?>
            <div class="card shadow">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bill as $order): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-primary">#<?php echo htmlspecialchars($order['bill_var_id']); ?></td>
                                    <td><?php echo date('d M Y, H:i', strtotime($order['bill_time'])); ?></td>
                                    <td class="fw-bold text-success">
                                        <?=number_format($order['bill_totalPrice'],0,",",".")?> đ
                                    </td>
                                    <td>
                                        <?php
                                            $payment_methods = [
                                                1 => '<span class="text-secondary">Cash on delivery</span>',
                                                2 => '<span class="text-info">Bank transfer</span>',
                                                3 => '<span class="text-primary">PayPal</span>',
                                                4 => '<span class="text-success">Credit Card</span>'
                                            ];
                                            echo $payment_methods[$order['bill_payment']] ?? 'Unknown';
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill <?php echo getStatusBadgeClass($order['bill_status']); ?>">
                                            <?php echo $order['status_name']; ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-primary" onclick="showBillDetails(<?=$order['bill_id']?>)">
                                            <i class="fas fa-eye me-1"></i> Details
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <img src="<?= _WEB_ROOT ?>/public/assets/images/empty-order.svg" alt="No Orders" class="mb-4" style="width: 200px;">
                    <h4 class="text-secondary">No Orders Found</h4>
                    <p class="text-muted mb-4">Looks like you haven't placed any orders yet.</p>
                    <a href="<?= _WEB_ROOT ?>" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for Order Details -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn btn-close btn-danger" onclick="closeOrderModal()"><i class="bi bi-x-circle"></i>Close</button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showBillDetails(billId) {
    $.ajax({
        url: _WEB_ROOT + '/order_detail/' + billId,
        type: 'GET',
        beforeSend: function() {
            $('#orderDetailsContent').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
            $('#orderDetailsModal').modal('show');
        },
        success: function(response) {
            $('#orderDetailsContent').html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
            $('#orderDetailsContent').html('<div class="alert alert-danger">Error loading order details</div>');
        }
    });
}

function closeOrderModal() {
    $('#orderDetailsModal').modal('hide');
}
</script>

<style>
.badge {
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 500;
}

.table {
    font-size: 14px;
}

.table td, .table th {
    padding: 1rem;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 13px;
}

.card {
    border-radius: 10px;
    border: none;
}

.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

@media (max-width: 768px) {
    .table td, .table th {
        padding: 0.75rem;
    }
}
</style>

<?php
function getStatusBadgeClass($status) {
    switch ($status) {
        case 1:
        case 2:
        case 3:
            return 'bg-warning text-dark';   // Pending
        case 4:
            return 'bg-info text-white';     // Approved
        case 5:
            return 'bg-primary text-white';  // Delivering
        case 6:
            return 'bg-success text-white';  // Delivered
        case 7:
            return 'bg-success text-white';  // Completed
        case 8:
            return 'bg-danger text-white';   // Cancelled
        default:
            return 'bg-secondary text-white';
    }
}
?>
