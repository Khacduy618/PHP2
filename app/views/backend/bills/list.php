<div class="container-fluid px-4">
    <h1 class="mt-4">Bills Management</h1>

    <!-- Order Details Section -->
    <div class="container-fluid text-center my-4">
        <div class="d-flex justify-content-center gap-3">
            <a href="<?=_WEB_ROOT?>/archivedBills-bill" class="btn btn-warning px-4 py-2 text-dark fw-bold">View Saved Orders Store</a>
        </div>
    </div>

    <?php
    // Khai báo các mảng trạng thái và màu sắc
    $statusClasses = [
        1 => 'bg-danger',     // Unpaid
        2 => 'bg-success',    // Paid
        3 => 'bg-warning',    // Processing
        4 => 'bg-info',       // Approved
        5 => 'bg-primary',    // Delivering
        6 => 'bg-secondary',  // Delivered
        7 => 'bg-success'     // Completed
    ];

    $statusLabels = [
        1 => 'Unpaid',
        2 => 'Paid',
        3 => 'Processing',
        4 => 'Approved',
        5 => 'Delivering',
        6 => 'Delivered',
        7 => 'Completed'
    ];
    ?>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
            <tr>
                <th>BILL CODE</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Bill Time</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($bills)): ?>
                <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td><?= $bill['bill_var_id']; ?></td>
                        <td><?= number_format($bill['total_price'], 0, ',', '.'); ?> đ</td>
                        <td>
                            <label>
                                <select class="form-select status-select <?= $statusClasses[$bill['bill_status']] ?>"
                                        data-bill-id="<?= $bill['bill_id']; ?>"
                                        style="width: auto; display: inline-block;">
                                    <!-- Hiển thị trạng thái hiện tại -->
                                    <option value="<?= $bill['bill_status'] ?>" selected>
                                        <?= $statusLabels[$bill['bill_status']] ?>
                                    </option>
                                    <!-- Hiển thị các trạng thái khác -->
                                    <?php foreach ($statusLabels as $key => $label): ?>
                                        <?php if ($key != $bill['bill_status']): ?>
                                            <option value="<?= $key ?>"><?= $label ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </td>
                        <td><?= $bill['bill_time']; ?></td>
                        <td>
                            <a href="<?=_WEB_ROOT?>/detail-bill/<?= $bill['bill_id']; ?>"
                               class="btn btn-primary btn-sm">Bill details</a>
                            <?php if ($bill['bill_status'] == 7): ?>
                                <a href="<?=_WEB_ROOT?>/archivedBill/<?= $bill['bill_id']; ?>/8"
                                   class="btn btn-warning btn-sm" onclick="return confirm('Bạn có chắc chắn muốn lưu trữ đơn hàng này không?')">Archive</a> 
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No bills found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Hiển thị thông báo trạng thái -->
<?php
$statusMessages = [
    3 => 'Đơn hàng đang xử lý',
    5 => 'Đơn hàng đang giao',
    6 => 'Đơn hàng giao thành công'
];

$currentStatus = isset($_GET['status']) ? (int)$_GET['status'] : null;
if (isset($currentStatus) && isset($statusMessages[$currentStatus])): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= $statusMessages[$currentStatus]; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const statusClasses = {
        1: 'bg-danger',
        2: 'bg-success', 
        3: 'bg-warning',
        4: 'bg-info',
        5: 'bg-primary',
        6: 'bg-secondary',
        7: 'bg-success'
    };

    const statusLabels = {
        1: 'Unpaid',
        2: 'Paid',
        3: 'Processing', 
        4: 'Approved',
        5: 'Delivering',
        6: 'Delivered',
        7: 'Completed'
    };

    document.querySelectorAll('.status-select').forEach(function(selectElement) {
        selectElement.addEventListener('change', function(e) {
            e.preventDefault();
            const billId = this.getAttribute('data-bill-id');
            const newStatus = this.value;
            const currentSelect = this;

            // Gửi AJAX request
            fetch(`<?=_WEB_ROOT?>/status/${billId}/${newStatus}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Cập nhật UI
                    currentSelect.className = `form-select status-select ${statusClasses[newStatus]}`;
                    
                    // Cập nhật options
                    let options = '';
                    // Thêm option đã chọn
                    options += `<option value="${newStatus}" selected>${statusLabels[newStatus]}</option>`;
                    
                    // Thêm các options khác
                    Object.entries(statusLabels).forEach(([key, label]) => {
                        if (key != newStatus) {
                            options += `<option value="${key}">${label}</option>`;
                        }
                    });
                    
                    currentSelect.innerHTML = options;

                    // Hiển thị thông báo thành công
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success alert-dismissible fade show';
                    successAlert.innerHTML = `
                        Status updated successfully to ${statusLabels[newStatus]}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    currentSelect.parentElement.appendChild(successAlert);

                    // Tự động ẩn alert sau 3 giây
                    setTimeout(() => {
                        successAlert.remove();
                    }, 3000);
                } else {
                    throw new Error('Update failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Hiển thị thông báo lỗi
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                errorAlert.innerHTML = `
                    Failed to update status
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                currentSelect.parentElement.appendChild(errorAlert);

                // Reset về giá trị cũ
                currentSelect.value = currentSelect.querySelector('option[selected]').value;

                // Tự động ẩn alert sau 3 giây
                setTimeout(() => {
                    errorAlert.remove();
                }, 3000);
            });
        });
    });
});
</script>