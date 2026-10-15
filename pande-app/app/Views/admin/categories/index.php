<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Product Categories &amp; Image Management</h2>
            <p class="text-muted small mb-0">Organize store departments, upload category showcase artwork, and manage storefront displays</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Categories Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark"><i class="bi bi-tags-fill text-primary me-2"></i>Active Store Departments (<?= count($categories) ?>)</span>
                    <span class="badge bg-primary-subtle text-primary">Manage Visual Images &amp; Badges</span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-3" style="width: 70px;">Image</th>
                                <th>Category Name</th>
                                <th>Slug</th>
                                <th>Icon</th>
                                <th>Sort</th>
                                <th>Status</th>
                                <th class="pe-3 text-end" style="width: 140px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="bg-light rounded border d-flex align-items-center justify-content-center overflow-hidden" style="width: 50px; height: 42px;">
                                            <?php if (!empty($cat['image_url'])): ?>
                                                <img src="<?= asset($cat['image_url']) ?>" alt="<?= e($cat['name']) ?>" class="img-fluid" style="max-height: 38px; max-width: 46px; object-fit: contain;">
                                            <?php else: ?>
                                                <i class="bi <?= e($cat['icon_class'] ?: 'bi-folder') ?> text-primary fs-5"></i>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        <div><?= e($cat['name']) ?></div>
                                        <?php if (!empty($cat['image_url'])): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem;">
                                                <i class="bi bi-image me-1"></i>Custom Visual
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-size: 0.72rem;">Default Icon</span>
                                        <?php endif; ?>
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
                                        <div class="btn-group btn-group-sm">
                                            <!-- Edit Modal Trigger -->
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $cat['id'] ?>" title="Edit Category & Image">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <form action="<?= APP_URL ?>/admin/categories/delete/<?= $cat['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete category \'<?= e($cat['name']) ?>\'? Safe rejection will occur if products exist.')">
                                                <?= $csrfInput ?>
                                                <button type="submit" class="btn btn-outline-danger" title="Delete Category">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Edit Category Modal -->
                                        <div class="modal fade text-start" id="editCategoryModal<?= $cat['id'] ?>" tabindex="-1" aria-labelledby="editCatLabel<?= $cat['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content shadow border-0">
                                                    <form action="<?= APP_URL ?>/admin/categories/update/<?= $cat['id'] ?>" method="POST" enctype="multipart/form-data">
                                                        <?= $csrfInput ?>
                                                        <div class="modal-header bg-light">
                                                            <h5 class="modal-title fw-bold text-dark" id="editCatLabel<?= $cat['id'] ?>">
                                                                <i class="bi bi-pencil-square text-primary me-2"></i>Edit Category: <?= e($cat['name']) ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-semibold text-muted">Category Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="name" class="form-control" value="<?= e($cat['name']) ?>" required>
                                                            </div>

                                                            <div class="row g-2 mb-3">
                                                                <div class="col-6">
                                                                    <label class="form-label small fw-semibold text-muted">Slug URL</label>
                                                                    <input type="text" name="slug" class="form-control form-control-sm font-monospace" value="<?= e($cat['slug']) ?>">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label small fw-semibold text-muted">Sort Order</label>
                                                                    <input type="number" name="sort_order" class="form-control form-control-sm" value="<?= (int)$cat['sort_order'] ?>">
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label small fw-semibold text-muted">Bootstrap Icon</label>
                                                                <input type="text" name="icon_class" class="form-control form-control-sm" value="<?= e($cat['icon_class'] ?: 'bi-cpu') ?>">
                                                            </div>

                                                            <!-- CATEGORY IMAGE MANAGEMENT -->
                                                            <div class="card bg-light border p-3 rounded-3 mb-3">
                                                                <label class="form-label small fw-bold text-dark d-block mb-1">
                                                                    <i class="bi bi-card-image text-primary me-1"></i> Category Visual Image
                                                                </label>
                                                                <small class="text-muted d-block mb-2">Display image on homepage, navigation cards &amp; category banners</small>
                                                                
                                                                <?php if (!empty($cat['image_url'])): ?>
                                                                    <div class="d-flex align-items-center gap-2 mb-2 p-2 bg-white rounded border">
                                                                        <img src="<?= asset($cat['image_url']) ?>" alt="Current Visual" style="width: 48px; height: 38px; object-fit: contain;">
                                                                        <div class="flex-grow-1 overflow-hidden">
                                                                            <span class="small font-monospace text-truncate d-block text-dark"><?= e($cat['image_url']) ?></span>
                                                                        </div>
                                                                        <div class="form-check form-check-inline mb-0">
                                                                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="rmImg<?= $cat['id'] ?>">
                                                                            <label class="form-check-label small text-danger" for="rmImg<?= $cat['id'] ?>">Remove</label>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <div class="mb-2">
                                                                    <label class="form-label small text-muted">Upload New Image File (JPG, PNG, WebP, SVG)</label>
                                                                    <input type="file" name="category_image_file" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp,image/svg+xml">
                                                                </div>

                                                                <div>
                                                                    <label class="form-label small text-muted">Or Asset / SVG Path</label>
                                                                    <input type="text" name="image_url" class="form-control form-control-sm font-monospace" value="<?= e($cat['image_url'] ?? '') ?>" placeholder="assets/images/products/laptop.svg">
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label small fw-semibold text-muted">Description</label>
                                                                <textarea name="description" rows="2" class="form-control form-control-sm"><?= e($cat['description'] ?? '') ?></textarea>
                                                            </div>

                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_active_<?= $cat['id'] ?>" <?= $cat['is_active'] ? 'checked' : '' ?>>
                                                                <label class="form-check-label small" for="edit_active_<?= $cat['id'] ?>">Active &amp; Visible in Storefront</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                                                                <i class="bi bi-save me-1"></i> Save Changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Category Form with Image Management -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="p-2 bg-primary-subtle text-primary rounded-2 fs-5"><i class="bi bi-folder-plus"></i></span>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Add New Category</h5>
                        <small class="text-muted">Create department &amp; assign visual media</small>
                    </div>
                </div>

                <form action="<?= APP_URL ?>/admin/categories/store" method="POST" enctype="multipart/form-data">
                    <?= $csrfInput ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. Graphic Tablets &amp; Styluses">
                    </div>

                    <!-- Category Image Upload & Media -->
                    <div class="card bg-light border p-3 rounded-3 mb-3">
                        <label class="form-label small fw-bold text-dark d-block mb-1">
                            <i class="bi bi-image-fill text-primary me-1"></i> Category Visual Image
                        </label>
                        <small class="text-muted d-block mb-2">Upload photograph or select hardware artwork</small>

                        <div class="mb-2">
                            <label class="form-label small text-muted">Upload Image File (JPG, PNG, WebP, SVG)</label>
                            <input type="file" name="category_image_file" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp,image/svg+xml">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small text-muted">Or Preset Asset Path</label>
                            <input type="text" id="newCatImageUrl" name="image_url" class="form-control form-control-sm font-monospace" placeholder="assets/images/products/laptop.svg">
                        </div>

                        <div class="d-flex flex-wrap gap-1 mt-1">
                            <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size: 0.7rem;" onclick="document.getElementById('newCatImageUrl').value='assets/images/products/laptop.svg'">Laptop</button>
                            <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size: 0.7rem;" onclick="document.getElementById('newCatImageUrl').value='assets/images/products/desktop.svg'">Desktop</button>
                            <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size: 0.7rem;" onclick="document.getElementById('newCatImageUrl').value='assets/images/products/ssd.svg'">SSD/RAM</button>
                            <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size: 0.7rem;" onclick="document.getElementById('newCatImageUrl').value='assets/images/products/networking.svg'">Network</button>
                            <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size: 0.7rem;" onclick="document.getElementById('newCatImageUrl').value='assets/images/products/printer.svg'">Printer</button>
                            <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2" style="font-size: 0.7rem;" onclick="document.getElementById('newCatImageUrl').value='assets/images/products/monitor.svg'">Monitor</button>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Bootstrap Icon</label>
                            <input type="text" name="icon_class" class="form-control form-control-sm" value="bi-cpu" placeholder="bi-laptop, bi-display...">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control form-control-sm" value="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" rows="2" class="form-control form-control-sm" placeholder="Brief explanation of items in this department..."></textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="cat_is_active" checked>
                        <label class="form-check-label small" for="cat_is_active">Active &amp; Visible in Navigation</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold py-2 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Create Category with Image
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
