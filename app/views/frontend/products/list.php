<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= _WEB_ROOT; ?>/trang-chu">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"">Shop</a></li>
            </ol>
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

        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="toolbox">
                        <div class="toolbox-left">
                            <div class="toolbox-info">
                                Showing <span><?= count($product_list) ?> of <?= $total_products ?></span> Products
                            </div><!-- End .toolbox-info -->
                        </div><!-- End .toolbox-left -->

                        <div class="toolbox-right">
                            <div class="toolbox-sort">
                                <label for="sortby">Sort by:</label>
                                <div class="select-custom">
                                    <?php
                                    $current_url = $_SERVER['REQUEST_URI'];
                                    $url_parts = explode('/', trim($current_url, '/'));
                                    
                                    // Remove 'php2' from parts if it exists
                                    if(isset($url_parts[0]) && $url_parts[0] == 'php2') {
                                        array_shift($url_parts);
                                    }
                                    
                                    // Get current parameters or set defaults
                                    $category_id = isset($url_parts[1]) ? $url_parts[1] : '0';
                                    $search = isset($url_parts[2]) ? $url_parts[2] : '';
                                    $perpage = isset($url_parts[4]) ? $url_parts[4] : '12';
                                    
                                    // Current sort value is in position 3
                                    $current_sort = isset($url_parts[3]) ? $url_parts[3] : 'popularity';
                                    ?>
                                    <select name="sortby" id="sortby" class="form-control" 
                                            onchange="window.location.href='<?= _WEB_ROOT ?>/product/<?= $category_id ?>/<?= $search ? $search : ''?>/' + this.value + '/<?= $perpage ?>'">
                                        <option value="popularity" <?= $current_sort == 'popularity' ? 'selected' : '' ?>>Most Popular</option>
                                        <option value="rating" <?= $current_sort == 'rating' ? 'selected' : '' ?>>Most Rated</option>
                                        <option value="date" <?= $current_sort == 'date' ? 'selected' : '' ?>>Date</option>
                                        <option value="price-low" <?= $current_sort == 'price-low' ? 'selected' : '' ?>>Price Low to High</option>
                                        <option value="price-high" <?= $current_sort == 'price-high' ? 'selected' : '' ?>>Price High to Low</option>
                                    </select>
                                </div>
                            </div><!-- End .toolbox-sort -->
                        </div><!-- End .toolbox-right -->
                    </div><!-- End .toolbox -->
                    
                    <div class="products mb-3">
                        <div class="row justify-content-center">

                            <?php 
                                
                                    foreach ($product_list as $value) {
                                        extract($value);
                            ?>

                            <div class="col-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="product product-7 text-center">
                                    <figure class="product-media">
                                    <?php 
                                        // Kiểm tra sản phẩm có được tạo trong vòng 3 tháng không
                                        $created_date = strtotime($created_at);
                                        $one_month_ago = strtotime('-3 month');
                                        if ($created_date >= $one_month_ago) { 
                                        ?>
                                            <span class="product-label label-new">New</span>
                                        <?php } ?>
                                        <a <?php if ($product_status!= 0 && $product_count != 0) { ?> href="<?php echo _WEB_ROOT; ?>/product-detail/<?=$product_id?>"  <?php } ?> >
                                            <?php 
                                                $image = !empty($product_img) 
                                                    ? _WEB_ROOT . '/public/uploads/products/' . $product_img
                                                    : _WEB_ROOT . '/public/uploads/products/default-product.jpg';
                                            ?>
                                            <img src="<?=$image?>" alt="Product image" class="product-image">
                                        </a>

                                        <div class="product-action-vertical">
                                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                                            <a href="#" class="btn-product-icon btn-compare" title="Compare"><span>Compare</span></a>
                                        </div><!-- End .product-action-vertical -->

                                        <div class="product-action">
                                            <?php if ($product_status!= 0 && $product_count != 0) { ?>
                                            <form action="<?=_WEB_ROOT?>/add-to-cart" class="w-100" method="post">
                                                
                                                <input type="hidden" name="product_id" value="<?php echo $product_id?>">
                                                <input type="hidden" name="quantity" value="1">
                                                
                                                <button type="submit" class="btn-product btn-cart "><span>add to cart</span></button>
                                                
                                            </form>
                                            <?php } ?>
                                        </div><!-- End .product-action -->
                                    </figure><!-- End .product-media -->

                                    <div class="product-body">
                                        <div class="product-cat">
                                            <a href="#"><?=$category_name?></a>
                                        </div><!-- End .product-cat -->
                                        <h3 class="product-title"><a href="product.html"><?=$product_name?></a></h3><!-- End .product-title -->
                                        <div class="product-price">
                                        <?=number_format($product_price,0,",",".")?> đ
                                        </div><!-- End .product-price -->
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-val" style="width: 20%;"></div><!-- End .ratings-val -->
                                            </div><!-- End .ratings -->
                                            <span class="ratings-text">( 2 Reviews )</span>
                                        </div><!-- End .rating-container -->

                                        <!-- <div class="product-nav product-nav-thumbs">
                                            <a href="#" class="active">
                                                <img src="<?php echo _WEB_ROOT; ?>/public/assets/images/products/product-4-thumb.jpg" alt="product desc">
                                            </a>
                                            <a href="#">
                                                <img src="<?php echo _WEB_ROOT; ?>/public/assets/images/products/product-4-2-thumb.jpg" alt="product desc">
                                            </a>

                                            <a href="#">
                                                <img src="<?php echo _WEB_ROOT; ?>/public/assets/images/products/product-4-3-thumb.jpg" alt="product desc">
                                            </a>
                                        </div> -->
                                    </div><!-- End .product-body -->
                                </div><!-- End .product -->
                            </div><!-- End .col-sm-6 col-lg-4 col-xl-3 -->

                            <?php }?>

                        </div><!-- End .row -->
                    </div><!-- End .products -->


                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php
                            // Get current URL parameters
                            $current_url = $_SERVER['REQUEST_URI'];
                            $url_parts = explode('/', trim($current_url, '/'));
                            
                            // Remove 'php2' from parts if it exists
                            if(isset($url_parts[0]) && $url_parts[0] == 'php2') {
                                array_shift($url_parts);
                            }
                            
                            // Get current parameters
                            $category_id = isset($url_parts[1]) ? $url_parts[1] : '0';
                            $search = isset($url_parts[2]) ? $url_parts[2] : '';
                            $sort = isset($url_parts[3]) ? $url_parts[3] : 'popularity';
                            $current_page = isset($url_parts[4]) ? intval($url_parts[4]) : 1;
                            
                            // Calculate total pages
                            $total_pages = ceil($total_products / 12);
                            
                            // Previous page
                            $prev_page = max(1, $current_page - 1);
                            $prev_disabled = $current_page == 1 ? 'disabled' : '';
                            ?>
                            
                            <!-- Previous button -->
                            <li class="page-item <?= $prev_disabled ?>">
                                <a class="page-link page-link-prev" 
                                   href="<?= _WEB_ROOT ?>/product/<?= $category_id ?>/<?= $search ?>/<?= $sort ?>/<?= $prev_page ?>" 
                                   aria-label="Previous" <?= $prev_disabled ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                    <span aria-hidden="true"><i class="icon-long-arrow-left"></i></span>Prev
                                </a>
                            </li>

                            <?php
                            // Display page numbers
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $start_page + 4);
                            
                            for($i = $start_page; $i <= $end_page; $i++):
                                $active = $i == $current_page ? 'active' : '';
                            ?>
                                <li class="page-item <?= $active ?>" aria-current="page">
                                    <a class="page-link" 
                                       href="<?= _WEB_ROOT ?>/product/<?= $category_id ?>/<?= $search ?>/<?= $sort ?>/<?= $i ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if($end_page < $total_pages): ?>
                                <li class="page-item-total">of <?= $total_pages ?></li>
                            <?php endif; ?>

                            <?php
                            // Next page
                            $next_page = min($total_pages, $current_page + 1);
                            $next_disabled = $current_page == $total_pages ? 'disabled' : '';
                            ?>
                            
                            <!-- Next button -->
                            <li class="page-item <?= $next_disabled ?>">
                                <a class="page-link page-link-next" 
                                   href="<?= _WEB_ROOT ?>/product/<?= $category_id ?>/<?= $search ?>/<?= $sort ?>/<?= $next_page ?>" 
                                   aria-label="Next" <?= $next_disabled ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                    Next <span aria-hidden="true"><i class="icon-long-arrow-right"></i></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div><!-- End .col-lg-9 -->
                <?php $this->render('frontend/products/sidebar') ?>
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->