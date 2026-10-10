<!-- Slim Top Utility Bar (eBay-Style) -->
<div class="top-utility-bar py-1">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
        <!-- User Greeting / Authentication Quick Access -->
        <div class="d-flex align-items-center gap-3">
            <?php if (!empty($isAuthenticated)): ?>
                <span>Hi, <strong class="text-dark"><?= e($currentUser['first_name']) ?></strong>!</span>
                <span class="text-secondary opacity-50">|</span>
                <a href="<?= APP_URL ?>/customer/dashboard">My Dashboard</a>
                <span class="text-secondary opacity-50">|</span>
                <a href="<?= APP_URL ?>/customer/orders">My Orders</a>
            <?php else: ?>
                <span>Hi! <a href="<?= APP_URL ?>/login" class="fw-bold text-primary">Sign in</a> or <a href="<?= APP_URL ?>/register" class="fw-bold text-primary">register</a></span>
            <?php endif; ?>
            <span class="d-none d-md-inline text-secondary opacity-50">|</span>
            <a href="<?= APP_URL ?>/shop" class="d-none d-md-inline">Daily Deals</a>
            <span class="d-none d-md-inline text-secondary opacity-50">|</span>
            <a href="<?= APP_URL ?>/contact" class="d-none d-md-inline">Help & Contact</a>
        </div>

        <!-- Location & WhatsApp Hotline -->
        <div class="d-flex align-items-center gap-3">
            <span class="d-none d-lg-inline text-muted">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Kariakoo Showroom, Dar es Salaam
            </span>
            <span class="d-none d-lg-inline text-secondary opacity-50">|</span>
            <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>" target="_blank" rel="noopener" class="text-success fw-bold">
                <i class="bi bi-whatsapp me-1"></i> WhatsApp: +255 787 311 342
            </a>
            <?php if (!empty($isAdmin) || !empty($isStaff)): ?>
                <span class="text-secondary opacity-50">|</span>
                <a href="<?= APP_URL ?>/admin" class="badge bg-navy text-white text-decoration-none">
                    <i class="bi bi-speedometer2 me-1"></i> Admin Panel
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="main-header sticky-top bg-white">
    <div class="container">
        <div class="row align-items-center gy-2">
            <!-- Brand Logo & Category Trigger -->
            <div class="col-6 col-lg-3 d-flex align-items-center gap-2">
                <a href="<?= APP_URL ?>/" class="d-inline-flex align-items-center text-decoration-none" title="Pande Computers - Your Trusted Tech Partner">
                    <img src="<?= asset('assets/images/logo-horizontal.svg') ?>" alt="Pande Computers" class="header-logo-img">
                </a>

                <!-- Shop by Category (eBay style) -->
                <div class="dropdown d-none d-xl-block">
                    <button class="btn btn-sm btn-link text-dark text-decoration-none dropdown-toggle fw-semibold ps-2" type="button" data-bs-toggle="dropdown">
                        Shop by category
                    </button>
                    <ul class="dropdown-menu shadow-lg border-0 py-2" style="width: 320px; max-height: 420px; overflow-y: auto;">
                        <li class="px-3 py-1 border-bottom text-uppercase small fw-bold text-secondary">Browse Electronics Departments</li>
                        <?php foreach ($allCategories as $cat): ?>
                            <li>
                                <a class="dropdown-item py-2 d-flex justify-content-between align-items-center" href="<?= APP_URL ?>/category/<?= e($cat['slug']) ?>">
                                    <span><i class="bi <?= e($cat['icon_class'] ?: 'bi-chevron-right') ?> text-primary me-2"></i> <?= e($cat['name']) ?></span>
                                    <span class="badge bg-light text-secondary rounded-pill"><?= (int)($cat['product_count'] ?? 0) ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item fw-bold text-primary py-2" href="<?= APP_URL ?>/shop">View All Categories &rarr;</a></li>
                    </ul>
                </div>
            </div>

            <!-- Prominent eBay-Style Search Bar -->
            <div class="col-12 col-lg-6 order-3 order-lg-2">
                <form action="<?= APP_URL ?>/shop" method="GET" class="w-100">
                    <div class="ebay-search-container">
                        <i class="bi bi-search text-muted ps-3 d-flex align-items-center"></i>
                        <input type="text" name="q" class="ebay-search-input" 
                               placeholder="Search for computers, monitors, printers, components..." 
                               value="<?= e($_GET['q'] ?? '') ?>" aria-label="Search tech products">

                        <!-- In-Search Category Selector -->
                        <select name="category" class="ebay-category-select">
                            <option value="">All Categories</option>
                            <?php foreach ($allCategories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= (!empty($_GET['category']) && (int)$_GET['category'] === (int)$c['id']) ? 'selected' : '' ?>>
                                    <?= e($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- High-Contrast Search Action Button -->
                        <button type="submit" class="ebay-search-btn">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Customer Account & Cart Actions -->
            <div class="col-6 col-lg-3 order-2 order-lg-3 text-end d-flex justify-content-end align-items-center gap-3">
                <!-- User Account Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none dropdown-toggle p-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <?php if (!empty($isAuthenticated) && !empty($currentUser['avatar'])): ?>
                            <img src="<?= APP_URL . '/' . e($currentUser['avatar']) ?>" 
                                 alt="<?= e($currentUser['first_name'] ?? 'Account') ?>" 
                                 class="rounded-circle object-fit-cover border shadow-sm" 
                                 style="width: 38px; height: 38px;">
                        <?php else: ?>
                            <div class="rounded-circle bg-light border p-2 d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                                <i class="bi bi-person-fill fs-5"></i>
                            </div>
                        <?php endif; ?>
                        <div class="text-start d-none d-md-block" style="line-height: 1.2;">
                            <span class="small text-muted d-block" style="font-size: 0.72rem;">Account</span>
                            <span class="fw-bold small text-truncate d-block" style="max-width: 100px;">
                                <?= !empty($isAuthenticated) ? e($currentUser['first_name']) : 'Sign in' ?>
                            </span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="min-width: 220px;">
                        <?php if (!empty($isAuthenticated)): ?>
                            <li class="px-3 py-2 border-bottom d-flex align-items-center gap-2">
                                <?php if (!empty($currentUser['avatar'])): ?>
                                    <img src="<?= APP_URL . '/' . e($currentUser['avatar']) ?>" 
                                         alt="<?= e($currentUser['first_name']) ?>" 
                                         class="rounded-circle object-fit-cover border" 
                                         style="width: 36px; height: 36px; min-width: 36px;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" 
                                         style="width: 36px; height: 36px; min-width: 36px;">
                                        <?= strtoupper(substr($currentUser['first_name'] ?? 'U', 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.88rem;"><?= e($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?></div>
                                    <div class="small text-muted text-truncate" style="font-size: 0.75rem;"><?= e($currentUser['email']) ?></div>
                                </div>
                            </li>
                            <?php if (!empty($isAdmin) || !empty($isStaff)): ?>
                                <li><a class="dropdown-item py-2 fw-semibold text-primary" href="<?= APP_URL ?>/admin"><i class="bi bi-speedometer2 me-2"></i> Admin Panel</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item py-2" href="<?= APP_URL ?>/customer/dashboard"><i class="bi bi-grid me-2"></i> My Dashboard</a></li>
                            <li><a class="dropdown-item py-2" href="<?= APP_URL ?>/customer/orders"><i class="bi bi-box-seam me-2"></i> My Orders</a></li>
                            <li><a class="dropdown-item py-2" href="<?= APP_URL ?>/customer/profile"><i class="bi bi-person-gear me-2"></i> Profile Settings</a></li>
                            <li><a class="dropdown-item py-2" href="<?= APP_URL ?>/customer/addresses"><i class="bi bi-geo-alt me-2"></i> Saved Addresses</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?= APP_URL ?>/logout" method="POST">
                                    <?= $csrfInput ?>
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</button>
                                </form>
                            </li>
                        <?php else: ?>
                            <li class="p-3 text-center">
                                <a href="<?= APP_URL ?>/login" class="btn btn-primary w-100 fw-bold mb-2">Sign in</a>
                                <div class="small text-muted">New customer? <a href="<?= APP_URL ?>/register" class="text-primary fw-bold">Start here.</a></div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Shopping Cart (eBay style with count badge & price) -->
                <?php $headerCartUrl = APP_URL . '/cart' . (!empty($_GET['sid']) ? ('?sid=' . urlencode($_GET['sid'])) : ''); ?>
                <a href="<?= $headerCartUrl ?>" class="btn btn-link text-dark text-decoration-none p-0 d-flex align-items-center gap-2 position-relative header-cart-link" title="Shopping Cart">
                    <div class="position-relative">
                        <i class="bi bi-cart3 fs-3 text-dark header-cart-icon"></i>
                        <span class="badge bg-pc-red rounded-pill position-absolute top-0 start-100 translate-middle cart-count-badge" style="font-size: 0.7rem;">
                            <?= (int)($cartTotals['count'] ?? 0) ?>
                        </span>
                    </div>
                    <div class="text-start d-none d-lg-block" style="line-height: 1.2;">
                        <span class="small text-muted d-block" style="font-size: 0.72rem;">My Cart</span>
                        <span class="fw-bold small text-dark header-cart-subtotal font-tabular"><?= formatTZS($cartTotals['subtotal'] ?? 0) ?></span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Category Bar (Horizontal eBay Departments) -->
    <nav class="department-nav-bar d-none d-md-block">
        <div class="container d-flex align-items-center overflow-x-auto">
            <a href="<?= APP_URL ?>/shop" class="dept-link fw-bold text-primary">All Products</a>
            <?php foreach (array_slice($allCategories, 0, 10) as $cat): ?>
                <a href="<?= APP_URL ?>/category/<?= e($cat['slug']) ?>" class="dept-link">
                    <?= e($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>
</header>
