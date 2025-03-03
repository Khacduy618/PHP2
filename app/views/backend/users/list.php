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
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" 
                       name="search" 
                       value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" 
                       placeholder="Search customer..."
                       onchange="this.form.submit()"
                       form="searchForm">
            </div>
            <form id="searchForm" action="" method="GET">
                <input type="hidden" name="mod" value="user">
                <input type="hidden" name="act" value="list">
                <?php if (isset($_GET['status'])): ?>
                    <input type="hidden" name="status" value="<?= htmlspecialchars($_GET['status']) ?>">
                <?php endif; ?>
            </form>
        </div>


        <!-- Status Filter -->
        <div class="col-md-2">
            <select class="form-select" onchange="filterStatus(this.value)">
                <option value="">All Status</option>
                <option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= isset($_GET['status']) && $_GET['status'] == '0' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>


        <div class="col-md-3 text-end ms-auto">
            <a class="btn btn-success shadow-sm" href="<?=_WEB_ROOT?>/add-new-user">
                <i class="bi bi-plus-circle me-2"></i>Add new user
            </a>
        </div>
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
 <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?mod=user&act=list&page=<?= $pagination['current_page'] - 1 ?><?= isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : '' ?>" aria-label="Previous">&laquo;</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?mod=user&act=list&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?mod=user&act=list&page=<?= $pagination['current_page'] + 1 ?>" aria-label="Next">&raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
function filterStatus(status) {
    const url = new URL(window.location.href);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
}
</script>