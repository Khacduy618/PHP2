//Đăng ký
$().ready(function () {
    $("#form_dangky").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            "TaiKhoan": {
                required: true,
                maxlength: 15
            },
            "MatKhau": {
                required: true,
                minlength: 8
            },
            "MatKhau2": {
                equalTo: "#MatKhau",
                minlength: 8
            },
            "Email": {
                required: true,
                email: true
            },
            "Ho": {
                required: true
            },
            "Ten": {
                required: true
            },
            "HinhAnh": {
                required: true
            }
        },
        messages: {
            "TaiKhoan": {
                required: "Bắt buộc nhập tài khoản !",
                maxlength: "Hãy nhập tối đa 15 ký tự !"
            },
            "MatKhau": {
                required: "Bắt buộc nhập mật khẩu !",
                minlength: "Hãy nhập ít nhất 8 ký tự !"
            },
            "MatKhau2": {
                equalTo: "Hai mật khẩu phải giống nhau !",
                minlength: "Hãy nhập ít nhất 8 ký tự !"
            },
            "Email": {
                required: "Bắt buộc nhập email !",
                email: "Hãy nhập đúng định dạng email !"
            },
            "Ho": {
                required: "Bắt buộc nhập họ !"
            },
            "Ten": {
                required: "Bắt buộc nhập tên !"
            },
            "HinhAnh": {
                required: "Bắt buộc chọn hình ảnh !"
            }
        }
    });
});
//Thanh toán
$().ready(function () {
    $("#form_thanhtoan").validate({
        onfocusout: false,
        onkeyup: false,
        onclick: false,
        rules: {
            "Ho": {
                required: true,
                maxlength: 20
            },
            "Ten": {
                required: true,
                maxlength: 20
            },
            "DiaChi": {
                required: true,
                maxlength: 200
            },
            "Email": {
                required: true,
                email: true
            },
            "SDT": {
                required: true,
                maxlength: 12
            },
            "GhiChu": {
                required: true
            },
            "PhuongThucTT": {
                required: true
            }
        },
        messages: {
            "Ho": {
                required: "Vui lòng nhập họ !",
                maxlength: 20
            },
            "Ten": {
                required: "Vui lòng nhập tên !",
                maxlength: 20
            },
            "DiaChi": {
                required: "Vui lòng nhập địa chỉ !",
                maxlength: 200
            },
            "Email": {
                required: "Vui lòng nhập Email !",
                email: true
            },
            "SDT": {
                required: "Vui lòng nhập số điện thoại !",
                maxlength: 12
            },
            "GhiChu": {
                required: "Vui lòng nhập ghi chú !"
            },
            "PhuongThucTT": {
                required: "Vui lòng chọn phương thức !"
            }
        }
    });
});
//Kiểm tra số lượng nhập vào mua hàng
// $().ready(function () {
//     $("#form_incart").validate({
//         onfocusout: false,
//         onkeyup: false,
//         onclick: false,
//         rules: {
//             "#qty": {
//                 required: true,
//                 minlength: 1,
//                 maxlength: 50
//             }
//         },
//         messages: {
//             "#qty": {
//                 required: "Vui lòng nhập số lượng !",
//                 minlength: "Số lượng phải lớn hơn hoặc bằng 1",
//                 maxlength: "Số lượng không vượt quá 50"
//             }
//         }
//     });
// });