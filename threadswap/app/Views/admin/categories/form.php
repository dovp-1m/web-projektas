<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm" style="max-width:560px">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4"><?= esc($title) ?></h5>

        <?php $isEdit = isset($category); ?>
        <form action="<?= base_url($isEdit ? 'admin/categories/edit/' . $category['id'] : 'admin/categories/create') ?>"
              method="post" novalidate>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" name="name"
                       class="form-control <?= isset($validation) && $validation->hasError('name') ? 'is-invalid' : '' ?>"
                       value="<?= esc($old['name'] ?? $category['name'] ?? '') ?>">
                <?php if (isset($validation) && $validation->hasError('name')): ?>
                    <div class="field-error"><?= $validation->getError('name') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"
                ><?= esc($old['description'] ?? $category['description'] ?? '') ?></textarea>
            </div>

            <?php if ($isEdit): ?>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive"
                           <?= ($old['is_active'] ?? $category['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="isActive">Active (visible to users)</label>
                </div>
            <?php endif; ?>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">Save Category</button>
                <a href="<?= base_url('admin/categories') ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>