<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex align-items-center mb-4">
            <a href="<?= site_url('posts') ?>" class="btn btn-outline-secondary btn-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>New Post</h2>
        </div>

        <!-- Validation errors -->
        <?php if (validation_errors()): ?>
        <div class="alert alert-danger">
            <strong><i class="bi bi-exclamation-triangle me-2"></i>Fix these errors before saving:</strong>
            <?= validation_errors('<div class="small mt-1">', '</div>') ?>
        </div>
        <?php endif; ?>

        <?= form_open('posts/store') ?>
        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">

                <!-- Title – validator 1,2,3 -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Title <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           class="form-control form-control-lg <?= form_error('title') ? 'is-invalid' : '' ?>"
                           value="<?= set_value('title') ?>"
                           placeholder="Post title…"
                           minlength="3" maxlength="255" required>
                    <?php if (form_error('title')): ?>
                    <div class="invalid-feedback"><?= form_error('title') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Excerpt – validator 6 -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Excerpt <span class="text-muted small">(optional)</span></label>
                    <textarea name="excerpt"
                              class="form-control <?= form_error('excerpt') ? 'is-invalid' : '' ?>"
                              rows="2"
                              maxlength="500"
                              placeholder="Short summary shown on the blog list…"><?= set_value('excerpt') ?></textarea>
                    <?php if (form_error('excerpt')): ?>
                    <div class="invalid-feedback"><?= form_error('excerpt') ?></div>
                    <?php endif; ?>
                    <div class="form-text">Maximum 500 characters.</div>
                </div>

                <!-- Body – validators 4,5 -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Body <span class="text-danger">*</span>
                    </label>
                    <textarea name="body"
                              class="form-control <?= form_error('body') ? 'is-invalid' : '' ?>"
                              rows="14"
                              placeholder="Write your post content here…"
                              required><?= set_value('body') ?></textarea>
                    <?php if (form_error('body')): ?>
                    <div class="invalid-feedback"><?= form_error('body') ?></div>
                    <?php endif; ?>
                    <div class="form-text">Minimum 20 characters.</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-3">

                    <!-- Category – validators 7,8 -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Category <span class="text-danger">*</span>
                        </label>
                        <select name="category_id"
                                class="form-select <?= form_error('category_id') ? 'is-invalid' : '' ?>"
                                required>
                            <option value="">— Select category —</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= set_select('category_id', $cat['id']) ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (form_error('category_id')): ?>
                        <div class="invalid-feedback"><?= form_error('category_id') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Status – validator 9 -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status"
                                class="form-select <?= form_error('status') ? 'is-invalid' : '' ?>"
                                required>
                            <option value="draft"      <?= set_select('status', 'draft',     TRUE) ?>>Draft</option>
                            <option value="published"  <?= set_select('status', 'published')       ?>>Published</option>
                        </select>
                        <?php if (form_error('status')): ?>
                        <div class="invalid-feedback"><?= form_error('status') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-floppy me-2"></i>Save Post
                    </button>
                    <a href="<?= site_url('posts') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>

        <?= form_close() ?>
    </div>
</div>
