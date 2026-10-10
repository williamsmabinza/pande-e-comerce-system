<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Account - Pande Computers') ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('assets/images/logo.svg') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5 col-xl-4">
                <!-- Brand Emblem -->
                <div class="text-center mb-4">
                    <a href="<?= APP_URL ?>/" class="d-inline-block">
                        <img src="<?= asset('assets/images/logo.svg') ?>" alt="Pande Computers" width="85" height="85" class="shadow-sm rounded-circle p-1 bg-white">
                    </a>
                    <h4 class="fw-black mt-2 mb-0 text-dark">PANDE COMPUTERS</h4>
                    <p class="text-primary small fw-bold tracking-wide">YOUR TRUSTED TECH PARTNER</p>
                </div>

                <!-- Flash Alerts -->
                <?php \App\Core\View::partial('alerts'); ?>

                <!-- Form Card -->
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <?= $content ?>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="<?= APP_URL ?>/" class="text-muted small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Return to Storefront
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
