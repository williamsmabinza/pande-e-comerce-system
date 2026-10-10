<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Pande Computers - Your Trusted Tech Partner | Dar es Salaam') ?></title>
    <meta name="description" content="<?= e($metaDescription ?? 'Pande Computers is your premier computer and technology retailer in Kariakoo, Dar es Salaam, Tanzania. Genuine laptops, monitors, printers, networking, and accessories.') ?>">
    <link rel="icon" type="image/svg+xml" href="<?= asset('assets/images/logo.svg') ?>">
    
    <!-- Open Graph Metadata -->
    <meta property="og:title" content="<?= e($pageTitle ?? 'Pande Computers - Your Trusted Tech Partner') ?>">
    <meta property="og:description" content="<?= e($metaDescription ?? 'Genuine electronics and IT hardware in Kariakoo, Dar es Salaam.') ?>">
    <meta property="og:image" content="<?= asset('assets/images/logo.svg') ?>">
    <meta property="og:type" content="website">

    <!-- Bootstrap 5.3 CSS & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Theme Styling -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    <meta name="csrf-token" content="<?= e($csrfToken ?? \App\Core\CSRF::getToken()) ?>">
    <meta name="session-id" content="<?= e(\App\Core\Session::getId()) ?>">
</head>
<body class="bg-body-tertiary d-flex flex-column min-vh-100">
    <!-- Header -->
    <?php \App\Core\View::partial('header', $data ?? []); ?>

    <!-- Flash Alerts -->
    <?php \App\Core\View::partial('alerts'); ?>

    <!-- Main View Content -->
    <main class="flex-grow-1">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <?php \App\Core\View::partial('footer', $data ?? []); ?>

    <!-- Floating WhatsApp Help Button -->
    <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>" 
       target="_blank" rel="noopener" 
       class="floating-whatsapp-btn shadow-lg" 
       title="Chat with Pande Computers on WhatsApp">
        <i class="bi bi-whatsapp"></i>
        <span class="d-none d-sm-inline ms-1 fw-bold">WhatsApp Order</span>
    </a>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
