<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex align-items-center mb-4">
            <a href="<?= site_url('categories') ?>" class="btn btn-outline-secondary btn-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Edit Category</h2>
        </div>

        <?php if (validation_errors()): ?>
        <div class="alert alert-danger">
            <strong><i class="bi bi-exclamation-triangle me-2"></i>Please fix:</strong>
            <?= validation_errors('<div class="small mt-1">', '</div>') ?>
        </div>
        <?php endif; ?>

        <?= form_open('categories/update/' . $category['id']) ?>
        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control <?= form_error('name') ? 'is-invalid' : '' ?>"
                           value="<?= set_value('name', $category['name']) ?>"
                           minlength="2" maxlength="100" required>
                    <?php if (form_error('name')): ?>
                    <div class="invalid-feedback"><?= form_error('name') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Slug <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text text-muted">/blog/category/</span>
                        <input type="text" name="slug"
                               class="form-control <?= form_error('slug') ? 'is-invalid' : '' ?>"
                               value="<?= set_value('slug', $category['slug']) ?>"
                               minlength="2" maxlength="100"
                               pattern="[a-zA-Z0-9_\-]+" required>
                        <?php if (form_error('slug')): ?>
                        <div class="invalid-feedback"><?= form_error('slug') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description"
                              class="form-control <?= form_error('description') ? 'is-invalid' : '' ?>"
                              rows="3" maxlength="1000"><?= set_value('description', $category['description']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Colour <span class="text-danger">*</span></label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="color" name="color"
                               class="form-control form-control-color"
                               value="<?= set_value('color', $category['color']) ?>"
                               style="width:50px;height:38px;">
                        <input type="text" id="colorText"
                               class="form-control"
                               value="<?= set_value('color', $category['color']) ?>"
                               placeholder="#0d6efd" readonly>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-floppy me-2"></i>Save Changes
                    </button>
                    <a href="<?= site_url('categories') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var picker = document.querySelector('input[type="color"]');
    var text   = document.getElementById('colorText');
    if (picker && text) {
        picker.addEventListener('input', function () { text.value = this.value; });
    }
});
</script>
