
<ul class="navbar-nav">
    <li class="nav-item">
        <a href="#" class="nav-link">
            <span class="icon">
                <div class="user">
                    <img src="<?=_WEB_ROOT?>/public/uploads/avatar/<?=$_SESSION['user']['user_images']?>" alt="Avatar">
                </div>
            </span>
            <span class="title"></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= _WEB_ROOT?>/dash-board">
            <span class="icon">
                <i class="fa-solid fa-house fa-xl"></i>
            </span>
            <span class="title">Dashboard</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="<?= _WEB_ROOT?>/category">
            <span class="icon">
                <i class="fa-solid fa-list fa-xl"></i>
            </span>
            <span class="title">Categories</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="<?= _WEB_ROOT?>/product">
            <span class="icon">
                <i class="fa-solid fa-box fa-xl"></i>
            </span>
            <span class="title">Product</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="<?= _WEB_ROOT?>/favorite">
            <span class="icon">
                <i class="fa-solid fa-heart fa-xl"></i>
            </span>
            <span class="title">Favorite</span>
        </a>
    </li>
   
    <li class="nav-item">
        <a class="nav-link" href="<?= _WEB_ROOT?>/coupon">
            <span class="icon">
                <i class="fa-solid fa-ticket fa-xl"></i>
            </span>
            <span class="title">Coupon</span>
        </a>
    </li>
   
    <li class="nav-item">
        <a class="nav-link" href="<?= _WEB_ROOT?>/blog">
            <span class="icon">
                <i class="fa-solid fa-blog fa-xl"></i>
            </span>
            <span class="title">Blog</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="<?= _WEB_ROOT?>/review">
            <span class="icon">
                <i class="fa-solid fa-star fa-xl"></i>
            </span>
            <span class="title">Review</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="<?= _WEB_ROOT?>/bill">
            <span class="icon">
                <i class="fa-solid fa-receipt fa-xl"></i>
            </span>
            <span class="title">Bill</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="<?= _WEB_ROOT?>/user">
            <span class="icon">
                <i class="fa-solid fa-users fa-xl"></i>
            </span>
            <span class="title">User</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="?mod=authorization&act=authorization_index">
            <span class="icon">
                <i class="fa-solid fa-user-shield fa-xl"></i>
            </span>
            <span class="title">Authorization</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" href="../?mod=login">
            <span class="icon">
                <i class="fa-solid fa-store fa-xl"></i>
            </span>
            <span class="title">SHOP</span>
        </a>
    </li>
</ul>