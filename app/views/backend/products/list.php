<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 font-weight-bold"><?=$title?> Management</h2>
    </div>

    <!-- Search and Filter Section -->
    <div class="row gap-3 mb-4">
        <div class="col-md-4">
            <form action="" method="get">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="hidden" name="mod" value="product">
                    <input type="hidden" name="act" value="list">
                    <input type="text" class="form-control" name="keyword" placeholder="Search products...">
                </div>
            </form>
        </div>
        
        <div class="col-md-2">
            <select name="category" id="category" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                <option value="?mod=product&act=list&status=<?= $status ?>&per_page=<?= $_GET['per_page'] ?? 12 ?>&page=<?= $_GET['page'] ?? 1 ?>&field=<?= $_GET['field'] ?? '' ?>&sort=<?= $_GET['sort'] ?? '' ?>">All categories</option>
                <?php foreach($categorie_list as $category): extract($category); ?>
                    <option value="?mod=product&act=list&product_cat=<?= $category_id ?? 0 ?>&status=<?= $status ?>&per_page=<?= $_GET['per_page'] ?? 12 ?>&page=<?= $_GET['page'] ?? 1 ?>&field=<?= $_GET['field'] ?? '' ?>&sort=<?= $_GET['sort'] ?? '' ?>"
                        <?= (isset($_GET['product_cat']) && $_GET['product_cat'] == $category_id) ? 'selected' : '' ?>>
                        <?= $category_name?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" id="status" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                <option value="?mod=product&act=list&keyword=<?= $_GET['keyword'] ?? '' ?>&product_cat=<?= $_GET['product_cat'] ?? 0 ?>&per_page=<?= $_GET['per_page'] ?? 12 ?>&page=<?= $_GET['page'] ?? 1 ?>&field=<?= $_GET['field'] ?? '' ?>&sort=<?= $_GET['sort'] ?? '' ?>">All status</option>
                <option value="?mod=product&keyword=<?= $_GET['keyword'] ?? '' ?>&product_cat=<?= $_GET['product_cat'] ?? 0 ?>&status=1&per_page=<?= $_GET['per_page'] ?? 12 ?>&page=<?= $_GET['page'] ?? 1 ?>&field=<?= $_GET['field'] ?? '' ?>&sort=<?= $_GET['sort'] ?? '' ?>"
                                        <?= (isset($_GET['status']) && $_GET['status'] == 1) ? 'selected' : '' ?>> Active</option>
                <option value="?mod=product&keyword=<?= $_GET['keyword'] ?? '' ?>&product_cat=<?= $_GET['product_cat'] ?? 0 ?>&status=0&per_page=<?= $_GET['per_page'] ?? 12 ?>&page=<?= $_GET['page'] ?? 1 ?>&field=<?= $_GET['field'] ?? '' ?>&sort=<?= $_GET['sort'] ?? '' ?>"
                                        <?= (isset($_GET['status']) && $_GET['status'] == 0) ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        
        <div class="col-md-3 text-end ms-auto">
            <a class="btn btn-success shadow-sm" href="<?=_WEB_ROOT?>/add-new-product">
                <i class="bi bi-plus-circle me-2"></i>Add new product
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <div class="messages mb-4">
        <?php
        if(!empty($_GET['msg'])){
            $msg = unserialize(urldecode($_GET['msg']));
            foreach($msg as $key => $value){
                echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                        '.$value.'
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        }
        if(!empty($_SESSION['msg'])){
            echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                    '.$_SESSION['msg'].'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION['msg']);
        }
        ?>
    </div>

    <!-- Table Section -->
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Image</th>
                        <th width="20%">Name</th>
                        <th width="10%">Price</th>
                        <th width="10%">Discount</th>
                        <th width="10%">Stock</th>
                        <th width="10%">Category</th>
                        <th width="10%">Status</th>
                        <th width="10%" colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($product_list as $value) {
                            extract($value);?>
                    <tr>
                        <td><?= $product_id ?></td>
                        <td>
                            <img src="<?= _WEB_ROOT ?>/public/uploads/products/<?= $product_img ?>" 
                                class="img-thumbnail" 
                                style="max-width: 100px; height: auto;">
                        </td>
                        <td class="fw-semibold"><?= $product_name ?></td>
                        <td><?= number_format($product_price) ?> đ</td>
                        <td><?= $product_discount ?>%</td>
                        <td><?= $product_count?></td>
                        <td><?= $category_name?></td>
                        <td>
                            <span class="badge <?= $product_status == 1 ? 'bg-success' : 'bg-danger' ?>">
                                <?= $product_status == 1 ? "Active" : "Inactive" ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                            <a class="btn btn-outline-primary" href="javascript:void(0)" onclick="showProductDetails(<?=$product_id?>)">
                                <i class="bi bi-eye"></i>
                            </a>
                                <a class="btn btn-outline-primary"  href="<?php echo _WEB_ROOT?>/edit-product/<?=$product_id?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a class="btn btn-outline-danger" href="<?php echo _WEB_ROOT?>/delete-product/<?=$product_id?>"
                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="productDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Technical Specifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productDetailsContent">
                <!-- Nội dung chi tiết sản phẩm sẽ được load vào đây -->
            </div>
        </div>
    </div>
</div>
    <!-- Pagination Section -->
    <?php
// Retrieve current sorting parameters
$field = isset($_GET['field']) ? $_GET['field'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$product_cat = isset($_GET['product_cat']) ? $_GET['product_cat'] : 0;
$per_page = isset($orderdata['itemPerPage']) ? $orderdata['itemPerPage'] : 12; // Default to 12 if not set

if (isset($orderdata['totalRecord']) && $orderdata['totalRecord'] > 12) {
?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php
            // Link to first page
            if ($orderdata['currentPage'] > 2) {
                $first_page = 1;
                ?>
        <li class="page-item"><a class="page-link"
                href="?mod=product&act=list&keyword=<?= $_GET['keyword'] ?? '' ?>&product_cat=<?= $_GET['product_cat'] ?? 0 ?>&status=<?= $status ?>&per_page=<?= $per_page ?>&page=<?= $first_page ?>&field=<?= $field ?>&sort=<?= $sort ?>">First</a>
        </li>
        <?php
            }
            // Link to previous page
            if ($orderdata['currentPage'] > 1) {
                $prev_page = $orderdata['currentPage'] - 1;
                ?>
        <li class="page-item"><a class="page-link page-link-prev"
                href="?mod=product&act=list&keyword=<?= $_GET['keyword'] ?? '' ?>&product_cat=<?= $_GET['product_cat'] ?? 0 ?>&status=<?= $status ?>&per_page=<?= $per_page ?>&page=<?= $prev_page ?>&field=<?= $field ?>&sort=<?= $sort ?>"><i
                    class="icon-long-arrow-left"></i>Prev</a></li>
        <?php }
        // Numbered page links
        for ($num = 1; $num <= $orderdata['totalPages']; $num++) {
            if ($num != $orderdata['currentPage']) {
                if ($num > $orderdata['currentPage'] - 3 && $num < $orderdata['currentPage'] + 3) {
                    ?>
        <li class="page-item"><a class="page-link"
                href="?mod=product&act=list&keyword=<?= $_GET['keyword'] ?? '' ?>&product_cat=<?= $_GET['product_cat'] ?? 0 ?>&status=<?= $status ?>&per_page=<?= $per_page ?>&page=<?= $num ?>&field=<?= $field ?>&sort=<?= $sort ?>"><?= $num ?></a>
        </li>
        <?php 
                }
            } else { ?>
        <li class="page-item active"><a class="page-link"><?= $num ?></a></li>
        <?php }
        }
        // Link to next page
        if ($orderdata['currentPage'] < $orderdata['totalPages']) {
            $next_page = $orderdata['currentPage'] + 1;
            ?>
        <li class="page-item"><a class="page-link page-link-next"
                href="?mod=product&act=list&keyword=<?= $_GET['keyword'] ?? '' ?>&product_cat=<?= $_GET['product_cat'] ?? 0 ?>&status=<?= $status ?>&per_page=<?= $per_page ?>&page=<?= $next_page ?>&field=<?= $field ?>&sort=<?= $sort ?>">Next<span><i
                        class="icon-long-arrow-right"></i></span></a></li>
        <?php
        }
        // Link to last page
        if ($orderdata['currentPage'] < $orderdata['totalPages'] - 2) {
            $end_page = $orderdata['totalPages'];
            ?>
        <li class="page-item"><a class="page-link"
                href="?mod=product&act=list&keyword=<?= $_GET['keyword'] ?? '' ?>&product_cat=<?= $_GET['product_cat'] ?? 0 ?>&status=<?= $status ?>&per_page=<?= $per_page ?>&page=<?= $end_page ?>&field=<?= $field ?>&sort=<?= $sort ?>">Last</a>
        </li>
        <?php
        }
        ?>
    </ul>
</nav>
<?php } ?>
<!-- <script>
function showProductDetails(productId) {
    $.ajax({
        url: '?mod=product&act=details&id=' + productId,
        type: 'GET',
        success: function(response) {
            $('#productDetailsContent').html(response);
            $('#productDetailsModal').modal('show');
        },
        error: function() {
            alert('Có lỗi xảy ra khi tải thông tin sản phẩm');
        }
    });
}
</script> -->