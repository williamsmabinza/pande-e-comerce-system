<footer class="site-footer bg-navy text-white mt-5 pt-5 pb-4">
    <div class="container">
        <div class="row gy-4">
            <!-- Col 1: Brand & Slogan -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="<?= asset('assets/images/logo.svg') ?>" alt="Pande Computers" width="56" height="56" class="bg-white p-1 rounded-circle shadow-sm">
                    <div>
                        <h5 class="fw-black mb-0 text-white tracking-wider">PANDE COMPUTERS</h5>
                        <p class="small text-info mb-0 fw-semibold tracking-wide">YOUR TRUSTED TECH PARTNER</p>
                    </div>
                </div>
                <p class="text-white-50 small mb-3">
                    Uncompromised power. Unbeatable prices. We supply genuine laptops, desktop computers, monitors, printers, networking hardware, and tech accessories to businesses, professionals, and households across Tanzania.
                </p>
                <div class="d-flex flex-column gap-2 small text-white-50">
                    <div><i class="bi bi-geo-alt-fill text-warning me-2"></i> <?= e($storeLocation ?? 'Ndanda & Aggrey Street, Kariakoo, Dar es Salaam, Tanzania') ?></div>
                    <div><i class="bi bi-globe2 text-warning me-2"></i> <a href="https://pandecomputers.co.tz" target="_blank" rel="noopener" class="text-white-50 text-decoration-none hover-white">www.pandecomputers.co.tz</a></div>
                    <div><i class="bi bi-telephone-fill text-warning me-2"></i> <?= e($storePhone ?? '+255 787 311 342') ?></div>
                    <div><i class="bi bi-whatsapp text-success me-2"></i> WhatsApp: <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>" target="_blank" rel="noopener" class="text-white-50 text-decoration-none hover-white">+255 787 311 342</a></div>
                    <div><i class="bi bi-envelope-fill text-warning me-2"></i> <?= e($storeEmail ?? 'sales@pandecomputers.co.tz') ?></div>
                </div>
                <!-- Social Media Links -->
                <div class="d-flex align-items-center gap-2 mt-3 pt-2">
                    <span class="small text-white-50 me-1">Connect:</span>
                    <a href="https://facebook.com/pandecomputers" target="_blank" rel="noopener" class="btn btn-sm btn-outline-light rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://instagram.com/pandecomputers" target="_blank" rel="noopener" class="btn btn-sm btn-outline-light rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="https://twitter.com/pandecomputers" target="_blank" rel="noopener" class="btn btn-sm btn-outline-light rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="X (Twitter)"><i class="bi bi-twitter-x"></i></a>
                    <a href="https://linkedin.com/company/pandecomputers" target="_blank" rel="noopener" class="btn btn-sm btn-outline-light rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="WhatsApp Direct"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>

            <!-- Col 2: Popular Categories -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="text-uppercase fw-bold text-white mb-3 tracking-wider">Categories</h6>
                <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2">
                    <li><a href="<?= APP_URL ?>/category/laptops-and-notebooks" class="text-white-50 text-decoration-none hover-white">Laptops & Notebooks</a></li>
                    <li><a href="<?= APP_URL ?>/category/desktop-computers-and-all-in-one-pcs" class="text-white-50 text-decoration-none hover-white">Desktop PCs</a></li>
                    <li><a href="<?= APP_URL ?>/category/computer-monitors-and-displays" class="text-white-50 text-decoration-none hover-white">Monitors & Displays</a></li>
                    <li><a href="<?= APP_URL ?>/category/printers-and-multifunction-printers" class="text-white-50 text-decoration-none hover-white">Printers & Scanners</a></li>
                    <li><a href="<?= APP_URL ?>/category/networking-equipment-routers-and-switches" class="text-white-50 text-decoration-none hover-white">Networking & Wi-Fi</a></li>
                    <li><a href="<?= APP_URL ?>/category/power-protection-ups-systems-and-surge-protectors" class="text-white-50 text-decoration-none hover-white">UPS & Power</a></li>
                    <li><a href="<?= APP_URL ?>/shop" class="text-info text-decoration-none fw-semibold">View All Catalog &rarr;</a></li>
                </ul>
            </div>

            <!-- Col 3: Customer Service & Help -->
            <div class="col-lg-3 col-md-6 col-6">
                <h6 class="text-uppercase fw-bold text-white mb-3 tracking-wider">Customer Care</h6>
                <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2">
                    <li><a href="<?= APP_URL ?>/about" class="text-white-50 text-decoration-none hover-white">About Pande Computers</a></li>
                    <li><a href="<?= APP_URL ?>/contact" class="text-white-50 text-decoration-none hover-white">Contact & Kariakoo Store</a></li>
                    <li><a href="<?= APP_URL ?>/delivery-and-returns" class="text-white-50 text-decoration-none hover-white">Delivery & Returns Policy</a></li>
                    <li><a href="<?= APP_URL ?>/terms-and-conditions" class="text-white-50 text-decoration-none hover-white">Terms & Conditions</a></li>
                    <li><a href="<?= APP_URL ?>/privacy-policy" class="text-white-50 text-decoration-none hover-white">Privacy Policy</a></li>
                    <li><a href="<?= APP_URL ?>/customer/dashboard" class="text-white-50 text-decoration-none hover-white">Customer Dashboard</a></li>
                    <li class="pt-1 border-top border-secondary border-opacity-25 mt-1">
                        <a href="<?= APP_URL ?>/admin/login" class="text-warning text-decoration-none hover-white fw-semibold">
                            <i class="bi bi-shield-lock-fill me-1"></i> Admin & Staff Login
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 4: WhatsApp Orders & Payment info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-uppercase fw-bold text-white mb-3 tracking-wider">Fast WhatsApp Order</h6>
                <p class="small text-white-50">
                    Place your order in seconds directly through WhatsApp. Our Kariakoo sales specialists verify availability and provide seamless payment & delivery instructions.
                </p>
                <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>" target="_blank" rel="noopener" class="btn btn-success w-100 fw-bold py-2 mb-3 shadow">
                    <i class="bi bi-whatsapp me-2"></i> Chat with Us Now
                </a>
                <div class="bg-dark bg-opacity-50 p-2 rounded small text-white-50 text-center">
                    <i class="bi bi-shield-check text-success me-1"></i> Cash on Delivery & Mobile Money (M-Pesa, Tigo Pesa, Airtel Money, Halopesa) supported.
                </div>
            </div>
        </div>

        <hr class="border-secondary opacity-25 my-4">

        <!-- Prominent Admin Portal & Staff Access Tab Bar in Footer -->
        <div class="bg-black bg-opacity-40 border border-secondary border-opacity-25 rounded-3 p-3 mb-4 shadow-sm">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning text-dark p-2 rounded-circle fs-5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Admin & Staff Portal</span>
                            <span class="text-white-50 small">&bull; Kariakoo Store Operations &bull; Live Control Hub</span>
                        </div>
                        <div class="small text-white-50 mt-1">
                            Authorized personnel only: Product SKUs, Inventory Restocking, Order Verification & System Configuration.
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <?php if (\App\Core\Auth::isStaff()): ?>
                        <div class="btn-group">
                            <a href="<?= APP_URL ?>/admin" class="btn btn-warning text-dark fw-bold px-3 py-2 shadow-sm text-decoration-none d-flex align-items-center gap-2">
                                <i class="bi bi-speedometer2"></i>
                                <span>Admin Dashboard</span>
                                <span class="badge bg-dark text-warning ms-1"><?= e(\App\Core\Auth::name()) ?></span>
                            </a>
                            <a href="<?= APP_URL ?>/admin/orders" class="btn btn-dark border-secondary px-3 py-2 text-decoration-none small text-white-50" title="Manage Orders">
                                <i class="bi bi-receipt me-1"></i> Orders
                            </a>
                            <a href="<?= APP_URL ?>/admin/inventory" class="btn btn-dark border-secondary px-3 py-2 text-decoration-none small text-white-50" title="Inventory">
                                <i class="bi bi-boxes me-1"></i> Stock
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/admin/login" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-pill shadow text-decoration-none d-flex align-items-center gap-2" id="footerAdminLoginTab">
                            <i class="bi bi-key-fill"></i>
                            <span>Admin Login Tab</span>
                            <span class="badge bg-navy text-white ms-1 font-monospace" style="font-size: 0.7rem;">Staff Console &rarr;</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row align-items-center small text-white-50 gy-2">
            <div class="col-md-5 text-center text-md-start">
                &copy; <?= date('Y') ?> <strong>Pande Computers</strong>. All rights reserved. Kariakoo, Dar es Salaam, Tanzania.
            </div>
            <div class="col-md-4 text-center">
                <?php if (\App\Core\Auth::isStaff()): ?>
                    <a href="<?= APP_URL ?>/admin" class="text-warning text-decoration-none fw-semibold">
                        <i class="bi bi-check-circle-fill text-success me-1"></i> Logged in as <?= e(\App\Core\Auth::role()) ?> (<?= e(\App\Core\Auth::name()) ?>)
                    </a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/admin/login" class="text-white-50 text-decoration-none hover-white">
                        <i class="bi bi-shield-lock me-1"></i> Staff Sign-In Portal (admin@pandecomputers.co.tz)
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-md-3 text-center text-md-end">
                <span>Currency: <strong>TZS</strong> (Tanzanian Shillings)</span>
            </div>
        </div>
    </div>
</footer>
