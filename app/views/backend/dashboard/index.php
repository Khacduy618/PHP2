<h1 class="text-uppercase card-body">Admin Dashboard</h1>

<div class="cardBox">
    <div class="card">
        <div>
            <div class="numbers"></div>
            <div class="cardName">Users</div>
        </div>

        <div class="iconBx">
            <ion-icon name="eye-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>
            <div class="numbers"></div>
            <div class="cardName">Bills</div>
        </div>

        <div class="iconBx">
            <ion-icon name="cart-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>
            <div class="numbers"></div>
            <div class="cardName">Blogs</div>
        </div>

        <div class="iconBx">
            <ion-icon name="book-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>

            <div class="numbers">đ</div>
            <div class="cardName">Revenue</div>
        </div>

        <div class="iconBx">
            <ion-icon name="cash-outline"></ion-icon>
        </div>
    </div>
</div>

<!-- ================ Charts ================= -->

<?php $this->render('dashboard/chart'); ?>

<!-- ================ Order Details List ================= -->
<div class="details">
    <div class="recent">
        <div class="cardHeader">
            <h4>Product Best Selling</h4>
        </div>
        <table id="limit-table">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Image</td>
                    <td>Price</td>
                    <td>Quantity in bill</td>
                </tr>
            </thead>

            <tbody>
                
                <tr>
                    <td></td>
                    <td>
                        <div class="img">
                            <img src="../uploaded/" alt="">
                        </div>
                    </td>
                    <td> đ</td>
                    <td></td>
                </tr>

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
               
                <tr>
                    <td></td>
                    <td>
                        <div class="img">
                            <img src="../uploaded/" alt="">
                        </div>
                    </td>
                    <td>đ</td>
                    <td></td>
                </tr>

                
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
               
                <tr>
                    <td></td>
                    <td>
                        <div class="img">
                            <img src="../uploaded/" alt="">
                        </div>
                    </td>
                    <td> đ</td>
                    <td></td>
                </tr>

                
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
                
                <tr>
                    <td></td>
                    <td>
                        <div class="img">
                            <img src="../uploaded/" alt="">
                        </div>
                    </td>
                    <td>đ</td>
                    <td></td>
                </tr>

               
            </tbody>
        </table>
        <button id="onViewAll" class="btn btn-danger">View All</button>
    </div>
</div>