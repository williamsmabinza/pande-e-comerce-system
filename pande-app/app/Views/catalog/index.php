<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>/shop" class="text-decoration-none">All Tech</a></li>
            <?php if (!empty($selectedCategory)): ?>
                <li class="breadcrumb-item active" aria-current="page"><?= e($selectedCategory['name']) ?></li>
            <?php else: ?>
                <li class="breadcrumb-item active" aria-current="page">Catalog</li>
            <?php endif; ?>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <aside class="col-lg-3">
            <div class="card border border-pc rounded-3 p-3 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem;"><i class="bi bi-funnel me-1 text-primary"></i> Filter Products</h5>
                    <a href="<?= APP_URL ?>/shop" class="small text-danger text-decoration-none">Clear All</a>
                </div>

                <form action="<?= APP_URL ?>/shop" method="GET" id="catalog-filter-form">
                    <?php if (!empty($filters['query'])): ?>
                        <input type="hidden" name="q" value="<?= e($filters['query']) ?>">
                    <?php endif; ?>

                    <!-- Categories Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary mb-2">Category</label>
                        <div class="category-filter-list" style="max-height: 280px; overflow-y: auto;">
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="category" id="cat-all" value="" <?= empty($filters['category_id']) ? 'checked' : '' ?> onchange="this.form.submit()">
                                <label class="form-check-label small" for="cat-all">All Categories</label>
                            </div>
                            <?php foreach ($allCategories as $c): ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="category" id="cat-<?= $c['id'] ?>" value="<?= $c['id'] ?>" <?= (!empty($filters['category_id']) && (int)$filters['category_id'] === (int)$c['id']) ? 'checked' : '' ?> onchange="this.form.submit()">
                                    <label class="form-check-label small text-truncate" for="cat-<?= $c['id'] ?>">
                                        <?= e($c['name']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Price Range (TZS) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-secondary mb-2">Price Range (TZS)</label>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min TZS" value="<?= e($filters['min_price'] ?? '') ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max TZS" value="<?= e($filters['max_price'] ?? '') ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100 fw-bold">Apply Price</button>
                    </div>

                    <!-- Availability -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-secondary mb-2">Item Availability</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="in-stock" <?= !empty($filters['in_stock']) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <label class="form-check-label small" for="in-stock">In Stock Items Only</label>
                        </div>
                    </div>

                    <input type="hidden" name="sort" value="<?= e($filters['sort'] ?? 'newest') ?>">
                </form>
            </div>

            <!-- Kariakoo Store Callout Box -->
            <div class="p-3 bg-white border border-pc rounded-3 small">
                <div class="fw-bold text-dark mb-1"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Kariakoo Retail Store</div>
                <p class="text-muted mb-2">Visit us at Ndanda & Aggrey Street, Kariakoo, Dar es Salaam for direct purchases & testing.</p>
                <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>" target="_blank" rel="noopener" class="text-success fw-bold text-decoration-none">
                    <i class="bi bi-whatsapp"></i> Chat on WhatsApp &rarr;
                </a>
            </div>
        </aside>

        <!-- Product Listing Grid -->
        <main class="col-lg-9">
            <!-- Filter Bar & Sort Header -->
            <div class="card border border-pc rounded-3 p-3 bg-white mb-4">
                <div class="row align-items-center gy-2">
                    <div class="col-sm-6">
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem;">
                            <?= !empty($selectedCategory) ? e($selectedCategory['name']) : (!empty($filters['query']) ? 'Search: "' . e($filters['query']) . '"' : 'All Electronics Products') ?>
                        </h4>
                        <span class="small text-muted"><?= $total ?> items found &bull; Dar es Salaam inventory</span>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <div class="d-inline-flex align-items-center gap-2">
                            <label class="small text-muted text-nowrap">Sort:</label>
                            <select class="form-select form-select-sm" style="width: 180px;" onchange="location = this.value;">
                                <option value="<?= APP_URL ?>/shop?<?= http_build_query(array_merge($filters, ['sort' => 'newest', 'page' => 1])) ?>" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
                                <option value="<?= APP_URL ?>/shop?<?= http_build_query(array_merge($filters, ['sort' => 'price_asc', 'page' => 1])) ?>" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="<?= APP_URL ?>/shop?<?= http_build_query(array_merge($filters, ['sort' => 'price_desc', 'page' => 1])) ?>" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                                <option value="<?= APP_URL ?>/shop?<?= http_build_query(array_merge($filters, ['sort' => 'name_asc', 'page' => 1])) ?>" <?= ($filters['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>Product Name (A-Z)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <?php if (empty($products)): ?>
                <div class="card border border-pc rounded-3 p-5 text-center bg-white my-4">
                    <i class="bi bi-search text-muted mb-3" style="font-size: 3.5rem;"></i>
                    <h5 class="fw-bold text-dark">No Electronics Found</h5>
                    <p class="text-muted small mb-4">We couldn't find items matching your filter settings. Try clearing the filters or searching with general terms.</p>
                    <div>
                        <a href="<?= APP_URL ?>/shop" class="btn btn-primary px-4 fw-bold">View All Products</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-md-3 g-3 g-md-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="ebay-product-card">
                                <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                                    <?php $discount = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100); ?>
                                    <span class="product-discount-badge"><?= $discount ?>% OFF</span>
                                <?php endif; ?>

                                <span class="product-brand-tag"><?= e($product['brand']) ?></span>

                                <a href="<?= APP_URL ?>/product/<?= e($product['slug']) ?>" class="card-image-box text-decoration-none">
                                    <img src="<?= asset($product['primary_image'] ?? 'assets/images/products/laptop.svg') ?>" 
                                         alt="<?= e($product['name']) ?>" 
                                         loading="lazy" 
                                         class="product-thumb-img"
                                         onerror="this.src='<?= asset('assets/images/products/laptop.svg') ?>'">
                                    <span class="product-watermark"><i class="bi bi-shield-check"></i> Pande Computers</span>
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
                                        <div class="d-flex align-items-baseline mb-1">
                                            <span class="card-price-primary font-tabular"><?= formatTZS($product['price']) ?></span>
                                            <?php if (!empty($product['compare_at_price']) && $product['compare_at_price'] > $product['price']): ?>
                                                <span class="card-price-old font-tabular"><?= formatTZS($product['compare_at_price']) ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="small text-muted mb-2 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                            <i class="bi bi-geo-alt text-danger"></i> Kariakoo Pickup &bull; DSM Delivery
                                        </div>

                                        <div class="mb-3">
                                            <?php if ($product['stock_quantity'] > 0): ?>
                                                <span class="stock-indicator stock-in">
                                                    <i class="bi bi-check-circle-fill"></i> In Stock (<?= $product['stock_quantity'] ?>)
                                                </span>
                                            <?php else: ?>
                                                <span class="stock-indicator text-muted">
                                                    <i class="bi bi-x-circle-fill"></i> Out of stock
                                                </span>
                                            <?php endif; ?>
                                        </div>

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
                                                <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>?text=<?= rawurlencode("Hello Pande Computers, I want to order: {$product['name']} (SKU: {$product['sku']}) for " . formatTZS($product['price'])) ?>" 
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

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-5" aria-label="Catalog pagination">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= APP_URL ?>/shop?<?= http_build_query(array_merge($filters, ['page' => $currentPage - 1])) ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= APP_URL ?>/shop?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= APP_URL ?>/shop?<?= http_build_query(array_merge($filters, ['page' => $currentPage + 1])) ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>
