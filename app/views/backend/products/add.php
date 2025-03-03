<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0 font-weight-bold"><?=$title?></h2>
    </div>
    
    <!-- Messages section -->
    <div class="messages mb-4">
        <?php if(isset($_SESSION['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-check-circle me-2"></i><?= $_SESSION['msg'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['msg1'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['msg1'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['msg1']); ?>
        <?php endif; ?>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="<?=_WEB_ROOT?>/store-product" method="POST" enctype="multipart/form-data">
                <div class="row gap-3">
                <div class="col-5 mb-3">
                        <label for="product_img" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="product_img" name="product_img" accept="image/*" >
                        <div class="form-text">Recommended size: 800x800 pixels</div>
                    </div>

                    <!-- Preview Image -->
                    <div class="col-5 mb-3">
                        <div id="imagePreview" class="mt-2" style="max-width: 200px;">
                            <img id="preview_imgProduct" src="#" alt="Preview" style="max-width: 100%; display: none;">
                        </div>
                    </div>
                    <!-- Product Name -->
                    <div class="col-md-5 mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" >
                    </div>

                    <!-- Product Price -->
                    <div class="col-md-5 mb-3">
                        <label for="product_price" class="form-label">Price (VND)</label>
                        <input type="number" 
                               class="form-control" 
                               id="product_price" 
                               name="product_price" 
                               min="0" 
                               max="999999999" 
                               value="0"
                               oninput="this.value = this.value > 999999999 ? 999999999 : Math.max(0, this.value)">
                    </div>

                    <!-- Product Discount -->
                    <div class="col-md-5 mb-3">
                        <label for="product_discount" class="form-label">Discount (%)</label>
                        <input type="number" 
                               class="form-control" 
                               id="product_discount" 
                               name="product_discount" 
                               min="0" 
                               max="100" 
                               value="0"
                               oninput="this.value = this.value > 100 ? 100 : Math.max(0, this.value)">
                    </div>

                    <!-- Product Stock -->
                    <div class="col-md-5 mb-3">
                        <label for="product_count" class="form-label">Stock Quantity</label>
                        <input type="number" 
                               class="form-control" 
                               id="product_count" 
                               name="product_count" 
                               min="0" 
                               max="999999" 
                               value="0"
                               oninput="this.value = this.value > 999999 ? 999999 : Math.max(0, this.value)">
                    </div>

                    <!-- Product Category -->
                    <div class="col-md-5 mb-3">
                        <label for="product_cat" class="form-label">Category</label>
                        <select class="form-select" id="product_cat" name="product_cat" >
                            <option value="">Select Category</option>
                            <?php foreach($category_list as $category):
                                extract($category); ?>
                                <option value="<?= $category_id ?>">
                                    <?= $category_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Product Status -->
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Status</label>
                        <div class="group-control">
                        <input type="checkbox" class="form-check-input" id="product_status" name="product_status">
                        <label for="product_status">Active</label>
                        </div>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label for="screen_cam" class="form-label">Screen/Camera</label>
                        <input type="text" class="form-control" id="screen_cam" name="screen_cam">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="os" class="form-label">Operating System</label>
                        <input type="text" class="form-control" id="os" name="os">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="gpu" class="form-label">GPU</label>
                        <input type="text" class="form-control" id="gpu" name="gpu">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="cpu" class="form-label">CPU</label>
                        <input type="text" class="form-control" id="cpu" name="cpu">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="pin" class="form-label">Battery</label>
                        <input type="text" class="form-control" id="pin" name="pin">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="colors" class="form-label">Colors</label>
                        <input type="text" class="form-control" id="colors" name="colors">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="sizes" class="form-label">Sizes</label>
                        <input type="text" class="form-control" id="sizes" name="sizes">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="ram" class="form-label">RAM</label>
                        <input type="text" class="form-control" id="ram" name="ram">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="rom" class="form-label">ROM/Storage</label>
                        <input type="text" class="form-control" id="rom" name="rom">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="bluetooth" class="form-label">Bluetooth</label>
                        <input type="text" class="form-control" id="bluetooth" name="bluetooth">
                    </div>  
                </div>
                <!-- Product Details -->
                
                <!-- Form Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Product
                    </button>
                    <a href="<?=_WEB_ROOT?>/product" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
<script>
document.getElementById('product_img').onchange = function(evt) {
    const preview = document.getElementById('preview_imgProduct');
    preview.style.display = 'block';
    const [file] = this.files;
    
    if (file) {
        preview.src = URL.createObjectURL(file);
    }
};
</script>
