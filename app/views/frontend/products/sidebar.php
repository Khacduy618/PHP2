<aside class="col-lg-3 order-lg-first">
    <div class="sidebar sidebar-shop">
        <div class="widget widget-clean">
            <label>Filters:</label>
            <a href="#" class="sidebar-filter-clear">Clean All</a>
        </div><!-- End .widget widget-clean -->

        <div class="widget widget-collapsible">
            <h3 class="widget-title">
                <a data-toggle="collapse" href="#widget-1" role="button" aria-expanded="true" aria-controls="widget-1">
                    Category
                </a>
            </h3><!-- End .widget-title -->

            <div class="collapse show" id="widget-1">
                <div class="widget-body">
                    <div class="filter-items filter-items-count">
                        <?php
                           $category = new \App\Controllers\Category();
                            echo $category->list_cat();
                        ?>
                    </div><!-- End .filter-items -->
                </div><!-- End .widget-body -->
            </div><!-- End .collapse -->
        </div><!-- End .widget -->

        
    </div><!-- End .sidebar sidebar-shop -->
</aside><!-- End .col-lg-3 -->