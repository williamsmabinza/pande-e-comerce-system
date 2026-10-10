<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">My Orders</h1>
            <p class="text-muted mb-0">Track and manage your Pande Computers purchases</p>
        </div>
        <a href="<?= APP_URL ?>/shop" class="btn btn-outline-primary fw-semibold"><i class="bi bi-cart-plus me-1"></i> Continue Shopping</a>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Order #</th>
                        <th>Date Placed</th>
                        <th>Delivery Method</th>
                        <th>Amount</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-box2 fs-1 d-block mb-2"></i>
                                No orders placed yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td class="ps-4 fw-bold font-monospace text-primary">
                                    <a href="<?= APP_URL ?>/customer/order/<?= e($o['order_number']) ?>" class="text-decoration-none">
                                        <?= e($o['order_number']) ?>
                                    </a>
                                </td>
                                <td class="small text-muted"><?= date('M d, Y H:i', strtotime($o['created_at'])) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= ucfirst(e($o['delivery_method'])) ?></span></td>
                                <td class="fw-bold"><?= formatTZS($o['total_amount']) ?></td>
                                <td>
                                    <span class="badge bg-primary text-uppercase"><?= e($o['order_status']) ?></span>
                                </td>
                                <td>
                                    <?php if ($o['payment_status'] === 'verified'): ?>
                                        <span class="badge bg-success">Verified</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Awaiting Payment</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?= APP_URL ?>/customer/order/<?= e($o['order_number']) ?>" class="btn btn-outline-dark btn-sm">
                                            Details
                                        </a>
                                        <?php if (!empty($o['whatsapp_link'])): ?>
                                            <a href="<?= e($o['whatsapp_link']) ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm" title="Reopen WhatsApp">
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
