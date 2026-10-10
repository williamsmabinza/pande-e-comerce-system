<div class="container py-4">
    <!-- Breadcrumb & Header -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-1 small">
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>/" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>/customer/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Profile Settings</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Customer Profile & Security</h1>
            <p class="text-muted small mb-0">Manage your personal details, profile picture, contact numbers, and security credentials</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= APP_URL ?>/customer/dashboard" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
            <a href="<?= APP_URL ?>/customer/addresses" class="btn btn-outline-primary btn-sm fw-semibold">
                <i class="bi bi-geo-alt me-1"></i> Saved Addresses
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Avatar & Summary Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white text-center mb-4">
                <div class="position-relative d-inline-block mx-auto mb-3">
                    <?php if (!empty($user['avatar'])): ?>
                        <img id="avatar-preview-img" 
                             src="<?= APP_URL . '/' . e($user['avatar']) ?>" 
                             alt="<?= e($user['first_name']) ?>" 
                             class="rounded-circle object-fit-cover shadow-sm border border-2 border-primary" 
                             style="width: 130px; height: 130px;">
                    <?php else: ?>
                        <div id="avatar-placeholder" 
                             class="rounded-circle bg-primary-subtle text-primary border border-2 border-primary d-flex align-items-center justify-content-center shadow-sm mx-auto" 
                             style="width: 130px; height: 130px; font-size: 3rem; font-weight: 700;">
                            <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <img id="avatar-preview-img" 
                             src="" 
                             alt="Avatar Preview" 
                             class="rounded-circle object-fit-cover shadow-sm border border-2 border-primary d-none mx-auto" 
                             style="width: 130px; height: 130px;">
                    <?php endif; ?>

                    <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success p-2 border border-2 border-white" title="Active Account">
                        <i class="bi bi-check-lg text-white"></i>
                    </span>
                </div>

                <h5 class="fw-bold text-dark mb-1"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                <p class="text-muted small mb-2"><i class="bi bi-envelope me-1"></i> <?= e($user['email']) ?></p>
                <div class="d-inline-flex align-items-center gap-1 badge bg-light text-dark border px-3 py-2 rounded-pill small mb-3">
                    <i class="bi bi-shield-check text-success"></i> Verified Tanzanian Customer
                </div>

                <hr class="my-3">

                <!-- Avatar Upload Form -->
                <form action="<?= APP_URL ?>/customer/profile" method="POST" enctype="multipart/form-data" id="avatar-form">
                    <?= $csrfInput ?>
                    <input type="hidden" name="first_name" value="<?= e($user['first_name']) ?>">
                    <input type="hidden" name="last_name" value="<?= e($user['last_name']) ?>">
                    <input type="hidden" name="phone" value="<?= e($user['phone']) ?>">

                    <div class="mb-3 text-start">
                        <label for="avatar-input" class="form-label small fw-bold text-secondary mb-1">
                            <i class="bi bi-camera me-1"></i> Update Profile Image
                        </label>
                        <input class="form-control form-control-sm" 
                               type="file" 
                               name="avatar" 
                               id="avatar-input" 
                               accept="image/png, image/jpeg, image/jpg, image/webp" 
                               required>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">
                            Supports JPG, PNG, WEBP. Max file size: 5MB.
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm fw-bold flex-grow-1">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Upload Photo
                        </button>
                    </div>
                </form>

                <?php if (!empty($user['avatar'])): ?>
                    <form action="<?= APP_URL ?>/customer/profile" method="POST" class="mt-2">
                        <?= $csrfInput ?>
                        <input type="hidden" name="remove_avatar" value="1">
                        <button type="submit" class="btn btn-link text-danger text-decoration-none btn-sm p-0" onclick="return confirm('Remove your profile photo?');">
                            <i class="bi bi-trash3 me-1"></i> Remove Photo
                        </button>
                    </form>
                <?php endif; ?>

                <hr class="my-3">

                <div class="text-start small">
                    <div class="d-flex justify-content-between text-muted mb-1">
                        <span>Member Since:</span>
                        <span class="fw-semibold text-dark"><?= !empty($user['created_at']) ? date('M Y', strtotime($user['created_at'])) : 'Recent' ?></span>
                    </div>
                    <div class="d-flex justify-content-between text-muted mb-1">
                        <span>Primary Phone:</span>
                        <span class="fw-semibold text-dark"><?= e($user['phone']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>Account Role:</span>
                        <span class="badge bg-secondary text-uppercase"><?= e($user['role'] ?? 'customer') ?></span>
                    </div>
                </div>
            </div>

            <!-- Need Help Box -->
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-light text-start">
                <div class="d-flex align-items-center gap-2 mb-2 text-primary fw-bold small">
                    <i class="bi bi-headset fs-5"></i> Need Assistance?
                </div>
                <p class="small text-muted mb-2">Our customer service team at Kariakoo is ready to help you with orders, deliveries, or account updates.</p>
                <a href="https://wa.me/255787311342?text=<?= urlencode('Hello Pande Computers, I need help with my customer account (' . $user['email'] . ')') ?>" 
                   target="_blank" 
                   class="btn btn-success btn-sm fw-semibold w-100">
                    <i class="bi bi-whatsapp me-1"></i> WhatsApp Support (+255 787 311 342)
                </a>
            </div>
        </div>

        <!-- Right Column: Personal Info & Password Settings -->
        <div class="col-lg-8">
            <!-- Section 1: Personal Details Form -->
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>Personal Details
                    </h5>
                    <span class="badge bg-primary-subtle text-primary">Customer Information</span>
                </div>

                <form action="<?= APP_URL ?>/customer/profile" method="POST">
                    <?= $csrfInput ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" value="<?= e($user['first_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" value="<?= e($user['last_name']) ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control bg-light" value="<?= e($user['email']) ?>" readonly>
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-lock me-1"></i> Email is your unique account identifier and cannot be changed.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Phone Number / WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" class="form-control" value="<?= e($user['phone']) ?>" placeholder="+255 7XX XXX XXX" required>
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.75rem;">
                                Used for order updates, courier coordination, and WhatsApp payment confirmations.
                            </div>
                        </div>
                    </div>

                    <div class="text-end pt-2 border-top">
                        <button type="submit" class="btn btn-primary fw-bold px-4 py-2">
                            <i class="bi bi-check2-circle me-1"></i> Save Profile Details
                        </button>
                    </div>
                </form>
            </div>

            <!-- Section 2: Security & Password Change -->
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white mb-4" id="password">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-shield-lock text-warning me-2"></i>Change Password
                    </h5>
                    <span class="badge bg-warning-subtle text-dark">Security Settings</span>
                </div>

                <form action="<?= APP_URL ?>/customer/password" method="POST">
                    <?= $csrfInput ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-key"></i></span>
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Enter your current password" required>
                            <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="current_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Minimum 6 characters" minlength="6" required>
                                <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="new_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Re-type new password" minlength="6" required>
                                <button class="btn btn-outline-secondary toggle-pwd" type="button" data-target="new_password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border small text-muted mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-primary fs-5"></i>
                        <div>
                            Choose a strong password containing at least 6 characters. Do not share your password with anyone.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <a href="<?= APP_URL ?>/forgot-password" class="small text-decoration-none text-muted">
                            <i class="bi bi-question-circle me-1"></i> Forgot your current password?
                        </a>
                        <button type="submit" class="btn btn-warning fw-bold px-4 py-2 text-dark">
                            <i class="bi bi-shield-check me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Section 3: Quick Navigation Cards -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                        <div class="d-flex align-items-start gap-3">
                            <div class="p-2 rounded bg-primary-subtle text-primary fs-4">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-dark">Delivery Locations</h6>
                                <p class="small text-muted mb-2"><?= count($addresses ?? []) ?> saved delivery addresses for fast checkout.</p>
                                <a href="<?= APP_URL ?>/customer/addresses" class="btn btn-sm btn-outline-primary fw-semibold">
                                    Manage Addresses &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100">
                        <div class="d-flex align-items-start gap-3">
                            <div class="p-2 rounded bg-success-subtle text-success fs-4">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-dark">Order History</h6>
                                <p class="small text-muted mb-2">Track order fulfillment, payment status, and warranty receipts.</p>
                                <a href="<?= APP_URL ?>/customer/orders" class="btn btn-sm btn-outline-success fw-semibold">
                                    View My Orders &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Instant Image Preview on File Selection
    const avatarInput = document.getElementById('avatar-input');
    const avatarImg = document.getElementById('avatar-preview-img');
    const avatarPlaceholder = document.getElementById('avatar-placeholder');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (avatarImg) {
                        avatarImg.src = evt.target.result;
                        avatarImg.classList.remove('d-none');
                    }
                    if (avatarPlaceholder) {
                        avatarPlaceholder.classList.add('d-none');
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Toggle Password Visibility
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
