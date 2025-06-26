<div class="main-container">
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="product-card mb-4">
                        <img src="<?= base_url('images/' . $product['image']) ?>" class="product-image" alt="<?= esc($product['name']) ?>">
                        <div class="product-name"><?= esc($product['name']) ?></div>
                        <div class="product-price">
                            ₹<?= number_format($product['price'], 0) ?>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-outline-primary w-50">
                                <i class="bi bi-cart-plus me-1"></i> <span>Add to Cart</span>
                            </button>
                            <button class="btn btn-primary w-50" style="background-color: #9c27b0; border-color: #9c27b0;">
                                <i class="bi bi-lightning-fill me-1"></i><span>Buy Now</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
</div>


 