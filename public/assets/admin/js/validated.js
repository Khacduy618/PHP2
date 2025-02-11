//Khách hàng
$().ready(function () {
    $("#form_nguoidung").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            "Ho": {
                required: true,
                RegExp: /^[a-zA-Z\s\-_]+$/
            },
            "Ten": {
                required: true,
                RegExp: /^[a-zA-Z\s\-_]+$/
            },
            "GioiTinh": {
                required: true,
            },
            "Email": {
                required: true,
                email: true
            },
            "SDT": {
                required: true,
                maxlength: 12
            },
            "DiaChi": {
                required: true,
                maxlength: 200
            },
            "Hinh": {
                required: true
            },
            "TaiKhoan": {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            "MatKhau": {
                required: true,
                minlength: 8,
                maxlength: 20
            },
            "TrangThai": {
                required: true
            }

        },
        messages: {
            "Ho": {
                required: "Họ không được để trống !",
                RegExp: "Họ chỉ chứa alphabet và ký tự trắng !"
            },
            "Ten": {
                required: "Tên không được để trống !",
                RegExp: "Tên chỉ chứa alphabet và ký tự trắng !"
            },
            "GioiTinh": {
                required: "Vui lòng chọn giới tính !"
            },
            "Email": {
                required: "Email không được để trống !",
                email: "Email không hợp lệ !"
            },
            "SDT": {
                required: "Số điện thoại không được để trống !",
                maxlength: "Số kí tự phải nhỏ hơn hoặc bằng 12 !"
            },
            "DiaChi": {
                required: "Địa chỉ không được để trống !",
                maxlength: "Số kí tự nhỏ hơn hoặc bằng 200 !"
            },
            "Hinh": {
                required: "Hình không được để trống !"
            },
            "TaiKhoan": {
                required: "Tài khoản không được để trống !",
                minlength: "Số kí tự phải lớn hơn hoặc bằng 6 !",
                maxlength: "Số kí tự phải nhỏ hơn hoặc bằng 20 !"
            },
            "MatKhau": {
                required: "Mật khẩu không được để trống !",
                minlength: "Số kí tự phải lớn hơn hoặc bằng 8 !",
                maxlength: "Số kí tự phải nhỏ hơn hoặc bằng 20 !"
            },
            "TrangThai": {
                required: "Vui lòng chọn trạng thái !",
            }
        }
    });
});
//Sản phẩm
$().ready(function () {
    $("#form_sanpham").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            "TenSP": {
                required: true
            },
            "DonGia": {
                required: true,
                number: true,
                min: 1
            },
            "GiamGia": {
                required: true,
                number: true,
                range: [0, 1]
            },
            "HinhAnh1": {
                required: true
            },
            "ThongTin": {
                required: true
            },
            "CPU": {
                required: true
            },
            "RAM": {
                required: true
            },
            "SSD_HDD": {
                required: true
            },
            "GPU":{
                required: true
            },
            "OS": {
                required: true
            },
            "kich_thuoc_man_hinh":{
                required: true
            },
            "kich_thuoc": {
                required: true
            },
            "mau_sac": {
                required: true
            },
            "chat_lieu": {
                required: true
            },
            "cong_giao_tiep": {
                required: true
            },
            "ThoiGian": {
                required: true,
                beforeToday: true
            }

        },
        messages: {
            "TenSP": {
                required: "Tên sản phẩm không được trống !"
            },
            "DonGia": {
                required: "Đơn giá không được trống !",
                number: "Phải là số dương !",
                min: "Phải là số dương !"
            },
            "GiamGia": {
                required: "Giảm giá không được trống !",
                number: "Phải là số dương !",
                range: "Phải là số từ 0 đến 1 (ví dụ 0.2 là giảm 20%)"
            },
            "HinhAnh1": {
                required: "Hình ảnh không được trống !"
            },
            "ThongTin": {
                required: "Thông tin không được trống !"
            },
            "CPU": {
                required: "CPU không được trống !"
            },
            "RAM": {
                required: "RAM không được trống !"
            },
            "SSD_HDD": {
                required: "Ổ cứng không được trống !"
            },
            "GPU":{
                required: "Card màn hình không được trống !"
            },
            "OS": {
                required: "Hệ điều hành không được trống !"
            },
            "kich_thuoc_man_hinh":{
                required: "Kích thước màn hình không được trống !"
            },
            "kich_thuoc": {
                required: "Kích thước không được trống !"
            },
            "mau_sac": {
                required: "Màu sắc không được trống !"
            },
            "chat_lieu": {
                required: "Chất liệu không được trống !"
            },
            "cong_giao_tiep": {
                required: "Cổng giao tiếp không được trống !"
            },
            "ThoiGian": {
                required: "Ngày nhập không được trống !",
                date: "Phải là ngày tháng năm",
                beforeToday: "Trước ngày (hoặc trước thời gian) hiện tại !"
            }
        }
    });
});