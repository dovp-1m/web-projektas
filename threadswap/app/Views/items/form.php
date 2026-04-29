<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm" style="max-width:700px">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4"><?= esc($title) ?></h4>

        <?php $isEdit = isset($item); ?>
        <form action="<?= base_url($isEdit ? 'my/listings/edit/' . $item['id'] : 'my/listings/create') ?>"
              method="post" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Title -->
                <div class="col-12">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" maxlength="200"
                           class="form-control <?= isset($validation) && $validation->hasError('title') ? 'is-invalid' : '' ?>"
                           value="<?= esc($old['title'] ?? $item['title'] ?? '') ?>">
                    <?php if (isset($validation) && $validation->hasError('title')): ?>
                        <div class="field-error"><?= $validation->getError('title') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Brand -->
                <div class="col-md-6">
                    <label class="form-label">Brand *</label>
                    <input type="text" name="brand"
                           class="form-control <?= isset($validation) && $validation->hasError('brand') ? 'is-invalid' : '' ?>"
                           value="<?= esc($old['brand'] ?? $item['brand'] ?? '') ?>">
                    <?php if (isset($validation) && $validation->hasError('brand')): ?>
                        <div class="field-error"><?= $validation->getError('brand') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Category -->
                <div class="col-md-6">
                    <label class="form-label">Category *</label>
                    <select name="category_id"
                            class="form-select <?= isset($validation) && $validation->hasError('category_id') ? 'is-invalid' : '' ?>">
                        <option value="">Select category...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= ($old['category_id'] ?? $item['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('category_id')): ?>
                        <div class="field-error"><?= $validation->getError('category_id') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Price -->
                <div class="col-md-4">
                    <label class="form-label">Price (€) *</label>
                    <input type="number" name="price" step="0.01" min="0.01" max="9999"
                           class="form-control <?= isset($validation) && $validation->hasError('price') ? 'is-invalid' : '' ?>"
                           value="<?= esc($old['price'] ?? $item['price'] ?? '') ?>">
                    <?php if (isset($validation) && $validation->hasError('price')): ?>
                        <div class="field-error"><?= $validation->getError('price') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Size -->
                <div class="col-md-4">
                    <label class="form-label">Size *</label>
                    <select name="size"
                            class="form-select <?= isset($validation) && $validation->hasError('size') ? 'is-invalid' : '' ?>">
                        <option value="">Select...</option>
                        <?php foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL', 'One Size'] as $s): ?>
                            <option value="<?= $s ?>"
                                <?= ($old['size'] ?? $item['size'] ?? '') === $s ? 'selected' : '' ?>>
                                <?= $s ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('size')): ?>
                        <div class="field-error"><?= $validation->getError('size') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Condition -->
                <div class="col-md-4">
                    <label class="form-label">Condition *</label>
                    <select name="condition"
                            class="form-select <?= isset($validation) && $validation->hasError('condition') ? 'is-invalid' : '' ?>">
                        <option value="">Select...</option>
                        <?php foreach (['new' => 'New', 'like_new' => 'Like New', 'good' => 'Good', 'fair' => 'Fair'] as $v => $l): ?>
                            <option value="<?= $v ?>"
                                <?= ($old['condition'] ?? $item['condition'] ?? '') === $v ? 'selected' : '' ?>>
                                <?= $l ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('condition')): ?>
                        <div class="field-error"><?= $validation->getError('condition') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="form-label">Description *</label>
                    <textarea name="description" rows="4" maxlength="2000"
                              class="form-control <?= isset($validation) && $validation->hasError('description') ? 'is-invalid' : '' ?>"
                    ><?= esc($old['description'] ?? $item['description'] ?? '') ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('description')): ?>
                        <div class="field-error"><?= $validation->getError('description') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Status (edit only) -->
                <?php if ($isEdit): ?>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <?php foreach (['active' => 'Active', 'sold' => 'Sold', 'hidden' => 'Hidden'] as $v => $l): ?>
                                <option value="<?= $v ?>"
                                    <?= ($old['status'] ?? $item['status'] ?? 'active') === $v ? 'selected' : '' ?>>
                                    <?= $l ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Image -->
                <div class="col-12">
                    <label class="form-label">
                        Photo <?= $isEdit ? '(leave empty to keep current)' : '' ?>
                    </label>
                    <?php if ($isEdit && !empty($item['image'])): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('uploads/' . $item['image']) ?>"
                                 style="max-height:120px; border-radius:8px; object-fit:cover;"
                                 alt="Current photo">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <?php if (isset($validation) && $validation->hasError('image')): ?>
                        <div class="field-error"><?= $validation->getError('image') ?></div>
                    <?php endif; ?>
                    <div class="form-text">JPG, PNG, or WebP. Maximum 2 MB.</div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-dark">
                    <?= $isEdit ? 'Save Changes' : 'List Item' ?>
                </button>
                <a href="<?= base_url('my/listings') ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>