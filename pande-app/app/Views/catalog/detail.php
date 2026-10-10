<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>/shop" class="text-decoration-none">All Tech</a></li>
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>/category/<?= e($product['category_slug']) ?>" class="text-decoration-none"><?= e($product['category_name']) ?></a></li>
            <li class="breadcrumb-item active text-truncate" style="max-width: 280px;" aria-current="page"><?= e($product['name']) ?></li>
        </ol>
    </nav>

    <!-- Main Product Card -->
    <div class="card border border-pc rounded-3 p-4 bg-white mb-4">
        <div class="row g-4 g-lg-5">
            <!-- Product Gallery Column -->
            <div class="col-lg-5">
                <div class="border rounded-3 p-4 text-center bg-white position-relative d-flex align-items-center justify-content-center" style="min-height: 360px;">
                    <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                        <?php $discount = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100); ?>
                        <span class="product-discount-badge fs-6 px-3 py-1"><?= $discount ?>% OFF</span>
                    <?php endif; ?>
                    <span class="product-brand-tag fs-6 px-3 py-1"><?= e($product['brand']) ?></span>

                    <img src="<?= asset($product['primary_image'] ?? 'assets/images/products/laptop.svg') ?>" 
                         alt="<?= e($product['name']) ?>" 
                         class="img-fluid main-detail-img"
                         style="max-height: 300px; max-width: 90%; object-fit: contain;"
                         onerror="this.src='<?= asset('assets/images/products/laptop.svg') ?>'">
                </div>
                <div class="d-flex align-items-center justify-content-center gap-2 mt-3 text-muted small">
                    <i class="bi bi-shield-check text-success"></i> Genuine device verified at Kariakoo showroom.
                </div>
            </div>

            <!-- Product Details Column -->
            <div class="col-lg-7 d-flex flex-column">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-light text-primary border"><?= e($product['brand']) ?></span>
                    <span class="text-muted small">SKU: <strong class="text-dark font-monospace"><?= e($product['sku']) ?></strong></span>
                    <?php if (!empty($product['model_number'])): ?>
                        <span class="text-muted small">&bull; Model: <strong class="text-dark"><?= e($product['model_number']) ?></strong></span>
                    <?php endif; ?>
                </div>

                <h1 class="h3 fw-bold text-dark mb-3"><?= e($product['name']) ?></h1>

                <!-- Price Box (eBay style) -->
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="d-flex align-items-baseline gap-2 mb-1">
                        <span class="fs-2 fw-black text-dark"><?= formatTZS($product['price']) ?></span>
                        <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                            <span class="fs-5 text-muted text-decoration-line-through"><?= formatTZS($product['compare_at_price']) ?></span>
                            <?php $savings = (float)$product['compare_at_price'] - (float)$product['price']; ?>
                            <span class="badge bg-success small">Save <?= formatTZS($savings) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="small text-muted">
                        Inclusive of all local retail fees. Tanzanian Shillings (TZS).
                    </div>
                </div>

                <!-- Stock & Warranty Status -->
                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <div class="p-2 border rounded small d-flex align-items-center gap-2">
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <i class="bi bi-check-circle-fill text-pc-green fs-5"></i>
                                <div>
                                    <div class="fw-bold text-pc-green">In Stock (<?= $product['stock_quantity'] ?> units available)</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Kariakoo store pickup or fast delivery</div>
                                </div>
                            <?php else: ?>
                                <i class="bi bi-x-circle-fill text-pc-red fs-5"></i>
                                <div>
                                    <div class="fw-bold text-pc-red">Out of Stock</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Message on WhatsApp for backorder</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-2 border rounded small d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-primary fs-5"></i>
                            <div>
                                <div class="fw-bold text-dark"><?= e($product['warranty_info']) ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;">Official retailer warranty included</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Description -->
                <p class="text-secondary mb-4 small leading-relaxed"><?= nl2br(e($product['short_description'])) ?></p>

                <!-- Purchase Controls -->
                <div class="mt-auto pt-3 border-top">
                    <form action="<?= APP_URL ?>/cart/add" method="POST" class="mb-3 add-to-cart-form">
                        <?= $csrfInput ?>
                        <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                        <div class="row g-3 align-items-center">
                            <div class="col-4 col-sm-3">
                                <label class="form-label small fw-bold mb-1">Quantity:</label>
                                <input type="number" name="quantity" class="form-control text-center fw-bold" value="1" min="1" max="<?= max(1, (int)$product['stock_quantity']) ?>" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>>
                            </div>
                            <div class="col-8 col-sm-9">
                                <label class="form-label small fw-bold mb-1 invisible">Action</label>
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>>
                                    <i class="bi bi-cart-plus-fill me-2"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Direct WhatsApp Buy Button -->
                    <?php
                    $waMsg = "Hello Pande Computers, I want to order this product directly:\n" .
                             "Product: {$product['name']}\n" .
                             "SKU: {$product['sku']}\n" .
                             "Price: " . formatTZS($product['price']) . "\n" .
                             "Please confirm availability and arrange payment/delivery.";
                    ?>
                    <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>?text=<?= rawurlencode($waMsg) ?>" 
                       target="_blank" rel="noopener" 
                       class="btn btn-whatsapp btn-lg w-100 fw-bold shadow">
                        <i class="bi bi-whatsapp me-2"></i> Buy Now via WhatsApp (+255 787 311 342)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Specifications & Description -->
    <div class="card border border-pc rounded-3 mb-5 bg-white">
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs border-0" id="detailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold px-4 py-3 border-0 border-bottom border-primary border-3" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">Overview & Details</button>
                </li>
                <?php if (!empty($product['specs_array'])): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold px-4 py-3 border-0" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs-pane" type="button" role="tab">Specifications Sheet</button>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content" id="detailTabsContent">
                <div class="tab-pane fade show active leading-relaxed" id="desc-pane" role="tabpanel">
                    <?= nl2br(e($product['description'])) ?>
                </div>

                <?php if (!empty($product['specs_array'])): ?>
                    <div class="tab-pane fade" id="specs-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0 small">
                                <tbody>
                                    <?php foreach ($product['specs_array'] as $key => $val): ?>
                                        <tr>
                                            <th class="bg-light text-secondary" style="width: 30%;"><?= e($key) ?></th>
                                            <td class="fw-semibold text-dark"><?= e((string)$val) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-3">Similar Products in <?= e($product['category_name']) ?></h4>
            <div class="row row-cols-2 row-cols-md-4 g-3">
                <?php foreach ($relatedProducts as $rel): ?>
                    <div class="col">
                        <div class="ebay-product-card">
                            <span class="product-brand-tag"><?= e($rel['brand']) ?></span>
                            <a href="<?= APP_URL ?>/product/<?= e($rel['slug']) ?>" class="card-image-box text-decoration-none">
                                <img src="<?= asset($rel['primary_image'] ?? 'assets/images/products/laptop.svg') ?>" 
                                     alt="<?= e($rel['name']) ?>" 
                                     loading="lazy" 
                                     class="product-thumb-img"
                                     onerror="this.src='<?= asset('assets/images/products/laptop.svg') ?>'">
                            </a>
                            <div class="card-content-box">
                                <a href="<?= APP_URL ?>/product/<?= e($rel['slug']) ?>" class="card-product-title">
                                    <?= e($rel['name']) ?>
                                </a>
                                <div class="mt-auto pt-2">
                                    <div class="card-price-primary font-tabular mb-2"><?= formatTZS($rel['price']) ?></div>
                                    <a href="<?= APP_URL ?>/product/<?= e($rel['slug']) ?>" class="btn btn-outline-primary btn-sm w-100 fw-bold">View Item</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
