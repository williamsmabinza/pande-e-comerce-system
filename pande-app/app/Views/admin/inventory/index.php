<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Stock & Inventory Management</h2>
            <p class="text-muted small mb-0">Live stock counts, threshold alerts, and inventory movement audit logs</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                <i class="bi bi-sliders me-1"></i> Adjust Product Stock
            </button>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="mb-3 d-flex gap-2">
        <a href="<?= APP_URL ?>/admin/inventory" class="btn btn-sm <?= $filter === 'all' ? 'btn-dark' : 'btn-outline-dark' ?>">All Items</a>
        <a href="<?= APP_URL ?>/admin/inventory?filter=low" class="btn btn-sm <?= $filter === 'low' ? 'btn-warning' : 'btn-outline-warning' ?>">
            <i class="bi bi-exclamation-circle me-1"></i> Low Stock Alerts
        </a>
        <a href="<?= APP_URL ?>/admin/inventory?filter=out" class="btn btn-sm <?= $filter === 'out' ? 'btn-danger' : 'btn-outline-danger' ?>">
            <i class="bi bi-x-circle me-1"></i> Out of Stock
        </a>
    </div>

    <div class="row g-4">
        <!-- Products Stock Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="fw-bold mb-0 text-dark">Current Stock Levels</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Product Name</th>
                                <th>Category</th>
                                <th>SKU</th>
                                <th>Selling Price</th>
                                <th>Stock</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $p): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">
                                        <?= e($p['name']) ?>
                                    </td>
                                    <td class="small text-muted"><?= e($p['category_name']) ?></td>
                                    <td><span class="badge bg-light text-dark border font-monospace"><?= e($p['sku']) ?></span></td>
                                    <td><?= formatTZS($p['price']) ?></td>
                                    <td>
                                        <?php if ($p['stock_quantity'] <= 0): ?>
                                            <span class="badge bg-danger">0 Out of Stock</span>
                                        <?php elseif ($p['stock_quantity'] <= $p['low_stock_threshold']): ?>
                                            <span class="badge bg-warning text-dark"><?= $p['stock_quantity'] ?> Low (Threshold: <?= $p['low_stock_threshold'] ?>)</span>
                                        <?php else: ?>
                                            <span class="badge bg-success-subtle text-success border"><?= $p['stock_quantity'] ?> units</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <button type="button" class="btn btn-outline-dark btn-sm" onclick="setAdjustProduct(<?= $p['id'] ?>, '<?= addslashes(e($p['name'])) ?>', <?= (int)$p['stock_quantity'] ?>)">
                                            Adjust
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Inventory Movements History -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-3">
                <h5 class="fw-bold mb-3 text-dark">Recent Movement Logs</h5>
                <div class="movements-list" style="max-height: 550px; overflow-y: auto;">
                    <?php if (empty($movements)): ?>
                        <div class="text-center text-muted py-3 small">No stock movements logged.</div>
                    <?php else: ?>
                        <?php foreach ($movements as $m): ?>
                            <div class="p-2 border-bottom small">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark line-clamp-1"><?= e($m['product_name']) ?></strong>
                                    <span class="badge bg-<?= $m['quantity_change'] >= 0 ? 'success' : 'danger' ?>">
                                        <?= $m['quantity_change'] > 0 ? '+' : '' ?><?= $m['quantity_change'] ?>
                                    </span>
                                </div>
                                <div class="text-secondary"><?= e($m['reason']) ?></div>
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                    <span>Stock: <?= $m['previous_stock'] ?> &rarr; <?= $m['new_stock'] ?></span>
                                    <span><?= date('M d, H:i', strtotime($m['created_at'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="<?= APP_URL ?>/admin/inventory/adjust" method="POST">
                <?= $csrfInput ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Manual Stock Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Select Product</label>
                        <select name="product_id" id="modal_product_id" class="form-select" required>
                            <option value="">Select a product...</option>
                            <?php foreach ($products as $pr): ?>
                                <option value="<?= $pr['id'] ?>"><?= e($pr['name']) ?> (Current: <?= $pr['stock_quantity'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Adjustment Action</label>
                        <select name="adjustment_type" class="form-select" required>
                            <option value="add">Add Received Stock (+)</option>
                            <option value="subtract">Deduct Damaged / Lost Stock (-)</option>
                            <option value="set">Set Exact Count (=)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Units Quantity</label>
                        <input type="number" name="quantity" class="form-control" required min="0" placeholder="e.g. 5">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Reason for Adjustment <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control" required placeholder="e.g. New shipment arrived from distributor">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save Stock Movement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setAdjustProduct(id, name, qty) {
    document.getElementById('modal_product_id').value = id;
    const modal = new bootstrap.Modal(document.getElementById('adjustStockModal'));
    modal.show();
}
</script>
