<div class="container py-4">
    <h1 class="h3 fw-bold text-dark mb-4"><i class="bi bi-cart3 text-primary me-2"></i> Shopping Cart (<?= $totals['count'] ?> <?= $totals['count'] === 1 ? 'item' : 'items' ?>)</h1>

    <?php if (empty($cartItems)): ?>
        <div id="empty-cart-container" data-is-empty="1" class="card border border-pc rounded-3 p-5 text-center bg-white my-4">
            <i class="bi bi-cart-x text-muted mb-3" style="font-size: 4rem;"></i>
            <h4 class="fw-bold text-dark">Your cart is empty</h4>
            <p class="text-muted mb-4">Time to start shopping computers, printers, and tech components.</p>
            <div>
                <a href="<?= APP_URL ?>/shop<?= !empty($_GET['sid']) ? ('?sid=' . urlencode($_GET['sid'])) : '' ?>" class="btn btn-primary btn-lg px-4 fw-bold">
                    <i class="bi bi-laptop me-2"></i> Start Shopping Now
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4" id="active-cart-container">
            <!-- Items Table -->
            <div class="col-lg-8">
                <div class="card border border-pc rounded-3 overflow-hidden bg-white mb-3">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th class="ps-4" style="width: 45%;">Item Description</th>
                                    <th>Price</th>
                                    <th style="width: 120px;">Qty</th>
                                    <th>Total</th>
                                    <th class="pe-4 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $productId => $item): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="border rounded p-1 bg-white text-center d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                                                    <img src="<?= asset($item['image'] ?? 'assets/images/products/laptop.svg') ?>" 
                                                         alt="<?= e($item['name']) ?>" 
                                                         style="max-width: 48px; max-height: 48px; object-fit: contain;">
                                                </div>
                                                <div>
                                                    <a href="<?= APP_URL ?>/product/<?= e($item['slug']) ?>" class="fw-bold text-dark text-decoration-none hover-primary line-clamp-1">
                                                        <?= e($item['name']) ?>
                                                    </a>
                                                    <div class="small text-muted font-monospace" style="font-size: 0.75rem;">
                                                        SKU: <?= e($item['sku']) ?> &bull; <?= e($item['brand']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            <?= formatTZS($item['price']) ?>
                                        </td>
                                        <td>
                                            <form action="<?= APP_URL ?>/cart/update" method="POST" class="d-flex align-items-center">
                                                <?= $csrfInput ?>
                                                <input type="hidden" name="_session_id" value="<?= e(\App\Core\Session::getId()) ?>">
                                                <input type="hidden" name="product_id" value="<?= (int)$productId ?>">
                                                <input type="number" name="quantity" class="form-control form-control-sm text-center fw-bold" value="<?= (int)$item['quantity'] ?>" min="1" max="<?= (int)$item['max_stock'] ?>" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            <?= formatTZS($item['line_total']) ?>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="<?= APP_URL ?>/cart/remove" method="POST" class="d-inline">
                                                <?= $csrfInput ?>
                                                <input type="hidden" name="_session_id" value="<?= e(\App\Core\Session::getId()) ?>">
                                                <input type="hidden" name="product_id" value="<?= (int)$productId ?>">
                                                <button type="submit" class="btn btn-link text-danger p-0" title="Remove item">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?= APP_URL ?>/shop<?= !empty($_GET['sid']) ? ('?sid=' . urlencode($_GET['sid'])) : '' ?>" class="btn btn-outline-secondary btn-sm fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                    </a>
                    <form action="<?= APP_URL ?>/cart/clear" method="POST">
                        <?= $csrfInput ?>
                        <input type="hidden" name="_session_id" value="<?= e(\App\Core\Session::getId()) ?>">
                        <button type="submit" class="btn btn-link text-muted btn-sm text-decoration-none" onclick="return confirm('Clear your shopping cart?')">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="col-lg-4">
                <div class="card border border-pc rounded-3 p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3">Order Summary</h5>

                    <!-- Free Delivery Progress in Dar es Salaam -->
                    <?php if ($totals['amount_to_free_delivery'] > 0): ?>
                        <div class="alert alert-warning small py-2 mb-3">
                            <i class="bi bi-truck me-1"></i> Add <strong><?= formatTZS($totals['amount_to_free_delivery']) ?></strong> more for <strong>FREE DELIVERY</strong> in Dar es Salaam!
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success small py-2 mb-3">
                            <i class="bi bi-check-circle-fill me-1"></i> You qualify for <strong>FREE DELIVERY</strong> in Dar es Salaam!
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Items Subtotal (<?= $totals['count'] ?>)</span>
                        <span class="fw-semibold text-dark"><?= formatTZS($totals['subtotal']) ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping (Dar es Salaam)</span>
                        <span class="fw-semibold text-dark">
                            <?= $totals['delivery_fee'] > 0 ? formatTZS($totals['delivery_fee']) : '<span class="text-success fw-bold">FREE</span>' ?>
                        </span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold fs-5 text-dark">Estimated Total</span>
                        <span class="fw-black fs-4 text-primary"><?= formatTZS($totals['total']) ?></span>
                    </div>

                    <a href="<?= APP_URL ?>/checkout<?= !empty($_GET['sid']) ? ('?sid=' . urlencode($_GET['sid'])) : '' ?>" class="btn btn-primary btn-lg w-100 fw-bold mb-3 shadow">
                        Go to Checkout <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                    <div class="small text-muted text-center">
                        <i class="bi bi-shield-lock me-1"></i> Guaranteed checkout via official WhatsApp line.
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
