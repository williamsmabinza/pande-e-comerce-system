<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Store Configuration & Settings</h2>
            <p class="text-muted small mb-0">Customize your business contacts, WhatsApp number, delivery fees, and order rules</p>
        </div>
    </div>

    <form action="<?= APP_URL ?>/admin/settings/update" method="POST">
        <?= $csrfInput ?>

        <div class="row g-4">
            <!-- Business Identity -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i> Business Identity & Branding</h5>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Official Business Name</label>
                        <input type="text" name="store_name" class="form-control" value="<?= e(\App\Models\Setting::get('store_name', 'PANDE COMPUTERS')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Brand Slogan</label>
                        <input type="text" name="store_slogan" class="form-control" value="<?= e(\App\Models\Setting::get('store_slogan', 'YOUR TRUSTED TECH PARTNER')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Supporting Tagline</label>
                        <input type="text" name="store_tagline" class="form-control" value="<?= e(\App\Models\Setting::get('store_tagline', 'UNCOMPROMISED POWER. UNBEATABLE PRICES.')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Store Physical Location</label>
                        <textarea name="store_address" rows="2" class="form-control" required><?= e(\App\Models\Setting::get('store_address', 'Ndanda & Aggrey Street, Kariakoo, Dar es Salaam, Tanzania')) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Contact & WhatsApp Details -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-whatsapp text-success me-2"></i> WhatsApp & Communications</h5>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Official WhatsApp Number for Checkout <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-whatsapp text-success"></i></span>
                            <input type="text" name="whatsapp_number" class="form-control font-monospace fw-bold" value="<?= e(\App\Models\Setting::get('whatsapp_number', '+255787311342')) ?>" required>
                        </div>
                        <div class="form-text">Used to generate click-to-chat links when customers complete checkout.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Customer Service Telephone</label>
                        <input type="text" name="store_phone" class="form-control" value="<?= e(\App\Models\Setting::get('store_phone', '+255 787 311 342')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sales Email Address</label>
                        <input type="email" name="store_email" class="form-control" value="<?= e(\App\Models\Setting::get('store_email', 'sales@pandecomputers.co.tz')) ?>" required>
                    </div>
                </div>
            </div>

            <!-- Fulfillment & Ordering Settings -->
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3 p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-truck text-info me-2"></i> Delivery & Order Processing Rules</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Default Delivery Fee in Dar es Salaam (TZS)</label>
                            <input type="number" step="0.01" name="delivery_fee_default" class="form-control" value="<?= e(\App\Models\Setting::get('delivery_fee_default', '10000')) ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Free Delivery Threshold (TZS)</label>
                            <input type="number" step="0.01" name="free_delivery_threshold" class="form-control" value="<?= e(\App\Models\Setting::get('free_delivery_threshold', '600000')) ?>" required>
                            <div class="form-text">Orders equal to or above this amount get free delivery.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Order Number Prefix</label>
                            <input type="text" name="order_prefix" class="form-control font-monospace" value="<?= e(\App\Models\Setting::get('order_prefix', 'PAN-2026-')) ?>" required>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-primary fw-bold px-4 py-2 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Save Store Configuration
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
