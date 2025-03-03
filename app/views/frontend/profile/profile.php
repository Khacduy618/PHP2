<div class="container mt-2">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-4"><?=$title?></h2>
            <?php
            if (isset($_COOKIE['msg'])) {
                echo '<div class="alert alert-success">' . $_COOKIE['msg'] . '</div>';
            }
            ?>
            
            <?php if (!empty($profile)):
                extract($profile) ?>
            <div class="card">
                <div class="card-body">
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
                                <form action="<?php echo _WEB_ROOT; ?>/update_avatar" 
                                      method="POST" 
                                      enctype="multipart/form-data"
                                      class="d-inline">
                                    <input type="file" 
                                           name="user_image" 
                                           id="user_image" 
                                           class="d-none"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <label for="user_image" class="btn btn-outline-primary">
                                        Thay đổi ảnh
                                    </label>
                                    <button type="submit" id="submit-btn" class="btn btn-primary d-none">
                                        Lưu thay đổi
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4>Thông tin cơ bản</h4>
                            <table class="table">
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $user_email; ?></td>
                                </tr>
                                <tr>
                                    <th>Họ tên:</th>
                                    <td><?php echo $user_name ?? 'Chưa cập nhật'; ?></td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại:</th>
                                    <td><?php echo $user_phone ?? 'Chưa cập nhật'; ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h4>Địa chỉ</h4>
                            <table class="table">
                                <tr>
                                    <th>Tên địa chỉ</th>
                                    <td><?php echo $address_name ?? 'Chưa cập nhật'; ?></td>
                                </tr>
                                <tr>
                                    <th>Tỉnh:</th>
                                    <td><?php echo $address_province ?? 'Chưa cập nhật'; ?></td>
                                </tr>
                                <tr>
                                    <th>Thành phố:</th>
                                    <td><?php echo $address_city ?? 'Chưa cập nhật'; ?></td>
                                </tr>
                                <tr>
                                    <th>Địa chỉ:</th>
                                    <td><?php echo $address_street ?? 'Chưa cập nhật'; ?></td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?php echo _WEB_ROOT; ?>/edit-profile" class="btn btn-primary">
                            Chỉnh sửa thông tin
                        </a>
                        <a href="<?php echo _WEB_ROOT; ?>/edit-password" class="btn btn-primary">
                            Đổi mật khẩu
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Không tìm thấy thông tin người dùng hoặc bạn chưa đăng nhập.
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
            document.getElementById('submit-btn').classList.remove('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>