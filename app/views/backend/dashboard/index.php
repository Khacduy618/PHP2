<h1 class="text-uppercase card-body">Admin Dashboard</h1>

<div class="cardBox">
    <div class="card">
        <div>
            <div class="numbers"><?=$total_user?></div>
            <div class="cardName">Users</div>
        </div>

        <div class="iconBx">
            <ion-icon name="eye-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>
            <div class="numbers"><?=$total_dh?></div>
            <div class="cardName">Bills</div>
        </div>

        <div class="iconBx">
            <ion-icon name="cart-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>
            <div class="numbers"><?=$total_blogs?></div>
            <div class="cardName">Blogs</div>
        </div>

        <div class="iconBx">
            <ion-icon name="book-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>

            <div class="numbers"><?=number_format($revenue,0,",",".")?>đ</div>
            <div class="cardName">Revenue</div>
        </div>

        <div class="iconBx">
            <ion-icon name="cash-outline"></ion-icon>
        </div>
    </div>
</div>

<!-- ================ Charts ================= -->

<?php require_once 'chart.php'; ?>

<!-- ================ Order Details List ================= -->
<div class="details">
    <div class="recent">
        <div class="cardHeader">
            <h4>Product Best Selling</h4>
        </div>
        <table id="limit-table" class="mb-2">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Image</td>
                    <td>Price</td>
                    <td>Quantity in bill</td>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ($product_top_5 as $pr) {
                        extract($pr);
                    ?>
                <tr>
                    <td><?=$product_name?></td>
                    <td>
                        <div class="img">
                            <img src="<?=_WEB_ROOT?>/public/uploads/products/<?=$product_img?>" alt="">
                        </div>
                    </td>
                    <td><?=number_format($product_price,0,",",".")?> đ</td>
                    <td><?=$total_sold?></td>
                </tr>

                <?php
                            }
                    ?>
            </tbody>
        </table>
        <table class="all-table">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Image</td>
                    <td>Price</td>
                    <td>Quantity in bill</td>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ($product_top as $pr) {
                        extract($pr);
                    ?>
                <tr>
                    <td><?=$product_name?></td>
                    <td>
                        <div class="img">
                            <img src="<?=_WEB_ROOT?>/public/uploads/products/<?=$product_img?>" alt="">
                        </div>
                    </td>
                    <td><?=number_format($product_price,0,",",".")?> đ</td>
                    <td><?=$total_sold?></td>
                </tr>

                <?php
                            }
                    ?>
            </tbody>
        </table>
        <button id="onViewAll-table" class="btn btn-danger">View All</button>

    </div>


    <div class="recent">
        <div class="cardHeader">
            <h4>Product in stock</h4>
        </div>
        <table id="limit" class="mb-2">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Image</td>
                    <td>Price</td>
                    <td>Quantity in stock</td>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ($product_nonSell_5 as $pr) {
                        extract($pr);
                    ?>
                <tr>
                    <td><?=$product_name?></td>
                    <td>
                        <div class="img">
                            <img src="<?=_WEB_ROOT?>/public/uploads/products/<?=$product_img?>" alt="">
                        </div>
                    </td>
                    <td><?=number_format($product_price,0,",",".")?> đ</td>
                    <td><?=$product_count?></td>
                </tr>

                <?php
                            }
                    ?>
            </tbody>
        </table>
        <table class="all">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Image</td>
                    <td>Price</td>
                    <td>Quantity in stock</td>
                </tr>
            </thead>

            <tbody>
                <?php
                    foreach ($product_nonSell as $pr) {
                        extract($pr);
                    ?>
                <tr>
                    <td><?=$product_name?></td>
                    <td>
                        <div class="img">
                            <img src="<?=_WEB_ROOT?>/public/uploads/products/<?=$product_img?>" alt="">
                        </div>
                    </td>
                    <td><?=number_format($product_price,0,",",".")?> đ</td>
                    <td><?=$product_count?></td>
                </tr>

                <?php
                            }
                    ?>
            </tbody>
        </table>
        <button id="onViewAll" class="btn btn-danger">View All</button>
    </div>
</div>