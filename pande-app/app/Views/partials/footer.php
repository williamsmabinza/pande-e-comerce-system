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
                    <div><i class="bi bi-telephone-fill text-warning me-2"></i> <?= e($storePhone ?? '+255 787 311 342') ?></div>
                    <div><i class="bi bi-whatsapp text-success me-2"></i> WhatsApp: +255 787 311 342</div>
                    <div><i class="bi bi-envelope-fill text-warning me-2"></i> <?= e($storeEmail ?? 'sales@pandecomputers.co.tz') ?></div>
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

        <div class="row align-items-center small text-white-50 gy-2">
            <div class="col-md-5 text-center text-md-start">
                &copy; <?= date('Y') ?> <strong>Pande Computers</strong>. All rights reserved. Kariakoo, Dar es Salaam, Tanzania.
            </div>
            <div class="col-md-4 text-center">
                <!-- Admin & Staff Portal Tab in Footer -->
                <?php if (\App\Core\Auth::isStaff()): ?>
                    <a href="<?= APP_URL ?>/admin" class="btn btn-sm btn-warning text-dark fw-bold px-3 py-1 rounded-pill shadow-sm text-decoration-none">
                        <i class="bi bi-speedometer2 me-1"></i> Admin Dashboard (<?= e(\App\Core\Auth::name()) ?>)
                    </a>
                <?php else: ?>
                    <a href="<?= APP_URL ?>/admin/login" class="btn btn-sm btn-outline-secondary text-white-50 px-3 py-1 rounded-pill hover-white text-decoration-none border-opacity-50">
                        <i class="bi bi-shield-lock-fill text-warning me-1"></i> Admin & Staff Login Tab
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-md-3 text-center text-md-end">
                <span>Currency: <strong>TZS</strong> (Tanzanian Shillings)</span>
            </div>
        </div>
    </div>
</footer>
