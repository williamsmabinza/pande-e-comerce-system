<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Product Categories</h2>
            <p class="text-muted small mb-0">Organize and manage departments across the Pande Computers storefront</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Categories Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3">Category Name</th>
                                <th>Slug</th>
                                <th>Icon</th>
                                <th>Sort</th>
                                <th>Status</th>
                                <th class="pe-3 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">
                                        <i class="bi <?= e($cat['icon_class'] ?: 'bi-folder') ?> text-primary me-2"></i>
                                        <?= e($cat['name']) ?>
                                    </td>
                                    <td class="font-monospace small text-muted"><?= e($cat['slug']) ?></td>
                                    <td><code><?= e($cat['icon_class'] ?: 'bi-cpu') ?></code></td>
                                    <td><?= (int)$cat['sort_order'] ?></td>
                                    <td>
                                        <span class="badge bg-<?= $cat['is_active'] ? 'success' : 'secondary' ?>">
                                            <?= $cat['is_active'] ? 'Active' : 'Disabled' ?>
                                        </span>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <form action="<?= APP_URL ?>/admin/categories/delete/<?= $cat['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this category? If products exist, deletion will be safely rejected.')">
                                            <?= $csrfInput ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm border-0" title="Delete Category">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Category Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h5 class="fw-bold text-dark mb-3">Add New Category</h5>

                <form action="<?= APP_URL ?>/admin/categories/store" method="POST">
                    <?= $csrfInput ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. Graphic Tablets & Styluses">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bootstrap Icon Class</label>
                        <input type="text" name="icon_class" class="form-control form-control-sm" value="bi-cpu" placeholder="bi-laptop, bi-display, bi-printer...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Display Sort Order</label>
                        <input type="number" name="sort_order" class="form-control form-control-sm" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" rows="2" class="form-control form-control-sm" placeholder="Brief explanation of items in this department..."></textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="cat_is_active" checked>
                        <label class="form-check-label small" for="cat_is_active">Active & Visible in Navigation</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold py-2 shadow-sm">
                        Create Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
