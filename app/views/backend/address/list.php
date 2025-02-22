<div class="row">
    <div class="row frmtitle">
        <h1><?=$title?></h1>
    </div>

    <div class="row mb-3 justify-content-around">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" v-model="searchQuery" placeholder="Search customer...">
            </div>
        </div>
        <div class="col-md-2">
            <select class="form-select" v-model="roleFilter">
                <option value="">All Roles</option>
                <option value="0">User</option>
                <option value="1">Admin</option>
                <option value="2">Employee</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" v-model="statusFilter">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="col-md-1">
            <select class="form-select" v-model="sortBy">
                <option value="">Sort by</option>
                <option value="id">ID</option>
                <option value="name">Name</option>
            </select>
        </div>

        <div class="col-md-3 d-flex gap-3 align-items-center">
            
            <a href="<?=_WEB_ROOT?>/add-new-address/<?=$_SESSION['user']['user_email']?>"   class="btn btn-success">Add new Address</a>
            
        </div>
    </div>

    <!-- Display message -->
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message']; ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="row frmcontent">
        <form id="bulkUpdateForm" action="?mod=address&act=updateStatus" method="post">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>STREET</th>
                            <th>CITY</th>
                            <th>PROVINCE</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($addresses)): ?>
                        <?php foreach ($addresses as $address): ?>
                            <tr>
                                <td><?= $address['address_id']; ?></td>
                                <td><?= $address['address_name']; ?></td>
                                <td><?= $address['address_city']; ?></td>
                                <td><?= $address['address_street']; ?></td>
                                <td></td>
                                <td>
                                    <form action="<?=_WEB_ROOT?>/update" method="post">
                                        <input type="hidden" name="address_id" value="<?= $address['address_id']; ?>">
                                        <input type="hidden" name="user_email" value="<?= $user_email; ?>">
                                        <select id="address_status" name="address_status" class="form-select shadow-sm " onchange="this.form.submit()">
                                        
                                            <option value="0" <?= $address['address_status'] == "0" ? "selected" : "" ?>>Use</option>
                                            <option value="1" <?= $address['address_status'] == "1" ? "selected" : "" ?>>Wait</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No addresses found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="?<?=_WEB_ROOT?>/user" class="btn btn-outline-secondary px-4 py-2 shadow ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>