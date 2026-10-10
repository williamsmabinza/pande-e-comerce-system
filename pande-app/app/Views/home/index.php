<!-- eBay-Style Promotional Hero Section -->
<section class="py-4">
    <div class="container">
        <div class="promo-hero-banner p-4 p-md-5">
            <div class="row align-items-center gy-4">
                <div class="col-lg-7">
                    <span class="promo-hero-badge mb-3">
                        <i class="bi bi-patch-check-fill text-warning me-1"></i> Official Retailer &bull; Kariakoo, Dar es Salaam
                    </span>
                    <h1 class="display-4 fw-black text-white mb-3 tracking-tight">
                        Everything Tech.<br>
                        <span class="text-warning">All in One Place.</span>
                    </h1>
                    <p class="lead text-white-50 mb-4 pe-lg-3">
                        Shop computers, printers, mobile accessories, gaming equipment, TVs, and electrical products at Pande Computers. Uncompromised Power. Unbeatable Prices.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?= APP_URL ?>/shop" class="btn btn-warning btn-lg px-4 py-3 fw-black text-dark shadow-sm">
                            <i class="bi bi-bag-check-fill me-2"></i> START SHOPPING &rarr;
                        </a>
                        <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>?text=<?= rawurlencode("Hello Pande Computers, I am interested in exploring your current technology inventory in Kariakoo.") ?>" 
                           target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg px-4 py-3 fw-bold shadow-sm">
                            <i class="bi bi-whatsapp me-2"></i> ORDER VIA WHATSAPP
                        </a>
                    </div>
                </div>

                <!-- Showcase Offer Card -->
                <div class="col-lg-5">
                    <div class="bg-white rounded-3 p-4 text-dark shadow-lg border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-pc-red text-white fw-bold px-2 py-1">SPOTLIGHT DEAL</span>
                            <span class="small text-muted font-monospace">HP 840 G8</span>
                        </div>
                        <div class="text-center py-2" style="min-height: 150px; display: flex; align-items: center; justify-content: center;">
                            <img src="<?= asset('assets/images/products/laptop.svg') ?>" alt="HP EliteBook 840 G8" class="img-fluid" style="max-height: 140px; object-fit: contain;">
                        </div>
                        <h5 class="fw-bold text-dark mb-1">HP EliteBook 840 G8 Ultrabook</h5>
                        <div class="small text-muted mb-2">Intel Core i7 &bull; 16GB RAM &bull; 512GB SSD &bull; Windows 11</div>
                        <div class="d-flex align-items-baseline gap-2 mb-3">
                            <span class="fs-4 fw-black text-dark font-tabular">TZS 1,850,000</span>
                            <span class="small text-muted text-decoration-line-through font-tabular">TZS 2,100,000</span>
                            <span class="badge bg-danger-subtle text-danger small">-12%</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-7">
                                <form action="<?= APP_URL ?>/cart/add" method="POST" class="add-to-cart-form">
                                    <?= $csrfInput ?>
                                    <input type="hidden" name="product_id" value="1">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary fw-bold btn-sm py-2 w-100">
                                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                            <div class="col-5">
                                <a href="<?= APP_URL ?>/product/hp-elitebook-840-g8-core-i7-16gb-512gb-ssd" class="btn btn-outline-dark fw-bold btn-sm py-2 w-100">
                                    Details &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Pills -->
            <div class="row g-2 mt-4 pt-3 border-top border-white border-opacity-10 text-white small">
                <div class="col-md-4 col-sm-6">
                    <div class="hero-feature-pill d-flex align-items-center gap-2">
                        <i class="bi bi-shop text-warning fs-5"></i>
                        <div>
                            <strong class="d-block text-white">Kariakoo Walk-In Showroom</strong>
                            <span class="text-white-50">Ndanda & Aggrey Street, DSM</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="hero-feature-pill d-flex align-items-center gap-2">
                        <i class="bi bi-truck text-info fs-5"></i>
                        <div>
                            <strong class="d-block text-white">Same-Day DSM & Upcountry</strong>
                            <span class="text-white-50">Free DSM delivery over TZS 600k</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="hero-feature-pill d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-success fs-5"></i>
                        <div>
                            <strong class="d-block text-white">100% Genuine Tech Guarantee</strong>
                            <span class="text-white-50">Full manufacturer warranty</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shop by Category (eBay-Style Category Tiles) -->
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h4 fw-bold text-dark mb-0">Shop by Category</h3>
            <a href="<?= APP_URL ?>/shop" class="small fw-bold text-primary text-decoration-none">
                See all categories <i class="bi bi-chevron-right small"></i>
            </a>
        </div>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
            <?php foreach (array_slice($allCategories, 0, 12) as $cat): ?>
                <div class="col">
                    <a href="<?= APP_URL ?>/category/<?= e($cat['slug']) ?>" class="category-bubble-card">
                        <div class="category-bubble-icon">
                            <i class="bi <?= e($cat['icon_class'] ?: 'bi-cpu') ?>"></i>
                        </div>
                        <span class="category-bubble-title"><?= e($cat['name']) ?></span>
                        <span class="small text-muted" style="font-size: 0.72rem;"><?= (int)($cat['product_count'] ?? 0) ?> items</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Deals & Products (eBay-Style Product Grid) -->
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="h4 fw-bold text-dark mb-0">Featured Electronics Deals</h3>
                <span class="small text-muted">Genuine manufacturer hardware in Kariakoo</span>
            </div>
            <a href="<?= APP_URL ?>/shop" class="small fw-bold text-primary text-decoration-none">
                Explore all tech <i class="bi bi-chevron-right small"></i>
            </a>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col">
                    <div class="ebay-product-card">
                        <!-- Discount Badge -->
                        <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                            <?php $discount = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100); ?>
                            <span class="product-discount-badge"><?= $discount ?>% OFF</span>
                        <?php endif; ?>

                        <!-- Brand Tag -->
                        <span class="product-brand-tag"><?= e($product['brand']) ?></span>

                        <!-- Thumbnail Box -->
                        <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-image-box text-decoration-none">
                            <img src="<?= asset($product['primary_image'] ?? 'assets/images/products/laptop.svg') ?>" 
                                 alt="<?= e($product['name']) ?>" 
                                 loading="lazy" 
                                 class="product-thumb-img"
                                 onerror="this.src='<?= asset('assets/images/products/laptop.svg') ?>'">
                        </a>

                        <!-- Content Box -->
                        <div class="card-content-box">
                            <div class="small text-muted mb-1 d-flex justify-content-between" style="font-size: 0.72rem;">
                                <span><?= e($product['category_name']) ?></span>
                                <span class="text-secondary">Brand New</span>
                            </div>
                            <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-product-title">
                                <?= e($product['name']) ?>
                            </a>

                            <div class="mt-auto pt-2">
                                <!-- Price -->
                                <div class="d-flex align-items-baseline mb-1">
                                    <span class="card-price-primary font-tabular"><?= formatTZS($product['price']) ?></span>
                                    <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                                        <span class="card-price-old font-tabular"><?= formatTZS($product['compare_at_price']) ?></span>
                                    <?php endif; ?>
                                </div>

                                <!-- Trust & Delivery Hint -->
                                <div class="small text-muted mb-2 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-geo-alt text-danger"></i> Kariakoo Pickup &bull; DSM Delivery
                                </div>

                                <!-- Stock Status -->
                                <div class="mb-3">
                                    <?php if ($product['stock_quantity'] > 5): ?>
                                        <span class="stock-indicator stock-in">
                                            <i class="bi bi-check-circle-fill"></i> In Stock (<?= $product['stock_quantity'] ?> units)
                                        </span>
                                    <?php elseif ($product['stock_quantity'] > 0): ?>
                                        <span class="stock-indicator stock-low">
                                            <i class="bi bi-exclamation-circle-fill"></i> Only <?= $product['stock_quantity'] ?> left in stock
                                        </span>
                                    <?php else: ?>
                                        <span class="stock-indicator text-muted">
                                            <i class="bi bi-x-circle-fill"></i> Out of stock
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row g-2">
                                    <div class="col-8">
                                        <form action="<?= APP_URL ?>/cart/add" method="POST" class="add-to-cart-form">
                                            <?= $csrfInput ?>
                                            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary w-100 btn-sm py-2 fw-bold" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>>
                                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-4">
                                        <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>?text=<?= rawurlencode("Hello Pande Computers, I want to confirm availability for: {$product['name']} (SKU: {$product['sku']}) priced at " . formatTZS($product['price'])) ?>" 
                                           target="_blank" rel="noopener" class="btn btn-whatsapp w-100 btn-sm py-2" title="Order directly on WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Arrivals in Tanzania -->
<section class="py-4 bg-white border-top border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="h4 fw-bold text-dark mb-0">Latest New Arrivals</h3>
                <span class="small text-muted">Fresh stock just unpacked at Kariakoo</span>
            </div>
            <a href="<?= APP_URL ?>/shop?sort=newest" class="small fw-bold text-primary text-decoration-none">
                View all arrivals <i class="bi bi-chevron-right small"></i>
            </a>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
            <?php foreach ($newArrivals as $product): ?>
                <div class="col">
                    <div class="ebay-product-card">
                        <span class="product-brand-tag"><?= e($product['brand']) ?></span>
                        <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-image-box text-decoration-none">
                            <img src="<?= asset($product['primary_image'] ?? 'assets/images/products/desktop.svg') ?>" 
                                 alt="<?= e($product['name']) ?>" 
                                 loading="lazy" 
                                 class="product-thumb-img"
                                 onerror="this.src='<?= asset('assets/images/products/desktop.svg') ?>'">
                        </a>
                        <div class="card-content-box">
                            <div class="small text-muted mb-1 d-flex justify-content-between" style="font-size: 0.72rem;">
                                <span><?= e($product['category_name']) ?></span>
                                <span class="text-secondary">Brand New</span>
                            </div>
                            <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-product-title">
                                <?= e($product['name']) ?>
                            </a>
                            <div class="mt-auto pt-2">
                                <div class="card-price-primary font-tabular mb-1"><?= formatTZS($product['price']) ?></div>
                                <div class="small text-muted mb-2 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-geo-alt text-danger"></i> Kariakoo Stock &bull; Free Warranty
                                </div>
                                <div class="row g-2">
                                    <div class="col-8">
                                        <form action="<?= APP_URL ?>/cart/add" method="POST" class="add-to-cart-form">
                                            <?= $csrfInput ?>
                                            <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-outline-primary w-100 btn-sm fw-bold" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>>
                                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-4">
                                        <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>?text=<?= rawurlencode("Hello Pande Computers, I am interested in ordering: {$product['name']} (SKU: {$product['sku']}) for " . formatTZS($product['price'])) ?>" 
                                           target="_blank" rel="noopener" class="btn btn-whatsapp w-100 btn-sm" title="Order via WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Trust & Marketplace Assurance -->
<section class="py-5 bg-ebay-gray">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <i class="bi bi-shield-lock-fill text-primary fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark mb-1">Authentic Guarantee</h6>
                    <p class="small text-muted mb-0">100% genuine equipment backed by official warranty terms.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <i class="bi bi-shop text-warning fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark mb-1">Kariakoo Showroom</h6>
                    <p class="small text-muted mb-0">Visit our physical store to inspect and test computers before buying.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <i class="bi bi-truck text-info fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark mb-1">Nationwide Delivery</h6>
                    <p class="small text-muted mb-0">Dispatched across Dar es Salaam, Arusha, Mwanza, Dodoma & beyond.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <i class="bi bi-whatsapp text-success fs-1 mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark mb-1">WhatsApp Checkout</h6>
                    <p class="small text-muted mb-0">Direct customer service and instant verification via WhatsApp.</p>
                </div>
            </div>
        </div>
    </div>
</section>
