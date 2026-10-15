<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin Dashboard - Pande Computers') ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('assets/images/logo.svg') ?>">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/admin.css') ?>">
</head>
<body class="bg-light admin-body">
    <div class="d-flex">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar bg-navy text-white flex-shrink-0 d-flex flex-column" style="width: 260px; min-height: 100vh;">
            <!-- Brand -->
            <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center gap-2">
                <img src="<?= asset('assets/images/logo.svg') ?>" alt="Logo" width="38" height="38" class="bg-white rounded-circle p-1">
                <div>
                    <h6 class="fw-bold mb-0 text-white tracking-wide">PANDE COMPUTERS</h6>
                    <span class="badge bg-primary text-uppercase" style="font-size: 0.65rem;">Staff Management</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="nav flex-column p-3 gap-1 flex-grow-1">
                <a href="<?= APP_URL ?>/admin" class="nav-link admin-nav-link text-white-50 <?= in_array(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), ['/admin', '/admin/', '/admin/dashboard'], true) ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Overview
                </a>
                
                <div class="text-uppercase text-secondary fw-bold small mt-3 mb-1 px-3" style="font-size: 0.7rem;">Catalog & Stock</div>
                <a href="<?= APP_URL ?>/admin/products" class="nav-link admin-nav-link text-white-50 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/products') ? 'active' : '' ?>">
                    <i class="bi bi-laptop me-2"></i> Products & SKUs
                </a>
                <a href="<?= APP_URL ?>/admin/categories" class="nav-link admin-nav-link text-white-50 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/categories') ? 'active' : '' ?>">
                    <i class="bi bi-tags me-2"></i> Categories
                </a>
                <a href="<?= APP_URL ?>/admin/inventory" class="nav-link admin-nav-link text-white-50 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/inventory') ? 'active' : '' ?>">
                    <i class="bi bi-boxes me-2"></i> Inventory & Stock
                </a>

                <div class="text-uppercase text-secondary fw-bold small mt-3 mb-1 px-3" style="font-size: 0.7rem;">Fulfillment & Sales</div>
                <a href="<?= APP_URL ?>/admin/orders" class="nav-link admin-nav-link text-white-50 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/orders') ? 'active' : '' ?>">
                    <i class="bi bi-receipt me-2"></i> Orders & WhatsApp
                </a>
                <a href="<?= APP_URL ?>/admin/customers" class="nav-link admin-nav-link text-white-50 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/customers') ? 'active' : '' ?>">
                    <i class="bi bi-people me-2"></i> Customer Accounts
                </a>
                <a href="<?= APP_URL ?>/admin/reports" class="nav-link admin-nav-link text-white-50 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/reports') ? 'active' : '' ?>">
                    <i class="bi bi-graph-up-arrow me-2"></i> Reports & Analytics
                </a>

                <?php if (!empty($isAdmin)): ?>
                    <div class="text-uppercase text-secondary fw-bold small mt-3 mb-1 px-3" style="font-size: 0.7rem;">Administration</div>
                    <a href="<?= APP_URL ?>/admin/settings" class="nav-link admin-nav-link text-white-50 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/settings') ? 'active' : '' ?>">
                        <i class="bi bi-gear-fill me-2"></i> Store Settings
                    </a>
                    <a href="<?= APP_URL ?>/admin/users" class="nav-link admin-nav-link text-white-50 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/users') ? 'active' : '' ?>">
                        <i class="bi bi-people-fill me-2"></i> Staff & Users
                    </a>
                <?php endif; ?>

                <div class="mt-auto pt-3 border-top border-secondary border-opacity-25">
                    <a href="<?= APP_URL ?>/" target="_blank" class="nav-link admin-nav-link text-info">
                        <i class="bi bi-box-arrow-up-right me-2"></i> View Live Store
                    </a>
                    <form action="<?= APP_URL ?>/logout" method="POST" class="mt-2">
                        <?= $csrfInput ?>
                        <button type="submit" class="btn btn-outline-danger w-100 btn-sm text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Admin Content Area -->
        <div class="flex-grow-1 d-flex flex-column min-vh-100">
            <!-- Admin Top Bar -->
            <header class="bg-white shadow-sm border-bottom py-2 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0 fw-bold text-dark"><?= e($pageTitle ?? 'Administration') ?></h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> System Online
                    </span>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle btn-sm d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-badge-fill text-primary"></i>
                            <span><?= e($currentUser['first_name'] ?? 'Admin') ?> (<?= ucfirst(e($currentUser['role'] ?? 'staff')) ?>)</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="<?= APP_URL ?>/admin/settings"><i class="bi bi-sliders me-2"></i> Store Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?= APP_URL ?>/logout" method="POST">
                                    <?= $csrfInput ?>
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Alerts -->
            <?php \App\Core\View::partial('alerts'); ?>

            <!-- View Content -->
            <main class="p-4 flex-grow-1">
                <?= $content ?>
            </main>

            <!-- Admin Footer -->
            <footer class="bg-white border-top py-2 px-4 text-muted small text-center text-md-start">
                <span>Pande Computers Management System &copy; <?= date('Y') ?> Kariakoo, Dar es Salaam. Production-ready.</span>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="<?= asset('assets/js/admin.js') ?>"></script>
</body>
</html>
