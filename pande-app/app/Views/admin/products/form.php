<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><?= $product ? 'Edit Product' : 'Add New Product' ?></h2>
            <p class="text-muted small mb-0">Fill in technical specs, inventory numbers, and pricing in TZS</p>
        </div>
        <a href="<?= APP_URL ?>/admin/products" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= $csrfInput ?>

        <div class="row g-4">
            <!-- Left Column: Details -->
            <div class="col-lg-8">
                <!-- Basic Info Card -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Product Identification</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="<?= e($product['name'] ?? '') ?>" placeholder="e.g. HP EliteBook 840 G8 Core i7 16GB 512GB SSD">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Brand / Manufacturer <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control" required value="<?= e($product['brand'] ?? '') ?>" placeholder="e.g. HP, Dell, Lenovo, Apple">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Model Number</label>
                            <input type="text" name="model_number" class="form-control" value="<?= e($product['model_number'] ?? '') ?>" placeholder="e.g. 840 G8 or SE2422H">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (!empty($product['category_id']) && (int)$product['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
                                        <?= e($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">SKU / Product Code <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control font-monospace" required value="<?= e($product['sku'] ?? '') ?>" placeholder="e.g. LAP-HP-840G8">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Short Summary Description <span class="text-danger">*</span></label>
                        <textarea name="short_description" rows="2" class="form-control" required placeholder="Brief bullet highlights shown on catalog cards..."><?= e($product['short_description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Full Product Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="6" class="form-control" required placeholder="Detailed specifications, features, in-the-box contents..."><?= e($product['description'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Product Image Upload Card -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Product Media & Photography</h5>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Upload Product Image (PNG, JPG, WEBP &bull; Max 5MB)</label>
                        <input type="file" name="product_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                    </div>

                    <?php if (!empty($images)): ?>
                        <div class="d-flex gap-3 mt-3">
                            <?php foreach ($images as $img): ?>
                                <div class="position-relative border p-1 rounded bg-light" style="width: 100px; height: 100px;">
                                    <img src="<?= asset($img['image_path']) ?>" alt="Product" class="img-fluid w-100 h-100 object-fit-contain">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Pricing, Inventory, Status -->
            <div class="col-lg-4">
                <!-- Pricing Card -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Pricing (TZS)</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Selling Price (TZS) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="price" class="form-control form-control-lg fw-bold text-primary" required value="<?= e($product['price'] ?? '') ?>" placeholder="1850000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Comparison / Original Price (TZS)</label>
                        <input type="number" step="0.01" name="compare_at_price" class="form-control" value="<?= e($product['compare_at_price'] ?? '') ?>" placeholder="2100000">
                        <div class="form-text">Shown crossed-out if higher than selling price.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-danger">Cost Price (Admin Only)</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" value="<?= e($product['cost_price'] ?? '') ?>" placeholder="1550000">
                        <div class="form-text">Kept strictly confidential for profit margins.</div>
                    </div>
                </div>

                <!-- Stock & Inventory Card -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Inventory Settings</h5>

                    <?php if (!$product): ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Initial Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" class="form-control" required value="10" min="0">
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Current Quantity in Stock</label>
                            <div class="fs-4 fw-black text-dark"><?= (int)$product['stock_quantity'] ?> units</div>
                            <div class="small text-muted mt-1">To adjust stock, use the <a href="<?= APP_URL ?>/admin/inventory">Inventory Movements</a> module.</div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Low Stock Alert Threshold</label>
                        <input type="number" name="low_stock_threshold" class="form-control" value="<?= e($product['low_stock_threshold'] ?? 5) ?>" min="1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Warranty Terms</label>
                        <input type="text" name="warranty_info" class="form-control" value="<?= e($product['warranty_info'] ?? '1 Year Official Warranty') ?>">
                    </div>
                </div>

                <!-- Flags & Visibility -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Publishing Status</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Visibility Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= ($product['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active (Visible in Store)</option>
                            <option value="draft" <?= ($product['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft (Hidden)</option>
                            <option value="inactive" <?= ($product['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive (Archived)</option>
                        </select>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" <?= !empty($product['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="is_featured">Featured on Homepage</label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1" id="is_new_arrival" <?= !empty($product['is_new_arrival']) ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="is_new_arrival">Highlight as New Arrival</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Save Product
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
