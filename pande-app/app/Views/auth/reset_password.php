<h4 class="fw-bold text-dark text-center mb-1">Set New Password</h4>
<p class="text-muted small text-center mb-4">Create a new secure password for your Pande Computers account</p>

<div class="card bg-light border-0 p-3 mb-3 rounded-3">
    <div class="d-flex align-items-center gap-2 small">
        <i class="bi bi-person-check text-primary fs-5"></i>
        <div>
            <span class="d-block text-secondary" style="font-size: 0.75rem;">Account:</span>
            <strong class="text-dark"><?= e($tokenUser['first_name'] . ' ' . $tokenUser['last_name']) ?></strong> (<?= e($tokenUser['email']) ?>)
        </div>
    </div>
</div>

<form action="<?= APP_URL ?>/reset-password" method="POST">
    <?= $csrfInput ?>
    <input type="hidden" name="token" value="<?= e($token) ?>">

    <div class="mb-3">
        <label class="form-label fw-semibold small text-secondary">New Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock text-secondary"></i></span>
            <input type="password" name="password" id="reset-password" class="form-control" placeholder="Minimum 6 characters" minlength="6" required autofocus>
            <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="reset-password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold small text-secondary">Confirm New Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill text-secondary"></i></span>
            <input type="password" name="password_confirmation" id="reset-password-confirm" class="form-control" placeholder="Re-enter your new password" minlength="6" required>
            <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="reset-password-confirm">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">
        <i class="bi bi-check2-circle me-1"></i> Update Password & Sign In
    </button>
</form>

<div class="text-center small border-top pt-3">
    <a href="<?= APP_URL ?>/login" class="text-primary text-decoration-none fw-semibold">&larr; Back to Sign In</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-pwd').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        });
    });
});
</script>
