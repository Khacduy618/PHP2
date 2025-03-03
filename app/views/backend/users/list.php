<div class="row">
    <div class="row frmtitle">
        <h1><?=$title?></h1>
    </div>
    <?php if(isset($_COOKIE['msg'])): ?>
<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
    <strong><?= $_COOKIE['msg'] ?></strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<?php if(isset($_COOKIE['msg1'])): ?>
<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
    <strong><?= $_COOKIE['msg1'] ?></strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>
    <div class="row mb-3 gap-3 justify-content-around">
        <!-- Search -->
        <div class="col-md-4">
            <form action="javascript:void(0);" onsubmit="handleSearch(event)">
                <div class="input-group">
                    <span class="input-group-text" onclick="resetSearch()" style="cursor: pointer;" title="Reset search">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="search" class="form-control" name="q" id="q" 
                           value="<?= isset($_SESSION['search_keyword']) ? $_SESSION['search_keyword'] : '' ?>" 
                           placeholder="Search user...">
                </div>
            </form>
        </div>

        <!-- Status Filter -->
        <div class="col-md-2">
            <select name="status" id="status" class="form-select" 
                    onchange="window.location.href='<?=_WEB_ROOT?>/user/<?=$pagination['search'] ?? ''?>/' + this.value + '/1'">
                <option value="">All Status</option>
                <option value="1" <?= isset($pagination['status']) && $pagination['status'] == '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= isset($pagination['status']) && $pagination['status'] == '0' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="col-md-3 text-end ms-auto">
            <a class="btn btn-success shadow-sm" href="<?=_WEB_ROOT?>/add-new-user">
                <i class="bi bi-plus-circle me-2"></i>Add new user
            </a>
        </div>
    </div>

    <!-- Users Count -->
    <div class="mb-3">
        <span class="text-muted">Showing <?= count($users) ?> of <?= $total_users ?? count($users) ?> Users</span>
    </div>

    <div class="row frmcontent">
        <form action="?act=deleteSelected" method="post" id="userForm">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>AVARTA</th>
                            <th>USER NAME</th>
                            <th>EMAIL</th>
                            <th>PHONE NUMBER</th>
                            <th>STATUS</th>
                            <!-- <th>ROLES</th> -->
                            <th>ADDRESS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($users)) {
                            foreach ($users as $user) {
                                extract($user);
                                $edituser = "?mod=user&act=edit&user_email=" . $user_email;
                                $deleteuser = "?mod=user&act=delete&user_email=" . $user_email;
                                $user_images = !empty($user_images) 
                                ? _WEB_ROOT . '/public/uploads/avatar/' . $user_images
                                : _WEB_ROOT . '/public/uploads/avatar/user.png';
                                $images = "<img src='$user_images' alt='User Image' width='50'>";
                                
                                
                                $user_status_display = ($user_status == 1) ? 'Active' : 'Inactive';
                                $user_role_display = $user_role == 0 ? 'User' : ($user_role == 1 ? 'Admin' : 'Employee');
                                ?>
                                <tr>
                                    <td><?= $images ?></td>
                                    <td><?= $user_name ?></td>
                                    <td><?= $user_email ?></td>
                                    <td><?= $user_phone ?></td>
                                    <td><?= $user_status_display ?></td>
                                    <td>
                                            <a href="<?= _WEB_ROOT ?>/address/<?=$user_email?>" class="btn btn-info">DETAIL</a>
                                    </td>
                                    <td>
                                        
                                            <a href="<?php echo _WEB_ROOT?>/edit-user/<?=$user_email?>" class="btn btn-warning">Edit</a>
                                        
                                        <a href="<?=_WEB_ROOT ?>/delete-user/<?=$user_email?>" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')"  class="btn btn-danger ">DELETE
                                        </a>
                                        
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='10'>Không có người dùng nào.</td></tr>";
                        }

                        ?>

                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<!-- Pagination -->
<?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <!-- First page -->
        <?php if ($pagination['page'] > 2): ?>
            <li class="page-item">
                <a class="page-link" href="<?=_WEB_ROOT?>/user/<?=$pagination['search']?>/<?=$pagination['status']?>/1">First</a>
            </li>
        <?php endif; ?>

        <!-- Previous page -->
        <?php if ($pagination['page'] > 1): ?>
            <li class="page-item">
                <a class="page-link" href="<?=_WEB_ROOT?>/user/<?=$pagination['search']?>/<?=$pagination['status']?>/<?=$pagination['page']-1?>">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        <?php endif; ?>

        <!-- Page numbers -->
        <?php for($i = max(1, $pagination['page']-2); $i <= min($pagination['total_pages'], $pagination['page']+2); $i++): ?>
            <li class="page-item <?=$i == $pagination['page'] ? 'active' : ''?>">
                <a class="page-link" href="<?=_WEB_ROOT?>/user/<?=$pagination['search']?>/<?=$pagination['status']?>/<?=$i?>"><?=$i?></a>
            </li>
        <?php endfor; ?>

        <!-- Next page -->
        <?php if ($pagination['page'] < $pagination['total_pages']): ?>
            <li class="page-item">
                <a class="page-link" href="<?=_WEB_ROOT?>/user/<?=$pagination['search']?>/<?=$pagination['status']?>/<?=$pagination['page']+1?>">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        <?php endif; ?>

        <!-- Last page -->
        <?php if ($pagination['page'] < $pagination['total_pages'] - 1): ?>
            <li class="page-item">
                <a class="page-link" href="<?=_WEB_ROOT?>/user/<?=$pagination['search']?>/<?=$pagination['status']?>/<?=$pagination['total_pages']?>">Last</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>

<!-- Add JavaScript for search functionality -->
<script>
function handleSearch(e) {
    e.preventDefault();
    const searchTerm = document.getElementById('q').value;
    const currentStatus = document.getElementById('status').value;
    // Convert search term to URL-friendly format
    const urlFriendlySearch = searchTerm.trim().replace(/\s+/g, '-').toLowerCase();
    // Redirect to the route-based URL with correct path
    window.location.href = '<?=_WEB_ROOT?>/user/' + (urlFriendlySearch || '') + '/' + (currentStatus || '') + '/1';
}

function resetSearch() {
    // Reset input value
    document.getElementById('q').value = '';
    const currentStatus = document.getElementById('status').value;
    // Redirect to user page without search parameters but maintain status
    window.location.href = '<?=_WEB_ROOT?>/user//' + (currentStatus || '') + '/1';
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