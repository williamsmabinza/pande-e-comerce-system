<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Orders & WhatsApp Fulfillment</h2>
            <p class="text-muted small mb-0">Review incoming customer orders, update statuses, verify payments, and contact clients</p>
        </div>
        <div>
            <a href="<?= APP_URL ?>/admin/reports/export-orders" class="btn btn-outline-dark btn-sm fw-semibold">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Orders CSV
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 p-3 mb-4">
        <form action="<?= APP_URL ?>/admin/orders" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Search order #, customer name, phone..." value="<?= e($filters['q'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Order Statuses</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="processing" <?= ($filters['status'] ?? '') === 'processing' ? 'selected' : '' ?>>Processing</option>
                    <option value="ready_collection" <?= ($filters['status'] ?? '') === 'ready_collection' ? 'selected' : '' ?>>Ready for Collection</option>
                    <option value="shipped" <?= ($filters['status'] ?? '') === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_status" class="form-select">
                    <option value="">All Payment Statuses</option>
                    <option value="awaiting_confirmation" <?= ($filters['payment_status'] ?? '') === 'awaiting_confirmation' ? 'selected' : '' ?>>Awaiting Confirmation</option>
                    <option value="verified" <?= ($filters['payment_status'] ?? '') === 'verified' ? 'selected' : '' ?>>Verified</option>
                    <option value="failed" <?= ($filters['payment_status'] ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Filter</button>
                <a href="<?= APP_URL ?>/admin/orders" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th class="ps-3">Order #</th>
                        <th>Date Placed</th>
                        <th>Customer Contact</th>
                        <th>Delivery Mode</th>
                        <th>Total (TZS)</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th class="pe-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No orders match the selected criteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td class="ps-3 fw-bold font-monospace text-primary">
                                    <a href="<?= APP_URL ?>/admin/orders/detail/<?= $o['id'] ?>" class="text-decoration-none">
                                        <?= e($o['order_number']) ?>
                                    </a>
                                </td>
                                <td class="small text-muted"><?= date('M d, Y H:i', strtotime($o['created_at'])) ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= e($o['customer_name']) ?></div>
                                    <div class="small text-muted">
                                        <i class="bi bi-whatsapp text-success me-1"></i> <?= e($o['customer_phone']) ?>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= ucfirst(e($o['delivery_method'])) ?></span></td>
                                <td class="fw-bold text-dark"><?= formatTZS($o['total_amount']) ?></td>
                                <td>
                                    <?php if ($o['payment_status'] === 'verified'): ?>
                                        <span class="badge bg-success">Verified</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Awaiting Payment</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $badgeColor = match ($o['order_status']) {
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        'confirmed', 'processing' => 'primary',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badgeColor ?> text-uppercase"><?= e($o['order_status']) ?></span>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="<?= APP_URL ?>/admin/orders/detail/<?= $o['id'] ?>" class="btn btn-outline-dark btn-sm">
                                            Manage
                                        </a>
                                        <?php if (!empty($o['whatsapp_link'])): ?>
                                            <a href="<?= e($o['whatsapp_link']) ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm" title="Contact on WhatsApp">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
