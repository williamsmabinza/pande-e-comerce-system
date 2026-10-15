<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-navy text-white px-2 py-1"><i class="bi bi-boxes me-1"></i> Inventory Ledger</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <?= $kpis['totalUnits'] ?> Total Stock Units
                </span>
            </div>
            <h2 class="fw-black text-dark mb-0">Stock & Inventory Management</h2>
            <p class="text-muted small mb-0">Live stock counts, threshold monitors, supplier receipts, and complete movement audit history</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= APP_URL ?>/admin/products" class="btn btn-outline-dark btn-sm fw-semibold">
                <i class="bi bi-laptop me-1"></i> Product Catalog
            </a>
            <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                <i class="bi bi-sliders me-1"></i> Record Stock Movement
            </button>
        </div>
    </div>

    <!-- Inventory KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-info">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Monitored SKUs</div>
                <div class="fs-4 fw-black text-info mt-1"><?= $kpis['totalItems'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Active product lines</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-success">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Healthy Stock</div>
                <div class="fs-4 fw-black text-success mt-1"><?= $kpis['healthyCount'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Above safety threshold</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-warning">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Low Stock Alerts</div>
                <div class="fs-4 fw-black text-warning mt-1"><?= $kpis['lowCount'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Reorder recommended</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-danger">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Stockouts</div>
                <div class="fs-4 fw-black text-danger mt-1"><?= $kpis['outCount'] ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">0 units remaining</div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-primary">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Inventory Valuation</div>
                <div class="fs-5 fw-black text-primary mt-1"><?= formatTZS($kpis['retailValuation']) ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Retail valuation</div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white h-100 border-start border-4 border-dark">
                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.68rem;">Acquisition Cost Basis</div>
                <div class="fs-5 fw-black text-dark mt-1"><?= formatTZS($kpis['costValuation']) ?></div>
                <div class="small text-muted" style="font-size: 0.72rem;">Cost basis valuation</div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 p-3 mb-4 bg-white">
        <form action="<?= APP_URL ?>/admin/inventory" method="GET" class="row g-2 align-items-center">
            <div class="col-lg-5 col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Search product name, SKU, brand..." value="<?= e($q ?? '') ?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (!empty($catId) && (int)$catId === (int)$cat['id']) ? 'selected' : '' ?>>
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3 col-md-8">
                <div class="btn-group btn-group-sm w-100" role="group">
                    <a href="<?= APP_URL ?>/admin/inventory?filter=all<?= $q ? '&q=' . urlencode($q) : '' ?>" class="btn <?= $filter === 'all' ? 'btn-dark' : 'btn-outline-secondary' ?>">All</a>
                    <a href="<?= APP_URL ?>/admin/inventory?filter=healthy<?= $q ? '&q=' . urlencode($q) : '' ?>" class="btn <?= $filter === 'healthy' ? 'btn-success text-white' : 'btn-outline-success' ?>">Healthy</a>
                    <a href="<?= APP_URL ?>/admin/inventory?filter=low<?= $q ? '&q=' . urlencode($q) : '' ?>" class="btn <?= $filter === 'low' ? 'btn-warning text-dark' : 'btn-outline-warning' ?>">Low</a>
                    <a href="<?= APP_URL ?>/admin/inventory?filter=out<?= $q ? '&q=' . urlencode($q) : '' ?>" class="btn <?= $filter === 'out' ? 'btn-danger' : 'btn-outline-danger' ?>">Out</a>
                </div>
            </div>
            <div class="col-lg-1 col-md-4 d-flex gap-1">
                <button type="submit" class="btn btn-dark btn-sm w-100 fw-semibold">Filter</button>
                <a href="<?= APP_URL ?>/admin/inventory" class="btn btn-outline-secondary btn-sm" title="Clear"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <!-- Main Two-Column Layout: Stock Levels (Left) & Movement Logs (Right) -->
    <div class="row g-4">
        <!-- Products Stock Table (Left) -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-card-checklist text-primary me-2"></i> Current Stock Balances</h5>
                    <span class="badge bg-light text-muted border"><?= count($products) ?> SKUs</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3" style="width: 32%;">Product & Brand</th>
                                <th>Category</th>
                                <th>SKU</th>
                                <th>Pricing (TZS)</th>
                                <th>Stock Units</th>
                                <th>Threshold</th>
                                <th class="pe-3 text-end" style="width: 130px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No products match the selected inventory criteria.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark line-clamp-1"><?= e($p['name']) ?></div>
                                            <div class="small text-muted"><?= e($p['brand']) ?></div>
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
                                                <div class="small text-muted" style="font-size: 0.7rem;">Cost: <?= formatTZS($p['cost_price']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($p['stock_quantity'] <= 0): ?>
                                                <span class="badge bg-danger">0 Out of Stock</span>
                                            <?php elseif ($p['stock_quantity'] <= $p['low_stock_threshold']): ?>
                                                <span class="badge bg-warning text-dark"><?= $p['stock_quantity'] ?> Low</span>
                                            <?php else: ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle"><?= $p['stock_quantity'] ?> in stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2 small" onclick="openThresholdModal(<?= $p['id'] ?>, '<?= addslashes(e($p['name'])) ?>', <?= (int)$p['low_stock_threshold'] ?>)" title="Set Safety Threshold">
                                                Min: <?= $p['low_stock_threshold'] ?> <i class="bi bi-pencil" style="font-size: 0.65rem;"></i>
                                            </button>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <button type="button" class="btn btn-outline-dark btn-sm fw-semibold" onclick="setAdjustProduct(<?= $p['id'] ?>, '<?= addslashes(e($p['name'])) ?>', <?= (int)$p['stock_quantity'] ?>)">
                                                <i class="bi bi-sliders me-1"></i> Adjust
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Inventory Movements History (Right) -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history text-secondary me-2"></i> Stock Movement Ledger</h5>
                        <small class="text-muted">Recorded audit trail of receipts & adjustments</small>
                    </div>
                </div>
                <div class="movements-list" style="max-height: 650px; overflow-y: auto;">
                    <?php if (empty($movements)): ?>
                        <div class="text-center text-muted py-4 small">
                            <i class="bi bi-clipboard-x fs-2 text-muted d-block mb-2"></i>
                            No stock movements recorded in ledger.
                        </div>
                    <?php else: ?>
                        <?php foreach ($movements as $m): ?>
                            <div class="p-2 border-bottom small">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark line-clamp-1" style="max-width: 220px;" title="<?= e($m['product_name']) ?>">
                                        <?= e($m['product_name']) ?>
                                    </strong>
                                    <span class="badge bg-<?= $m['quantity_change'] >= 0 ? 'success' : 'danger' ?>">
                                        <?= $m['quantity_change'] > 0 ? '+' : '' ?><?= $m['quantity_change'] ?> units
                                    </span>
                                </div>
                                <div class="text-secondary mb-1" style="font-size: 0.78rem;">
                                    <?= e($m['reason']) ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.72rem;">
                                    <span>
                                        <i class="bi bi-person-fill text-secondary"></i>
                                        <?= !empty($m['first_name']) ? e($m['first_name'] . ' ' . $m['last_name']) : 'System' ?>
                                    </span>
                                    <span>
                                        Stock: <strong><?= $m['previous_stock'] ?></strong> &rarr; <strong><?= $m['new_stock'] ?></strong>
                                    </span>
                                    <span><?= date('d M H:i', strtotime($m['created_at'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Record Stock Movement                            -->
<!-- ======================================================== -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="<?= APP_URL ?>/admin/inventory/adjust" method="POST">
                <?= $csrfInput ?>
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-sliders me-2"></i> Record Stock Movement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Target Product <span class="text-danger">*</span></label>
                        <select name="product_id" id="modal_product_id" class="form-select" required>
                            <option value="">Select a product...</option>
                            <?php foreach ($products as $pr): ?>
                                <option value="<?= $pr['id'] ?>"><?= e($pr['name']) ?> (Current Stock: <?= $pr['stock_quantity'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Adjustment Action Type <span class="text-danger">*</span></label>
                        <select name="adjustment_type" class="form-select" required>
                            <option value="add">Add Received Units (+) — e.g. Supplier delivery or restock</option>
                            <option value="subtract">Deduct Units (-) — e.g. Damaged, defective, or write-off</option>
                            <option value="set">Set Exact Count (=) — e.g. Inventory audit correction</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Units Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control form-control-lg fw-bold text-primary" required min="0" placeholder="e.g. 10">
                        <div class="form-text small">Quantity cannot be negative. For deductions, stock will not drop below zero.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Movement Reason Category <span class="text-danger">*</span></label>
                        <select name="reason_type" class="form-select" required>
                            <option value="Supplier Shipment Received">Supplier Shipment Received</option>
                            <option value="Physical Inventory Audit Correction">Physical Inventory Audit Correction</option>
                            <option value="Damaged / Defective Stock Write-Off">Damaged / Defective Stock Write-Off</option>
                            <option value="Customer Return to Stock">Customer Return to Stock</option>
                            <option value="Internal Showroom / Demo Unit">Internal Showroom / Demo Unit</option>
                            <option value="Stock Shrinkage / Missing Units">Stock Shrinkage / Missing Units</option>
                            <option value="Other Manual Adjustment">Other Manual Adjustment</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Internal Notes / Supplier Reference</label>
                        <input type="text" name="notes" class="form-control" placeholder="e.g. Delivery Note #DN-2026-884 from Redington Tanzania">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Post Movement to Ledger
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: Update Safety Threshold                           -->
<!-- ======================================================== -->
<div class="modal fade" id="thresholdModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow">
            <form action="<?= APP_URL ?>/admin/inventory/threshold" method="POST">
                <?= $csrfInput ?>
                <input type="hidden" name="product_id" id="threshold_product_id">
                <div class="modal-header bg-dark text-white py-2">
                    <h6 class="modal-title fw-bold">Low-Stock Alert Level</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <p class="small text-muted mb-2" id="threshold_product_name"></p>
                    <label class="form-label small fw-semibold">Minimum Safety Threshold</label>
                    <input type="number" name="threshold" id="threshold_input" class="form-control fw-bold" required min="0">
                    <div class="form-text small">Alert triggers when units are at or below this value.</div>
                </div>
                <div class="modal-footer bg-light p-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold">Save Threshold</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setAdjustProduct(id, name, qty) {
    const select = document.getElementById('modal_product_id');
    if (select) {
        select.value = id;
    }
    const modal = new bootstrap.Modal(document.getElementById('adjustStockModal'));
    modal.show();
}

function openThresholdModal(id, name, currentThreshold) {
    document.getElementById('threshold_product_id').value = id;
    document.getElementById('threshold_product_name').textContent = name;
    document.getElementById('threshold_input').value = currentThreshold;
    const modal = new bootstrap.Modal(document.getElementById('thresholdModal'));
    modal.show();
}
</script>
