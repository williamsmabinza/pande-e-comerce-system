<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-navy text-white px-2 py-1"><i class="bi bi-pencil-square me-1"></i> Catalog Editor</span>
                <?php if ($product): ?>
                    <span class="badge bg-light text-dark border font-monospace">SKU: <?= e($product['sku']) ?></span>
                <?php endif; ?>
            </div>
            <h2 class="fw-black text-dark mb-0"><?= $product ? 'Edit Hardware Product' : 'Add New Hardware Product' ?></h2>
            <p class="text-muted small mb-0">Configure specifications, SKU, pricing in TZS, cost margins, stock levels, and media</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= APP_URL ?>/admin/products" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Back to Products
            </a>
            <?php if ($product): ?>
                <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" target="_blank" class="btn btn-outline-primary btn-sm fw-semibold">
                    <i class="bi bi-box-arrow-up-right me-1"></i> View Live
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($product && !empty($orderCount)): ?>
        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-receipt fs-3 text-info"></i>
            <div>
                <strong>Active Order History:</strong> This product is referenced in <strong><?= $orderCount ?></strong> customer order(s).
                Price or name updates will apply to future sales, while previous invoices remain historically intact.
            </div>
        </div>
    <?php endif; ?>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= $csrfInput ?>
        <div class="row g-4">
            <!-- Left Column: Details -->
            <div class="col-lg-8">
                <!-- Identification & Descriptions -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-card-text text-primary me-2"></i> Product Identification & Specs</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Product Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="<?= e($product['name'] ?? '') ?>" placeholder="e.g. HP EliteBook 840 G8 Core i7 16GB 512GB SSD">
                        <div class="form-text small">Display title shown on product cards, cart, and search results.</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Brand / Manufacturer <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control" required value="<?= e($product['brand'] ?? '') ?>" placeholder="e.g. HP, Dell, Lenovo, Apple, Canon, Epson">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Model Number / Series</label>
                            <input type="text" name="model_number" class="form-control" value="<?= e($product['model_number'] ?? '') ?>" placeholder="e.g. 840 G8, XPS 15 9520, L3250">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Department / Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category...</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (!empty($product['category_id']) && (int)$product['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
                                        <?= e($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">SKU / Unique Product Code <span class="text-danger">*</span></label>
                            <input type="text" name="sku" class="form-control font-monospace" required value="<?= e($product['sku'] ?? '') ?>" placeholder="e.g. LAP-HP-840G8">
                            <div class="form-text small">Must be unique across the entire catalog.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Short Summary Highlights <span class="text-danger">*</span></label>
                        <textarea name="short_description" rows="2" class="form-control" required placeholder="Quick key selling points for storefront catalog cards..."><?= e($product['short_description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Detailed Product Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5" class="form-control" required placeholder="In-depth overview, architecture, durability, and retail package contents..."><?= e($product['description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Technical Specifications (Key: Value or Bullet List)</label>
                        <textarea name="specifications" rows="4" class="form-control font-monospace small" placeholder="Processor: Intel Core i7-1185G7&#10;RAM: 16GB DDR4 3200MHz&#10;Storage: 512GB NVMe PCIe SSD&#10;Display: 14.0-inch FHD IPS Anti-Glare&#10;OS: Windows 11 Pro Genuine"><?= e($product['specifications'] ?? '') ?></textarea>
                        <div class="form-text small">Displayed on product details page in the technical specifications tab.</div>
                    </div>
                </div>

                <!-- Product Media & Photography -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-images text-primary me-2"></i> Product Media & Photography</h5>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Upload Product Image (JPG, PNG, WebP &bull; Max 5MB)</label>
                        <input type="file" name="product_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <div class="form-text small">Select a high-resolution image directly from your local computer.</div>
                    </div>

                    <?php if (!empty($images)): ?>
                        <div class="d-flex flex-wrap gap-3 mt-3">
                            <?php foreach ($images as $img): ?>
                                <div class="position-relative border p-1 rounded bg-light" style="width: 100px; height: 100px;">
                                    <img src="<?= asset($img['image_path']) ?>" alt="Product" class="img-fluid w-100 h-100 object-fit-contain">
                                    <?php if (!empty($img['is_primary'])): ?>
                                        <span class="position-absolute bottom-0 start-0 badge bg-primary m-1" style="font-size: 0.6rem;">Primary</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Pricing, Inventory, Lifecycle Status -->
            <div class="col-lg-4">
                <!-- Pricing Card -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cash-stack text-success me-2"></i> Pricing & Cost Margins</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Selling Price (TZS) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="price" class="form-control form-control-lg fw-bold text-primary" required value="<?= e($product['price'] ?? '') ?>" placeholder="1850000">
                        <div class="form-text small">Final customer retail price in Tanzanian Shillings.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Compare-At / Was Price (TZS)</label>
                        <input type="number" step="0.01" name="compare_at_price" class="form-control" value="<?= e($product['compare_at_price'] ?? '') ?>" placeholder="2100000">
                        <div class="form-text small">Original price shown struck-through for discounts.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-danger">Cost Price (Admin Only, TZS)</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" value="<?= e($product['cost_price'] ?? '') ?>" placeholder="1550000">
                        <div class="form-text small">Confidential wholesale cost used for profit calculations.</div>
                    </div>
                </div>

                <!-- Inventory Settings -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-boxes text-warning me-2"></i> Stock & Inventory Control</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            <?= $product ? 'Available Stock Units' : 'Initial Stock Quantity' ?> <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="stock_quantity" class="form-control fw-bold" required value="<?= (int)($product['stock_quantity'] ?? 10) ?>" min="0">
                        <?php if ($product): ?>
                            <div class="form-text small">Modifying this value automatically logs a stock adjustment in the inventory audit ledger.</div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Low Stock Alert Threshold</label>
                        <input type="number" name="low_stock_threshold" class="form-control" value="<?= e($product['low_stock_threshold'] ?? 5) ?>" min="1">
                        <div class="form-text small">Triggers executive warning alerts when units drop below this number.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Warranty Terms & Condition</label>
                        <input type="text" name="warranty_info" class="form-control" value="<?= e($product['warranty_info'] ?? '1 Year Official Warranty') ?>">
                    </div>
                </div>

                <!-- Lifecycle Status & Promotion -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-toggles text-secondary me-2"></i> Product Lifecycle & Display</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Product Lifecycle State</label>
                        <select name="status" class="form-select fw-semibold">
                            <option value="active" <?= ($product['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>
                                Active (Public & Purchasable)
                            </option>
                            <option value="discontinued" <?= ($product['status'] ?? '') === 'discontinued' ? 'selected' : '' ?>>
                                Discontinued (Hidden, Order History Intact)
                            </option>
                            <option value="archived" <?= ($product['status'] ?? '') === 'archived' ? 'selected' : '' ?>>
                                Archived (Internal Inventory Only)
                            </option>
                            <option value="draft" <?= ($product['status'] ?? '') === 'draft' ? 'selected' : '' ?>>
                                Draft (Unpublished Catalog Item)
                            </option>
                        </select>
                        <div class="form-text small">Discontinued or Archived products are hidden from public shopping without altering past order records.</div>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" <?= !empty($product['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-semibold" for="is_featured">Feature on Storefront Homepage</label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1" id="is_new_arrival" <?= !empty($product['is_new_arrival']) ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-semibold" for="is_new_arrival">Highlight as New Arrival Badge</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> <?= $product ? 'Save Changes' : 'Publish Product to Catalog' ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
