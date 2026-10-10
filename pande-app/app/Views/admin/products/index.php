<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Product Catalog & SKUs</h2>
            <p class="text-muted small mb-0">Manage hardware products, prices, stock levels, and categories</p>
        </div>
        <div>
            <a href="<?= APP_URL ?>/admin/products/create" class="btn btn-primary fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-3 p-3 mb-4">
        <form action="<?= APP_URL ?>/admin/products" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Search by name, SKU, or brand..." value="<?= e($filters['q'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (!empty($filters['category']) && (int)$filters['category'] === (int)$cat['id']) ? 'selected' : '' ?>>
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Filter</button>
                <a href="<?= APP_URL ?>/admin/products" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th class="ps-3" style="width: 35%;">Product</th>
                        <th>Category</th>
                        <th>SKU</th>
                        <th>Price (TZS)</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="pe-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No products found matching criteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light p-2 rounded text-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-laptop text-primary fs-5"></i>
                                        </div>
                                        <div>
                                            <a href="<?= APP_URL ?>/admin/products/edit/<?= $p['id'] ?>" class="fw-bold text-dark text-decoration-none hover-primary">
                                                <?= e($p['name']) ?>
                                            </a>
                                            <div class="small text-muted"><?= e($p['brand']) ?> &bull; <?= e($p['warranty_info']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="small text-secondary"><?= e($p['category_name']) ?></td>
                                <td><span class="badge bg-light text-dark border font-monospace"><?= e($p['sku']) ?></span></td>
                                <td class="fw-bold text-dark"><?= formatTZS($p['price']) ?></td>
                                <td>
                                    <?php if ($p['stock_quantity'] <= 0): ?>
                                        <span class="badge bg-danger">0 Out of Stock</span>
                                    <?php elseif ($p['stock_quantity'] <= $p['low_stock_threshold']): ?>
                                        <span class="badge bg-warning text-dark"><?= $p['stock_quantity'] ?> Low Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle"><?= $p['stock_quantity'] ?> in stock</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $p['status'] === 'active' ? 'primary' : 'secondary' ?> text-uppercase"><?= e($p['status']) ?></span>
                                </td>
                                <td class="pe-3 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="<?= APP_URL ?>/admin/products/edit/<?= $p['id'] ?>" class="btn btn-outline-dark btn-sm" title="Edit Product">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="<?= APP_URL ?>/product/<?= e($p['slug']) ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="View live">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
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
