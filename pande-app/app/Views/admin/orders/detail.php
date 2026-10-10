<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/admin/orders">Orders</a></li>
                    <li class="breadcrumb-item active"><?= e($order['order_number']) ?></li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark mb-0">Order: <?= e($order['order_number']) ?></h2>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= APP_URL ?>/admin/orders/invoice/<?= $order['id'] ?>" target="_blank" class="btn btn-outline-dark fw-semibold">
                <i class="bi bi-printer me-1"></i> Print Invoice
            </a>
            <?php if (!empty($order['whatsapp_link'])): ?>
                <a href="<?= e($order['whatsapp_link']) ?>" target="_blank" rel="noopener" class="btn btn-success fw-semibold">
                    <i class="bi bi-whatsapp me-1"></i> Message Customer
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <!-- Order Items Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="fw-bold mb-0 text-dark">Order Items Snapshot</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Product Name</th>
                                <th>SKU</th>
                                <th>Unit Price</th>
                                <th>Qty</th>
                                <th class="pe-3 text-end">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">
                                        <?= e($item['product_name']) ?>
                                    </td>
                                    <td><span class="badge bg-light text-dark border font-monospace"><?= e($item['sku']) ?></span></td>
                                    <td><?= formatTZS($item['unit_price']) ?></td>
                                    <td><?= (int)$item['quantity'] ?></td>
                                    <td class="pe-3 text-end fw-bold"><?= formatTZS($item['line_total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Subtotal:</th>
                                <th class="text-end pe-3"><?= formatTZS($order['subtotal']) ?></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Delivery Fee:</th>
                                <th class="text-end pe-3"><?= formatTZS($order['delivery_fee']) ?></th>
                            </tr>
                            <tr class="table-primary">
                                <th colspan="4" class="text-end fs-6">Grand Total:</th>
                                <th class="text-end pe-3 fs-6 text-primary"><?= formatTZS($order['total_amount']) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Status Transition History -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h5 class="fw-bold mb-3 text-dark">Status Audit Trail</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($history as $h): ?>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary text-uppercase me-2"><?= e($h['new_status']) ?></span>
                                <span class="small text-secondary"><?= e($h['notes']) ?></span>
                            </div>
                            <span class="small text-muted"><?= date('M d, Y H:i:s', strtotime($h['created_at'])) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Management Controls Sidebar -->
        <div class="col-lg-4">
            <!-- Payment Verification Box -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 border-start border-4 border-<?= $order['payment_status'] === 'verified' ? 'success' : 'warning' ?>">
                <h5 class="fw-bold text-dark mb-3">Payment Verification</h5>
                <div class="mb-3">
                    <span class="text-muted small d-block">Current Payment Status:</span>
                    <?php if ($order['payment_status'] === 'verified'): ?>
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i> Verified & Paid</span>
                        <div class="small text-muted mt-2">
                            Method: <strong><?= e($order['payment_method']) ?></strong><br>
                            Reference: <code><?= e($order['payment_reference'] ?? 'N/A') ?></code><br>
                            Verified At: <?= e($order['payment_verified_at'] ?? 'N/A') ?>
                        </div>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i> Awaiting Payment Confirmation</span>
                        <div class="small text-muted mt-2">
                            Customer closes order and sends mobile money payment screenshot via WhatsApp.
                        </div>

                        <!-- Manual Verification Form -->
                        <form action="<?= APP_URL ?>/admin/orders/verify-payment/<?= $order['id'] ?>" method="POST" class="mt-3">
                            <?= $csrfInput ?>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Payment Channel</label>
                                <select name="payment_method" class="form-select form-select-sm">
                                    <option value="M-Pesa">Vodacom M-Pesa</option>
                                    <option value="Tigo Pesa">Tigo Pesa</option>
                                    <option value="Airtel Money">Airtel Money</option>
                                    <option value="Halopesa">Halopesa</option>
                                    <option value="Cash at Store">Cash at Kariakoo Store</option>
                                    <option value="Bank Transfer">CRDB / NMB Bank Transfer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Transaction Reference / Code</label>
                                <input type="text" name="payment_reference" class="form-control form-control-sm" placeholder="e.g. 8HQ76YTT1" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm w-100 fw-bold">
                                <i class="bi bi-check2-circle me-1"></i> Mark Payment Verified
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Order Status Update Box -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3">Update Order Status</h5>
                <form action="<?= APP_URL ?>/admin/orders/update-status/<?= $order['id'] ?>" method="POST">
                    <?= $csrfInput ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Lifecycle Status</label>
                        <select name="order_status" class="form-select">
                            <option value="pending" <?= $order['order_status'] === 'pending' ? 'selected' : '' ?>>Pending Review</option>
                            <option value="confirmed" <?= $order['order_status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="processing" <?= $order['order_status'] === 'processing' ? 'selected' : '' ?>>Processing / Packed</option>
                            <option value="ready_collection" <?= $order['order_status'] === 'ready_collection' ? 'selected' : '' ?>>Ready for Kariakoo Pickup</option>
                            <option value="shipped" <?= $order['order_status'] === 'shipped' ? 'selected' : '' ?>>Dispatched / In Transit</option>
                            <option value="completed" <?= $order['order_status'] === 'completed' ? 'selected' : '' ?>>Completed / Delivered</option>
                            <option value="cancelled" <?= $order['order_status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled (Restores Stock)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Internal Notes</label>
                        <textarea name="notes" rows="2" class="form-control form-control-sm" placeholder="Reason for change or carrier tracking details..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Customer & Delivery Info -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h5 class="fw-bold text-dark mb-3">Customer & Delivery Details</h5>
                <div class="mb-2">
                    <span class="text-muted small d-block">Customer Name:</span>
                    <strong><?= e($order['customer_name']) ?></strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted small d-block">WhatsApp / Phone:</span>
                    <a href="tel:<?= e($order['customer_phone']) ?>" class="text-dark fw-bold"><?= e($order['customer_phone']) ?></a>
                </div>
                <div class="mb-2">
                    <span class="text-muted small d-block">Fulfillment Mode:</span>
                    <span class="badge bg-secondary"><?= ucfirst(e($order['delivery_method'])) ?></span>
                </div>
                <div class="mb-2">
                    <span class="text-muted small d-block">Delivery Address:</span>
                    <span><?= e($order['delivery_address'] ?: 'Kariakoo Store Pickup') ?>, <?= e($order['delivery_city']) ?></span>
                </div>
                <?php if (!empty($order['order_notes'])): ?>
                    <div class="mt-3 p-2 bg-light rounded small text-secondary">
                        <strong>Customer Note:</strong><br>
                        <?= nl2br(e($order['order_notes'])) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
