<?php
$avatar = !empty($user_images) 
? _WEB_ROOT . '/public/uploads/avatar/' . $user_images
: _WEB_ROOT . '/public/uploads/avatar/user.png';
?>

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
    <div class="row mb-4 frmcontent">
        <?php
            if (isset($user) && is_array($user)) {
                extract($user);
            ?>
        <form action="<?=_WEB_ROOT?>/update-user" method="post" enctype="multipart/form-data" class="p-3">
            <!-- Hiển thị ảnh -->
            <div class="d-flex align-items-center mb-4" style="gap: 1.5rem;">
                <div>
                    <img
                        src="<?= $avatar ?>"
                        alt="User Image"
                        id="user_image_preview"
                        class="border"
                        style="width: 110px; height: 110px; object-fit: cover;">
                </div>
                <div class="d-flex flex-column justify-content-end" style="height: 109px;">
                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm shadow-sm"
                        onclick="document.getElementById('user_images').click();">
                        <i class="fas fa-upload"></i> Upload New Image
                    </button>
                </div>
                <a href="<?= _WEB_ROOT?>/user" class="btn btn-outline-secondary px-4 py-2 shadow ms-auto">Back to list</a>
            </div>
            <input type="file" id="user_images" name="user_images" accept="image/*" class="d-none" onchange="updateImagePreview(event)">

            <!-- Trường thông tin người dùng -->
            <div class="mb-3">
                <label for="user_name" class="form-label fw-bold">Full Name</label>
                <input type="text" id="user_name" name="user_name" class="form-control shadow-sm" value="<?= $user_name?>" >
            </div>

            <div class="mb-3">
                <label for="user_email" class="form-label fw-bold">Email</label>
                <input type="email" id="user_email" name="user_email" class="form-control shadow-sm bg-light" value="<?= $user_email ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="user_phone" class="form-label fw-bold">Phone Number</label>
                <input type="text" id="user_phone" name="user_phone" class="form-control shadow-sm" value="<?= $user_phone ?>" >
            </div>


            <!-- Vai trò và trạng thái -->
            <div class="mb-3">
                <label for="user_role" class="form-label fw-bold">Role</label>
                <select id="user_role" name="user_role" class="form-select shadow-sm">
                    <option value="0" <?= $user_role == "0" ? "selected" : "" ?>>User</option>
                    <option value="2" <?= $user_role == "2" ? "selected" : "" ?>>Employee</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="user_status" class="form-label fw-bold">Status</label>
                <select id="user_status" name="user_status" class="form-select shadow-sm">
                    <option value="1" <?= $user_status == "1" ? "selected" : "" ?>>Active</option>
                    <option value="0" <?= $user_status == "0" ? "selected" : "" ?>>Inactive</option>
                </select>
            </div>

            <!-- Nút hành động -->
            <div class="mt-4">
                <button type="submit" class="btn btn-success px-4 py-2 shadow">Update</button>
            </div>
        </form>
        <?php
            } else {
                echo '<p class="text-danger fw-bold">User not found!</p>';
            }
            ?>
    </div>
</div>

<script>
    function updateImagePreview(event) {
        const input = event.target;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('user_image_preview').src = e.target.result;
        };
        if (input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const citySelect = document.getElementById('address_city');
        const selectedCity = '<?= $address[0]['address_city'] ?? '' ?>';

        fetch('https://provinces.open-api.vn/api/')
            .then(response => response.json())
            .then(data => {
                citySelect.innerHTML = '<option value="">Choose</option>';
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.name;
                    option.textContent = city.name;
                    if (city.name === selectedCity) {
                        option.selected = true;
                    }
                    citySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching cities:', error);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
            });
    });
</script>