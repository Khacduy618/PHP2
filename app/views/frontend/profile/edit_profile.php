<div class="container mt-2">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-4"><?=$title?></h2>
            <?php if(isset($_COOKIE['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?= $_COOKIE['msg'] ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_COOKIE['msg1'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?= $_COOKIE['msg1'] ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($profile)): extract($profile); ?>
            <div class="card">
                <div class="card-body">
                    <form id="profileForm" action="<?=_WEB_ROOT?>/update-profile" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 text-center mb-4">
                                <div class="profile-image">
                                    <?php 
                                    $avatar = !empty($user_images) 
                                        ? _WEB_ROOT . '/public/uploads/avatar/' . $user_images
                                        : _WEB_ROOT . '/public/uploads/avatar/default-avatar.jpg';
                                    ?>
                                    <img src="<?php echo $avatar; ?>" 
                                         alt="Profile Image" 
                                         class="rounded-circle"
                                         id="preview-image"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <div class="mt-3">
                                    <input type="file" 
                                           name="user_image" 
                                           id="user_image" 
                                           class="d-none"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <label for="user_image" class="btn btn-outline-primary">
                                        Thay đổi ảnh
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h4>Thông tin cơ bản</h4>
                                <div class="mb-3">
                                    <label class="form-label">Email:</label>
                                    <input type="email" class="form-control" value="<?=$user_email?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Họ tên:</label>
                                    <input type="text" class="form-control" name="user_name" 
                                           value="<?=$user_name?>" required minlength="2" maxlength="50">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại:</label>
                                    <input type="tel" class="form-control" name="user_phone" 
                                           value="<?=$user_phone?>" required pattern="[0-9]{10,12}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h4>Địa chỉ</h4>
                                <div class="mb-3">
                                    <label class="form-label">Tên địa chỉ:</label>
                                    <input type="text" class="form-control" name="address_name" 
                                           value="<?=$address_name?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tỉnh:</label>
                                    <input type="text" class="form-control" name="address_province" 
                                           value="<?=$address_province ?? ''?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Thành phố:</label>
                                    <input type="text" class="form-control" name="address_city" 
                                           value="<?=$address_city?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Địa chỉ chi tiết:</label>
                                    <input type="text" class="form-control" name="address_street" 
                                           value="<?=$address_street?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <a href="<?=_WEB_ROOT?>/profile" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Form validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userName = document.querySelector('input[name="user_name"]').value.trim();
    const userPhone = document.querySelector('input[name="user_phone"]').value.trim();
    const addressName = document.querySelector('input[name="address_name"]').value.trim();
    const addressCity = document.querySelector('input[name="address_city"]').value.trim();
    const addressStreet = document.querySelector('input[name="address_street"]').value.trim();
    
    // Validate họ tên
    if (userName.length < 2 || userName.length > 50) {
        alert('Họ tên phải từ 2 đến 50 ký tự');
        return;
    }

    // Validate số điện thoại
    if (!/^[0-9]{10,12}$/.test(userPhone)) {
        alert('Số điện thoại phải từ 10 đến 12 số');
        return;
    }

    // Validate các trường địa chỉ
    if (!addressName || !addressCity || !addressStreet) {
        alert('Vui lòng điền đầy đủ thông tin địa chỉ');
        return;
    }

    // Validate file ảnh nếu có
    const imageInput = document.getElementById('user_image');
    if (imageInput.files.length > 0) {
        const file = imageInput.files[0];
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        
        if (!validTypes.includes(file.type)) {
            alert('Chỉ chấp nhận file ảnh định dạng JPG, JPEG hoặc PNG');
            return;
        }
        
        if (fileSize > 2) {
            alert('Kích thước ảnh không được vượt quá 2MB');
            return;
        }
    }

    this.submit();
});
</script>