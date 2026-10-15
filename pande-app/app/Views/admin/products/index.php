<div class="container-fluid py-2">
    <!-- Page Header & Action Controls -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-navy text-white px-2 py-1"><i class="bi bi-laptop me-1"></i> Catalog Control</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                    <?= count($products) ?> Products Loaded
                </span>
            </div>
            <h2 class="fw-black text-dark mb-0">Product Catalog & Full Inventory Control</h2>
            <p class="text-muted small mb-0">Manage hardware catalog, technical specifications, prices, multi-tier stock levels, and lifecycle states</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= APP_URL ?>/admin/inventory" class="btn btn-outline-dark btn-sm fw-semibold">
                <i class="bi bi-boxes me-1"></i> Stock Adjustments
            </a>
            <a href="<?= APP_URL ?>/admin/products/create" class="btn btn-primary btn-sm fw-bold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Executive Catalog KPI Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-success">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Active Products</div>
                <div class="fs-4 fw-black text-success mt-1"><?= $kpis['totalActive'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Live on storefront</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-secondary">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Discontinued / Archived</div>
                <div class="fs-4 fw-black text-secondary mt-1"><?= $kpis['totalDiscontinued'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Hidden, orders safe</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-warning">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Low Safety Stock</div>
                <div class="fs-4 fw-black text-warning mt-1"><?= $kpis['totalLowStock'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Below reorder level</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-danger">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Out of Stock</div>
                <div class="fs-4 fw-black text-danger mt-1"><?= $kpis['totalOutOfStock'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Immediate restock</div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-primary">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Retail Inventory Value</div>
                <div class="fs-5 fw-black text-primary mt-1"><?= formatTZS($kpis['totalCatalogValuation']) ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Gross retail price basis</div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-dark">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Cost Basis Valuation</div>
                <div class="fs-5 fw-black text-dark mt-1"><?= formatTZS($kpis['totalCostValuation']) ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Internal acquisition cost</div>
            </div>
        </div>
    </div>

    <!-- Advanced Search, Categories & Stock Filters -->
    <div class="card border-0 shadow-sm rounded-3 p-3 mb-4 bg-white">
        <form action="<?= APP_URL ?>/admin/products" method="GET" class="row g-2 align-items-center">
            <div class="col-lg-4 col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Search name, SKU, brand, model..." value="<?= e($filters['q'] ?? '') ?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="category" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (!empty($filters['category']) && (int)$filters['category'] === (int)$cat['id']) ? 'selected' : '' ?>>
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="stock_status" class="form-select form-select-sm">
                    <option value="all" <?= ($filters['stock_status'] ?? '') === 'all' ? 'selected' : '' ?>>All Stock Levels</option>
                    <option value="in_stock" <?= ($filters['stock_status'] ?? '') === 'in_stock' ? 'selected' : '' ?>>In Stock</option>
                    <option value="low_stock" <?= ($filters['stock_status'] ?? '') === 'low_stock' ? 'selected' : '' ?>>Low Stock</option>
                    <option value="out_of_stock" <?= ($filters['stock_status'] ?? '') === 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="all" <?= ($filters['status'] ?? '') === 'all' ? 'selected' : '' ?>>All Statuses</option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active (Visible)</option>
                    <option value="discontinued" <?= ($filters['status'] ?? '') === 'discontinued' ? 'selected' : '' ?>>Discontinued</option>
                    <option value="archived" <?= ($filters['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
                    <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                </select>
            </div>
            <div class="col-lg-1 col-md-4 d-flex gap-1">
                <button type="submit" class="btn btn-dark btn-sm w-100 fw-semibold">Filter</button>
                <a href="<?= APP_URL ?>/admin/products" class="btn btn-outline-secondary btn-sm" title="Clear filters"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <!-- Bulk Operations Toolbar & Products Table -->
    <form action="<?= APP_URL ?>/admin/products/bulk" method="POST" id="bulkForm">
        <?= $csrfInput ?>
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
            <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark">Hardware Products List</span>
                    <span class="badge bg-light text-muted border"><?= count($products) ?> items</span>
                </div>
                <!-- Bulk Actions Controls -->
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted d-none d-md-inline" id="selectedCountNotice">0 selected</span>
                    <select name="bulk_action" id="bulkActionSelect" class="form-select form-select-sm" style="width: auto;">
                        <option value="">Bulk Actions...</option>
                        <option value="activate">Set Status: Active</option>
                        <option value="discontinue">Set Status: Discontinue</option>
                        <option value="archive">Set Status: Archive</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="button" class="btn btn-outline-dark btn-sm fw-semibold" onclick="confirmBulkAction()">
                        Apply
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light small">
                        <tr>
                            <th class="ps-3" style="width: 40px;">
                                <input type="checkbox" class="form-check-input" id="selectAllCheckbox" onclick="toggleSelectAll(this)">
                            </th>
                            <th style="width: 32%;">Product & Specifications</th>
                            <th>Category</th>
                            <th>SKU</th>
                            <th>Price & Cost</th>
                            <th>Stock Count</th>
                            <th>Status</th>
                            <th class="pe-3 text-end" style="width: 220px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
                                    No products found matching your search or filter criteria.
                                    <div class="mt-2">
                                        <a href="<?= APP_URL ?>/admin/products" class="btn btn-outline-primary btn-sm">Reset Filters</a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td class="ps-3">
                                        <input type="checkbox" name="product_ids[]" value="<?= $p['id'] ?>" class="form-check-input product-select-checkbox" onchange="updateSelectedCount()">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-light p-1 rounded border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                                                <img src="<?= asset($p['primary_image'] ?? 'assets/images/products/laptop.svg') ?>" alt="<?= e($p['name']) ?>" class="img-fluid" style="max-height: 40px; max-width: 40px; object-fit: contain;">
                                            </div>
                                            <div>
                                                <a href="<?= APP_URL ?>/admin/products/edit/<?= $p['id'] ?>" class="fw-bold text-dark text-decoration-none hover-primary line-clamp-1">
                                                    <?= e($p['name']) ?>
                                                </a>
                                                <div class="small text-muted">
                                                    <span class="fw-semibold text-secondary"><?= e($p['brand']) ?></span>
                                                    <?php if (!empty($p['model_number'])): ?>
                                                        &bull; <span class="font-monospace"><?= e($p['model_number']) ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($p['is_featured'])): ?>
                                                        &bull; <span class="badge bg-warning-subtle text-warning border border-warning-subtle py-0" style="font-size: 0.65rem;">Featured</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small">
                                        <span class="badge bg-light text-dark border"><?= e($p['category_name'] ?? 'General') ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace"><?= e($p['sku']) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= formatTZS($p['price']) ?></div>
                                        <?php if (!empty($p['cost_price'])): ?>
                                            <div class="small text-muted" style="font-size: 0.72rem;" title="Internal acquisition cost">
                                                Cost: <?= formatTZS($p['cost_price']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($p['stock_quantity'] <= 0): ?>
                                            <span class="badge bg-danger">0 Out of Stock</span>
                                        <?php elseif ($p['stock_quantity'] <= $p['low_stock_threshold']): ?>
                                            <span class="badge bg-warning text-dark"><?= $p['stock_quantity'] ?> Low (Min: <?= $p['low_stock_threshold'] ?>)</span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle"><?= $p['stock_quantity'] ?> units</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $badgeClass = match($p['status']) {
                                                'active' => 'bg-success',
                                                'discontinued' => 'bg-secondary',
                                                'archived' => 'bg-dark',
                                                'draft' => 'bg-warning text-dark',
                                                default => 'bg-secondary'
                                            };
                                        ?>
                                        <span class="badge <?= $badgeClass ?> text-uppercase" style="font-size: 0.65rem;">
                                            <?= e($p['status']) ?>
                                        </span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="btn-group btn-group-sm">
                                            <!-- Edit Action -->
                                            <a href="<?= APP_URL ?>/admin/products/edit/<?= $p['id'] ?>" class="btn btn-outline-dark" title="Edit Product Specs & Pricing">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <!-- View Live Storefront Action -->
                                            <a href="<?= APP_URL ?>/product/<?= e($p['slug']) ?>" target="_blank" class="btn btn-outline-secondary" title="View in Customer Storefront">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            <!-- More Actions Dropdown -->
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Actions</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 small">
                                                <?php if ($p['status'] === 'active'): ?>
                                                    <li>
                                                        <form action="<?= APP_URL ?>/admin/products/status/<?= $p['id'] ?>" method="POST">
                                                            <?= $csrfInput ?>
                                                            <input type="hidden" name="status" value="discontinued">
                                                            <button type="submit" class="dropdown-item text-secondary py-1">
                                                                <i class="bi bi-slash-circle me-2"></i> Discontinue Product
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="<?= APP_URL ?>/admin/products/status/<?= $p['id'] ?>" method="POST">
                                                            <?= $csrfInput ?>
                                                            <input type="hidden" name="status" value="archived">
                                                            <button type="submit" class="dropdown-item text-muted py-1">
                                                                <i class="bi bi-archive me-2"></i> Archive Product
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php else: ?>
                                                    <li>
                                                        <form action="<?= APP_URL ?>/admin/products/status/<?= $p['id'] ?>" method="POST">
                                                            <?= $csrfInput ?>
                                                            <input type="hidden" name="status" value="active">
                                                            <button type="submit" class="dropdown-item text-success py-1">
                                                                <i class="bi bi-check-circle me-2"></i> Reactivate Product
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger py-1" onclick="openDeleteModal(<?= $p['id'] ?>, '<?= addslashes(e($p['name'])) ?>', <?= (int)$p['order_count'] ?>)">
                                                        <i class="bi bi-trash3 me-2"></i> Delete Product...
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
    </form>
</div>

<!-- ======================================================== -->
<!-- MODAL: Delete Product Confirmation                       -->
<!-- ======================================================== -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form id="deleteProductForm" action="" method="POST">
                <?= $csrfInput ?>
                <div class="modal-header bg-danger text-white py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Product Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-3 text-dark">
                        Are you sure you want to delete <strong id="deleteProductName" class="text-danger"></strong>?
                    </p>
                    <p class="text-muted small mb-3">
                        This action will remove the product from the active catalogue.
                    </p>
                    <div id="deleteOrderNotice" class="alert alert-warning border-0 small d-none">
                        <i class="bi bi-shield-lock-fill me-1"></i>
                        <strong>Order Protection Active:</strong> This product is referenced in customer purchase history. To preserve invoice records, payment logs, and sales reports, the system will safely <strong>Discontinue and Archive</strong> the product (removing it from public shopping) while retaining all historical order data.
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

<!-- ======================================================== -->
<!-- MODAL: Bulk Action Confirmation                          -->
<!-- ======================================================== -->
<div class="modal fade" id="bulkConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-check2-square me-2"></i> Confirm Bulk Operation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-2">
                    Are you sure you want to execute <strong id="bulkActionName" class="text-primary"></strong> on <strong id="bulkItemCount" class="text-dark">0</strong> selected product(s)?
                </p>
                <p class="text-muted small mb-0">
                    If any selected products are linked to customer order history, their transaction history will be preserved by safely archiving them instead of permanent deletion.
                </p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm fw-bold" onclick="document.getElementById('bulkForm').submit()">
                    Proceed with Bulk Operation
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.product-select-checkbox');
    checkboxes.forEach(cb => cb.checked = master.checked);
    updateSelectedCount();
}

function updateSelectedCount() {
    const checked = document.querySelectorAll('.product-select-checkbox:checked');
    const notice = document.getElementById('selectedCountNotice');
    if (notice) {
        notice.textContent = checked.length + ' selected';
    }
}

function openDeleteModal(productId, productName, orderCount) {
    const form = document.getElementById('deleteProductForm');
    form.action = '<?= APP_URL ?>/admin/products/delete/' + productId;
    document.getElementById('deleteProductName').textContent = productName;
    
    const notice = document.getElementById('deleteOrderNotice');
    if (orderCount > 0) {
        notice.classList.remove('d-none');
    } else {
        notice.classList.add('d-none');
    }
    
    const modal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
    modal.show();
}

function confirmBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    const checked = document.querySelectorAll('.product-select-checkbox:checked');
    
    if (!action) {
        alert('Please select a bulk action from the dropdown menu.');
        return;
    }
    if (checked.length === 0) {
        alert('Please select at least one product using the checkboxes.');
        return;
    }
    
    document.getElementById('bulkActionName').textContent = action.toUpperCase();
    document.getElementById('bulkItemCount').textContent = checked.length;
    
    const modal = new bootstrap.Modal(document.getElementById('bulkConfirmModal'));
    modal.show();
}
</script>
