<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 text-center p-4 p-md-5">
                <div class="mx-auto mb-3 rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="bi bi-check2-circle fs-1"></i>
                </div>

                <span class="badge bg-success-subtle text-success border border-success-subtle align-self-center px-3 py-1 mb-2">Order Created in Database</span>
                <h1 class="h2 fw-black text-dark mb-2">Thank You for Your Order!</h1>
                <p class="text-muted mb-3">Your order record has been successfully generated and saved in our inventory system.</p>

                <!-- Order Number Banner -->
                <div class="p-3 bg-light rounded-3 d-inline-block mx-auto mb-4 border">
                    <span class="text-muted small text-uppercase fw-bold d-block">Official Order Number</span>
                    <span class="display-6 fw-black text-primary font-monospace"><?= e($order['order_number']) ?></span>
                </div>

                <!-- Primary WhatsApp CTA -->
                <div class="mb-4">
                    <a href="<?= e($order['whatsapp_link']) ?>" 
                       target="_blank" rel="noopener" 
                       class="btn btn-success btn-lg px-5 py-3 fw-black shadow-lg hover-scale">
                        <i class="bi bi-whatsapp me-2 fs-4"></i> Click to Send Order on WhatsApp Now &rarr;
                    </a>
                    <div class="small text-muted mt-2">
                        Clicking this button opens WhatsApp with your prefilled items and order number.
                    </div>
                </div>

                <!-- Next Steps Info -->
                <div class="row g-3 text-start mt-2 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 h-100 bg-body-tertiary">
                            <span class="badge bg-primary rounded-circle mb-2">1</span>
                            <h6 class="fw-bold mb-1">Send WhatsApp Message</h6>
                            <p class="small text-muted mb-0">Tap the green button above to notify our Kariakoo sales desk.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 h-100 bg-body-tertiary">
                            <span class="badge bg-primary rounded-circle mb-2">2</span>
                            <h6 class="fw-bold mb-1">Confirm Availability</h6>
                            <p class="small text-muted mb-0">Our sales rep verifies stock and provides payment details.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 h-100 bg-body-tertiary">
                            <span class="badge bg-primary rounded-circle mb-2">3</span>
                            <h6 class="fw-bold mb-1">Pick up or Delivery</h6>
                            <p class="small text-muted mb-0">Receive your items via delivery or collect in Kariakoo.</p>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Breakdown -->
                <div class="text-start border-top pt-4">
                    <h5 class="fw-bold text-dark mb-3">Order Details Breakdown</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle small mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>SKU</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Line Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td class="fw-bold"><?= e($item['product_name']) ?></td>
                                        <td><?= e($item['sku']) ?></td>
                                        <td><?= (int)$item['quantity'] ?></td>
                                        <td><?= formatTZS($item['unit_price']) ?></td>
                                        <td class="fw-bold"><?= formatTZS($item['line_total']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal:</th>
                                    <th><?= formatTZS($order['subtotal']) ?></th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Delivery:</th>
                                    <th><?= formatTZS($order['delivery_fee']) ?></th>
                                </tr>
                                <tr class="table-primary">
                                    <th colspan="4" class="text-end fs-6">Total Amount:</th>
                                    <th class="fs-6 text-primary"><?= formatTZS($order['total_amount']) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <span class="text-muted small">Customer: <strong><?= e($order['customer_name']) ?></strong> &bull; Phone: <strong><?= e($order['customer_phone']) ?></strong></span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= APP_URL ?>/" class="btn btn-outline-dark btn-sm">Return Home</a>
                            <?php if (!empty($currentUser)): ?>
                                <a href="<?= APP_URL ?>/customer/orders" class="btn btn-primary btn-sm">View in My Orders</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
