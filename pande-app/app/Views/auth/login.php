<h4 class="fw-bold text-dark text-center mb-1">Customer Sign In</h4>
<p class="text-muted small text-center mb-4">Access your orders, saved addresses, and profile</p>

<form action="<?= APP_URL ?>/login" method="POST">
    <?= $csrfInput ?>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope text-secondary"></i></span>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold small mb-0">Password</label>
            <a href="<?= APP_URL ?>/forgot-password" class="small text-primary text-decoration-none">Forgot?</a>
        </div>
        <div class="input-group mt-1">
            <span class="input-group-text"><i class="bi bi-lock text-secondary"></i></span>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">
        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Account
    </button>
</form>

<div class="text-center small border-top pt-3">
    <span class="text-muted">Don't have an account yet?</span>
    <a href="<?= APP_URL ?>/register" class="fw-bold text-primary text-decoration-none ms-1">Create Account</a>
</div>
