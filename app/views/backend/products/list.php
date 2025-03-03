<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 font-weight-bold"><?=$title?></h2>
    </div>

    <!-- Search and Filter Section -->
    <div class="row gap-3 mb-4">
        <!-- Search Form -->
        <div class="col-md-4">
            <form action="javascript:void(0);" onsubmit="handleSearch(event)">
                <div class="input-group">
                    <span class="input-group-text" onclick="resetSearch()" style="cursor: pointer;" title="Reset search">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="search" class="form-control" name="q" id="q" 
                           value="<?= isset($_SESSION['search_keyword']) ? $_SESSION['search_keyword'] : '' ?>" 
                           placeholder="Search product ...">
                </div>
            </form>
        </div>
        
        <!-- Category Filter -->
        <div class="col-md-2">
            <?php
            $current_url = $_SERVER['REQUEST_URI'];
            $url_parts = explode('/', trim($current_url, '/'));
            
            // Remove 'php2' from parts if it exists
            if(isset($url_parts[0]) && $url_parts[0] == 'php2') {
                array_shift($url_parts);
            }
            
            // Get current parameters
            $category_id = isset($url_parts[1]) ? $url_parts[1] : '0';
            $search = isset($url_parts[2]) ? $url_parts[2] : '';
            $sort = isset($url_parts[3]) ? $url_parts[3] : 'popularity';
            $perpage = isset($url_parts[4]) ? $url_parts[4] : '12';
            ?>
            <select name="category" id="category" class="form-select" 
                    onchange="window.location.href='<?=_WEB_ROOT?>/product/' + this.value + '/<?=$search?>/<?=$sort?>/<?=$perpage?>'">
                <option value="0">All categories</option>
                <?php foreach($category_list as $category): ?>
                    <option value="<?=$category['category_id']?>" 
                            <?= $category_id == $category['category_id'] ? 'selected' : '' ?>>
                        <?=$category['category_name']?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Sort Filter -->
        <div class="col-md-2">
            <select name="sortby" id="sortby" class="form-select" 
                    onchange="window.location.href='<?=_WEB_ROOT?>/product/<?=$category_id?>/<?=$search?>/' + this.value + '/<?=$perpage?>'">
                <option value="popularity" <?= $sort == 'popularity' ? 'selected' : '' ?>>Most Popular</option>
                <option value="active" <?= $sort == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $sort == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                <option value="date" <?= $sort == 'date' ? 'selected' : '' ?>>Date</option>
            </select>
        </div>
        
        <!-- Add New Product Button -->
        <div class="col-md-3 text-end ms-auto">
            <a class="btn btn-success shadow-sm" href="<?=_WEB_ROOT?>/add-new-product">
                <i class="bi bi-plus-circle me-2"></i>Add new product
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?=$_SESSION['msg']?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <!-- Products Count -->
    <div class="mb-3">
        <span class="text-muted">Showing <?= count($product_list) ?> of <?= $total_products ?? count($product_list) ?> Products</span>
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

    <!-- Pagination -->
    <?php 
    if (isset($pagination) && $pagination['total_pages'] > 1): 
        $perpage = 12;
        $page = $pagination['page'];
        $total_pages = $pagination['total_pages'];
        $category_id = $pagination['category_id'];
        $search = $pagination['search'];
        $sort = $pagination['sort'];
    ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <!-- First page -->
            <?php if ($page > 2): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=_WEB_ROOT?>/product/<?=$category_id?>/<?=$search?>/<?=$sort?>/<?=$perpage?>/1">First</a>
                </li>
            <?php endif; ?>

            <!-- Previous page -->
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=_WEB_ROOT?>/product/<?=$category_id?>/<?=$search?>/<?=$sort?>/<?=$page-1?>">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Page numbers -->
            <?php for($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                <li class="page-item <?=$i == $page ? 'active' : ''?>">
                    <a class="page-link" href="<?=_WEB_ROOT?>/product/<?=$category_id?>/<?=$search?>/<?=$sort?>/<?=$i?>"><?=$i?></a>
                </li>
            <?php endfor; ?>

            <!-- Next page -->
            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=_WEB_ROOT?>/product/<?=$category_id?>/<?=$search?>/<?=$sort?>/<?=$page+1?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Last page -->
            <?php if ($page < $total_pages - 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=_WEB_ROOT?>/product/<?=$category_id?>/<?=$search?>/<?=$sort?>/<?=$total_pages?>">Last</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<script>
function handleSearch(e) {
    e.preventDefault();
    const searchTerm = document.getElementById('q').value;
    // Convert search term to URL-friendly format
    const urlFriendlySearch = searchTerm.trim().replace(/\s+/g, '-').toLowerCase();
    // Redirect to the route-based URL with correct path
    window.location.href = '<?=_WEB_ROOT?>/product/0/' + (urlFriendlySearch || '') + '/popularity/12';
}

function resetSearch() {
    // Reset input value
    document.getElementById('q').value = '';
    // Redirect to product page without search parameters
    window.location.href = '<?=_WEB_ROOT?>/product';
}

// Thêm hiệu ứng hover cho icon search
document.querySelector('.input-group-text').addEventListener('mouseover', function() {
    this.style.backgroundColor = '#e9ecef';
});

document.querySelector('.input-group-text').addEventListener('mouseout', function() {
    this.style.backgroundColor = '';
});
</script>

<style>
/* Thêm style cho icon search */
.input-group-text {
    transition: background-color 0.3s ease;
}

.input-group-text:hover {
    background-color: #e9ecef;
}

.input-group-text i {
    transition: transform 0.3s ease;
}

.input-group-text:hover i {
    transform: scale(1.1);
}
</style>