<div class="container-fluid py-2">
    <!-- Top Executive Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-navy text-white px-2 py-1"><i class="bi bi-shield-lock-fill text-warning me-1"></i> Admin Console</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Store Active
                </span>
            </div>
            <h2 class="fw-black text-dark mb-0 mt-1">Executive Admin Command Center</h2>
            <p class="text-muted small mb-0">Pande Computers Retail Operations &bull; Kariakoo, Dar es Salaam &bull; Live Control Hub</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= APP_URL ?>/admin/products/create" class="btn btn-primary fw-semibold btn-sm shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Product
            </a>
            <a href="<?= APP_URL ?>/admin/orders" class="btn btn-outline-dark fw-semibold btn-sm">
                <i class="bi bi-receipt me-1"></i> All Orders
            </a>
            <a href="<?= APP_URL ?>/admin/reports/export-orders" class="btn btn-outline-success fw-semibold btn-sm">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
            </a>
            <form action="<?= APP_URL ?>/admin/backup-database" method="POST" class="d-inline">
                <?= $csrfInput ?>
                <button type="submit" class="btn btn-outline-secondary fw-semibold btn-sm" onclick="return confirm('Create a timestamped backup of the database now?')">
                    <i class="bi bi-database-down me-1"></i> Backup DB
                </button>
            </form>
            <a href="<?= APP_URL ?>/" target="_blank" class="btn btn-dark fw-semibold btn-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> Live Store
            </a>
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

    <!-- Executive KPI Grid (6 Metric Cards) -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Verified Sales -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Gross Sales (TZS)</span>
                        <h3 class="fw-black text-dark mb-0 mt-1"><?= formatTZS($stats['totalSales']) ?></h3>
                        <div class="small text-muted mt-2">
                            <span class="text-success fw-semibold"><i class="bi bi-graph-up-arrow me-1"></i> Today: <?= formatTZS($stats['todaySales']) ?></span>
                            <span class="mx-1">&bull;</span>
                            <span>This Month: <?= formatTZS($stats['thisMonthSales']) ?></span>
                        </div>
                    </div>
                    <div class="p-3 bg-success-subtle text-success rounded-3 fs-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Total Orders & Fulfillment Status -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Customer Orders</span>
                        <h3 class="fw-black text-dark mb-0 mt-1"><?= $stats['totalOrders'] ?> <span class="fs-6 fw-normal text-muted">Orders</span></h3>
                        <div class="small text-muted mt-2">
                            <span class="badge bg-success-subtle text-success"><?= $stats['completedOrders'] ?> Completed</span>
                            <span class="badge bg-primary-subtle text-primary"><?= $stats['processingOrders'] ?> In Progress</span>
                            <span class="badge bg-secondary-subtle text-secondary"><?= $stats['pendingOrders'] ?> Pending</span>
                        </div>
                    </div>
                    <div class="p-3 bg-primary-subtle text-primary rounded-3 fs-3">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Awaiting Payment Verification -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Awaiting Payment</span>
                        <h3 class="fw-black text-warning mb-0 mt-1"><?= $stats['awaitingPayment'] ?> <span class="fs-6 fw-normal text-muted">Orders</span></h3>
                        <div class="small text-muted mt-2">
                            <a href="#orders-hub" class="text-decoration-none fw-semibold text-warning" onclick="switchTab('orders-hub')">
                                <i class="bi bi-whatsapp me-1"></i> Verify Mobile Money Orders &rarr;
                            </a>
                        </div>
                    </div>
                    <div class="p-3 bg-warning-subtle text-warning rounded-3 fs-3">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Critical Stock Alerts -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-danger">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Critical Stock Alerts</span>
                        <h3 class="fw-black text-danger mb-0 mt-1"><?= $stats['lowStockProducts'] + $stats['outOfStockProducts'] ?> <span class="fs-6 fw-normal text-muted">SKUs</span></h3>
                        <div class="small text-muted mt-2">
                            <span class="text-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i> <?= $stats['outOfStockProducts'] ?> Out of stock</span>
                            <span class="mx-1">&bull;</span>
                            <span><?= $stats['lowStockProducts'] ?> Low stock</span>
                            <a href="#inventory-hub" class="text-decoration-none ms-1 text-danger fw-semibold" onclick="switchTab('inventory-hub')">Restock &rarr;</a>
                        </div>
                    </div>
                    <div class="p-3 bg-danger-subtle text-danger rounded-3 fs-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Active Catalog & Categories -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Active Catalog</span>
                        <h3 class="fw-black text-dark mb-0 mt-1"><?= $stats['activeProducts'] ?> <span class="fs-6 fw-normal text-muted">Items</span></h3>
                        <div class="small text-muted mt-2">
                            <span>Organized in <strong><?= $stats['totalCategories'] ?></strong> Categories</span>
                            <span class="mx-1">&bull;</span>
                            <a href="<?= APP_URL ?>/admin/products" class="text-info text-decoration-none fw-semibold">View SKUs &rarr;</a>
                        </div>
                    </div>
                    <div class="p-3 bg-info-subtle text-info rounded-3 fs-3">
                        <i class="bi bi-laptop"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Customers & Staff Accounts -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-secondary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Customers & Staff</span>
                        <h3 class="fw-black text-dark mb-0 mt-1"><?= $stats['totalCustomers'] ?> <span class="fs-6 fw-normal text-muted">Clients</span></h3>
                        <div class="small text-muted mt-2">
                            <span><strong><?= $stats['totalStaff'] ?></strong> Active Staff Members</span>
                            <span class="mx-1">&bull;</span>
                            <a href="#customers-hub" class="text-secondary text-decoration-none fw-semibold" onclick="switchTab('customers-hub')">Directory &rarr;</a>
                        </div>
                    </div>
                    <div class="p-3 bg-secondary-subtle text-secondary rounded-3 fs-3">
                        <i class="bi bi-people-fill"></i>
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
                                <th>Customer & Contact</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status Transition</th>
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
                                    <th>Unit Price</th>
                                    <th>Current Stock</th>
                                    <th>Status Alert</th>
                                    <th class="pe-3 text-end">Quick Restock Actions</th>
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
                                    <span class="text-muted">Database Storage:</span>
                                    <span class="fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i> SQLite Active</span>
                                </li>
                                <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                    <span class="text-muted">Storage & Logs:</span>
                                    <span class="fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i> Writable (755)</span>
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

    <!-- Analytics & Intelligence Row: Best Sellers & Sales by Category -->
    <div class="row g-4">
        <!-- Top Best-Selling Products -->
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-trophy-fill text-warning me-2"></i> Top 5 Best-Selling Hardware Items</h5>
                    <a href="<?= APP_URL ?>/admin/reports" class="small fw-semibold text-primary text-decoration-none">Full Report &rarr;</a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($bestSellers)): ?>
                        <div class="p-4 text-center text-muted small">No completed sales recorded yet.</div>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($bestSellers as $idx => $bs): ?>
                                <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge rounded-circle bg-light text-dark fw-bold border p-2" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            #<?= $idx + 1 ?>
                                        </span>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark small"><?= e($bs['product_name']) ?></h6>
                                            <span class="small text-muted">SKU: <?= e($bs['sku']) ?> &bull; Units Sold: <strong><?= $bs['total_sold'] ?></strong></span>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success fw-bold fs-6"><?= formatTZS($bs['total_revenue']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Category Revenue Breakdown -->
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

// Auto open tab based on URL hash
window.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash;
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
</script>
