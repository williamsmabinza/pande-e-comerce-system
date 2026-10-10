<?php
use App\Core\Session;

$successes = Session::getFlash('success');
$errors = Session::getFlash('error');
$warnings = Session::getFlash('warning');
$infos = Session::getFlash('info');
?>

<div class="container mt-3">
    <?php foreach ($successes as $msg): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= e($msg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>

    <?php foreach ($errors as $msg): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div><?= e($msg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>

    <?php foreach ($warnings as $msg): ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div><?= e($msg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>

    <?php foreach ($infos as $msg): ?>
        <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div><?= e($msg) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>
</div>
