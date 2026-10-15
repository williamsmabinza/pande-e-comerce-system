<div class="text-center mb-3">
    <span class="badge bg-navy px-3 py-2 text-white border border-secondary border-opacity-25 rounded-pill mb-2">
        <i class="bi bi-shield-lock-fill text-warning me-1"></i> Staff & Admin Console
    </span>
    <h4 class="fw-bold text-dark mb-1">Administrative Sign In</h4>
    <p class="text-muted small mb-0">Authorized personnel access for store operations & orders</p>
</div>

<!-- Admin Login Form -->
<form action="<?= APP_URL ?>/admin/login" method="POST" id="adminLoginForm">
    <?= $csrfInput ?>

    <div class="mb-3">
        <label class="form-label fw-semibold small">Staff / Admin Email Address</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-person-badge text-secondary"></i></span>
            <input type="email" name="email" id="adminEmail" class="form-control" placeholder="admin@pandecomputers.co.tz" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold small mb-0">Security Password</label>
            <a href="<?= APP_URL ?>/forgot-password" class="small text-muted text-decoration-none">Reset password</a>
        </div>
        <div class="input-group mt-1">
            <span class="input-group-text bg-light"><i class="bi bi-key text-secondary"></i></span>
            <input type="password" name="password" id="adminPassword" class="form-control" placeholder="••••••••••••" required>
        </div>
    </div>

    <button type="submit" class="btn btn-navy w-100 fw-bold py-2 mb-3 shadow-sm text-white">
        <i class="bi bi-shield-check me-1 text-warning"></i> Sign In to Admin Console
    </button>
</form>

<!-- Quick Credentials Helper for Demo & Staff Testing -->
<div class="bg-light p-3 rounded-3 border mb-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="small fw-bold text-dark"><i class="bi bi-info-circle-fill text-primary me-1"></i> Demo / Staff Credentials</span>
        <span class="badge bg-success-subtle text-success small">Ready</span>
    </div>
    
    <div class="d-flex flex-column gap-2">
        <button type="button" class="btn btn-outline-dark btn-sm text-start d-flex justify-content-between align-items-center" onclick="fillAdmin('admin@pandecomputers.co.tz', 'AdminSecure2026!')">
            <div>
                <div class="fw-bold small text-dark"><i class="bi bi-shield-fill-check text-primary me-1"></i> System Administrator</div>
                <div class="text-muted" style="font-size: 0.75rem;">admin@pandecomputers.co.tz &bull; Password: <code>AdminSecure2026!</code></div>
            </div>
            <span class="badge bg-primary">Auto Fill &rarr;</span>
        </button>

        <button type="button" class="btn btn-outline-dark btn-sm text-start d-flex justify-content-between align-items-center" onclick="fillAdmin('williamsmabinza@gmail.com', 'AdminSecure2026!')">
            <div>
                <div class="fw-bold small text-dark"><i class="bi bi-person-check-fill text-success me-1"></i> Williams Mabinza (Admin)</div>
                <div class="text-muted" style="font-size: 0.75rem;">williamsmabinza@gmail.com &bull; Password: <code>AdminSecure2026!</code></div>
            </div>
            <span class="badge bg-success">Auto Fill &rarr;</span>
        </button>

        <button type="button" class="btn btn-outline-dark btn-sm text-start d-flex justify-content-between align-items-center" onclick="fillAdmin('staff@pandecomputers.co.tz', 'StaffPass2026!')">
            <div>
                <div class="fw-bold small text-dark"><i class="bi bi-person-badge text-info me-1"></i> Store Staff Officer</div>
                <div class="text-muted" style="font-size: 0.75rem;">staff@pandecomputers.co.tz &bull; Password: <code>StaffPass2026!</code></div>
            </div>
            <span class="badge bg-secondary">Auto Fill &rarr;</span>
        </button>
    </div>
</div>

<div class="text-center small border-top pt-3">
    <span class="text-muted">Customer searching for your account?</span>
    <a href="<?= APP_URL ?>/login" class="fw-bold text-primary text-decoration-none ms-1">Customer Login</a>
</div>

<script>
function fillAdmin(email, pass) {
    document.getElementById('adminEmail').value = email;
    document.getElementById('adminPassword').value = pass;
}
</script>
