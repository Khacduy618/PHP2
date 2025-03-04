<header class="header header-intro-clearance header-3">
    <div class="header-top">
        <div class="container">
            <div class="header-left">
                <a href="tel:#"><i class="icon-phone"></i>Call: +0123 456 789</a>
            </div><!-- End .header-left -->

            <div class="header-right">

                <ul class="top-menu">
                    <li>
                        <a href="#">Links</a>
                        <ul>
                            <li>
                                <div class="header-dropdown">
                                    <a href="#">USD</a>
                                    <div class="header-menu">
                                        <ul>
                                            <li><a href="#">Eur</a></li>
                                            <li><a href="#">Usd</a></li>
                                        </ul>
                                    </div><!-- End .header-menu -->
                                </div>
                            </li>
                            <li>
                                <div class="header-dropdown">
                                    <a href="#">English</a>
                                    <div class="header-menu">
                                        <ul>
                                            <li><a href="#">English</a></li>
                                            <li><a href="#">French</a></li>
                                            <li><a href="#">Spanish</a></li>
                                        </ul>
                                    </div><!-- End .header-menu -->
                                </div>
                            </li>
                            <?php
                                if (isset($_SESSION['user'])) {
                            ?>
                            <li>
                                <div class="header-dropdown">
                                    <a class="row align-items-center">
                                        <div class="avatar">
                                                
                                        </div>
                                        <strong><span><?= $_SESSION['user']['user_name'] ?></span></strong>
                                    </a>
                                    <div class="header-menu">
                                        <ul>
                                            <li><a href="<?php echo _WEB_ROOT?>/profile">My Account</a></li>
                                            <li><a href="<?php echo _WEB_ROOT?>/log-out" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất tài khoản này không?')">Sign Out</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <?php } else { ?>
                            <li><a href="<?php echo _WEB_ROOT ?>/dang-nhap">Sign in / Sign up</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul><!-- End .top-menu -->
            </div><!-- End .header-right -->

        </div><!-- End .container -->
    </div><!-- End .header-top -->

    <div class="header-middle">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>
                
                <a href="index.html" class="logo">
                    <img src="<?php echo _WEB_ROOT; ?>/public/assets/site/images/demos/demo-3/logo.png" alt="Molla Logo" width="105" height="25">
                </a>
            </div><!-- End .header-left -->

            <div class="header-center">
                <div class="header-search header-search-extended header-search-visible d-none d-lg-block">
                    <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                    <form action="javascript:void(0);" onsubmit="handleSearch(event)">
                        <div class="header-search-wrapper search-wrapper-wide">
                            <label for="q" class="sr-only">Search</label>
                            <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
                            <input type="search" class="form-control" name="q" id="q" 
                                value="<?= isset($_SESSION['search_keyword']) ? $_SESSION['search_keyword'] : '' ?>" 
                                placeholder="Search product ..." >
                        </div>
                    </form>
                    <script>
                        function handleSearch(e) {
                            e.preventDefault();
                            const searchTerm = document.getElementById('q').value;
                            // Convert search term to URL-friendly format
                            const urlFriendlySearch = searchTerm.trim().replace(/\s+/g, '-').toLowerCase();
                            // Redirect to the route-based URL with correct path
                            window.location.href = '<?= _WEB_ROOT ?>/product/0/' + (urlFriendlySearch || '') + '/popularity/12';
                        }
                    </script>
                </div>
            </div>

            <div class="header-right">
                <div class="dropdown compare-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static" title="Compare Products" aria-label="Compare Products">
                        <div class="icon">
                            <i class="icon-random"></i>
                        </div>
                        <p>Compare</p>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="compare-products">
                            <!-- <li class="compare-product">
                                <a href="#" class="btn-remove" title="Remove Product"><i class="icon-close"></i></a>
                                <h4 class="compare-product-title"><a href="product.html">Blue Night Dress</a></h4>
                            </li>
                            <li class="compare-product">
                                <a href="#" class="btn-remove" title="Remove Product"><i class="icon-close"></i></a>
                                <h4 class="compare-product-title"><a href="product.html">White Long Skirt</a></h4>
                            </li> -->
                        </ul>

                        <div class="compare-actions">
                            <!-- <a href="#" class="action-link">Clear All</a>
                            <a href="#" class="btn btn-outline-primary-2"><span>Compare</span><i class="icon-long-arrow-right"></i></a> -->
                        </div>
                    </div><!-- End .dropdown-menu -->
                </div><!-- End .compare-dropdown -->

                <div class="wishlist">
                    <a href="#" title="Wishlist">
                        <div class="icon">
                            <i class="icon-heart-o"></i>
                            <?php
                            if(isset($_SESSION['user'])) {
                                $favorite = new \App\Models\FavoriteModel();
                                $count = $favorite->countFavorites($_SESSION['user']['user_email']);
                            
                            ?>
                            <span class="wishlist-count badge"><?= $count ?></span>
                            <?php } else { ?>
                            <span class="wishlist-count badge">0</span>
                            <?php } ?>
                        </div>
                        <p>Wishlist</p>
                    </a>
                </div><!-- End .compare-dropdown -->

                <div class="dropdown cart-dropdown">
                <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static">
                        <div class="icon">
                            <i class="icon-shopping-cart"></i>
                            <?php
                            if(isset($_SESSION['user'])) {
                                $cart = new \App\Models\CartModel();
                                $cartItems = $cart->getCartItems($_SESSION['user']['user_email'],4);
                                $cartCount = count($cartItems);
                            ?>
                            <span class="cart-count"><?= $cartCount ?></span>
                            <?php } else { ?>
                            <span class="cart-count">0</span>
                            <?php } ?>
                        </div>
                        <p>Cart</p>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-cart-products">
                            <?php 
                            if(isset($_SESSION['user']) && !empty($cartItems)) {
                                $total = 0;
                                foreach($cartItems as $item) {
                                    $subtotal = $item['product_price'] * $item['quantity'];
                                    $total += $subtotal;
                            ?>
                            <div class="product">
                                <div class="product-cart-details">
                                    <h4 class="product-title">
                                        <a href="<?=_WEB_ROOT?>/product-detail/=<?=$item['pro_id']?>"><?=$item['product_name']?></a>
                                    </h4>

                                    <span class="cart-product-info">
                                        <span class="cart-product-qty"><?=$item['quantity']?></span>
                                        x <?=number_format($item['product_price'],0,",",".")?> đ
                                    </span>
                                </div>

                                <figure class="product-image-container">
                                    <a href="?act=product&id=<?=$item['pro_id']?>" class="product-image">
                                        <img src="<?=_WEB_ROOT?>/public/uploads/products/<?=$item['product_img']?>" alt="<?=$item['product_name']?>">
                                    </a>
                                </figure>
                               
                            </div>
                            <?php
                                }
                            echo '<div class="dropdown-cart-total">
                            <span>Total</span>
                            <span class="cart-total-price"> 
                                
                                    '.number_format($total,0,",",".").'đ
                              
                            </span>
                            </div>';

                            } else {
                            ?>
                            <div class="text-center p-3">No products in cart</div>
                            <?php } ?>
                        </div>

                        

                        <div class="dropdown-cart-action">
                            <?php if (isset($_SESSION['user'])) { ?>
                            <a href="<?=_WEB_ROOT?>/order_history" class="btn btn-outline-primary-2">Order History</a>
                            <?php } else { ?>
                            <a class="btn disabled"></a>
                            <?php } ?>

                            <a href="<?=_WEB_ROOT?>/cart" class="btn btn-outline-primary-2 ms-auto">View Cart</a>
                        </div>
                    </div>
                </div><!-- End .cart-dropdown -->
            </div><!-- End .header-right -->
        </div><!-- End .container -->
    </div><!-- End .header-middle -->

    <div class="header-bottom sticky-header">
        <div class="container">
            <div class="header-left">
                <div class="dropdown category-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static" title="Browse Categories">
                        Browse Categories <i class="icon-angle-down"></i>
                    </a>

                    <div class="dropdown-menu">
                        <nav class="side-nav">
                            <ul class="category-menu">
                            <?php
                                $category = new \App\Controllers\Category();
                                echo $category->list_cat_home();
                            ?>
                            </ul><!-- End .menu-vertical -->
                        </nav><!-- End .side-nav -->
                    </div><!-- End .dropdown-menu -->
                </div><!-- End .category-dropdown -->
            </div><!-- End .header-left -->

            <div class="header-center">
                <nav class="main-nav">
                    <ul class="menu sf-arrows">
                        <li>
                            <a href="<?php echo _WEB_ROOT?>/trang-chu" >Home</a>

                            
                        </li>
                        <li>
                            <a href="<?php echo _WEB_ROOT?>/product" class="sf-with-ul">Shop</a>

                            
                        </li>
                        <li>
                            <a href="<?php echo _WEB_ROOT?>/#r" >Contact</a>

                            
                        </li>
                        <li>
                            <a href="<?php echo _WEB_ROOT?>/#">About us</a>

                            
                        </li>
                        <li>
                            <a href="<?php echo _WEB_ROOT?>/#" >Blog</a>

                           
                        </li>
                        
                    </ul><!-- End .menu -->
                </nav><!-- End .main-nav -->
            </div><!-- End .header-center -->

            <div class="header-right">
                <i class="la la-lightbulb-o"></i><p>Clearance<span class="highlight">&nbsp;Up to 30% Off</span></p>
            </div>
        </div><!-- End .container -->
    </div><!-- End .header-bottom -->
</header><!-- End .header -->