<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Staff & User Management</h2>
            <p class="text-muted small mb-0">Role-based access control for Pande Computers staff and customers</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Users Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="fw-bold mb-0 text-dark">System Users</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">User</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold text-dark"><?= e($u['first_name'] . ' ' . $u['last_name']) ?></div>
                                        <div class="small text-muted"><?= e($u['email']) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= match($u['role']) { 'admin' => 'danger', 'staff' => 'primary', default => 'secondary' } ?> text-uppercase">
                                            <?= e($u['role']) ?>
                                        </span>
                                    </td>
                                    <td class="small text-secondary"><?= e($u['phone']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst(e($u['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                                    <td class="pe-3 text-end">
                                        <?php if ((int)$u['id'] !== (int)\App\Core\Auth::id()): ?>
                                            <form action="<?= APP_URL ?>/admin/users/toggle/<?= $u['id'] ?>" method="POST" class="d-inline">
                                                <?= $csrfInput ?>
                                                <button type="submit" class="btn btn-outline-<?= $u['status'] === 'active' ? 'warning' : 'success' ?> btn-sm">
                                                    <?= $u['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="small text-muted">Current Session</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Staff Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h5 class="fw-bold text-dark mb-3">Create Staff Account</h5>

                <form action="<?= APP_URL ?>/admin/users/create-staff" method="POST">
                    <?= $csrfInput ?>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">First Name</label>
                            <input type="text" name="first_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Last Name</label>
                            <input type="text" name="last_name" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Assigned Role</label>
                        <select name="role" class="form-select form-select-sm" required>
                            <option value="staff">Staff (Fulfillment & Catalog)</option>
                            <option value="admin">Administrator (Full Access)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Initial Password (Min 8 chars)</label>
                        <input type="password" name="password" class="form-control form-control-sm" required minlength="8">
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold py-2 shadow-sm">
                        Create Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
