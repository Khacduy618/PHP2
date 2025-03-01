<main class="main">
<nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Login</li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->
			<?php if(isset($_COOKIE['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <strong><?php echo htmlspecialchars($_COOKIE['msg']); ?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if(isset($_COOKIE['msg1'])): ?>
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <strong><?php echo htmlspecialchars($_COOKIE['msg1']); ?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>
    
<div class="container login-form">
    <h3><?=$title?></h3>
    <form action="<?php echo _WEB_ROOT; ?>/send_email" method="POST">
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email của bạn để đặt lại mật khẩu" required>
        </div>
        <div class="form-footer">
            <button type="submit" class="btn btn-outline-primary-2">
                <span>Gửi yêu cầu</span>
                <i class="icon-long-arrow-right"></i>
            </button>
        </div>
        <br/>
        <a href="<?php echo _WEB_ROOT; ?>/dang-nhap" class="forgot-link">Quay lại đăng nhập</a>
    </form>
</div> 
</main>