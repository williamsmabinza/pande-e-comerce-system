<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-1 small">
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>/" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= APP_URL ?>/customer/dashboard" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Delivery Addresses</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-1">Manage Delivery Addresses</h1>
            <p class="text-muted small mb-0">Saved delivery locations across Dar es Salaam and Tanzania for fast checkout</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= APP_URL ?>/customer/dashboard" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
            <a href="<?= APP_URL ?>/customer/profile" class="btn btn-outline-primary btn-sm fw-semibold">
                <i class="bi bi-person-gear me-1"></i> Edit Profile
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Existing Addresses List -->
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>Saved Delivery Locations (<?= count($addresses) ?>)
                </h5>
            </div>

            <div class="row g-3">
                <?php if (empty($addresses)): ?>
                    <div class="col-12">
                        <div class="card p-5 text-center text-muted border-0 shadow-sm rounded-3 bg-white">
                            <i class="bi bi-geo-alt fs-1 text-secondary opacity-50 mb-3"></i>
                            <h6 class="fw-bold text-dark mb-1">No Delivery Addresses Saved Yet</h6>
                            <p class="small text-muted mb-0">Use the form on the right to add your delivery address in Dar es Salaam or upcountry.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($addresses as $addr): ?>
                        <div class="col-12">
                            <div class="card p-4 border-0 shadow-sm rounded-3 bg-white position-relative <?= $addr['is_default'] ? 'border-start border-4 border-primary' : '' ?>">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="fw-bold mb-0 text-dark fs-6"><?= e($addr['recipient_name']) ?></h6>
                                            <?php if ($addr['is_default']): ?>
                                                <span class="badge bg-primary rounded-pill small px-2 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Default Delivery Address
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <!-- Edit Trigger Button -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary btn-edit-address"
                                                data-id="<?= e($addr['id']) ?>"
                                                data-name="<?= e($addr['recipient_name']) ?>"
                                                data-phone="<?= e($addr['phone']) ?>"
                                                data-street="<?= e($addr['street_address']) ?>"
                                                data-district="<?= e($addr['district']) ?>"
                                                data-city="<?= e($addr['region_city']) ?>"
                                                data-landmark="<?= e($addr['landmark'] ?? '') ?>"
                                                data-default="<?= e($addr['is_default']) ?>"
                                                title="Edit Address">
                                            <i class="bi bi-pencil-square me-1"></i> Edit
                                        </button>

                                        <!-- Set As Default Form -->
                                        <?php if (!$addr['is_default']): ?>
                                            <form action="<?= APP_URL ?>/customer/address/default/<?= e($addr['id']) ?>" method="POST" class="d-inline">
                                                <?= $csrfInput ?>
                                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Set as primary address">
                                                    Set Default
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <!-- Delete Form -->
                                        <form action="<?= APP_URL ?>/customer/address/delete/<?= e($addr['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                            <?= $csrfInput ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Address">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="small text-dark mb-1">
                                    <i class="bi bi-geo-alt text-primary me-2"></i><strong><?= e($addr['street_address']) ?></strong>, <?= e($addr['district']) ?>, <?= e($addr['region_city']) ?>
                                </div>

                                <?php if (!empty($addr['landmark'])): ?>
                                    <div class="small text-secondary mb-1">
                                        <i class="bi bi-pin-map text-muted me-2"></i>Landmark: <?= e($addr['landmark']) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="small text-muted d-flex align-items-center gap-2 mt-2 pt-2 border-top">
                                    <span><i class="bi bi-telephone text-success me-1"></i> Contact: <strong><?= e($addr['phone']) ?></strong></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add Address Form -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle text-primary"></i> Add New Address
                </h5>
                <form action="<?= APP_URL ?>/customer/addresses" method="POST">
                    <?= $csrfInput ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Recipient Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="recipient_name" class="form-control" placeholder="e.g. Juma Ally" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Contact Phone (WhatsApp / Calls) <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="+255 7XX XXX XXX" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Street Address / House / Building <span class="text-danger">*</span></label>
                        <input type="text" name="street_address" class="form-control" placeholder="e.g. Msimbazi St, Kariakoo or Block 4, Sinza Mori" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">District <span class="text-danger">*</span></label>
                            <input type="text" name="district" class="form-control" placeholder="Ilala / Kinondoni" required list="district-list">
                            <datalist id="district-list">
                                <option value="Ilala">
                                <option value="Kinondoni">
                                <option value="Temeke">
                                <option value="Ubungo">
                                <option value="Kigamboni">
                                <option value="Arusha">
                                <option value="Mwanza">
                                <option value="Dodoma">
                                <option value="Zanzibar">
                                <option value="Morogoro">
                                <option value="Mbeya">
                            </datalist>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">City / Region <span class="text-danger">*</span></label>
                            <input type="text" name="region_city" class="form-control" value="Dar es Salaam" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Landmark / Directions (Optional)</label>
                        <input type="text" name="landmark" class="form-control" placeholder="e.g. Near St. Joseph Church, Opposite Puma station">
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default" <?= empty($addresses) ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-semibold text-dark" for="is_default">Set as default delivery address</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        <i class="bi bi-save me-1"></i> Save Delivery Address
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Existing Address -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark" id="editAddressModalLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Edit Delivery Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-address-form" method="POST" action="">
                <?= $csrfInput ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Recipient Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="recipient_name" id="modal-recipient-name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Contact Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="modal-phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Street Address / House <span class="text-danger">*</span></label>
                        <input type="text" name="street_address" id="modal-street-address" class="form-control" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">District <span class="text-danger">*</span></label>
                            <input type="text" name="district" id="modal-district" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">City / Region <span class="text-danger">*</span></label>
                            <input type="text" name="region_city" id="modal-region-city" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Landmark (Optional)</label>
                        <input type="text" name="landmark" id="modal-landmark" class="form-control">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="modal-is-default">
                        <label class="form-check-label small fw-semibold" for="modal-is-default">Set as default delivery address</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                        <i class="bi bi-check2-circle me-1"></i> Update Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModalEl = document.getElementById('editAddressModal');
    let editModal = null;
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        editModal = new bootstrap.Modal(editModalEl);
    }

    document.querySelectorAll('.btn-edit-address').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const phone = this.getAttribute('data-phone');
            const street = this.getAttribute('data-street');
            const district = this.getAttribute('data-district');
            const city = this.getAttribute('data-city');
            const landmark = this.getAttribute('data-landmark');
            const isDef = this.getAttribute('data-default') === '1';

            const form = document.getElementById('edit-address-form');
            form.action = '<?= APP_URL ?>/customer/address/update/' + id;

            document.getElementById('modal-recipient-name').value = name || '';
            document.getElementById('modal-phone').value = phone || '';
            document.getElementById('modal-street-address').value = street || '';
            document.getElementById('modal-district').value = district || '';
            document.getElementById('modal-region-city').value = city || 'Dar es Salaam';
            document.getElementById('modal-landmark').value = landmark || '';
            document.getElementById('modal-is-default').checked = isDef;

            if (editModal) {
                editModal.show();
            } else {
                // Fallback if bootstrap JS is loading asynchronously
                const bsModal = new (window.bootstrap?.Modal || bootstrap.Modal)(editModalEl);
                bsModal.show();
            }
        });
    });
});
</script>
