<div class="row">
    <div class="row frmtitle">
        <h1><?=$title?></h1>
    </div>

    <form action="<?=_WEB_ROOT?>/store-user" method="POST" enctype="multipart/form-data" class="p-3">
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
            <label for="user_email" class="form-label fw-bold">Email</label>
            <input
                type="email"
                name="user_email"
                id="user_email"
                class="form-control shadow-sm"
                required>
        </div>

        <div class="mb-3">
            <label for="user_name" class="form-label fw-bold">Họ và tên</label>
            <input
                type="text"
                name="user_name"
                id="user_name"
                class="form-control shadow-sm"
                required>
        </div>
        
        <div class="mb-3">
            <label for="user_phone" class="form-label fw-bold">Điện thoại</label>
            <input
                type="text"
                name="user_phone"
                id="user_phone"
                class="form-control shadow-sm"
                required>
        </div>

        <div class="mb-3">
            <label for="user_role" class="form-label fw-bold">Quyền</label>
            <select name="user_role" id="user_role" class="form-select shadow-sm">
                <option value="0">User</option>
                <option value="1">Admin</option>
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