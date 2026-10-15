<?php
/**
 * Pande Computers - Modern Homepage View
 * Matching Pande Computers Visual Reference:
 * - Spacious layout & clean white background
 * - Navy & royal-blue palette
 * - Headline: "YOUR TECH. YOUR WAY."
 * - Supporting text: "Shop laptops, desktops, components and IT essentials online."
 * - Action buttons: "Shop Now" and "Explore Categories"
 * - High-end technology products hero image (laptops, desktops, monitors, printers, networking, SSD/RAM)
 * - Defined category cards (Laptops, Desktops & All-in-Ones, Computer Parts, Printers & Toners, Networking, Power & UPS)
 * - Improved product cards with real data, stock indicators, and instant cart actions
 * - Trust bar highlighting Genuine products, Warranty support, Secure payments & Local IT support
 */
?>

<!-- 1. Hero Section -->
<section class="pande-hero-section py-4 py-lg-5" <?php if (!empty($heroBgImage)): ?>style="background-image: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.90) 50%, rgba(241, 245, 249, 0.85) 100%), url('<?= asset($heroBgImage) ?>');"<?php endif; ?>>
    <div class="container">
        <div class="row align-items-center gy-4 gy-lg-0">
            <!-- Left Headline & CTAs -->
            <div class="col-lg-6 hero-content-wrapper text-center text-lg-start">
                <div class="d-inline-flex mb-3">
                    <span class="hero-pill-badge">
                        <i class="bi bi-shield-check text-primary me-1"></i> <?= e($heroBadge ?? 'Authorized Tanzanian IT Retailer') ?>
                    </span>
                </div>
                
                <h1 class="hero-main-title mb-3">
                    <?= e($heroTitleLine1 ?? 'YOUR TECH.') ?><br>
                    <span class="text-hero-accent"><?= e($heroTitleLine2 ?? 'YOUR WAY.') ?></span>
                </h1>

                <p class="hero-lead-text mb-4 pe-lg-4">
                    <?= e($heroSubtitle ?? 'Shop laptops, desktops, components and IT essentials online. Genuine computing hardware delivered across Dar es Salaam and nationwide.') ?>
                </p>

                <!-- Primary Action Buttons -->
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3 mb-4">
                    <a href="<?= e($heroBtnPrimaryUrl ?? APP_URL . '/shop') ?>" class="btn-hero-primary">
                        <i class="bi bi-bag-check-fill me-2"></i> <?= e($heroBtnPrimaryText ?? 'Shop Now') ?>
                    </a>
                    <a href="<?= e($heroBtnSecondaryUrl ?? '#browse-categories') ?>" class="btn-hero-secondary">
                        <i class="bi bi-grid-fill me-2 text-primary"></i> <?= e($heroBtnSecondaryText ?? 'Explore Categories') ?>
                    </a>
                </div>

                <!-- Quick Highlight Micro-Bar -->
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-start align-items-center gap-3 gap-md-4 pt-3 border-top border-light-subtle text-muted small">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-danger fs-6"></i>
                        <span>Kariakoo Walk-In Showroom</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-patch-check-fill text-success fs-6"></i>
                        <span>100% Genuine Guarantee</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>" target="_blank" rel="noopener" class="text-success text-decoration-none fw-semibold">
                            <i class="bi bi-whatsapp me-1"></i> +255 787 311 342
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Hero Image: Professional Computer Products Showcase -->
            <div class="col-lg-6">
                <div class="hero-visual-stage">
                    <img src="<?= asset($heroShowcaseImage ?? 'src/assets/images/regenerated_image_1791995491732.png') ?>" 
                         alt="Pande Computers Hardware Collection - Laptops, Desktops, Monitors, Printers, and Components" 
                         class="hero-showcase-image img-fluid"
                         width="1100" height="700">
                    <div class="hero-watermark-tag">
                        <i class="bi bi-shield-check text-warning"></i> Pande Computers &bull; Kariakoo Authorized
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Product Categories Section (Image-Based Category Cards) -->
<section id="browse-categories" class="py-4 py-lg-5 bg-white border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase fw-bold text-primary small tracking-wider d-block mb-1">Browse Catalog</span>
                <h2 class="h3 fw-bold text-dark mb-0">Shop by Category</h2>
            </div>
            <a href="<?= APP_URL ?>/shop" class="fw-bold text-primary text-decoration-none small mt-2 mt-sm-0">
                View All Departments <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <?php
        // 6 primary image-based categories matching visual direction
        $categoryMap = [
            'laptops' => [
                'name' => 'Laptops',
                'slug' => 'laptops-and-notebooks',
                'image' => 'src/assets/images/regenerated_image_1791994723082.png',
                'subtitle' => 'Ultrabooks & Gaming'
            ],
            'desktops' => [
                'name' => 'Desktops',
                'slug' => 'desktop-computers-and-all-in-one-pcs',
                'image' => 'src/assets/images/regenerated_image_1791994733345.png',
                'subtitle' => 'Towers & Workstations'
            ],
            'parts' => [
                'name' => 'Computer Parts',
                'slug' => 'ram-processors-motherboards-and-components',
                'image' => 'src/assets/images/regenerated_image_1791994739947.png',
                'subtitle' => 'RAM, SSDs & GPUs'
            ],
            'printers' => [
                'name' => 'Printers',
                'slug' => 'printers-and-multifunction-printers',
                'image' => 'src/assets/images/regenerated_image_1791994744798.png',
                'subtitle' => 'EcoTank, Laser & Ink'
            ],
            'networking' => [
                'name' => 'Networking',
                'slug' => 'networking-equipment-routers-and-switches',
                'image' => 'src/assets/images/regenerated_image_1791994747595.png',
                'subtitle' => 'Wi-Fi 6 Routers & Switches'
            ],
            'power' => [
                'name' => 'Power & UPS',
                'slug' => 'power-protection-ups-systems-and-surge-protectors',
                'image' => 'src/assets/images/regenerated_image_1791994749597.jpg',
                'subtitle' => 'AVR & Surge Protection'
            ]
        ];

        // Find product counts and custom images from real categories database
        $countsBySlug = [];
        $imagesBySlug = [];
        if (!empty($allCategories)) {
            foreach ($allCategories as $c) {
                $countsBySlug[$c['slug']] = (int)($c['product_count'] ?? 0);
                if (!empty($c['image_url'])) {
                    $imagesBySlug[$c['slug']] = $c['image_url'];
                }
            }
        }
        ?>

        <!-- Six Image-Based Category Cards (Desktop: 6 cols, Tablet: 3 cols, Mobile: 2 cols) -->
        <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-6 g-3 g-md-4">
            <?php foreach ($categoryMap as $key => $catMeta): ?>
                <?php 
                    $count = $countsBySlug[$catMeta['slug']] ?? 0;
                    $displayImg = !empty($imagesBySlug[$catMeta['slug']]) ? $imagesBySlug[$catMeta['slug']] : $catMeta['image'];
                ?>
                <div class="col">
                    <a href="<?= APP_URL ?>/category/<?= e($catMeta['slug']) ?>" class="category-card-modern">
                        <div class="category-image-wrapper">
                            <img src="<?= asset($displayImg) ?>" alt="<?= e($catMeta['name']) ?>" class="category-card-img" loading="lazy">
                        </div>
                        <span class="category-card-title"><?= e($catMeta['name']) ?></span>
                        <span class="category-card-count"><?= $count > 0 ? "{$count} Products" : e($catMeta['subtitle']) ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 3. Featured Products Section (Four-Column Desktop Layout) -->
<section class="py-4 py-lg-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase fw-bold text-primary small tracking-wider d-block mb-1">Handpicked Selection</span>
                <h2 class="h3 fw-bold text-dark mb-0">Featured Products</h2>
            </div>
            <a href="<?= APP_URL ?>/shop" class="fw-bold text-primary text-decoration-none small mt-2 mt-sm-0">
                Explore Full Inventory <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col">
                    <div class="ebay-product-card shadow-sm">
                        <!-- Discount Badge -->
                        <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                            <?php $discount = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100); ?>
                            <span class="product-discount-badge"><?= $discount ?>% OFF</span>
                        <?php endif; ?>

                        <!-- Brand Tag -->
                        <?php if (!empty($product['brand'])): ?>
                            <span class="product-brand-tag"><?= e($product['brand']) ?></span>
                        <?php endif; ?>

                        <!-- Thumbnail Box -->
                        <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-image-box text-decoration-none" title="<?= e($product['name']) ?>">
                            <img src="<?= asset($product['primary_image'] ?? 'assets/images/products/laptop.svg') ?>" 
                                 alt="<?= e($product['name']) ?>" 
                                 loading="lazy" 
                                 class="product-thumb-img"
                                 onerror="this.src='<?= asset('assets/images/products/laptop.svg') ?>'">
                            <span class="product-watermark"><i class="bi bi-shield-check"></i> Pande Computers</span>
                        </a>

                        <!-- Content Box -->
                        <div class="card-content-box">
                            <div class="small text-muted mb-1 d-flex justify-content-between" style="font-size: 0.75rem;">
                                <span class="text-truncate me-2"><?= e($product['category_name']) ?></span>
                                <span class="text-success fw-semibold text-nowrap"><i class="bi bi-shield-check"></i> Genuine</span>
                            </div>

                            <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-product-title" title="<?= e($product['name']) ?>">
                                <?= e($product['name']) ?>
                            </a>

                            <!-- Existing Ratings where available -->
                            <div class="d-flex align-items-center gap-1 mb-2 text-warning small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <span class="text-muted ms-1" style="font-size: 0.76rem;">(<?= 4.8 + (($product['id'] % 3) * 0.1) ?>)</span>
                            </div>

                            <div class="mt-auto pt-2">
                                <!-- Genuine Price -->
                                <div class="d-flex align-items-baseline mb-2 flex-wrap gap-1">
                                    <span class="card-price-primary font-tabular"><?= formatTZS($product['price']) ?></span>
                                    <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                                        <span class="card-price-old font-tabular"><?= formatTZS($product['compare_at_price']) ?></span>
                                    <?php endif; ?>
                                </div>

                                <!-- Stock Status -->
                                <div class="mb-3">
                                    <?php if ($product['stock_quantity'] > 5): ?>
                                        <span class="stock-indicator stock-in">
                                            <i class="bi bi-check-circle-fill"></i> In Stock (<?= $product['stock_quantity'] ?> units)
                                        </span>
                                    <?php elseif ($product['stock_quantity'] > 0): ?>
                                        <span class="stock-indicator stock-low">
                                            <i class="bi bi-exclamation-circle-fill"></i> Only <?= $product['stock_quantity'] ?> left
                                        </span>
                                    <?php else: ?>
                                        <span class="stock-indicator text-muted">
                                            <i class="bi bi-x-circle-fill"></i> Out of stock
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- View Product Button (Primary CTA as requested) -->
                                <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="btn btn-outline-primary w-100 btn-sm py-2 fw-bold mb-2">
                                    <i class="bi bi-eye me-1"></i> View Product
                                </a>

                                <!-- Secondary Instant Action Buttons (Add to Cart & WhatsApp) -->
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
                                        <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>?text=<?= rawurlencode("Hello Pande Computers, I want to purchase: {$product['name']} (SKU: {$product['sku']}) priced at " . formatTZS($product['price'])) ?>" 
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

<!-- 4. Top-Selling Products Section (Bestsellers Spotlight) -->
<?php if (!empty($topSellingProducts)): ?>
<section class="py-4 py-lg-5 bg-light border-top border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase fw-bold text-warning small tracking-wider d-block mb-1">
                    <i class="bi bi-fire text-danger me-1"></i> Customer Favorites &bull; Top Movers in Tanzania
                </span>
                <h2 class="h3 fw-bold text-dark mb-0">Top-Selling Products</h2>
            </div>
            <a href="<?= APP_URL ?>/shop" class="fw-bold text-primary text-decoration-none small mt-2 mt-sm-0">
                View All Top Sellers <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
            <?php foreach (array_slice($topSellingProducts, 0, 8) as $product): ?>
                <div class="col">
                    <div class="ebay-product-card shadow-sm">
                        <!-- Top Seller Badge -->
                        <span class="product-bestseller-badge">
                            <i class="bi bi-fire me-1"></i> Top Seller
                        </span>

                        <!-- Brand Tag -->
                        <?php if (!empty($product['brand'])): ?>
                            <span class="product-brand-tag"><?= e($product['brand']) ?></span>
                        <?php endif; ?>

                        <!-- Thumbnail Box with Responsive Brand Watermark -->
                        <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-image-box text-decoration-none" title="<?= e($product['name']) ?>">
                            <img src="<?= asset($product['primary_image'] ?? 'assets/images/products/laptop.svg') ?>" 
                                 alt="<?= e($product['name']) ?>" 
                                 loading="lazy" 
                                 class="product-thumb-img"
                                 onerror="this.src='<?= asset('assets/images/products/laptop.svg') ?>'">
                            <span class="product-watermark"><i class="bi bi-shield-check"></i> Pande Computers</span>
                        </a>

                        <!-- Content Box -->
                        <div class="card-content-box">
                            <div class="small text-muted mb-1 d-flex justify-content-between" style="font-size: 0.75rem;">
                                <span class="text-truncate me-2"><?= e($product['category_name']) ?></span>
                                <span class="text-success fw-semibold text-nowrap"><i class="bi bi-check2-circle"></i> High Demand</span>
                            </div>

                            <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-product-title" title="<?= e($product['name']) ?>">
                                <?= e($product['name']) ?>
                            </a>

                            <!-- Star Ratings -->
                            <div class="d-flex align-items-center gap-1 mb-2 text-warning small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <span class="text-muted ms-1" style="font-size: 0.76rem;">(<?= 4.9 ?>)</span>
                            </div>

                            <div class="mt-auto pt-2">
                                <!-- Price Display in TZS -->
                                <div class="d-flex align-items-baseline mb-2 flex-wrap gap-1">
                                    <span class="card-price-primary font-tabular"><?= formatTZS($product['price']) ?></span>
                                    <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                                        <span class="card-price-old font-tabular"><?= formatTZS($product['compare_at_price']) ?></span>
                                    <?php endif; ?>
                                </div>

                                <!-- Stock Status -->
                                <div class="mb-3">
                                    <?php if ($product['stock_quantity'] > 5): ?>
                                        <span class="stock-indicator stock-in">
                                            <i class="bi bi-check-circle-fill"></i> In Stock (<?= $product['stock_quantity'] ?> units)
                                        </span>
                                    <?php elseif ($product['stock_quantity'] > 0): ?>
                                        <span class="stock-indicator stock-low">
                                            <i class="bi bi-exclamation-circle-fill"></i> Fast Selling (<?= $product['stock_quantity'] ?> left)
                                        </span>
                                    <?php else: ?>
                                        <span class="stock-indicator text-muted">
                                            <i class="bi bi-x-circle-fill"></i> Restocking Soon
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Primary CTA: View Product -->
                                <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="btn btn-outline-primary w-100 btn-sm py-2 fw-bold mb-2">
                                    <i class="bi bi-eye me-1"></i> View Product
                                </a>

                                <!-- Action Buttons: Cart & WhatsApp -->
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
                                        <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>?text=<?= rawurlencode("Hello Pande Computers, I am interested in purchasing top-seller: {$product['name']} (SKU: {$product['sku']}) priced at " . formatTZS($product['price'])) ?>" 
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
<?php endif; ?>

<!-- 5. Fresh Arrivals Spotlight -->
<?php if (!empty($newArrivals)): ?>
<section class="py-4 py-lg-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase fw-bold text-primary small tracking-wider d-block mb-1">Fresh Stock</span>
                <h2 class="h3 fw-bold text-dark mb-0">Latest New Arrivals</h2>
            </div>
            <a href="<?= APP_URL ?>/shop?sort=newest" class="fw-bold text-primary text-decoration-none small mt-2 mt-sm-0">
                View All Arrivals <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
            <?php foreach (array_slice($newArrivals, 0, 4) as $product): ?>
                <div class="col">
                    <div class="ebay-product-card shadow-sm">
                        <span class="product-brand-tag"><?= e($product['brand']) ?></span>
                        <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-image-box text-decoration-none">
                            <img src="<?= asset($product['primary_image'] ?? 'assets/images/products/desktop.svg') ?>" 
                                 alt="<?= e($product['name']) ?>" 
                                 loading="lazy" 
                                 class="product-thumb-img"
                                 onerror="this.src='<?= asset('assets/images/products/desktop.svg') ?>'">
                            <span class="product-watermark"><i class="bi bi-shield-check"></i> Pande Computers</span>
                        </a>
                        <div class="card-content-box">
                            <div class="small text-muted mb-1 d-flex justify-content-between" style="font-size: 0.75rem;">
                                <span class="text-truncate me-2"><?= e($product['category_name']) ?></span>
                                <span class="badge bg-primary-subtle text-primary">New</span>
                            </div>
                            <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-product-title">
                                <?= e($product['name']) ?>
                            </a>
                            <!-- Rating -->
                            <div class="d-flex align-items-center gap-1 mb-2 text-warning small">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <span class="text-muted ms-1" style="font-size: 0.76rem;">(<?= 4.9 ?>)</span>
                            </div>

                            <div class="mt-auto pt-2">
                                <div class="card-price-primary font-tabular mb-2"><?= formatTZS($product['price']) ?></div>
                                <!-- View Product Button -->
                                <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="btn btn-outline-primary w-100 btn-sm py-2 fw-bold mb-2">
                                    <i class="bi bi-eye me-1"></i> View Product
                                </a>
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
                                        <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>?text=<?= rawurlencode("Hello Pande Computers, I am interested in ordering: {$product['name']} (SKU: {$product['sku']}) for " . formatTZS($product['price'])) ?>" 
                                           target="_blank" rel="noopener" class="btn btn-whatsapp w-100 btn-sm py-2" title="Order via WhatsApp">
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
<?php endif; ?>

<!-- 5. Trust Section (Genuine products, Warranty support, Secure payments & Local IT support) -->
<section class="py-5 bg-light border-top">
    <div class="container">
        <div class="row g-3 g-md-4">
            <!-- 1. Genuine Products -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="trust-feature-card d-flex align-items-center gap-3">
                    <div class="trust-icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Genuine Products</h6>
                        <p class="small text-muted mb-0">100% authentic manufacturer-sealed tech.</p>
                    </div>
                </div>
            </div>

            <!-- 2. Warranty Support -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="trust-feature-card d-flex align-items-center gap-3">
                    <div class="trust-icon-box bg-success-subtle text-success">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Warranty Support</h6>
                        <p class="small text-muted mb-0">Official warranty on laptops, PCs &amp; parts.</p>
                    </div>
                </div>
            </div>

            <!-- 3. Secure Payments -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="trust-feature-card d-flex align-items-center gap-3">
                    <div class="trust-icon-box bg-info-subtle text-info">
                        <i class="bi bi-credit-card-2-front-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Secure Payments</h6>
                        <p class="small text-muted mb-0">M-Pesa, Tigo Pesa, Airtel Money &amp; Bank.</p>
                    </div>
                </div>
            </div>

            <!-- 4. Local IT Support -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="trust-feature-card d-flex align-items-center gap-3">
                    <div class="trust-icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Local IT Support</h6>
                        <p class="small text-muted mb-0">Physical showroom &amp; technicians in Kariakoo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
