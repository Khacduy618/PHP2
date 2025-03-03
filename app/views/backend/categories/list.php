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
                           placeholder="Search category ...">
                </div>
            </form>
        </div>
        
        <!-- Status Filter -->
        <div class="col-md-2">
            <?php
            $current_url = $_SERVER['REQUEST_URI'];
            $url_parts = explode('/', trim($current_url, '/'));
            
            // Remove 'php2' from parts if it exists
            if(isset($url_parts[0]) && $url_parts[0] == 'php2') {
                array_shift($url_parts);
            }
            
            // Get current parameters
            $search = isset($url_parts[1]) ? $url_parts[1] : '';
            $sort = isset($url_parts[2]) ? $url_parts[2] : 'all';
            ?>
            <select name="status" id="status" class="form-select" 
                    onchange="window.location.href='<?=_WEB_ROOT?>/category/' + (document.getElementById('q').value || '') + '/' + this.value">
                <option value="all" <?= $sort == 'all' ? 'selected' : '' ?>>All Status</option>
                <option value="active" <?= $sort == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $sort == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        
        <!-- Add New Category Button -->
        <div class="col-md-3 text-end ms-auto">
            <a class="btn btn-success shadow-sm" href="<?=_WEB_ROOT?>/add-new-category">
                <i class="bi bi-plus-circle me-2"></i>Add new category
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

    <!-- Table Section -->
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Image</th>
                        <th width="20%">Name</th>
                        <th width="25%">Descriptions</th>
                        <th width="15%">Parent Category</th>
                        <th width="10%">Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(!empty($category_list)):
                        foreach($category_list as $category): 
                    ?>
                    <tr>
                        <td><?=$category['category_id']?></td>
                        <td>
                            <img src="<?=_WEB_ROOT?>/public/uploads/categories/<?=$category['category_img']?>" 
                                 class="img-thumbnail" 
                                 style="max-width: 100px; height: auto;">
                        </td>
                        <td class="fw-semibold"><?=$category['category_name']?></td>
                        <td><small><?=$category['category_desc']?></small></td>
                        <td class="text-center">
                            <?php
                            if ($category['parent_id'] == NULL || $category['parent_id'] == 0) {
                                echo "Main Category";
                            } else {
                                foreach($category_list as $parent) {
                                    if ($parent['category_id'] == $category['parent_id']) {
                                        echo $parent['category_name'];
                                        break;
                                    }
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <span class="badge <?=$category['category_status'] == 1 ? 'bg-success' : 'bg-danger'?>">
                                <?=$category['category_status'] == 1 ? "Active" : "Inactive"?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a class="btn btn-outline-primary" href="<?=_WEB_ROOT?>/edit-category/<?=$category['category_id']?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a class="btn btn-outline-danger" href="<?=_WEB_ROOT?>/delete-category/<?=$category['category_id']?>"
                                   onclick="return confirm('Are you sure you want to delete this category?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <tr>
                        <td colspan="7" class="text-center">No categories found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function handleSearch(e) {
    e.preventDefault();
    const searchTerm = document.getElementById('q').value.trim();
    // Convert search term to URL-friendly format
    const urlFriendlySearch = searchTerm.replace(/\s+/g, '-').toLowerCase();
    // Get current status filter
    const status = document.getElementById('status').value;
    // Redirect to the route-based URL with correct path
    window.location.href = '<?=_WEB_ROOT?>/category/' + 
        (urlFriendlySearch ? urlFriendlySearch : '') + '/' + 
        (status ? status : 'all');
}

function resetSearch() {
    // Reset input value
    document.getElementById('q').value = '';
    // Get current status filter
    const status = document.getElementById('status').value;
    // Redirect to category page without search parameters
    window.location.href = '<?=_WEB_ROOT?>/category';
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