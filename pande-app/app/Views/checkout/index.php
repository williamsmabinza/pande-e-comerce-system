<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="h2 fw-bold text-dark mb-1">Checkout & Order Placement</h1>
        <p class="text-muted">Direct order verification and fulfillment via Pande Computers Official WhatsApp</p>
    </div>

    <div class="row g-4">
        <!-- Customer & Delivery Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-person-lines-fill text-primary me-2"></i> Contact & Delivery Information</h4>

                <form action="<?= APP_URL ?>/checkout/process" method="POST" id="checkout-form">
                    <?= $csrfInput ?>
                    <input type="hidden" name="_session_id" value="<?= e(\App\Core\Session::getId()) ?>">
                    <input type="hidden" name="idempotency_key" value="<?= e($idempotencyKey) ?>">

                    <div class="row g-3">
                        <!-- Full Name -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Customer Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" required 
                                   value="<?= e($user ? trim($user['first_name'] . ' ' . $user['last_name']) : '') ?>" 
                                   placeholder="e.g. Salim Rashid Pande">
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone Number (WhatsApp Active) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-whatsapp text-success"></i></span>
                                <input type="text" name="customer_phone" class="form-control" required 
                                       value="<?= e($user['phone'] ?? '') ?>" 
                                       placeholder="e.g. +255 787 311 342 or 0787311342">
                            </div>
                            <div class="form-text">We will contact this number with your order invoice.</div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address (Optional)</label>
                            <input type="email" name="customer_email" class="form-control" 
                                   value="<?= e($user['email'] ?? '') ?>" 
                                   placeholder="e.g. name@example.com">
                        </div>

                        <!-- Delivery Method Selection -->
                        <div class="col-12 mt-4">
                            <label class="form-label fw-bold text-dark">Fulfillment Preference <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="card p-3 h-100 border-2 delivery-method-card cursor-pointer">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="delivery_method" id="method-delivery" value="delivery" checked onchange="toggleAddress(true)">
                                            <label class="form-check-label fw-bold" for="method-delivery">
                                                <i class="bi bi-truck text-primary me-1"></i> Delivery
                                            </label>
                                            <p class="small text-muted mb-0 mt-1">Delivered to your home, office, or bus upcountry in Tanzania.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card p-3 h-100 border-2 delivery-method-card cursor-pointer">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="delivery_method" id="method-collection" value="collection" onchange="toggleAddress(false)">
                                            <label class="form-check-label fw-bold" for="method-collection">
                                                <i class="bi bi-shop text-success me-1"></i> Store Collection
                                            </label>
                                            <p class="small text-muted mb-0 mt-1">Pick up in person at Ndanda & Aggrey St, Kariakoo, Dar es Salaam.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="col-12" id="address-container">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">City / Region <span class="text-danger">*</span></label>
                                    <input type="text" name="delivery_city" class="form-control" value="Dar es Salaam" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Street / Area / Landmark <span class="text-danger">*</span></label>
                                    <input type="text" name="delivery_address" id="delivery_address_input" class="form-control" 
                                           placeholder="e.g. Samora Ave, Ilala or Mbezi Beach near Shoprite" required>
                                </div>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Order Notes / Special Requests (Optional)</label>
                            <textarea name="order_notes" rows="2" class="form-control" placeholder="Any specific instructions for delivery, testing, or warranty requirements..."></textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-3 shadow">
                            <i class="bi bi-whatsapp me-2 fs-5"></i> Place Order & Continue to WhatsApp &rarr;
                        </button>
                        <p class="small text-muted text-center mt-2 mb-0">
                            By placing this order, a unique order number will be generated and saved in our database before opening your WhatsApp conversation.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary Column -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 p-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-3 text-dark">Order Items (<?= $totals['count'] ?>)</h5>

                <div class="checkout-items-list mb-3" style="max-height: 280px; overflow-y: auto;">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <div class="border rounded p-1 bg-white text-center d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
                                    <img src="<?= asset($item['image'] ?? 'assets/images/products/laptop.svg') ?>" 
                                         alt="<?= e($item['name']) ?>" 
                                         style="max-width: 36px; max-height: 36px; object-fit: contain;">
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark small line-clamp-1"><?= e($item['name']) ?></h6>
                                    <span class="text-muted" style="font-size: 0.75rem;">Qty: <?= (int)$item['quantity'] ?> &times; <?= formatTZS($item['price']) ?></span>
                                </div>
                            </div>
                            <span class="fw-bold text-dark small font-tabular"><?= formatTZS($item['line_total']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-semibold text-dark"><?= formatTZS($totals['subtotal']) ?></span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Delivery</span>
                    <span class="fw-semibold text-dark"><?= $totals['delivery_fee'] > 0 ? formatTZS($totals['delivery_fee']) : '<span class="text-success">FREE</span>' ?></span>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-baseline mb-4">
                    <span class="fw-bold fs-5 text-dark">Total Amount</span>
                    <span class="fw-black fs-4 text-primary"><?= formatTZS($totals['total']) ?></span>
                </div>

                <div class="alert alert-info small mb-0">
                    <div class="fw-bold mb-1"><i class="bi bi-shield-check me-1"></i> How Payment Works:</div>
                    <ol class="ps-3 mb-0 small text-secondary">
                        <li>We confirm product availability in store.</li>
                        <li>You complete payment via M-Pesa, Tigo Pesa, Airtel Money, or cash at store pickup.</li>
                        <li>An authorized admin marks your order as verified.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAddress(isDelivery) {
    const container = document.getElementById('address-container');
    const input = document.getElementById('delivery_address_input');
    if (isDelivery) {
        container.style.display = 'block';
        input.setAttribute('required', 'required');
    } else {
        container.style.display = 'none';
        input.removeAttribute('required');
    }
}
</script>
