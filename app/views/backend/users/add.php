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
    <form action="<?=_WEB_ROOT?>/store-user" method="POST" enctype="multipart/form-data" class="p-3" id="addUserForm">
        <div class="d-flex align-items-start mb-4" style="gap: 1rem;">
            <div>
                <img
                    src="<?=_WEB_ROOT?>/public/uploads/avatar/user.png"
                    alt="User Image"
                    id="user_image_preview"
                    class="border"
                    style="width: 110px; height: 110px; object-fit: cover;">
            </div>
            <!-- Nút tải ảnh mới lên -->
            <div class="d-flex flex-column justify-content-end" style="height: 109px;">
                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm shadow-sm"
                    onclick="document.getElementById('user_images').click();">
                    <i class="fas fa-upload"></i> Upload New Image
                </button>
            </div>
        </div>

        <input
            type="file"
            id="user_images"
            name="user_images"
            accept="image/*"
            class="d-none"
            onchange="updateImagePreview(event)">

        <!-- Các trường thông tin -->
        <div class="mb-3">
            <label for="user_email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
            <input
                type="email"
                name="user_email"
                id="user_email"
                class="form-control shadow-sm"
                required>
            <div class="invalid-feedback" id="email-error"></div>
        </div>

        <div class="mb-3">
            <label for="user_name" class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
            <input
                type="text"
                name="user_name"
                id="user_name"
                class="form-control shadow-sm"
                required>
            <div class="invalid-feedback" id="name-error"></div>
        </div>
        
        <div class="mb-3">
            <label for="user_phone" class="form-label fw-bold">Điện thoại <span class="text-danger">*</span></label>
            <input
                type="text"
                name="user_phone"
                id="user_phone"
                class="form-control shadow-sm"
                required>
            <div class="invalid-feedback" id="phone-error"></div>
        </div>

        <div class="mb-3">
            <label for="user_role" class="form-label fw-bold">Quyền</label>
            <select name="user_role" id="user_role" class="form-select shadow-sm">
                <option value="0">User</option>
                <option value="2">Employee</option>
            </select>
        </div>

        <!-- Nút gửi -->
        <div class="mt-4">
            <button type="submit" name="submit" class="btn btn-success px-4 py-2 shadow">Thêm Người Dùng</button>
            <a href="<?= _WEB_ROOT?>/user" class="btn btn-outline-secondary px-4 py-2 shadow ms-2">Cancel</a>
        </div>
    </form>
</div>

<script>
    // Cập nhật hình ảnh xem trước
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

    // Client-side validation
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        let isValid = true;
        
        // Reset previous error states
        this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        // Validate Email
        const email = document.getElementById('user_email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            email.classList.add('is-invalid');
            document.getElementById('email-error').textContent = 'Email không hợp lệ';
            isValid = false;
        }

        // Validate Name
        const name = document.getElementById('user_name');
        if (name.value.trim().length < 2 || name.value.trim().length > 50) {
            name.classList.add('is-invalid');
            document.getElementById('name-error').textContent = 'Tên phải từ 2-50 ký tự';
            isValid = false;
        }

        // Validate Phone
        const phone = document.getElementById('user_phone');
        const phoneRegex = /^[0-9]{10,12}$/;
        if (!phoneRegex.test(phone.value)) {
            phone.classList.add('is-invalid');
            document.getElementById('phone-error').textContent = 'Số điện thoại phải từ 10-12 số';
            isValid = false;
        }

        // Validate Image
        const image = document.getElementById('user_images');
        if (image.files.length > 0) {
            const file = image.files[0];
            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert('Kích thước ảnh không được vượt quá 2MB');
                isValid = false;
            }
            
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Chỉ chấp nhận file ảnh định dạng: jpg, jpeg, png');
                isValid = false;
            }
        } else {
            alert('Vui lòng chọn ảnh đại diện');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Real-time validation
    document.getElementById('user_phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 12) {
            this.value = this.value.slice(0, 12);
        }
    });

    document.getElementById('user_name').addEventListener('input', function(e) {
        if (this.value.length > 50) {
            this.value = this.value.slice(0, 50);
        }
    });

    // // Tải danh sách thành phố từ API
    // document.addEventListener('DOMContentLoaded', function() {
    //     const citySelect = document.getElementById('address_city');
    //     fetch('https://provinces.open-api.vn/api/')
    //         .then(response => response.json())
    //         .then(data => {
    //             citySelect.innerHTML = '<option value="">Chọn thành phố</option>';
    //             data.forEach(city => {
    //                 const option = document.createElement('option');
    //                 option.value = city.code; // Thay bằng mã code để nhất quán
    //                 option.textContent = city.name;
    //                 citySelect.appendChild(option);
    //             });
    //         })
    //         .catch(error => {
    //             console.error('Lỗi tải danh sách thành phố:', error);
    //             citySelect.innerHTML = '<option value="">Không thể tải dữ liệu</option>';
    //         });
    // });
</script>