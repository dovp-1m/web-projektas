<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus text-primary" style="font-size: 2.5rem;"></i>
                    <h2 class="fw-bold mt-2 mb-0">Create Account</h2>
                    <p class="text-muted small">Join BlogCMS as an Editor</p>
                </div>

                <!-- Validation errors summary – shows which field has an error -->
                <?php if (validation_errors()): ?>
                <div class="alert alert-danger">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Please fix these errors:</strong>
                    <?= validation_errors('<div class="small mt-1">', '</div>') ?>
                </div>
                <?php endif; ?>

                <?= form_open('register', ['class' => 'needs-validation', 'novalidate' => '']) ?>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="username"
                               name="username"
                               class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>"
                               value="<?= set_value('username') ?>"
                               placeholder="e.g. john_doe"
                               minlength="3" maxlength="50" required>
                        <?php if (form_error('username')): ?>
                        <div class="invalid-feedback"><?= form_error('username') ?></div>
                        <?php endif; ?>
                        <div class="form-text">3–50 characters, letters and numbers only.</div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control <?= form_error('email') ? 'is-invalid' : '' ?>"
                               value="<?= set_value('email') ?>"
                               placeholder="you@example.com"
                               required>
                        <?php if (form_error('email')): ?>
                        <div class="invalid-feedback"><?= form_error('email') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">
                            Password <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>"
                               minlength="8" required>
                        <?php if (form_error('password')): ?>
                        <div class="invalid-feedback"><?= form_error('password') ?></div>
                        <?php endif; ?>
                        <div class="form-text">Minimum 8 chars, at least one uppercase letter and one digit.</div>
                    </div>

                    <!-- Confirm -->
                    <div class="mb-4">
                        <label for="password_confirm" class="form-label fw-semibold">
                            Confirm Password <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               id="password_confirm"
                               name="password_confirm"
                               class="form-control <?= form_error('password_confirm') ? 'is-invalid' : '' ?>"
                               required>
                        <?php if (form_error('password_confirm')): ?>
                        <div class="invalid-feedback"><?= form_error('password_confirm') ?></div>
                        <?php endif; ?>
                    </div>

                    <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                        <i class="bi bi-person-check me-2"></i>Create Account
                    </button>

                <?= form_close() ?>

                <hr class="my-3">
                <p class="text-center text-muted small mb-0">
                    Already have an account?
                    <a href="<?= site_url('login') ?>" class="text-primary fw-semibold">Sign In</a>
                </p>
            </div>
        </div>
    </div>
</div>
