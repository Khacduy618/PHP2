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
            <?php
            if (isset($item) && is_array($item)) {
                extract($item);
            ?>
            <form action="<?=_WEB_ROOT?>/update-product" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?=$product_id?>">
                <div class="row gap-3">
                    <!-- Product Image -->
                    <div class="col-5 mb-3">
                        <label for="product_img" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="product_img" name="product_img" accept="image/*">
                        <div class="form-text">Recommended size: 800x800 pixels</div>
                        
                    </div>
                    <div class="col-5 mb-3">
                    <div class="mt-2">
                            <img src="<?php echo _WEB_ROOT ?>/public/uploads/products/<?=$product_img?>" class="img-thumbnail" style="max-height: 150px;" id="preview_imgProduct">
                        </div>
                    </div>
                    <!-- Product Name -->
                    <div class="col-md-5 mb-3">
                        <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="<?=$product_name?>" required>
                    </div>

                    <!-- Product Price -->
                    <div class="col-md-5 mb-3">
                        <label for="product_price" class="form-label">Price (VND) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="product_price" name="product_price" value="<?=$product_price?>"  min="0" 
                               max="999999999" 
                               oninput="this.value = this.value > 999999999 ? 999999999 : Math.max(0, this.value)">
                    </div>

                    <!-- Product Discount -->
                    <div class="col-md-5 mb-3">
                        <label for="product_discount" class="form-label">Discount (%)</label>
                        <input type="number" class="form-control" id="product_discount" name="product_discount" min="0" 
                               max="100" 
                               oninput="this.value = this.value > 100 ? 100 : Math.max(0, this.value)" value="<?=$product_discount?>">
                    </div>

                    <!-- Product Stock -->
                    <div class="col-md-5 mb-3">
                        <label for="product_count" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="product_count" name="product_count" min="0" 
                               max="999999" 
                               oninput="this.value = this.value > 999999 ? 999999 : Math.max(0, this.value)" value="<?=$product_count?>">
                    </div>

                    <!-- Product Category -->
                    <div class="col-md-5 mb-3">
                        <label for="product_cat" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="product_cat" name="product_cat" required>
                            <?php foreach($category_list as $category): ?>
                                <option value="<?= $category['category_id'] ?>" <?php echo ($product_cat == $category['category_id']) ? 'selected' : ''; ?>>
                                    <?= $category['category_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Product Status -->
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Status</label>
                        <input type="checkbox" class="form-check-input" value="1" name="product_status" id="product_status" <?php echo ($product_status == 1) ? 'checked' : ''; ?>>
                        <label for="product_status">Active</label>
                    </div>

                    
                    <div class="col-md-5 mb-3">
                        <label for="screen_cam" class="form-label">Screen/Camera</label>
                        <input type="text" class="form-control" id="screen_cam" name="screen_cam" value="<?=$screen_cam?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="os" class="form-label">Operating System</label>
                        <input type="text" class="form-control" id="os" name="os" value="<?=$os?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="gpu" class="form-label">GPU</label>
                        <input type="text" class="form-control" id="gpu" name="gpu" value="<?=$gpu?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="cpu" class="form-label">CPU</label>
                        <input type="text" class="form-control" id="cpu" name="cpu" value="<?=$cpu?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="pin" class="form-label">Battery</label>
                        <input type="text" class="form-control" id="pin" name="pin" value="<?=$pin?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="colors" class="form-label">Colors</label>
                        <input type="text" class="form-control" id="colors" name="colors" value="<?=$colors?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="sizes" class="form-label">Sizes</label>
                        <input type="text" class="form-control" id="sizes" name="sizes" value="<?=$sizes?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="ram" class="form-label">RAM</label>
                        <input type="text" class="form-control" id="ram" name="ram" value="<?=$ram?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="rom" class="form-label">ROM/Storage</label>
                        <input type="text" class="form-control" id="rom" name="rom" value="<?=$rom?>">
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="bluetooth" class="form-label">Bluetooth</label>
                        <input type="text" class="form-control" id="bluetooth" name="bluetooth" value="<?=$bluetooth?>">
                    </div>

                </div>

                <!-- Form Buttons -->
                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Product
                        </button>
                        <a href="<?=_WEB_ROOT?>/product" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </form>
            <?php
            } else {
                echo '<p class="text-danger fw-bold">Product not found!</p>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
<script>
document.getElementById('product_img').onchange = function(evt) {
    const preview = document.getElementById('preview_imgProduct');
    const [file] = this.files;
    
    if (file) {
        preview.src = URL.createObjectURL(file);
    }
};
</script>
