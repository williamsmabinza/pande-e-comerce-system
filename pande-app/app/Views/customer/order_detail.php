<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/customer/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/customer/orders">Orders</a></li>
                    <li class="breadcrumb-item active"><?= e($order['order_number']) ?></li>
                </ol>
            </nav>
            <h1 class="h2 fw-bold text-dark mb-0">Order: <?= e($order['order_number']) ?></h1>
        </div>
        <div>
            <?php if (!empty($order['whatsapp_link'])): ?>
                <a href="<?= e($order['whatsapp_link']) ?>" target="_blank" rel="noopener" class="btn btn-success fw-bold">
                    <i class="bi bi-whatsapp me-1"></i> Reopen WhatsApp Chat
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Items Card -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="fw-bold mb-0 text-dark">Ordered Items</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Product Name</th>
                                <th>SKU</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th class="pe-3 text-end">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">
                                        <?= e($item['product_name']) ?>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border"><?= e($item['sku']) ?></span></td>
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
                                <th colspan="4" class="text-end fs-6">Total Amount:</th>
                                <th class="text-end pe-3 fs-6 text-primary"><?= formatTZS($order['total_amount']) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Status History -->
            <?php if (!empty($order['history'])): ?>
                <div class="card border-0 shadow-sm rounded-3 p-4">
                    <h5 class="fw-bold mb-3 text-dark">Order Status Timeline</h5>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($order['history'] as $hist): ?>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary text-uppercase me-2"><?= e($hist['new_status']) ?></span>
                                    <span class="small text-secondary"><?= e($hist['notes']) ?></span>
                                </div>
                                <span class="small text-muted"><?= date('M d, Y H:i', strtotime($hist['created_at'])) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark">Delivery & Contact</h5>
                <div class="mb-3">
                    <span class="text-muted small d-block">Recipient Name</span>
                    <strong><?= e($order['customer_name']) ?></strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">WhatsApp Contact</span>
                    <strong><?= e($order['customer_phone']) ?></strong>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Fulfillment Mode</span>
                    <span class="badge bg-secondary"><?= ucfirst(e($order['delivery_method'])) ?></span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Delivery Address</span>
                    <span><?= e($order['delivery_address'] ?: 'Kariakoo Store Pickup') ?>, <?= e($order['delivery_city']) ?></span>
                </div>
                <hr>
                <div class="mb-2">
                    <span class="text-muted small d-block">Payment Status</span>
                    <?php if ($order['payment_status'] === 'verified'): ?>
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i> Verified & Paid</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark fs-6"><i class="bi bi-clock me-1"></i> Awaiting Payment Confirmation</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
