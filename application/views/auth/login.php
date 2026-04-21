<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-journal-richtext text-primary" style="font-size: 2.5rem;"></i>
                    <h2 class="fw-bold mt-2 mb-0">Welcome back</h2>
                    <p class="text-muted small">Sign in to your account</p>
                </div>

                <!-- Validation errors summary -->
                <?php if (validation_errors()): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= validation_errors('<div class="small">', '</div>') ?>
                </div>
                <?php endif; ?>

                <?= form_open('login', ['class' => 'needs-validation', 'novalidate' => '']) ?>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text"
                                   id="username"
                                   name="username"
                                   class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>"
                                   value="<?= set_value('username') ?>"
                                   placeholder="your_username"
                                   required>
                            <?php if (form_error('username')): ?>
                            <div class="invalid-feedback"><?= form_error('username') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>"
                                   placeholder="••••••••"
                                   required>
                            <?php if (form_error('password')): ?>
                            <div class="invalid-feedback"><?= form_error('password') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- CSRF -->
                    <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </button>

                <?= form_close() ?>

                <hr class="my-3">
                <p class="text-center text-muted small mb-0">
                    Don't have an account?
                    <a href="<?= site_url('register') ?>" class="text-primary fw-semibold">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>
