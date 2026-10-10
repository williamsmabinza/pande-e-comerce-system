<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Reports & Analytics</h2>
            <p class="text-muted small mb-0">Sales summaries, catalog valuation, and CSV data export</p>
        </div>
        <div>
            <a href="<?= APP_URL ?>/admin/reports/export-orders" class="btn btn-primary fw-semibold">
                <i class="bi bi-download me-1"></i> Export Orders to CSV
            </a>
        </div>
    </div>

    <!-- Inventory Valuation Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white">
                <span class="text-muted small text-uppercase fw-semibold">Total Stock Units</span>
                <h3 class="fw-black text-dark mb-0 mt-1"><?= (int)($valuation['total_units'] ?? 0) ?> items</h3>
                <span class="small text-muted">Across all active warehouse categories</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-primary">
                <span class="text-muted small text-uppercase fw-semibold">Total Retail Value</span>
                <h3 class="fw-black text-primary mb-0 mt-1"><?= formatTZS($valuation['total_retail_value'] ?? 0) ?></h3>
                <span class="small text-muted">Current catalog selling price value</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-4 border-info">
                <span class="text-muted small text-uppercase fw-semibold">Estimated Cost Value</span>
                <h3 class="fw-black text-dark mb-0 mt-1"><?= formatTZS($valuation['total_cost_value'] ?? 0) ?></h3>
                <span class="small text-muted">Estimated wholesale purchasing capital</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Daily Sales Revenue Summary -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="fw-bold mb-0 text-dark">Daily Sales Revenue (Last 14 Days)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Orders</th>
                                <th class="pe-3 text-end">Total Revenue (TZS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($dailySales)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">No verified sales in this window.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($dailySales as $ds): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-dark"><?= date('D, M d, Y', strtotime($ds['sale_date'])) ?></td>
                                        <td><span class="badge bg-secondary"><?= $ds['orders_count'] ?></span></td>
                                        <td class="pe-3 text-end fw-black text-success"><?= formatTZS($ds['revenue']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Best Sellers -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="fw-bold mb-0 text-dark">Best-Selling Products by Revenue</h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Item</th>
                                <th>Units Sold</th>
                                <th class="pe-3 text-end">Gross Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bestSellers)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">No sold items recorded.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bestSellers as $bs): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-dark small">
                                            <?= e($bs['product_name']) ?>
                                            <div class="text-muted font-monospace" style="font-size: 0.75rem;"><?= e($bs['sku']) ?></div>
                                        </td>
                                        <td><span class="badge bg-primary"><?= $bs['total_units'] ?> units</span></td>
                                        <td class="pe-3 text-end fw-bold"><?= formatTZS($bs['total_revenue']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
