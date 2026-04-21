<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex align-items-center mb-4">
            <a href="<?= site_url('posts') ?>" class="btn btn-outline-secondary btn-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Edit Post</h2>
        </div>

        <!-- Validation errors – backend errors visible in front end (requirement) -->
        <?php if (validation_errors()): ?>
        <div class="alert alert-danger">
            <strong><i class="bi bi-exclamation-triangle me-2"></i>Fix these errors:</strong>
            <?= validation_errors('<div class="small mt-1">', '</div>') ?>
        </div>
        <?php endif; ?>

        <?= form_open('posts/update/' . $post['id']) ?>
        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">

                <!-- Title – IMPORTANT: set_value() falls back to DB value, keeping data on error -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Title <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           class="form-control form-control-lg <?= form_error('title') ? 'is-invalid' : '' ?>"
                           value="<?= set_value('title', $post['title']) ?>"
                           minlength="3" maxlength="255" required>
                    <?php if (form_error('title')): ?>
                    <div class="invalid-feedback"><?= form_error('title') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Excerpt -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Excerpt</label>
                    <textarea name="excerpt"
                              class="form-control <?= form_error('excerpt') ? 'is-invalid' : '' ?>"
                              rows="2"
                              maxlength="500"><?= set_value('excerpt', $post['excerpt']) ?></textarea>
                    <?php if (form_error('excerpt')): ?>
                    <div class="invalid-feedback"><?= form_error('excerpt') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Body -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Body <span class="text-danger">*</span></label>
                    <textarea name="body"
                              class="form-control <?= form_error('body') ? 'is-invalid' : '' ?>"
                              rows="14"
                              required><?= set_value('body', $post['body']) ?></textarea>
                    <?php if (form_error('body')): ?>
                    <div class="invalid-feedback"><?= form_error('body') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                        <select name="category_id"
                                class="form-select <?= form_error('category_id') ? 'is-invalid' : '' ?>"
                                required>
                            <option value="">— Select —</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= set_select('category_id', $cat['id'], $cat['id'] == $post['category_id']) ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (form_error('category_id')): ?>
                        <div class="invalid-feedback"><?= form_error('category_id') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status"
                                class="form-select <?= form_error('status') ? 'is-invalid' : '' ?>"
                                required>
                            <option value="draft"     <?= set_select('status', 'draft',     $post['status'] === 'draft') ?>>Draft</option>
                            <option value="published" <?= set_select('status', 'published', $post['status'] === 'published') ?>>Published</option>
                        </select>
                        <?php if (form_error('status')): ?>
                        <div class="invalid-feedback"><?= form_error('status') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-muted small mt-3">
                    <i class="bi bi-clock me-1"></i>
                    Created: <?= date('M j, Y H:i', strtotime($post['created_at'])) ?>
                    &bull; Last updated: <?= date('M j, Y H:i', strtotime($post['updated_at'])) ?>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-floppy me-2"></i>Save Changes
                    </button>
                    <a href="<?= site_url('posts/view/' . $post['id']) ?>" class="btn btn-outline-secondary">Preview</a>
                    <a href="<?= site_url('posts') ?>" class="btn btn-outline-secondary ms-auto">Cancel</a>
                </div>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>
