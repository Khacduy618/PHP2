<div class="container-fluid py-4">
    <!-- Header Section with better styling -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 font-weight-bold">Categories Management</h2>
    </div>

    <!-- Search and Filter Section - Better organized -->
    <div class="row gap-3 mb-4">
        <div class="col-md-4">
            <form action="" method="get">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="hidden" name="mod" value="category">
                    <input type="hidden" name="act" value="list">
                    <input type="text" class="form-control" name="keyword" placeholder="Search customer...">
                </div>
            </form>
        </div>
        
        <div class="col-md-2">
            <select name="sortby" id="sortby" class="form-control" onchange="this.options[this.selectedIndex].value && (window.location= this.options[this.selectedIndex].value);">
                <option value="?mod=category&act=list">All status</option>
                    <option <?php if (isset($_GET['keyword']) && ($_GET['keyword'] = '')) {
                        echo 'value="?mod=category&act=list&keyword='.$_GET['keyword'].'&status=1"';
                    } echo 'value="?mod=category&act=list&status=1"';?>> Active</option>
                    <option <?php if (isset($_GET['keyword']) && ($_GET['keyword'] = '')) {
                        echo 'value="?mod=category&act=list&keyword='.$_GET['keyword'].'&status=0"';
                    }echo 'value="?mod=category&act=list&status=0"';?> >Inactive</option>
            </select>
            
        </div>
        
        <div class="col-md-3 text-end ms-auto">
            <a class="btn btn-success shadow-sm" href="<?=_WEB_ROOT?>/add-new-category">
                <i class="bi bi-plus-circle me-2"></i>Add new item
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

    <!-- Table with better styling -->
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead >
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Image</th>
                        <th width="20%">Name</th>
                        <th width="25%">Descriptions</th>
                        <th width="15%">Parent Category</th>
                        <th width="10%">Status</th>
                        <th width="10%" colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($category_list as $category){ extract($category); ?>
                    <tr>
                        <td><?= $category_id ?></td>
                        <td>
                            <img src="<?= _WEB_ROOT ?>/public/uploads/categories/<?= $category_img ?>" 
                                class="img-thumbnail" 
                                style="max-width: 100px; height: auto;">
                        </td>
                        <td class="fw-semibold"><?= $category_name ?></td>
                        <td><small><?= $category_desc ?></small></td>
                        <td class="text-center"> <?php
                                // Display parent category name instead of just ID
                                if ($parent_id == NULL || $parent_id == 0) {
                                    echo "Main Category";
                                } else {
                                    // Assuming you have the parent category information in the $categories array
                                    foreach($category_list as $parent) {
                                        if ($parent['category_id'] == $parent_id) {
                                            echo $parent['category_name'];
                                            break;
                                        }
                                    }
                                }
                                ?></td>
                        <td>
                            <span class="badge <?= $category_status == 1 ? 'bg-success' : 'bg-danger' ?>">
                                <?= $category_status == 1 ? "Active" : "Inactive" ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a class="btn btn-outline-primary" href="<?=_WEB_ROOT?>/edit-category/<?=$category_id?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a class="btn btn-outline-danger" href="<?=_WEB_ROOT?>/delete-category/<?=$category_id?>"
                                   onclick="return confirm('Are you sure you want to delete this item?')">
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

<?php
// Retrieve current sorting parameters
$field = isset($_GET['field']) ? $_GET['field'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$per_page = isset($orderdata['itemPerPage']) ? $orderdata['itemPerPage'] : 14; // Default to 12 if not set

if (isset($orderdata['totalRecord'] ) && $orderdata['totalRecord'] > $per_page) {
?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php
            // Link to first page
            if ($orderdata['currentPage'] > 2) {
                $first_page = 1;
                ?>
        <li class="page-item"><a class="page-link"
                href="?mod=category&per_page=<?= $per_page ?>&page=<?= $first_page ?>&field=<?= $field ?>&sort=<?= $sort ?>&keyword=<?= $keyword ?>&status=<?= $status ?>">First</a>
        </li>
        <?php
            }
            // Link to previous page
            if ($orderdata['currentPage'] > 2) {
                $prev_page = $orderdata['currentPage'] - 1;
                ?>
        <li class="page-item"><a class="page-link page-link-prev"
                href="?mod=category&per_page=<?= $per_page ?>&page=<?= $prev_page ?>&field=<?= $field ?>&sort=<?= $sort ?>&keyword=<?= $keyword ?>&status=<?= $status ?>"><i
                    class="icon-long-arrow-left"></i>Prev</a></li>
        <?php }
        // Numbered page links
        for ($num = 1; $num <= $orderdata['totalPages']; $num++) {
            if ($num != $orderdata['currentPage']) {
                if ($num > $orderdata['currentPage'] - 3 && $num < $orderdata['currentPage'] + 3) {
                    ?>
        <li class="page-item"><a class="page-link"
                href="?mod=category&per_page=<?= $per_page ?>&page=<?= $num ?>&field=<?= $field ?>&sort=<?= $sort ?>&keyword=<?= $keyword ?>&status=<?= $status ?>"><?= $num ?></a>
        </li>
        <?php 
                }
            } else { ?>
        <li class="page-item active"><a class="page-link"><?= $num ?></a></li>
        <?php }
        }
        // Link to next page
        if ($orderdata['currentPage'] < $orderdata['totalPages'] - 1) {
            $next_page = $orderdata['currentPage'] + 1;
            ?>
        <li class="page-item"><a class="page-link page-link-next"
                    href="?mod=category&per_page=<?= $per_page ?>&page=<?= $next_page ?>&field=<?= $field ?>&sort=<?= $sort ?>&keyword=<?= $keyword ?>&status=<?= $status ?>"><span><i
                        class="icon-long-arrow-right"></i></span>Next</a></li>
        <?php
        }
        // Link to last page
        if ($orderdata['currentPage'] < $orderdata['totalPages'] - 2) {
            $end_page = $orderdata['totalPages'];
            ?>
        <li class="page-item"><a class="page-link"
                href="?mod=category&per_page=<?= $per_page ?>&page=<?= $end_page ?>&field=<?= $field ?>&sort=<?= $sort ?>&keyword=<?= $keyword ?>&status=<?= $status ?>">Last</a>
        </li>
        <?php
        }
        ?>
    </ul>
</nav>
<?php } ?>