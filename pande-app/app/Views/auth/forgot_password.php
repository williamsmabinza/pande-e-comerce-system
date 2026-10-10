<h4 class="fw-bold text-dark text-center mb-1">Reset Your Password</h4>
<p class="text-muted small text-center mb-4">Enter your registered email address to receive password recovery instructions</p>

<?php if (!empty($resetLink)): ?>
    <div class="alert alert-success border-success-subtle shadow-sm p-3 mb-4 rounded-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-shield-check text-success fs-4"></i>
            <h6 class="fw-bold mb-0 text-success">Reset Link Ready</h6>
        </div>
        <p class="small text-dark mb-2">
            A password reset authorization token has been generated for <strong><?= e($resetEmail) ?></strong>.
        </p>
        <a href="<?= e($resetLink) ?>" class="btn btn-success btn-sm fw-bold w-100 py-2">
            <i class="bi bi-arrow-right-circle me-1"></i> Click Here to Reset Password Now
        </a>
    </div>
<?php endif; ?>

<form action="<?= APP_URL ?>/forgot-password" method="POST">
    <?= $csrfInput ?>

    <div class="mb-3">
        <label class="form-label fw-semibold small text-secondary">Registered Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope text-secondary"></i></span>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" value="<?= e($resetEmail ?? '') ?>" required autofocus>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">
        <i class="bi bi-send me-1"></i> Generate Password Reset Link
    </button>
</form>

<div class="alert alert-light border small text-muted mb-3">
    <div class="d-flex align-items-start gap-2">
        <i class="bi bi-whatsapp text-success fs-5"></i>
        <div>
            Need urgent access? Contact our Kariakoo tech support on WhatsApp at 
            <a href="https://wa.me/255787311342" target="_blank" class="fw-bold text-success text-decoration-none">+255 787 311 342</a> for instant identity verification.
        </div>
    </div>
</div>

<div class="text-center small border-top pt-3">
    <a href="<?= APP_URL ?>/login" class="text-primary text-decoration-none fw-semibold">&larr; Back to Sign In</a>
</div>
