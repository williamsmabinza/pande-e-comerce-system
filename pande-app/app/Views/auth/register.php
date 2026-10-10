<h4 class="fw-bold text-dark text-center mb-1">Create Customer Account</h4>
<p class="text-muted small text-center mb-4">Register to track your electronics purchases</p>

<form action="<?= APP_URL ?>/register" method="POST">
    <?= $csrfInput ?>

    <div class="row g-2 mb-3">
        <div class="col-6">
            <label class="form-label fw-semibold small">First Name</label>
            <input type="text" name="first_name" class="form-control" placeholder="Rashid" required>
        </div>
        <div class="col-6">
            <label class="form-label fw-semibold small">Last Name</label>
            <input type="text" name="last_name" class="form-control" placeholder="Pande" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope text-secondary"></i></span>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Phone Number (WhatsApp)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-whatsapp text-success"></i></span>
            <input type="text" name="phone" class="form-control" placeholder="+255 787 311 342" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Password (Min. 6 chars)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock text-secondary"></i></span>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold small">Confirm Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-shield-check text-secondary"></i></span>
            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required minlength="6">
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Register Account
    </button>
</form>

<div class="text-center small border-top pt-3">
    <span class="text-muted">Already registered?</span>
    <a href="<?= APP_URL ?>/login" class="fw-bold text-primary text-decoration-none ms-1">Sign In</a>
</div>
