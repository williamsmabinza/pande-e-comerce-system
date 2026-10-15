<div class="container-fluid py-2">
    <!-- Page Header & Action Controls -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-navy text-white px-2 py-1"><i class="bi bi-people-fill me-1"></i> Customer Accounts</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    <?= $kpis['totalCustomers'] ?> Registered Accounts
                </span>
            </div>
            <h2 class="fw-black text-dark mb-0">Customer Accounts & CRM Directory</h2>
            <p class="text-muted small mb-0">Manage customer profiles, contact numbers, order histories, account activation, and security access</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                <i class="bi bi-person-plus-fill me-1"></i> Add New Customer
            </button>
        </div>
    </div>

    <!-- Customer KPI Metrics Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-primary">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Total Registered</div>
                <div class="fs-4 fw-black text-primary mt-1"><?= $kpis['totalCustomers'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Customer accounts in database</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-success">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Active Accounts</div>
                <div class="fs-4 fw-black text-success mt-1"><?= $kpis['activeCustomers'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Authorized to shop & login</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-danger">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Suspended Accounts</div>
                <div class="fs-4 fw-black text-danger mt-1"><?= $kpis['suspendedCustomers'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Access suspended</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-info">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Customer Lifetime Spend</div>
                <div class="fs-5 fw-black text-info mt-1"><?= formatTZS($kpis['totalCustomerSpend']) ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Verified order purchases</div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card border-0 shadow-sm rounded-3 p-3 mb-4 bg-white">
        <form action="<?= APP_URL ?>/admin/customers" method="GET" class="row g-2 align-items-center">
            <div class="col-lg-5 col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Search customer name, email, or phone..." value="<?= e($filters['q'] ?? '') ?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="status" class="form-select form-select-sm">
                    <option value="all" <?= ($filters['status'] ?? '') === 'all' ? 'selected' : '' ?>>All Account Statuses</option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active Customers Only</option>
                    <option value="suspended" <?= ($filters['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended / Inactive Only</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-8">
                <select name="sort" class="form-select form-select-sm">
                    <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Sort: Newest Registrations First</option>
                    <option value="spent_desc" <?= ($filters['sort'] ?? '') === 'spent_desc' ? 'selected' : '' ?>>Sort: Highest Lifetime Spend</option>
                    <option value="orders_desc" <?= ($filters['sort'] ?? '') === 'orders_desc' ? 'selected' : '' ?>>Sort: Most Orders Placed</option>
                    <option value="name_asc" <?= ($filters['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>Sort: Customer Name (A-Z)</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-4 d-flex gap-1">
                <button type="submit" class="btn btn-dark btn-sm w-100 fw-semibold">Filter</button>
                <a href="<?= APP_URL ?>/admin/customers" class="btn btn-outline-secondary btn-sm" title="Clear filters"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person-lines-fill text-primary me-2"></i> Registered Customers Directory</h5>
            <span class="badge bg-light text-muted border"><?= count($customers) ?> customers</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="table-light small">
                    <tr>
                        <th class="ps-3" style="width: 28%;">Customer Name</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Account Status</th>
                        <th>Registered Date</th>
                        <th>Orders Count</th>
                        <th>Total Spend (TZS)</th>
                        <th class="pe-3 text-end" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-2 text-muted d-block mb-2"></i>
                                No customer records found matching your search.
                                <div class="mt-2">
                                    <a href="<?= APP_URL ?>/admin/customers" class="btn btn-outline-primary btn-sm">Reset Filters</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($customers as $c): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                            <?= strtoupper(substr($c['first_name'] ?? 'C', 0, 1) . substr($c['last_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= e($c['first_name'] . ' ' . $c['last_name']) ?></div>
                                            <div class="small text-muted">ID: #CUST-<?= $c['id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="small text-dark font-monospace"><?= e($c['email']) ?></span>
                                </td>
                                <td>
                                    <span class="small text-secondary fw-semibold"><?= e($c['phone']) ?></span>
                                </td>
                                <td>
                                    <?php if ($c['status'] === 'active'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle-fill me-1"></i> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-slash-circle-fill me-1"></i> Suspended
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted">
                                    <?= date('M d, Y', strtotime($c['created_at'])) ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-bold"><?= $c['order_count'] ?> orders</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= formatTZS($c['total_spent']) ?></span>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <!-- View Details Modal Trigger -->
                                        <button type="button" class="btn btn-outline-primary" onclick="viewCustomerDetails(<?= $c['id'] ?>)" title="View Order History & Addresses">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <!-- Edit Modal Trigger -->
                                        <button type="button" class="btn btn-outline-dark" onclick="openEditCustomerModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)" title="Edit Customer Details">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <!-- More Actions Dropdown -->
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 small">
                                            <li>
                                                <form action="<?= APP_URL ?>/admin/customers/toggle-status/<?= $c['id'] ?>" method="POST">
                                                    <?= $csrfInput ?>
                                                    <button type="submit" class="dropdown-item py-1 text-<?= $c['status'] === 'active' ? 'warning' : 'success' ?>">
                                                        <i class="bi bi-<?= $c['status'] === 'active' ? 'slash-circle' : 'check-circle' ?> me-2"></i>
                                                        <?= $c['status'] === 'active' ? 'Suspend Account' : 'Activate Account' ?>
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item py-1 text-danger" onclick="openDeleteCustomerModal(<?= $c['id'] ?>, '<?= addslashes(e($c['first_name'] . ' ' . $c['last_name'])) ?>', <?= (int)$c['order_count'] ?>)">
                                                    <i class="bi bi-trash3 me-2"></i> Delete Customer...
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Add Customer                                      -->
<!-- ======================================================== -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="<?= APP_URL ?>/admin/customers/store" method="POST">
                <?= $csrfInput ?>
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-plus-fill me-2"></i> Register New Customer Account
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control form-control-sm" required placeholder="e.g. Juma">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control form-control-sm" required placeholder="e.g. Mwinyi">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm" required placeholder="e.g. customer@gmail.com">
                        <div class="form-text small">Must be unique. Used for order notifications and sign in.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Phone Number (Tanzania) <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control form-control-sm" required placeholder="+255 7XX XXX XXX">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Initial Password (Optional)</label>
                        <input type="password" name="password" class="form-control form-control-sm" placeholder="Leave blank to generate secure temporary password">
                        <div class="form-text small">If left blank, a secure password will be auto-generated and presented to you.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Account Initial Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="active">Active (Permitted to sign in and order)</option>
                            <option value="suspended">Suspended (Blocked from ordering)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Create Customer Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Edit Customer Details                             -->
<!-- ======================================================== -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form id="editCustomerForm" action="" method="POST">
                <?= $csrfInput ?>
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2"></i> Edit Customer Account
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="edit_first_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="edit_last_name" class="form-control form-control-sm" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="edit_email" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" id="edit_phone" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Account Status</label>
                        <select name="status" id="edit_status" class="form-select form-select-sm">
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Reset Password (Optional)</label>
                        <input type="password" name="password" class="form-control form-control-sm" placeholder="Enter new password (min 6 chars) or leave blank to keep unchanged">
                        <div class="form-text small">Only fill this if you want to reset the customer's password.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="bi bi-save me-1"></i> Save Customer Updates
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Customer Order History & Profile Details          -->
<!-- ======================================================== -->
<div class="modal fade" id="customerDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-badge-fill me-2"></i> Customer Account Overview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="customerDetailBody">
                <div class="text-center py-4 text-muted">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2">Loading customer profile & transaction history...</div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Delete Customer Confirmation                      -->
<!-- ======================================================== -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form id="deleteCustomerForm" action="" method="POST">
                <?= $csrfInput ?>
                <div class="modal-header bg-danger text-white py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Customer Removal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-3 text-dark">
                        Are you sure you want to remove customer account for <strong id="deleteCustomerName" class="text-danger"></strong>?
                    </p>
                    <div id="deleteCustomerOrderNotice" class="alert alert-warning border-0 small d-none">
                        <i class="bi bi-shield-lock-fill me-1"></i>
                        <strong>Order Integrity Protection:</strong> This customer has existing purchase orders and invoices. To preserve accounting history and receipts, the account will be safely <strong>Suspended and Deactivated</strong> rather than deleted.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm fw-bold">
                        <i class="bi bi-trash3-fill me-1"></i> Confirm Action
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditCustomerModal(customer) {
    const form = document.getElementById('editCustomerForm');
    form.action = '<?= APP_URL ?>/admin/customers/update/' + customer.id;
    document.getElementById('edit_first_name').value = customer.first_name || '';
    document.getElementById('edit_last_name').value = customer.last_name || '';
    document.getElementById('edit_email').value = customer.email || '';
    document.getElementById('edit_phone').value = customer.phone || '';
    document.getElementById('edit_status').value = customer.status || 'active';
    
    const modal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
    modal.show();
}

function openDeleteCustomerModal(customerId, customerName, orderCount) {
    const form = document.getElementById('deleteCustomerForm');
    form.action = '<?= APP_URL ?>/admin/customers/delete/' + customerId;
    document.getElementById('deleteCustomerName').textContent = customerName;
    
    const notice = document.getElementById('deleteCustomerOrderNotice');
    if (orderCount > 0) {
        notice.classList.remove('d-none');
    } else {
        notice.classList.add('d-none');
    }
    
    const modal = new bootstrap.Modal(document.getElementById('deleteCustomerModal'));
    modal.show();
}

function viewCustomerDetails(customerId) {
    const body = document.getElementById('customerDetailBody');
    body.innerHTML = `
        <div class="text-center py-4 text-muted">
            <div class="spinner-border text-primary" role="status"></div>
            <div class="mt-2">Loading customer profile & transaction history...</div>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('customerDetailModal'));
    modal.show();
    
    fetch('<?= APP_URL ?>/admin/customers/detail/' + customerId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.customer) {
            body.innerHTML = `<div class="alert alert-danger">Customer not found.</div>`;
            return;
        }
        const c = data.customer;
        const orders = data.orders || [];
        const addresses = data.addresses || [];
        
        let ordersHtml = '';
        if (orders.length === 0) {
            ordersHtml = `<div class="text-muted small py-2">No purchase orders placed yet.</div>`;
        } else {
            ordersHtml = `
                <div class="table-responsive">
                    <table class="table table-sm align-middle small mb-0">
                        <thead>
                            <tr class="table-light">
                                <th>Order #</th>
                                <th>Items</th>
                                <th>Total (TZS)</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${orders.map(o => `
                                <tr>
                                    <td><strong>${o.order_number}</strong></td>
                                    <td>${o.items_count} items</td>
                                    <td class="fw-bold">${new Intl.NumberFormat().format(o.total_amount)} TZS</td>
                                    <td><span class="badge bg-${o.payment_status === 'verified' ? 'success' : 'warning'} text-uppercase">${o.payment_status}</span></td>
                                    <td><span class="badge bg-secondary text-uppercase">${o.order_status}</span></td>
                                    <td>${new Date(o.created_at).toLocaleDateString()}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        let addressHtml = '';
        if (addresses.length === 0) {
            addressHtml = `<div class="text-muted small">No delivery addresses saved.</div>`;
        } else {
            addressHtml = addresses.map(a => `
                <div class="p-2 border rounded bg-light mb-2 small">
                    <strong>${a.recipient_name}</strong> (${a.phone})<br>
                    ${a.street_address}, ${a.region_city} ${a.landmark ? '&bull; Near ' + a.landmark : ''}
                    ${a.is_default ? '<span class="badge bg-primary ms-1">Default Address</span>' : ''}
                </div>
            `).join('');
        }

        body.innerHTML = `
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold text-dark mb-2">Customer Profile</h6>
                        <div class="small"><strong>Full Name:</strong> ${c.first_name} ${c.last_name}</div>
                        <div class="small"><strong>Email:</strong> ${c.email}</div>
                        <div class="small"><strong>Phone:</strong> ${c.phone}</div>
                        <div class="small"><strong>Status:</strong> <span class="badge bg-${c.status === 'active' ? 'success' : 'danger'}">${c.status}</span></div>
                        <div class="small text-muted mt-1">Registered: ${new Date(c.created_at).toLocaleDateString()}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold text-dark mb-2">Delivery Addresses (${addresses.length})</h6>
                        ${addressHtml}
                    </div>
                </div>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-2">Order History (${orders.length})</h6>
                ${ordersHtml}
            </div>
        `;
    })
    .catch(err => {
        body.innerHTML = `<div class="alert alert-danger">Error loading customer details.</div>`;
    });
}
</script>
