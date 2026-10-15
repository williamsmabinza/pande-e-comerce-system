<div class="container-fluid py-2">
    <!-- Dashboard Overview Header & Operational Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-navy text-white px-2 py-1"><i class="bi bi-shield-lock-fill text-warning me-1"></i> Admin Command</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Store Active
                </span>
                <span class="badge bg-light text-muted border">
                    <i class="bi bi-calendar3 me-1"></i> <?= date('l, d F Y') ?>
                </span>
            </div>
            <h2 class="fw-black text-dark mb-0">Overview</h2>
            <p class="text-muted small mb-0">
                Welcome back, <strong><?= e($currentUser['first_name'] ?? 'Administrator') ?></strong>! Here is your business operations performance overview for Pande Computers Kariakoo.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Timeframe Date-Range Filter -->
            <div class="btn-group btn-group-sm" role="group">
                <a href="<?= APP_URL ?>/admin?period=all" class="btn <?= ($selectedPeriod ?? 'all') === 'all' ? 'btn-dark' : 'btn-outline-secondary' ?>">
                    All Time
                </a>
                <a href="<?= APP_URL ?>/admin?period=today" class="btn <?= ($selectedPeriod ?? '') === 'today' ? 'btn-dark' : 'btn-outline-secondary' ?>">
                    Today
                </a>
                <a href="<?= APP_URL ?>/admin?period=7days" class="btn <?= ($selectedPeriod ?? '') === '7days' ? 'btn-dark' : 'btn-outline-secondary' ?>">
                    Last 7 Days
                </a>
                <a href="<?= APP_URL ?>/admin?period=30days" class="btn <?= ($selectedPeriod ?? '') === '30days' ? 'btn-dark' : 'btn-outline-secondary' ?>">
                    Last 30 Days
                </a>
                <a href="<?= APP_URL ?>/admin?period=this_month" class="btn <?= ($selectedPeriod ?? '') === 'this_month' ? 'btn-dark' : 'btn-outline-secondary' ?>">
                    This Month
                </a>
            </div>

            <!-- Refresh Control -->
            <a href="<?= APP_URL ?>/admin<?= !empty($selectedPeriod) && $selectedPeriod !== 'all' ? '?period=' . urlencode($selectedPeriod) : '' ?>" class="btn btn-outline-primary btn-sm fw-semibold shadow-sm" title="Reload live database statistics">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </a>

            <!-- Hero Customizer Collapse Trigger -->
            <button class="btn btn-warning fw-bold btn-sm shadow-sm text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#heroContentEditor" aria-expanded="false" aria-controls="heroContentEditor">
                <i class="bi bi-image-fill me-1"></i> Hero Visuals
            </button>
        </div>
    </div>

    <!-- Quick Shortcuts Toolbar (8 Actions) -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-light">
        <div class="card-body p-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="small fw-bold text-muted px-2 text-uppercase" style="font-size: 0.72rem;">
                <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Actions:
            </span>
            <div class="d-flex flex-wrap gap-1">
                <a href="<?= APP_URL ?>/admin/products/create" class="btn btn-white btn-sm border fw-semibold shadow-sm">
                    <i class="bi bi-plus-circle text-primary me-1"></i> Add Product
                </a>
                <a href="<?= APP_URL ?>/admin/products" class="btn btn-white btn-sm border fw-semibold shadow-sm">
                    <i class="bi bi-laptop text-info me-1"></i> Manage Products
                </a>
                <a href="<?= APP_URL ?>/admin/orders" class="btn btn-white btn-sm border fw-semibold shadow-sm">
                    <i class="bi bi-receipt text-success me-1"></i> View Orders
                </a>
                <a href="<?= APP_URL ?>/admin/inventory" class="btn btn-white btn-sm border fw-semibold shadow-sm">
                    <i class="bi bi-boxes text-danger me-1"></i> Manage Inventory
                </a>
                <a href="<?= APP_URL ?>/admin/users" class="btn btn-white btn-sm border fw-semibold shadow-sm">
                    <i class="bi bi-people text-secondary me-1"></i> View Customers
                </a>
                <a href="#orders-hub" class="btn btn-white btn-sm border fw-semibold shadow-sm" onclick="switchTab('orders-hub')">
                    <i class="bi bi-wallet2 text-warning me-1"></i> Review Payments
                </a>
                <button type="button" class="btn btn-white btn-sm border fw-semibold shadow-sm" data-bs-toggle="collapse" data-bs-target="#announcementEditor">
                    <i class="bi bi-megaphone text-primary me-1"></i> Store Notice
                </button>
                <a href="<?= APP_URL ?>/" target="_blank" class="btn btn-dark btn-sm fw-semibold shadow-sm">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Open Storefront
                </a>
            </div>
        </div>
    </div>
    <!-- Live Store Announcement & Status Banner -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-navy text-white overflow-hidden">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 bg-white bg-opacity-10 rounded text-warning fs-4">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark text-uppercase fw-bold" style="font-size: 0.65rem;">
                                Store Status: <?= strtoupper(e($storeConfig['status'] ?? 'open')) ?>
                            </span>
                            <span class="small text-white-50">Customer Broadcast Banner</span>
                        </div>
                        <div class="fw-semibold text-white mt-1 small">
                            "<?= e($storeConfig['announcement'] ?? 'Kariakoo Store Open Mon - Sat (8:30 AM - 6:30 PM). Genuine Laptops, PCs & Accessories with Same-Day Delivery in Dar es Salaam.') ?>"
                        </div>
                    </div>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#announcementEditor">
                        <i class="bi bi-pencil-square me-1"></i> Modify Notice & Store Status
                    </button>
                </div>
            </div>

            <!-- Collapsible Edit Form -->
            <div class="collapse mt-3 pt-3 border-top border-white border-opacity-10" id="announcementEditor">
                <form action="<?= APP_URL ?>/admin/quick-announcement" method="POST" class="bg-dark bg-opacity-25 p-3 rounded-3">
                    <?= $csrfInput ?>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold text-white-50">Operation Mode</label>
                            <select name="store_status" class="form-select form-select-sm bg-light text-dark">
                                <option value="open" <?= ($storeConfig['status'] ?? '') === 'open' ? 'selected' : '' ?>>Open (Orders & WhatsApp Active)</option>
                                <option value="busy" <?= ($storeConfig['status'] ?? '') === 'busy' ? 'selected' : '' ?>>High Volume (Orders May Take Longer)</option>
                                <option value="maintenance" <?= ($storeConfig['status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>In-Store Only / Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small fw-semibold text-white-50">Live Notice Text</label>
                            <input type="text" name="store_announcement" class="form-control form-select-sm bg-light text-dark" value="<?= e($storeConfig['announcement'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">
                                <i class="bi bi-check2-circle me-1"></i> Update Notice
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <!-- ======================================================== -->
    <!-- STORE OVERVIEW (Immediately Below Store Status)          -->
    <!-- ======================================================== -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="fw-black text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-speedometer2 text-primary"></i> Store Overview
            </h4>
            <p class="text-muted small mb-0">Executive business overview, live order velocity, payment statuses, and inventory health</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-muted border">
                <i class="bi bi-clock-history me-1"></i> Real-Time Database Figures
            </span>
            <a href="<?= APP_URL ?>/admin<?= !empty($selectedPeriod) && $selectedPeriod !== 'all' ? '?period=' . urlencode($selectedPeriod) : '' ?>" class="btn btn-outline-primary btn-sm fw-semibold" title="Reload live database statistics">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh Overview
            </a>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- SECTION 0: Operational & Inventory Priority Alerts Hub    -->
    <!-- ======================================================== -->
    <?php
        $hasAlerts = ($stats['pendingOrders'] > 0) || ($stats['awaitingPayment'] > 0) || ($stats['outOfStockProducts'] > 0) || ($stats['lowStockProducts'] > 0);
    ?>
    <div class="row g-3 mb-4">
        <!-- Alert 1: Unverified Payments -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 <?= $stats['awaitingPayment'] > 0 ? 'border-warning' : 'border-success' ?>">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Payment Verification</div>
                        <div class="fs-4 fw-black <?= $stats['awaitingPayment'] > 0 ? 'text-warning' : 'text-success' ?> mt-1">
                            <?= $stats['awaitingPayment'] ?> Awaiting
                        </div>
                        <div class="small mt-1">
                            <?php if ($stats['awaitingPayment'] > 0): ?>
                                <a href="#orders-hub" class="text-warning fw-bold text-decoration-none" onclick="switchTab('orders-hub')">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Verify Mobile Receipts &rarr;
                                </a>
                            <?php else: ?>
                                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> All receipts verified</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-2 <?= $stats['awaitingPayment'] > 0 ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' ?> rounded-circle fs-4">
                        <i class="bi <?= $stats['awaitingPayment'] > 0 ? 'bi-clock-history' : 'bi-shield-check' ?>"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert 2: Orders Awaiting Action / Fulfillment -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 <?= $stats['pendingOrders'] > 0 ? 'border-primary' : 'border-success' ?>">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Fulfillment Action</div>
                        <div class="fs-4 fw-black <?= $stats['pendingOrders'] > 0 ? 'text-primary' : 'text-success' ?> mt-1">
                            <?= $stats['pendingOrders'] ?> Pending
                        </div>
                        <div class="small mt-1">
                            <?php if ($stats['pendingOrders'] > 0): ?>
                                <a href="#orders-hub" class="text-primary fw-bold text-decoration-none" onclick="switchTab('orders-hub')">
                                    <i class="bi bi-truck me-1"></i> Process &amp; Dispatch &rarr;
                                </a>
                            <?php else: ?>
                                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Pipeline current</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-2 <?= $stats['pendingOrders'] > 0 ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' ?> rounded-circle fs-4">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert 3: Out-of-Stock SKUs -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 <?= $stats['outOfStockProducts'] > 0 ? 'border-danger' : 'border-success' ?>">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Out-of-Stock Alert</div>
                        <div class="fs-4 fw-black <?= $stats['outOfStockProducts'] > 0 ? 'text-danger' : 'text-success' ?> mt-1">
                            <?= $stats['outOfStockProducts'] ?> SKUs Empty
                        </div>
                        <div class="small mt-1">
                            <?php if ($stats['outOfStockProducts'] > 0): ?>
                                <a href="#inventory-hub" class="text-danger fw-bold text-decoration-none" onclick="switchTab('inventory-hub')">
                                    <i class="bi bi-box-arrow-in-down me-1"></i> Restock Immediate &rarr;
                                </a>
                            <?php else: ?>
                                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Zero stockouts</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-2 <?= $stats['outOfStockProducts'] > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' ?> rounded-circle fs-4">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert 4: Low-Stock Warnings -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 <?= $stats['lowStockProducts'] > 0 ? 'border-warning' : 'border-success' ?>">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Low Safety Stock</div>
                        <div class="fs-4 fw-black <?= $stats['lowStockProducts'] > 0 ? 'text-warning' : 'text-success' ?> mt-1">
                            <?= $stats['lowStockProducts'] ?> Below Threshold
                        </div>
                        <div class="small mt-1">
                            <?php if ($stats['lowStockProducts'] > 0): ?>
                                <a href="#inventory-hub" class="text-warning fw-bold text-decoration-none" onclick="switchTab('inventory-hub')">
                                    <i class="bi bi-boxes me-1"></i> Reorder Units &rarr;
                                </a>
                            <?php else: ?>
                                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> All stock safe</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-2 <?= $stats['lowStockProducts'] > 0 ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success' ?> rounded-circle fs-4">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================================================== -->
    <!-- SECTION 1: Executive KPI Overview Matrix (Real DB Data)   -->
    <!-- ======================================================== -->

    <!-- Primary Row: Financial & Order Velocity Cards -->
    <div class="row g-3 mb-3">
        <!-- 1. Total Sales Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Gross Sales Revenue</span>
                        <h3 class="fw-black text-dark mb-0 mt-1"><?= formatTZS($stats['totalSales']) ?></h3>
                        <div class="small text-muted mt-2">
                            <span class="text-success fw-semibold"><i class="bi bi-graph-up-arrow me-1"></i> Today: <?= formatTZS($stats['todaySales']) ?></span>
                            <span class="mx-1">&bull;</span>
                            <span>Month: <?= formatTZS($stats['thisMonthSales']) ?></span>
                        </div>
                    </div>
                    <div class="p-3 bg-success-subtle text-success rounded-3 fs-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Today's Sales & Orders Today -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Today's Sales & Volume</span>
                        <h3 class="fw-black text-info mb-0 mt-1"><?= formatTZS($stats['todaySales']) ?></h3>
                        <div class="small text-muted mt-2">
                            <span class="badge bg-info-subtle text-info fw-bold"><?= $stats['todayOrders'] ?> orders received today</span>
                            <span class="mx-1">&bull;</span>
                            <span>Avg Order: <?= formatTZS($stats['avgOrderValue']) ?></span>
                        </div>
                    </div>
                    <div class="p-3 bg-info-subtle text-info rounded-3 fs-3">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Current Month Sales -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Current Month Sales</span>
                        <h3 class="fw-black text-primary mb-0 mt-1"><?= formatTZS($stats['thisMonthSales']) ?></h3>
                        <div class="small text-muted mt-2">
                            <span>Accounting Period: <strong><?= date('F Y') ?></strong></span>
                            <span class="mx-1">&bull;</span>
                            <a href="<?= APP_URL ?>/admin/reports" class="text-primary text-decoration-none fw-semibold">Audit &rarr;</a>
                        </div>
                    </div>
                    <div class="p-3 bg-primary-subtle text-primary rounded-3 fs-3">
                        <i class="bi bi-calendar3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Total Orders & Actionable Pending -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-dark">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Customer Orders</span>
                        <h3 class="fw-black text-dark mb-0 mt-1"><?= $stats['totalOrders'] ?> <span class="fs-6 fw-normal text-muted">Orders</span></h3>
                        <div class="small text-muted mt-2">
                            <span class="badge bg-warning text-dark"><?= $stats['pendingOrders'] ?> Pending Action</span>
                            <span class="mx-1">&bull;</span>
                            <a href="#orders-hub" class="text-dark fw-semibold text-decoration-none" onclick="switchTab('orders-hub')">Manage &rarr;</a>
                        </div>
                    </div>
                    <div class="p-3 bg-dark-subtle text-dark rounded-3 fs-3">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Row: Order Status Pipeline Breakdown -->
    <div class="row g-3 mb-3">
        <!-- New Orders Today -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white h-100">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-info-subtle text-info rounded"><i class="bi bi-sun fs-5"></i></div>
                    <div>
                        <div class="text-muted small fw-bold" style="font-size: 0.7rem;">NEW TODAY</div>
                        <div class="fs-4 fw-black text-dark"><?= $stats['todayOrders'] ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders Awaiting Action -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white h-100">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-warning-subtle text-warning rounded"><i class="bi bi-hourglass-split fs-5"></i></div>
                    <div>
                        <div class="text-muted small fw-bold" style="font-size: 0.7rem;">PENDING ACTION</div>
                        <div class="fs-4 fw-black text-warning"><?= $stats['pendingOrders'] ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Processing Orders -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white h-100">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary-subtle text-primary rounded"><i class="bi bi-gear-fill fs-5"></i></div>
                    <div>
                        <div class="text-muted small fw-bold" style="font-size: 0.7rem;">PROCESSING</div>
                        <div class="fs-4 fw-black text-primary"><?= $stats['processingOrders'] ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dispatched & Shipped Orders -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white h-100">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-info-subtle text-info rounded"><i class="bi bi-truck fs-5"></i></div>
                    <div>
                        <div class="text-muted small fw-bold" style="font-size: 0.7rem;">DISPATCHED</div>
                        <div class="fs-4 fw-black text-info"><?= $stats['dispatchedOrders'] ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed & Delivered -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white h-100">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-success-subtle text-success rounded"><i class="bi bi-check2-circle fs-5"></i></div>
                    <div>
                        <div class="text-muted small fw-bold" style="font-size: 0.7rem;">COMPLETED</div>
                        <div class="fs-4 fw-black text-success"><?= $stats['completedOrders'] ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancelled Orders -->
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white h-100">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-danger-subtle text-danger rounded"><i class="bi bi-x-circle fs-5"></i></div>
                    <div>
                        <div class="text-muted small fw-bold" style="font-size: 0.7rem;">CANCELLED</div>
                        <div class="fs-4 fw-black text-muted"><?= $stats['cancelledOrders'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tertiary Row: Catalog, Inventory, Valuation & Customer Base -->
    <div class="row g-3 mb-3">
        <!-- Catalog Active Products & Stocked -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Active Products</span>
                        <h3 class="fw-black text-dark mb-0 mt-1"><?= $stats['totalActiveProducts'] ?> <span class="fs-6 fw-normal text-muted">SKUs</span></h3>
                        <div class="small text-muted mt-2">
                            <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> <?= $stats['inStockProducts'] ?> In Stock</span>
                            <span class="mx-1">&bull;</span>
                            <span><?= $stats['totalCategories'] ?> Categories</span>
                        </div>
                    </div>
                    <div class="p-3 bg-info-subtle text-info rounded-3 fs-3">
                        <i class="bi bi-laptop"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Restock Alerts -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-danger">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Stock Attention Alerts</span>
                        <h3 class="fw-black text-danger mb-0 mt-1"><?= $stats['lowStockProducts'] + $stats['outOfStockProducts'] ?> <span class="fs-6 fw-normal text-muted">SKUs</span></h3>
                        <div class="small text-muted mt-2">
                            <span class="text-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i> <?= $stats['outOfStockProducts'] ?> Out</span>
                            <span class="mx-1">&bull;</span>
                            <span class="text-warning fw-bold"><?= $stats['lowStockProducts'] ?> Low</span>
                            <span class="mx-1">&bull;</span>
                            <a href="#inventory-hub" class="text-danger fw-semibold text-decoration-none" onclick="switchTab('inventory-hub')">Restock &rarr;</a>
                        </div>
                    </div>
                    <div class="p-3 bg-danger-subtle text-danger rounded-3 fs-3">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouse Units & Total Retail Valuation -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Warehouse Inventory Value</span>
                        <h3 class="fw-black text-dark mb-0 mt-1"><?= formatTZS($stats['totalRetailValuation']) ?></h3>
                        <div class="small text-muted mt-2">
                            <span>Total Units in Stock: <strong><?= number_format($stats['totalInventoryUnits']) ?></strong> items</span>
                        </div>
                    </div>
                    <div class="p-3 bg-warning-subtle text-warning rounded-3 fs-3">
                        <i class="bi bi-boxes"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registered Customer Directory & Growth -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-secondary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Registered Clients</span>
                        <h3 class="fw-black text-dark mb-0 mt-1"><?= $stats['totalCustomers'] ?> <span class="fs-6 fw-normal text-muted">Clients</span></h3>
                        <div class="small text-muted mt-2">
                            <span class="text-success fw-semibold"><i class="bi bi-person-plus-fill me-1"></i> +<?= $stats['todayCustomers'] ?> Joined Today</span>
                            <span class="mx-1">&bull;</span>
                            <span><strong><?= $stats['repeatCustomersCount'] ?></strong> Repeat Buyers</span>
                        </div>
                    </div>
                    <div class="p-3 bg-secondary-subtle text-secondary rounded-3 fs-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quaternary Row: Payment Workflow & Financial Health Velocity -->
    <div class="row g-3 mb-4">
        <!-- Confirmed Payments -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Verified Payments</span>
                        <h4 class="fw-black text-success mb-0 mt-1"><?= $stats['verifiedPayments'] ?> <span class="fs-6 fw-normal text-muted">Verified</span></h4>
                        <div class="small text-muted mt-2">
                            <span>Receipts confirmed &amp; banked</span>
                        </div>
                    </div>
                    <div class="p-2 bg-success-subtle text-success rounded-3 fs-4">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments Awaiting Verification -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Pending Payments</span>
                        <h4 class="fw-black text-warning mb-0 mt-1"><?= $stats['awaitingPayment'] ?> <span class="fs-6 fw-normal text-muted">Awaiting</span></h4>
                        <div class="small text-muted mt-2">
                            <a href="#orders-hub" class="text-warning fw-semibold text-decoration-none" onclick="switchTab('orders-hub')">Review in Orders &rarr;</a>
                        </div>
                    </div>
                    <div class="p-2 bg-warning-subtle text-warning rounded-3 fs-4">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7-Day & 30-Day Sales Rolling Window -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Rolling Sales Window</span>
                        <h5 class="fw-black text-dark mb-0 mt-1"><?= formatTZS($stats['sales7Days']) ?> <span class="fs-6 fw-normal text-muted">(7D)</span></h5>
                        <div class="small text-muted mt-2">
                            <span>30-Day Volume: <strong><?= formatTZS($stats['sales30Days']) ?></strong></span>
                        </div>
                    </div>
                    <div class="p-2 bg-primary-subtle text-primary rounded-3 fs-4">
                        <i class="bi bi-calendar2-range-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Refunds & Catalog Quality -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-secondary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Refunds &amp; Quality</span>
                        <h4 class="fw-black text-dark mb-0 mt-1"><?= $stats['refundRequests'] ?> <span class="fs-6 fw-normal text-muted">Refunds</span></h4>
                        <div class="small text-muted mt-2">
                            <?php if ($stats['incompleteProducts'] > 0): ?>
                                <span class="text-warning fw-bold"><i class="bi bi-exclamation-circle me-1"></i> <?= $stats['incompleteProducts'] ?> SKUs need details</span>
                            <?php else: ?>
                                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Catalog specs complete</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-2 bg-secondary-subtle text-secondary rounded-3 fs-4">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Command Operations Hub (Tabs System) -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <!-- Navigation Tab Header -->
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs nav-fill card-header-tabs m-0 border-0" id="adminControlTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold py-3 text-dark d-flex align-items-center justify-content-center gap-2" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders-pane" type="button" role="tab">
                        <i class="bi bi-receipt-cutoff text-primary"></i>
                        <span>Orders & WhatsApp Fulfillment</span>
                        <span class="badge bg-primary rounded-pill"><?= count($recentOrders) ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 text-dark d-flex align-items-center justify-content-center gap-2" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory-pane" type="button" role="tab">
                        <i class="bi bi-box-seam text-danger"></i>
                        <span>Stock Alerts & Restock Hub</span>
                        <?php if (!empty($criticalStockProducts)): ?>
                            <span class="badge bg-danger rounded-pill"><?= count($criticalStockProducts) ?></span>
                        <?php endif; ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 text-dark d-flex align-items-center justify-content-center gap-2" id="catalog-tab" data-bs-toggle="tab" data-bs-target="#catalog-pane" type="button" role="tab">
                        <i class="bi bi-tags text-success"></i>
                        <span>Quick Price & Catalog Editor</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 text-dark d-flex align-items-center justify-content-center gap-2" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers-pane" type="button" role="tab">
                        <i class="bi bi-people text-info"></i>
                        <span>Customer Directory</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold py-3 text-dark d-flex align-items-center justify-content-center gap-2" id="system-tab" data-bs-toggle="tab" data-bs-target="#system-pane" type="button" role="tab">
                        <i class="bi bi-gear-wide-connected text-secondary"></i>
                        <span>System Health & Audit Logs</span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content Panes -->
        <div class="card-body p-4 tab-content" id="adminControlTabsContent">
            
            <!-- ========================================== -->
            <!-- PANE 1: Orders & Fulfillment Control Hub   -->
            <!-- ========================================== -->
            <div class="tab-pane fade show active" id="orders-pane" role="tabpanel">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Customer Orders Live Control</h5>
                        <p class="text-muted small mb-0">Confirm payments, transition fulfillment statuses, or contact via WhatsApp</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?= APP_URL ?>/admin/orders" class="btn btn-outline-primary btn-sm fw-semibold">
                            <i class="bi bi-funnel me-1"></i> Advanced Filter & Search &rarr;
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Order #</th>
                                <th>Customer & Location</th>
                                <th>Items</th>
                                <th>Total (TZS)</th>
                                <th>Payment & Gateway</th>
                                <th>Fulfillment Status</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentOrders)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">No orders found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentOrders as $ro): ?>
                                    <tr>
                                        <!-- Order # -->
                                        <td class="ps-3">
                                            <div class="fw-bold font-monospace text-primary">
                                                <a href="<?= APP_URL ?>/admin/orders/detail/<?= $ro['id'] ?>" class="text-decoration-none">
                                                    <?= e($ro['order_number']) ?>
                                                </a>
                                            </div>
                                            <div class="small text-muted" style="font-size: 0.75rem;">
                                                <?= date('d M Y, H:i', strtotime($ro['created_at'])) ?>
                                            </div>
                                        </td>

                                        <!-- Customer -->
                                        <td>
                                            <div class="fw-semibold text-dark"><?= e($ro['customer_name']) ?></div>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <span class="small text-muted font-monospace"><?= e($ro['customer_phone']) ?></span>
                                                <?php
                                                    $cleanPhone = preg_replace('/[^0-9]/', '', $ro['customer_phone']);
                                                    if (str_starts_with($cleanPhone, '0')) {
                                                        $cleanPhone = '255' . substr($cleanPhone, 1);
                                                    }
                                                    $waOrderMsg = urlencode("Habari " . $ro['customer_name'] . ", greetings from Pande Computers Kariakoo. Concerning your Order #" . $ro['order_number'] . "...");
                                                ?>
                                                <a href="https://wa.me/<?= $cleanPhone ?>?text=<?= $waOrderMsg ?>" target="_blank" class="badge bg-success-subtle text-success text-decoration-none" title="Chat on WhatsApp">
                                                    <i class="bi bi-whatsapp"></i> Chat
                                                </a>
                                            </div>
                                        </td>

                                        <!-- Items count -->
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-box me-1"></i> <?= $ro['items_count'] ?? 1 ?> item(s)
                                            </span>
                                        </td>

                                        <!-- Amount -->
                                        <td>
                                            <div class="fw-bold text-dark"><?= formatTZS($ro['total_amount']) ?></div>
                                            <div class="text-muted" style="font-size: 0.7rem;"><?= e($ro['delivery_city'] ?? 'Dar es Salaam') ?></div>
                                        </td>

                                        <!-- Payment Status & Quick Verify -->
                                        <td>
                                            <?php if ($ro['payment_status'] === 'verified'): ?>
                                                <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i> Verified</span>
                                            <?php else: ?>
                                                <div class="d-flex flex-column gap-1">
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Awaiting</span>
                                                    <button type="button" class="btn btn-outline-success btn-sm py-0 px-2 fw-semibold" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal" data-order-id="<?= $ro['id'] ?>" data-order-num="<?= e($ro['order_number']) ?>" data-order-amount="<?= formatTZS($ro['total_amount']) ?>" onclick="prepareVerifyModal(this)">
                                                        <i class="bi bi-shield-check"></i> Verify
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Quick Order Status Dropdown Form -->
                                        <td>
                                            <form action="<?= APP_URL ?>/admin/quick-order-status" method="POST" class="d-flex align-items-center gap-1">
                                                <?= $csrfInput ?>
                                                <input type="hidden" name="order_id" value="<?= $ro['id'] ?>">
                                                <select name="order_status" class="form-select form-select-sm py-1" style="font-size: 0.8rem; width: 130px;" onchange="this.form.submit()">
                                                    <option value="pending" <?= $ro['order_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="confirmed" <?= $ro['order_status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                                    <option value="processing" <?= $ro['order_status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                                    <option value="ready_collection" <?= $ro['order_status'] === 'ready_collection' ? 'selected' : '' ?>>Ready Collection</option>
                                                    <option value="shipped" <?= $ro['order_status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                                    <option value="completed" <?= $ro['order_status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                    <option value="cancelled" <?= $ro['order_status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                </select>
                                            </form>
                                        </td>

                                        <!-- Actions -->
                                        <td class="pe-3 text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= APP_URL ?>/admin/orders/detail/<?= $ro['id'] ?>" class="btn btn-outline-dark" title="View Full Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= APP_URL ?>/admin/orders/invoice/<?= $ro['id'] ?>" target="_blank" class="btn btn-outline-secondary" title="Print Invoice">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- PANE 2: Stock Alerts & Restock Hub         -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="inventory-pane" role="tabpanel">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div>
                        <h5 class="fw-bold text-danger mb-0"><i class="bi bi-exclamation-triangle-fill me-1"></i> Low Stock & Critical Restock Alerts</h5>
                        <p class="text-muted small mb-0">Items requiring immediate stock replenishment to prevent missed sales</p>
                    </div>
                    <a href="<?= APP_URL ?>/admin/inventory" class="btn btn-outline-dark btn-sm fw-semibold">
                        <i class="bi bi-boxes me-1"></i> Open Full Inventory Manager &rarr;
                    </a>
                </div>

                <?php if (empty($criticalStockProducts)): ?>
                    <div class="p-4 text-center bg-light rounded-3">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        <h6 class="fw-bold text-dark mt-2 mb-1">Inventory Levels Healthy</h6>
                        <p class="text-muted small mb-0">All active products meet or exceed safety threshold levels.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th class="ps-3">Product Name</th>
                                    <th>SKU & Category</th>
                                    <th>Selling Price</th>
                                    <th>Current Inventory</th>
                                    <th>Stock Status</th>
                                    <th class="pe-3 text-end">Instant Restock Operations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($criticalStockProducts as $cp): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark"><?= e($cp['name']) ?></div>
                                            <div class="small text-muted"><?= e($cp['brand']) ?></div>
                                        </td>
                                        <td>
                                            <span class="font-monospace small fw-semibold text-secondary"><?= e($cp['sku']) ?></span>
                                            <div class="small text-muted" style="font-size: 0.75rem;"><?= e($cp['category_name']) ?></div>
                                        </td>
                                        <td class="fw-bold"><?= formatTZS($cp['price']) ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fs-5 fw-black <?= (int)$cp['stock_quantity'] === 0 ? 'text-danger' : 'text-warning' ?>">
                                                    <?= $cp['stock_quantity'] ?>
                                                </span>
                                                <span class="text-muted small">/ Threshold: <?= $cp['low_stock_threshold'] ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ((int)$cp['stock_quantity'] === 0): ?>
                                                <span class="badge bg-danger">OUT OF STOCK</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">LOW STOCK</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <div class="d-flex justify-content-end align-items-center gap-1">
                                                <!-- +5 Units -->
                                                <form action="<?= APP_URL ?>/admin/quick-stock-adjust" method="POST" class="d-inline">
                                                    <?= $csrfInput ?>
                                                    <input type="hidden" name="product_id" value="<?= $cp['id'] ?>">
                                                    <input type="hidden" name="adjustment_type" value="add">
                                                    <input type="hidden" name="quantity" value="5">
                                                    <input type="hidden" name="reason" value="Quick restock +5 units">
                                                    <button type="submit" class="btn btn-outline-success btn-sm py-1 px-2 fw-bold" title="Add 5 units">
                                                        +5
                                                    </button>
                                                </form>

                                                <!-- +10 Units -->
                                                <form action="<?= APP_URL ?>/admin/quick-stock-adjust" method="POST" class="d-inline">
                                                    <?= $csrfInput ?>
                                                    <input type="hidden" name="product_id" value="<?= $cp['id'] ?>">
                                                    <input type="hidden" name="adjustment_type" value="add">
                                                    <input type="hidden" name="quantity" value="10">
                                                    <input type="hidden" name="reason" value="Quick restock +10 units">
                                                    <button type="submit" class="btn btn-outline-success btn-sm py-1 px-2 fw-bold" title="Add 10 units">
                                                        +10
                                                    </button>
                                                </form>

                                                <!-- +25 Units -->
                                                <form action="<?= APP_URL ?>/admin/quick-stock-adjust" method="POST" class="d-inline">
                                                    <?= $csrfInput ?>
                                                    <input type="hidden" name="product_id" value="<?= $cp['id'] ?>">
                                                    <input type="hidden" name="adjustment_type" value="add">
                                                    <input type="hidden" name="quantity" value="25">
                                                    <input type="hidden" name="reason" value="Bulk restock +25 units">
                                                    <button type="submit" class="btn btn-success btn-sm py-1 px-2 fw-bold text-white" title="Add 25 units">
                                                        +25
                                                    </button>
                                                </form>

                                                <!-- Custom Modal Trigger -->
                                                <button type="button" class="btn btn-outline-dark btn-sm py-1 px-2" data-bs-toggle="modal" data-bs-target="#customRestockModal" data-product-id="<?= $cp['id'] ?>" data-product-name="<?= e($cp['name']) ?>" data-current-stock="<?= $cp['stock_quantity'] ?>" onclick="prepareStockModal(this)" title="Custom Stock Amount">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>


                <!-- Inventory Movements History Stream -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-arrow-left-right text-primary me-2"></i> Recent Stock Adjustments &amp; Movement History
                        </h6>
                        <a href="<?= APP_URL ?>/admin/inventory" class="small fw-semibold text-primary text-decoration-none">
                            Full Warehouse Ledger &rarr;
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Item / SKU</th>
                                    <th>Operation</th>
                                    <th>Quantity Change</th>
                                    <th>Stock Level Before &rarr; After</th>
                                    <th>Reason / Note</th>
                                    <th class="pe-3 text-end">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentStockMovements)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-3 text-muted">No stock movements recorded yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentStockMovements as $mv): ?>
                                        <tr>
                                            <td class="ps-3 fw-semibold">
                                                <?= e($mv['product_name'] ?? 'Product #' . $mv['product_id']) ?>
                                                <div class="font-monospace text-muted" style="font-size: 0.72rem;"><?= e($mv['sku'] ?? '-') ?></div>
                                            </td>
                                            <td>
                                                <span class="badge <?= match($mv['movement_type'] ?? '') {
                                                    'restock' => 'bg-success-subtle text-success',
                                                    'sale' => 'bg-primary-subtle text-primary',
                                                    'cancellation' => 'bg-info-subtle text-info',
                                                    'adjustment' => 'bg-warning-subtle text-warning',
                                                    default => 'bg-secondary-subtle text-secondary'
                                                } ?> text-uppercase" style="font-size: 0.68rem;">
                                                    <?= e($mv['movement_type'] ?? 'adjustment') ?>
                                                </span>
                                            </td>
                                            <td class="fw-bold font-monospace <?= (int)$mv['quantity_change'] > 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= (int)$mv['quantity_change'] > 0 ? '+' . (int)$mv['quantity_change'] : (int)$mv['quantity_change'] ?> units
                                            </td>
                                            <td class="font-monospace text-muted">
                                                <?= (int)$mv['previous_stock'] ?> &rarr; <strong class="text-dark"><?= (int)$mv['new_stock'] ?></strong>
                                            </td>
                                            <td class="text-muted text-truncate" style="max-width: 250px;">
                                                <?= e($mv['reason'] ?? '-') ?>
                                            </td>
                                            <td class="pe-3 text-end text-muted font-monospace" style="font-size: 0.75rem;">
                                                <?= date('H:i, d M Y', strtotime($mv['created_at'])) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <!-- ========================================== -->
            <!-- PANE 3: Quick Price & Catalog Editor       -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="catalog-pane" role="tabpanel">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Fast Price & Catalog Quick-Updater</h5>
                        <p class="text-muted small mb-0">Adjust retail prices in TZS directly without navigating to full edit pages</p>
                    </div>
                    <a href="<?= APP_URL ?>/admin/products" class="btn btn-outline-dark btn-sm fw-semibold">
                        <i class="bi bi-laptop me-1"></i> Full Catalog Management &rarr;
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Product Name</th>
                                <th>Category & SKU</th>
                                <th>Current Stock</th>
                                <th style="width: 320px;">Price Update (TZS)</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($catalogQuickProducts as $qp): ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark"><?= e($qp['name']) ?></div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-secondary"><?= e($qp['sku']) ?></div>
                                        <div class="small text-muted" style="font-size: 0.75rem;"><?= e($qp['category_name']) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge <?= (int)$qp['stock_quantity'] > 0 ? 'bg-light text-dark border' : 'bg-danger' ?>">
                                            <?= $qp['stock_quantity'] ?> units
                                        </span>
                                    </td>
                                    <td>
                                        <form action="<?= APP_URL ?>/admin/quick-price-update" method="POST" class="d-flex align-items-center gap-1">
                                            <?= $csrfInput ?>
                                            <input type="hidden" name="product_id" value="<?= $qp['id'] ?>">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text py-0 bg-light" style="font-size: 0.75rem;">TZS</span>
                                                <input type="number" name="price" class="form-control form-control-sm py-1 fw-bold" value="<?= (int)$qp['price'] ?>" step="1000" min="0" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm py-1 px-2 fw-semibold" title="Save New Price">
                                                Save
                                            </button>
                                        </form>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <a href="<?= APP_URL ?>/admin/products/edit/<?= $qp['id'] ?>" class="btn btn-outline-secondary btn-sm py-1 px-2" title="Full Product Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- PANE 4: Customer Directory                 -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="customers-pane" role="tabpanel">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Registered Customer Accounts</h5>
                        <p class="text-muted small mb-0">Recent verified clients and contact phone lines</p>
                    </div>
                    <?php if (!empty($isAdmin)): ?>
                        <a href="<?= APP_URL ?>/admin/users" class="btn btn-outline-dark btn-sm fw-semibold">
                            <i class="bi bi-people-fill me-1"></i> Manage All Staff & Users &rarr;
                        </a>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Customer Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Orders Placed</th>
                                <th>Joined On</th>
                                <th class="pe-3 text-end">Contact Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentCustomers as $rc): ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark"><?= e($rc['first_name'] . ' ' . $rc['last_name']) ?></div>
                                    </td>
                                    <td>
                                        <span class="small text-muted"><?= e($rc['email']) ?></span>
                                    </td>
                                    <td>
                                        <span class="small font-monospace fw-semibold text-dark"><?= e($rc['phone']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            <?= $rc['orders_placed'] ?> orders
                                        </span>
                                    </td>
                                    <td>
                                        <span class="small text-muted"><?= date('d M Y', strtotime($rc['created_at'])) ?></span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <?php
                                            $cPhone = preg_replace('/[^0-9]/', '', $rc['phone']);
                                            if (str_starts_with($cPhone, '0')) {
                                                $cPhone = '255' . substr($cPhone, 1);
                                            }
                                        ?>
                                        <a href="https://wa.me/<?= $cPhone ?>?text=<?= urlencode("Habari " . $rc['first_name'] . ", greetings from Pande Computers!") ?>" target="_blank" class="btn btn-outline-success btn-sm py-1 px-2 fw-semibold">
                                            <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- PANE 5: System Health, Backups & Audit Logs -->
            <!-- ========================================== -->
            <div class="tab-pane fade" id="system-pane" role="tabpanel">
                <div class="row g-4">
                    <!-- Left: System Diagnostics & Operations -->
                    <div class="col-lg-5">
                        <div class="card border bg-light rounded-3 p-3 mb-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="bi bi-cpu-fill text-primary me-2"></i> System Diagnostics</h6>
                            <ul class="list-group list-group-flush small bg-transparent">
                                <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                    <span class="text-muted">Environment:</span>
                                    <span class="fw-bold text-dark">Production Ready (PHP <?= PHP_VERSION ?>)</span>
                                </li>
                                <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                    <span class="text-muted">Database Connection:</span>
                                    <span class="fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i> Operational (PDO Connected)</span>
                                </li>
                                <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                    <span class="text-muted">Last Verified Backup:</span>
                                    <?php if (!empty($systemHealth['lastBackupTime'])): ?>
                                        <span class="fw-bold text-success"><i class="bi bi-shield-check me-1"></i> <?= e($systemHealth['lastBackupTime']) ?></span>
                                    <?php else: ?>
                                        <span class="fw-bold text-warning"><i class="bi bi-exclamation-triangle me-1"></i> None recorded</span>
                                    <?php endif; ?>
                                </li>
                                <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                    <span class="text-muted">Storage & Uploads:</span>
                                    <span class="fw-bold text-success"><i class="bi bi-folder-check me-1"></i> Writable (0755)</span>
                                </li>
                                <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                    <span class="text-muted">Security Rails:</span>
                                    <span class="fw-bold text-dark">CSRF &amp; RBAC Active</span>
                                </li>
                                <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                    <span class="text-muted">Currency Engine:</span>
                                    <span class="fw-bold text-dark">TZS (Tanzanian Shillings)</span>
                                </li>
                            </ul>
                        </div>

                        <!-- System Operations Tools -->
                        <div class="card border rounded-3 p-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-tools text-secondary me-2"></i> System Operations</h6>
                            <div class="d-flex flex-column gap-2">
                                <form action="<?= APP_URL ?>/admin/backup-database" method="POST">
                                    <?= $csrfInput ?>
                                    <button type="submit" class="btn btn-outline-primary w-100 text-start btn-sm py-2">
                                        <i class="bi bi-database-down me-2 text-primary"></i> <strong>Create Instant DB Backup</strong>
                                        <div class="text-muted small ps-4">Saves snapshot into storage/backups/</div>
                                    </button>
                                </form>

                                <form action="<?= APP_URL ?>/admin/clear-cache" method="POST">
                                    <?= $csrfInput ?>
                                    <button type="submit" class="btn btn-outline-secondary w-100 text-start btn-sm py-2">
                                        <i class="bi bi-arrow-repeat me-2 text-secondary"></i> <strong>Purge Temporary Logs & Cache</strong>
                                        <div class="text-muted small ps-4">Cleans rotated log buffers and temporary files</div>
                                    </button>
                                </form>

                                <a href="<?= APP_URL ?>/admin/reports/export-orders" class="btn btn-outline-success w-100 text-start btn-sm py-2">
                                    <i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i> <strong>Download All Orders (CSV)</strong>
                                    <div class="text-muted small ps-4">Complete export for bookkeeping and accounting</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Recent Activity Audit Trail -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white border-bottom p-3">
                                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-shield-check text-success me-2"></i> Recent Administrator Activity Audit Trail</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0 small">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Staff Member</th>
                                                <th>Action</th>
                                                <th>Details</th>
                                                <th class="pe-3 text-end">Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($recentActivityLogs)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-muted">No activity logs recorded.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($recentActivityLogs as $log): ?>
                                                    <tr>
                                                        <td class="ps-3 fw-semibold">
                                                            <?= e($log['first_name'] ?? 'System') ?> (<?= ucfirst(e($log['user_role'] ?? 'admin')) ?>)
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-secondary-subtle text-secondary font-monospace"><?= e($log['action']) ?></span>
                                                        </td>
                                                        <td class="text-muted text-truncate" style="max-width: 200px;">
                                                            <?= e($log['details'] ?? '-') ?>
                                                        </td>
                                                        <td class="pe-3 text-end text-muted font-monospace" style="font-size: 0.75rem;">
                                                            <?= date('H:i, d M', strtotime($log['created_at'])) ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ======================================================== -->
    <!-- SECTION 3: Business Intelligence, Sales & Category Trends -->
    <!-- ======================================================== -->
    <div class="row g-4 mb-4">
        <!-- Interactive Sales Performance Chart Card -->
        <div class="col-12 mb-3">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom p-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-graph-up-arrow text-primary me-2"></i> Sales Performance &amp; Order Volume Trend
                        </h5>
                        <p class="text-muted small mb-0">Daily revenue (TZS) and customer order volume over the last 7 active days</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <?php if ($stats['prev7DaysSales'] > 0): ?>
                            <?php 
                                $pctChange = round((($stats['sales7Days'] - $stats['prev7DaysSales']) / $stats['prev7DaysSales']) * 100, 1);
                            ?>
                            <span class="badge <?= $pctChange >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> border">
                                <i class="bi <?= $pctChange >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' ?> me-1"></i>
                                <?= $pctChange >= 0 ? '+' : '' ?><?= $pctChange ?>% vs. Prior 7D
                            </span>
                        <?php else: ?>
                            <span class="badge bg-light text-muted border">Prior 7D: Initial Baseline</span>
                        <?php endif; ?>
                        <span class="badge bg-primary-subtle text-primary fw-bold">Live Data</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <?php if (empty($dailySalesTrend)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-bar-chart-line fs-1 text-muted d-block mb-2"></i>
                            <h6 class="fw-bold text-dark">No Sales Activity In Selected Period</h6>
                            <p class="small text-muted mb-0">Sales charts will render automatically once customer orders are placed and verified.</p>
                        </div>
                    <?php else: ?>
                        <div style="height: 280px; position: relative;">
                            <canvas id="overviewSalesChart"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- 1. Top Best-Selling Products with Visuals & Stock -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-trophy-fill text-warning me-2"></i> Top Best-Selling Products</h5>
                    <a href="<?= APP_URL ?>/admin/reports" class="small fw-semibold text-primary text-decoration-none">Full Report &rarr;</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($bestSellers)): ?>
                        <div class="p-4 text-center text-muted small">
                            <i class="bi bi-bag-x fs-2 text-muted d-block mb-2"></i>
                            Sales ranking will appear after customer orders are recorded and completed.
                        </div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($bestSellers as $idx => $bs): ?>
                                <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge rounded-circle bg-light text-dark fw-bold border" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                                            #<?= $idx + 1 ?>
                                        </span>
                                        <div class="bg-light p-1 rounded border d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <img src="<?= asset($bs['primary_image'] ?? 'assets/images/products/laptop.svg') ?>" alt="<?= e($bs['product_name']) ?>" class="img-fluid" style="max-height: 40px; object-fit: contain;">
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark small text-truncate" style="max-width: 260px;"><?= e($bs['product_name']) ?></h6>
                                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                                <span class="font-monospace fw-semibold text-secondary"><?= e($bs['sku']) ?></span> &bull; 
                                                <span>Units Sold: <strong class="text-dark"><?= $bs['total_sold'] ?></strong></span> &bull; 
                                                <span>Stock: <strong class="<?= (int)($bs['stock_quantity'] ?? 0) <= 3 ? 'text-danger' : 'text-success' ?>"><?= $bs['stock_quantity'] ?? '-' ?></strong> units</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-black text-success fs-6"><?= formatTZS($bs['total_revenue']) ?></div>
                                        <span class="badge bg-success-subtle text-success" style="font-size: 0.68rem;">Completed Sales</span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 2. Category Revenue Breakdown -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-pie-chart-fill text-info me-2"></i> Sales Distribution by Category</h5>
                    <span class="badge bg-info-subtle text-info">Live Metrics</span>
                </div>
                <div class="card-body p-3">
                    <?php if (empty($salesByCategory)): ?>
                        <div class="p-4 text-center text-muted small">No category revenue data yet.</div>
                    <?php else: ?>
                        <?php foreach ($salesByCategory as $sc): ?>
                            <?php 
                                $pct = $stats['totalSales'] > 0 ? round(((float)$sc['category_revenue'] / $stats['totalSales']) * 100, 1) : 0;
                            ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small fw-semibold mb-1">
                                    <span><?= e($sc['category_name']) ?></span>
                                    <span><?= formatTZS($sc['category_revenue']) ?> <span class="text-muted fw-normal">(<?= $pct ?>%)</span></span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= max(5, $pct) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Intelligence Row: Daily Sales Velocity, Monthly Periods & Payment Rails -->
    <div class="row g-4 mb-4">
        <!-- Daily Sales Trend (Recent 7 days) -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-graph-up text-success me-2"></i> Daily Sales (Recent 7 Days)</h6>
                    <span class="badge bg-success-subtle text-success">Live Trend</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Orders</th>
                                <th class="pe-3 text-end">Revenue (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dailySalesTrend)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No sales in this window.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($dailySalesTrend as $dst): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-dark">
                                            <?= date('D, d M', strtotime($dst['sale_date'])) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary fw-bold"><?= $dst['orders_count'] ?></span>
                                        </td>
                                        <td class="pe-3 text-end fw-black text-success">
                                            <?= formatTZS($dst['daily_revenue']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Monthly Performance Trend (Last 6 Months) -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar3 text-primary me-2"></i> Monthly Performance Trend</h6>
                    <span class="badge bg-primary-subtle text-primary">6 Months</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Month</th>
                                <th>Volume</th>
                                <th class="pe-3 text-end">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($monthlyPerformance)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No monthly sales data yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($monthlyPerformance as $mp): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-dark">
                                            <?= date('F Y', strtotime($mp['period_month'] . '-01')) ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary"><?= $mp['orders_count'] ?> orders</span>
                                        </td>
                                        <td class="pe-3 text-end fw-black text-primary">
                                            <?= formatTZS($mp['month_revenue']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Channel Distribution -->
        <div class="col-xl-4 col-md-12">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-wallet-fill text-primary me-2"></i> Payment Gateways</h6>
                    <span class="badge bg-primary-subtle text-primary">TZ Shillings</span>
                </div>
                <div class="card-body p-3">
                    <?php if (empty($paymentMethodStats)): ?>
                        <div class="p-4 text-center text-muted small">No payment records yet.</div>
                    <?php else: ?>
                        <?php foreach ($paymentMethodStats as $pm): ?>
                            <?php 
                                $pct = $stats['totalSales'] > 0 ? round(((float)$pm['total_amount'] / $stats['totalSales']) * 100, 1) : 0;
                            ?>
                            <div class="p-2 mb-2 bg-light rounded-2 border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small"><i class="bi bi-check-circle text-success me-1"></i> <?= e($pm['payment_method']) ?></span>
                                    <span class="badge bg-secondary-subtle text-secondary small"><?= $pm['count'] ?> Orders</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center small text-muted">
                                    <span>Collected: <strong><?= formatTZS($pm['total_amount']) ?></strong></span>
                                    <span class="fw-semibold text-primary"><?= $pct ?>%</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- ======================================================== -->
    <!-- DEDICATED FRONT HOMEPAGE HERO VISUALS & MEDIA HUB        -->
    <!-- (Strictly for managing public homepage hero banners)     -->
    <!-- ======================================================== -->
<!-- Frontend Hero Content & Visual Images Control Hub -->
    <!-- Dedicated Multipart Form for Local Computer Image Uploads -->
    <div class="collapse mb-4" id="heroContentEditor">
        <div class="card border-0 shadow-sm rounded-3 bg-white border-top border-4 border-warning">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 bg-warning-subtle text-warning rounded-2 fs-5"><i class="bi bi-palette-fill"></i></span>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Front Homepage Hero Visuals &amp; Content Control Hub</h5>
                        <p class="text-muted small mb-0">Upload custom hero photographs, select graphics, and customize headlines &amp; promotional CTAs</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#heroContentEditor" aria-label="Close"></button>
            </div>
            <div class="card-body p-4">
                
                <!-- SECTION 1: DEDICATED DIRECT COMPUTER IMAGE UPLOAD -->
                <div class="card border-primary border-opacity-25 bg-primary bg-opacity-10 rounded-3 mb-4 shadow-sm">
                    <div class="card-body p-4">
                        <form id="heroUploadForm" action="<?= APP_URL ?>/admin/upload-hero-image" method="POST" enctype="multipart/form-data">
                            <?= $csrfInput ?>
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="p-2 bg-primary text-white rounded-2 fs-5"><i class="bi bi-cloud-arrow-up-fill"></i></span>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Upload New Hero Image from Computer</h6>
                                        <small class="text-muted">Upload high-resolution photography or banners (JPG, PNG, WebP, SVG &bull; Max 8MB)</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary text-white px-3 py-2"><i class="bi bi-laptop me-1"></i> Direct Local Computer Upload</span>
                            </div>

                            <!-- Target Selector -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark d-block mb-2">Apply Uploaded Image As:</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="btn btn-outline-primary w-100 text-start p-2 d-flex align-items-center gap-2" for="targetShowcase">
                                            <input type="radio" name="hero_upload_target" id="targetShowcase" value="showcase" checked class="form-check-input mt-0">
                                            <div>
                                                <div class="fw-bold small"><i class="bi bi-display me-1"></i> Hero Product Showcase (Right)</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Main prominent computer hardware photography</div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="btn btn-outline-primary w-100 text-start p-2 d-flex align-items-center gap-2" for="targetBackground">
                                            <input type="radio" name="hero_upload_target" id="targetBackground" value="background" class="form-check-input mt-0">
                                            <div>
                                                <div class="fw-bold small"><i class="bi bi-card-image me-1"></i> Section Background Wallpaper</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Atmospheric textured or gradient background</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- File Selector & Live Preview -->
                            <div class="p-3 bg-white rounded-3 border">
                                <input type="file" id="heroNativeFileInput" name="hero_image_file" accept="image/jpeg,image/jpg,image/png,image/webp,image/svg+xml,image/avif" class="d-none" onchange="handleHeroFileSelect(this)">
                                
                                <div id="heroUploadEmptyState" class="text-center py-3">
                                    <i class="bi bi-images text-primary fs-2 d-block mb-2"></i>
                                    <p class="small text-muted mb-2">Select a computer photograph, banner, or promotional graphic from your drive</p>
                                    <button type="button" class="btn btn-primary btn-sm px-4 fw-bold shadow-sm" onclick="document.getElementById('heroNativeFileInput').click()">
                                        <i class="bi bi-folder-symlink me-1"></i> Browse &amp; Select Image File
                                    </button>
                                </div>

                                <div id="heroUploadPreviewState" class="d-none">
                                    <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-2 border mb-3">
                                        <div class="bg-white p-1 rounded border shadow-sm" style="width: 90px; height: 65px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            <img id="heroFilePreviewImg" src="" alt="Selected Local Image" class="img-fluid" style="max-height: 60px; max-width: 100%; object-fit: contain;">
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="d-flex align-items-center gap-2">
                                                <strong id="heroSelectedFileName" class="text-dark text-truncate small d-block">filename.jpg</strong>
                                                <span id="heroSelectedFileType" class="badge bg-info-subtle text-info border small">PNG</span>
                                            </div>
                                            <div class="small text-muted" id="heroSelectedFileSize">0 KB</div>
                                            <div class="text-success small fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Ready to upload and activate immediately</div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('heroNativeFileInput').click()">
                                                <i class="bi bi-arrow-repeat me-1"></i> Choose Different File
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearHeroFileSelection()">
                                                <i class="bi bi-x-circle me-1"></i> Cancel
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm fw-bold px-4 shadow-sm">
                                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload &amp; Activate on Homepage
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- SECTION 2: HEADLINES, COPY, BUTTONS & PRESET GRAPHICS -->
                <form action="<?= APP_URL ?>/admin/quick-hero-settings" method="POST">
                    <?= $csrfInput ?>
                    <div class="row g-4">
                        <!-- Left Column: Copy & Text Content -->
                        <div class="col-lg-6 border-end-lg">
                            <h6 class="fw-bold text-navy mb-3"><i class="bi bi-fonts me-1"></i> Headlines &amp; Front Content</h6>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Hero Pill Badge</label>
                                <input type="text" name="hero_badge" class="form-control" 
                                       value="<?= e($heroConfig['badge'] ?? 'Authorized Tanzanian IT Retailer') ?>" required>
                                <div class="form-text small">Top micro badge displayed above headline.</div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Headline Line 1</label>
                                    <input type="text" name="hero_title_line1" class="form-control fw-bold" 
                                           value="<?= e($heroConfig['title1'] ?? 'YOUR TECH.') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Headline Line 2 (Accent Color)</label>
                                    <input type="text" name="hero_title_line2" class="form-control fw-bold text-primary" 
                                           value="<?= e($heroConfig['title2'] ?? 'YOUR WAY.') ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Supporting Subtitle Text</label>
                                <textarea name="hero_subtitle" rows="3" class="form-control"><?= e($heroConfig['subtitle'] ?? 'Shop laptops, desktops, components and IT essentials online. Genuine computing hardware delivered across Dar es Salaam and nationwide.') ?></textarea>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Primary Button Label</label>
                                    <input type="text" name="hero_btn_primary_text" class="form-control" 
                                           value="<?= e($heroConfig['btnPrimary'] ?? 'Shop Now') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Primary Button Link URL</label>
                                    <input type="text" name="hero_btn_primary_url" class="form-control font-monospace small" 
                                           value="<?= e($heroConfig['btnPrimaryUrl'] ?? APP_URL . '/shop') ?>" required>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Secondary Button Label</label>
                                    <input type="text" name="hero_btn_secondary_text" class="form-control" 
                                           value="<?= e($heroConfig['btnSecondary'] ?? 'Explore Categories') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Secondary Button Link URL</label>
                                    <input type="text" name="hero_btn_secondary_url" class="form-control font-monospace small" 
                                           value="<?= e($heroConfig['btnSecondaryUrl'] ?? '#browse-categories') ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Visual Images & Preset Artworks -->
                        <div class="col-lg-6">
                            <h6 class="fw-bold text-navy mb-3"><i class="bi bi-images me-1"></i> Active Visuals &amp; Preset Switcher</h6>

                            <!-- Showcase Product Image -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Hero Product Showcase Picture / Graphic Path</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light"><i class="bi bi-display"></i></span>
                                    <input type="text" id="heroShowcaseInput" name="hero_showcase_image" class="form-control font-monospace" 
                                           value="<?= e($heroConfig['showcaseImage'] ?? 'assets/images/hero-products-showcase.svg') ?>" required>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm fw-semibold" onclick="document.getElementById('heroShowcaseInput').value = 'src/assets/images/regenerated_image_1791810734527.webp'">
                                        <i class="bi bi-stars text-warning me-1"></i> Studio Tech Hardware Photography
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('heroShowcaseInput').value = 'assets/images/hero-products-showcase.svg'">
                                        Default Multi-Product Graphic
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('heroShowcaseInput').value = 'assets/images/hero-tech-tanzania.svg'">
                                        Tanzania Showroom Artwork
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('heroShowcaseInput').value = 'assets/images/products/laptop.svg'">
                                        Ultrabook Spotlight
                                    </button>
                                </div>
                            </div>

                            <!-- Hero Background Image -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Hero Section Background Image / Ambient Texture</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light"><i class="bi bi-card-image"></i></span>
                                    <input type="text" id="heroBgInput" name="hero_bg_image" class="form-control font-monospace" 
                                           value="<?= e($heroConfig['bgImage'] ?? 'assets/images/hero-tech-bg.svg') ?>">
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('heroBgInput').value = 'assets/images/hero-tech-bg.svg'">
                                        High-Tech Circuit SVG (Light)
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('heroBgInput').value = 'assets/images/hero-tech-tanzania.svg'">
                                        Atmospheric Tech Showroom (Deep)
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('heroBgInput').value = ''">
                                        Pure Crisp White Background
                                    </button>
                                </div>
                            </div>

                            <!-- Live Preview Card Box -->
                            <div class="p-3 bg-light rounded-3 border mt-3">
                                <div class="small fw-bold text-muted mb-2 text-uppercase" style="font-size: 0.72rem;">Current Graphic Asset Preview</div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-white p-2 rounded border" style="width: 100px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                        <img src="<?= asset($heroConfig['showcaseImage'] ?? 'assets/images/hero-products-showcase.svg') ?>" 
                                             alt="Showcase Preview" class="img-fluid" style="max-height: 55px; object-fit: contain;">
                                    </div>
                                    <div class="small">
                                        <strong class="d-block text-dark">Active Front Visual</strong>
                                        <span class="text-muted font-monospace" style="font-size: 0.75rem;"><?= e($heroConfig['showcaseImage'] ?? 'assets/images/hero-products-showcase.svg') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#heroContentEditor">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="bi bi-save me-1"></i> Save &amp; Publish Front Content
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
<!-- ======================================================== -->
<!-- MODAL: Quick Verify Payment                             -->
<!-- ======================================================== -->
<div class="modal fade" id="verifyPaymentModal" tabindex="-1" aria-labelledby="verifyPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= APP_URL ?>/admin/quick-verify-payment" method="POST">
                <?= $csrfInput ?>
                <input type="hidden" name="order_id" id="modalOrderId" value="">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="verifyPaymentModalLabel">
                        <i class="bi bi-shield-check me-2"></i> Verify Order Payment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Confirm receipt of customer funds via Mobile Money or Kariakoo store cash collection.
                    </p>

                    <div class="bg-light p-3 rounded-3 mb-3">
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Order Number:</span>
                            <strong class="font-monospace text-primary" id="modalOrderNumber">-</strong>
                        </div>
                        <div class="d-flex justify-content-between small mt-1">
                            <span class="text-muted">Total Payable:</span>
                            <strong class="text-dark" id="modalOrderAmount">-</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Payment Channel</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="M-Pesa (Vodacom)">M-Pesa (Vodacom) - 0787 311 342</option>
                            <option value="Tigo Pesa">Tigo Pesa</option>
                            <option value="Airtel Money">Airtel Money</option>
                            <option value="Halopesa">Halopesa</option>
                            <option value="Cash on Delivery / In-Store">Cash on Delivery / Store Pickup</option>
                            <option value="Bank Transfer (NMB / CRDB)">Bank Transfer (NMB / CRDB)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Transaction Reference / Receipt #</label>
                        <input type="text" name="payment_reference" class="form-control" placeholder="e.g. 9BH2309KJ1 / Cash receipt" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Confirm & Verify Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Custom Stock Adjust                               -->
<!-- ======================================================== -->
<div class="modal fade" id="customRestockModal" tabindex="-1" aria-labelledby="customRestockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= APP_URL ?>/admin/quick-stock-adjust" method="POST">
                <?= $csrfInput ?>
                <input type="hidden" name="product_id" id="modalStockProductId" value="">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="customRestockModalLabel">
                        <i class="bi bi-boxes me-2"></i> Adjust Stock Inventory
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-0">Product</label>
                        <h6 class="fw-bold text-dark" id="modalStockProductName">-</h6>
                        <span class="small text-muted">Current Stock: <strong id="modalStockCurrent">-</strong> units</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Adjustment Operation</label>
                        <select name="adjustment_type" class="form-select" required>
                            <option value="add">Add to current stock (+)</option>
                            <option value="set">Set exact stock level (=)</option>
                            <option value="subtract">Deduct from current stock (-)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="0" required placeholder="e.g. 20">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Adjustment Reason</label>
                        <input type="text" name="reason" class="form-control" placeholder="e.g. Container delivery from port / Physical audit" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="bi bi-check2 me-1"></i> Apply Stock Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prepareVerifyModal(btn) {
    document.getElementById('modalOrderId').value = btn.getAttribute('data-order-id');
    document.getElementById('modalOrderNumber').textContent = btn.getAttribute('data-order-num');
    document.getElementById('modalOrderAmount').textContent = btn.getAttribute('data-order-amount');
}

function prepareStockModal(btn) {
    document.getElementById('modalStockProductId').value = btn.getAttribute('data-product-id');
    document.getElementById('modalStockProductName').textContent = btn.getAttribute('data-product-name');
    document.getElementById('modalStockCurrent').textContent = btn.getAttribute('data-current-stock');
}

function switchTab(tabId) {
    const triggerEl = document.querySelector('#' + tabId.replace('-hub', '-tab'));
    if (triggerEl) {
        const tab = bootstrap.Tab.getOrCreateInstance(triggerEl);
        tab.show();
    }
}

// Native Hero Image File Upload Preview & Handling
function handleHeroFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate MIME / extension client-side
        const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPG, JPEG, PNG, or WebP).');
            input.value = '';
            return;
        }

        // Validate max size 8MB
        if (file.size > 8 * 1024 * 1024) {
            alert('Selected image exceeds the 8MB maximum size limit.');
            input.value = '';
            return;
        }

        document.getElementById('heroSelectedFileName').textContent = file.name;
        document.getElementById('heroSelectedFileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
        document.getElementById('heroSelectedFileType').textContent = file.type.split('/')[1].toUpperCase();

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('heroFilePreviewImg').src = e.target.result;
            document.getElementById('heroUploadEmptyState').classList.add('d-none');
            document.getElementById('heroUploadPreviewState').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
}

function clearHeroFileSelection() {
    const input = document.getElementById('heroNativeFileInput');
    if (input) input.value = '';
    document.getElementById('heroFilePreviewImg').src = '';
    document.getElementById('heroUploadEmptyState').classList.remove('d-none');
    document.getElementById('heroUploadPreviewState').classList.add('d-none');
}

// Auto open tab or hero collapse based on URL hash

window.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash;
    if (hash === '#heroContentEditor') {
        const heroCollapse = document.getElementById('heroContentEditor');
        if (heroCollapse) {
            const bsCollapse = bootstrap.Collapse.getOrCreateInstance(heroCollapse);
            bsCollapse.show();
            heroCollapse.scrollIntoView({ behavior: 'smooth' });
        }
    }
    if (hash) {
        const tabMap = {
            '#orders-hub': 'orders-tab',
            '#inventory-hub': 'inventory-tab',
            '#catalog-hub': 'catalog-tab',
            '#customers-hub': 'customers-tab',
            '#system-hub': 'system-tab',
        };
        const tabId = tabMap[hash];
        if (tabId) {
            const triggerEl = document.getElementById(tabId);
            if (triggerEl) {
                bootstrap.Tab.getOrCreateInstance(triggerEl).show();
            }
        }
    }
});

// Render Sales Performance Chart via Chart.js
window.addEventListener('DOMContentLoaded', () => {
    const chartCanvas = document.getElementById('overviewSalesChart');
    if (!chartCanvas || typeof Chart === 'undefined') return;

    <?php
        $chartLabels = [];
        $chartRevenues = [];
        $chartVolumes = [];
        foreach ($dailySalesTrend as $d) {
            $chartLabels[] = date('D, d M', strtotime($d['sale_date']));
            $chartRevenues[] = (float)$d['daily_revenue'];
            $chartVolumes[] = (int)$d['orders_count'];
        }
    ?>

    const labels = <?= json_encode($chartLabels) ?>;
    const revenues = <?= json_encode($chartRevenues) ?>;
    const volumes = <?= json_encode($chartVolumes) ?>;

    new Chart(chartCanvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Gross Revenue (TZS)',
                    data: revenues,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'y'
                },
                {
                    label: 'Orders Count',
                    data: volumes,
                    borderColor: '#10b981',
                    backgroundColor: '#10b981',
                    borderWidth: 2,
                    type: 'bar',
                    yAxisID: 'y1',
                    barThickness: 24
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        callback: function(value) {
                            return (value / 1000000).toFixed(1) + 'M TZS';
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            family: 'system-ui, -apple-system, sans-serif',
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.yAxisID === 'y') {
                                return 'Revenue: TZS ' + new Intl.NumberFormat().format(context.parsed.y);
                            } else {
                                return 'Orders: ' + context.parsed.y + ' orders';
                            }
                        }
                    }
                }
            }
        }
    });
});
</script>
