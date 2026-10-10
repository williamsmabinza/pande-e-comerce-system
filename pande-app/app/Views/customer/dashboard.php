<div class="container py-4">
    <!-- Customer Header Profile Banner -->
    <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
        <div class="row align-items-center g-3">
            <div class="col-md-auto text-center text-md-start">
                <div class="position-relative d-inline-block">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= APP_URL . '/' . e($user['avatar']) ?>" 
                             alt="<?= e($user['first_name']) ?>" 
                             class="rounded-circle object-fit-cover border border-2 border-primary shadow-sm" 
                             style="width: 84px; height: 84px;">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary-subtle text-primary border border-2 border-primary d-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 84px; height: 84px; font-size: 2.2rem; font-weight: 700;">
                            <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <a href="<?= APP_URL ?>/customer/profile" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1 border border-2 border-white small" title="Change Photo" style="line-height: 1; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-camera-fill" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>

            <div class="col-md">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h3 class="fw-bold text-dark mb-0"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></h3>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small px-2 py-1">
                        <i class="bi bi-patch-check-fill me-1"></i> Verified Customer
                    </span>
                    <span class="badge bg-light text-muted border small"><?= ucfirst(e($user['role'] ?? 'customer')) ?></span>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
                    <span><i class="bi bi-envelope text-primary me-1"></i> <?= e($user['email']) ?></span>
                    <span><i class="bi bi-telephone text-success me-1"></i> <?= e($user['phone']) ?></span>
                    <span><i class="bi bi-calendar-event me-1"></i> Member since <?= !empty($user['created_at']) ? date('M Y', strtotime($user['created_at'])) : '2024' ?></span>
                </div>
            </div>

            <div class="col-md-auto d-flex flex-wrap gap-2 justify-content-md-end">
                <a href="<?= APP_URL ?>/customer/profile" class="btn btn-outline-secondary btn-sm fw-semibold">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profile & Photo
                </a>
                <a href="<?= APP_URL ?>/customer/addresses" class="btn btn-outline-primary btn-sm fw-semibold">
                    <i class="bi bi-geo-alt me-1"></i> Delivery Addresses
                </a>
                <a href="<?= APP_URL ?>/shop" class="btn btn-primary btn-sm fw-bold">
                    <i class="bi bi-cart-plus me-1"></i> Shop Tech
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Metrics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary p-3 fs-4">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Orders</span>
                        <h4 class="fw-bold mb-0 text-dark"><?= $totalOrders ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning-subtle text-dark p-3 fs-4">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Pending / Active</span>
                        <h4 class="fw-bold mb-0 text-dark"><?= $pendingOrders ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success-subtle text-success p-3 fs-4">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Delivered</span>
                        <h4 class="fw-bold mb-0 text-dark"><?= $completedOrders ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info-subtle text-info-emphasis p-3 fs-4">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Spent</span>
                        <h5 class="fw-bold mb-0 text-dark"><?= formatTZS($totalSpent) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact, Location & Store Support Hub -->
    <div class="row g-4 mb-4">
        <!-- My Delivery Location & Contact Card -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i>My Delivery Address & Contact
                    </h5>
                    <a href="<?= APP_URL ?>/customer/addresses" class="small fw-semibold text-primary text-decoration-none">
                        Manage (<?= count($addresses) ?>) &rarr;
                    </a>
                </div>

                <?php if ($defaultAddress): ?>
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-0"><?= e($defaultAddress['recipient_name']) ?></h6>
                                <span class="badge bg-primary-subtle text-primary small">Default Shipping Address</span>
                            </div>
                            <a href="<?= APP_URL ?>/customer/addresses" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.8rem;">Edit</a>
                        </div>
                        <div class="small text-secondary mb-1">
                            <i class="bi bi-geo-alt me-1 text-danger"></i> <?= e($defaultAddress['street_address']) ?>, <?= e($defaultAddress['district']) ?>, <?= e($defaultAddress['region_city']) ?>
                        </div>
                        <?php if (!empty($defaultAddress['landmark'])): ?>
                            <div class="small text-muted mb-1">
                                <i class="bi bi-pin-map me-1 text-primary"></i> Landmark: <?= e($defaultAddress['landmark']) ?>
                            </div>
                        <?php endif; ?>
                        <div class="small text-dark mt-2 pt-1 border-top">
                            <i class="bi bi-telephone me-1 text-success"></i> Delivery Contact Phone: <strong><?= e($defaultAddress['phone']) ?></strong>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-3 bg-light rounded-3 text-center text-muted mb-3">
                        <p class="small mb-2">You haven't set up a delivery location yet.</p>
                        <a href="<?= APP_URL ?>/customer/addresses" class="btn btn-sm btn-outline-primary fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i> Add Delivery Address
                        </a>
                    </div>
                <?php endif; ?>

                <div class="row g-2 small text-muted">
                    <div class="col-sm-6">
                        <div class="p-2 border rounded bg-white">
                            <span class="d-block text-secondary fw-semibold">Contact Email</span>
                            <span class="text-dark fw-bold text-truncate d-block"><?= e($user['email']) ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-2 border rounded bg-white">
                            <span class="d-block text-secondary fw-semibold">WhatsApp Number</span>
                            <span class="text-dark fw-bold"><?= e($user['phone']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pande Computers Official Store & Support Contact -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-shop text-primary me-2"></i>Pande Computers Store & Support
                    </h5>
                    <span class="badge bg-success-subtle text-success">Open & Ready</span>
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-geo-fill text-danger fs-5 mt-1"></i>
                        <div>
                            <span class="fw-bold text-dark d-block">Physical Showroom & Order Pickup:</span>
                            <span class="small text-secondary"><?= e($storeLocation) ?></span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-clock-history text-muted fs-5 mt-1"></i>
                        <div>
                            <span class="fw-bold text-dark d-block">Working Hours:</span>
                            <span class="small text-secondary">Mon – Sat: 8:30 AM – 6:30 PM | Sunday: Closed</span>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <a href="https://wa.me/255787311342?text=<?= urlencode('Hello Pande Computers, I have an inquiry regarding my account or order (' . $user['email'] . ')') ?>" 
                           target="_blank" 
                           class="btn btn-outline-success btn-sm w-100 fw-semibold py-2 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-whatsapp fs-6"></i> WhatsApp: <?= e($storeWhatsApp) ?>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $storePhone) ?>" 
                           class="btn btn-outline-dark btn-sm w-100 fw-semibold py-2 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-telephone-outbound fs-6"></i> Call: <?= e($storePhone) ?>
                        </a>
                    </div>
                </div>

                <div class="p-2 px-3 bg-light rounded-3 small text-muted d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-envelope me-1 text-primary"></i> Support Email:</span>
                    <a href="mailto:<?= e($storeEmail) ?>" class="text-primary text-decoration-none fw-bold"><?= e($storeEmail) ?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i>Recent Orders
            </h5>
            <a href="<?= APP_URL ?>/customer/orders" class="small fw-semibold text-primary text-decoration-none">
                View All Orders &rarr;
            </a>
        </div>
        <div class="card-body p-0">
            <?php if (empty($orders)): ?>
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-cart-x fs-1 text-secondary opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark mb-1">No Orders Placed Yet</h5>
                    <p class="small text-muted mb-3">Browse our premium laptops, gaming PCs, and electronics with genuine warranty.</p>
                    <a href="<?= APP_URL ?>/shop" class="btn btn-primary fw-bold btn-sm px-4 py-2">
                        <i class="bi bi-cart-plus me-1"></i> Start Shopping
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Order Number</th>
                                <th>Date</th>
                                <th>Delivery Method</th>
                                <th>Total (TZS)</th>
                                <th>Order Status</th>
                                <th>Payment</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $ord): ?>
                                <tr>
                                    <td class="ps-3 fw-bold font-monospace text-primary">
                                        <?= e($ord['order_number']) ?>
                                    </td>
                                    <td class="small text-muted"><?= date('M d, Y', strtotime($ord['created_at'])) ?></td>
                                    <td class="small">
                                        <?php if (($ord['delivery_method'] ?? '') === 'pickup'): ?>
                                            <span class="badge bg-info-subtle text-info-emphasis">Kariakoo Pickup</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark border">Door Delivery</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold"><?= formatTZS($ord['total_amount']) ?></td>
                                    <td>
                                        <?php
                                            $st = strtolower($ord['order_status']);
                                            $badgeClass = match ($st) {
                                                'delivered' => 'bg-success',
                                                'confirmed', 'processing' => 'bg-primary',
                                                'dispatched' => 'bg-info text-dark',
                                                'cancelled' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        ?>
                                        <span class="badge <?= $badgeClass ?> text-uppercase" style="font-size: 0.72rem;">
                                            <?= e($ord['order_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($ord['payment_status'] === 'verified'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">Verified</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle">Awaiting Verification</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="<?= APP_URL ?>/customer/order/<?= e($ord['order_number']) ?>" class="btn btn-sm btn-outline-dark">
                                            View Order
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Shortcuts Grid -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded bg-primary-subtle text-primary p-3 fs-4">
                        <i class="bi bi-camera"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Profile Photo</h6>
                        <p class="small text-muted mb-2">Upload or change your customer avatar.</p>
                        <a href="<?= APP_URL ?>/customer/profile" class="small fw-bold text-primary text-decoration-none">Update Photo &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded bg-warning-subtle text-dark p-3 fs-4">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Password & Security</h6>
                        <p class="small text-muted mb-2">Keep your account safe with a strong password.</p>
                        <a href="<?= APP_URL ?>/customer/profile#password" class="small fw-bold text-dark text-decoration-none">Change Password &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded bg-success-subtle text-success p-3 fs-4">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">WhatsApp Concierge</h6>
                        <p class="small text-muted mb-2">Instant quotes, tech advice & delivery updates.</p>
                        <a href="https://wa.me/255787311342" target="_blank" class="small fw-bold text-success text-decoration-none">Chat Now (+255 787 311 342) &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
