<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 Page Not Found - Pande Computers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 text-center p-4">
    <div class="card border-0 shadow-sm rounded-4 p-5" style="max-width: 500px;">
        <h1 class="display-1 fw-black text-primary mb-0">404</h1>
        <h3 class="fw-bold text-dark mb-2">Page Not Found</h3>
        <p class="text-muted mb-4">The tech product, category, or page you were looking for cannot be found or has moved.</p>
        <div class="d-flex justify-content-center gap-2">
            <a href="<?= APP_URL ?>/" class="btn btn-primary fw-bold px-4"><i class="bi bi-house me-1"></i> Home</a>
            <a href="<?= APP_URL ?>/shop" class="btn btn-outline-dark fw-bold px-4"><i class="bi bi-grid me-1"></i> Shop Catalog</a>
        </div>
    </div>
</body>
</html>
