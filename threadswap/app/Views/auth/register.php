<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="auth-card">
    <h3 class="fw-bold mb-1">Create Account</h3>
    <p class="text-muted mb-4">Join ThreadSwap and start selling today.</p>

    <form action="<?= base_url('register') ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <div class="row g-3">
            <div class="col-6">
                <label class="form-label">First Name *</label>
                <input type="text" name="first_name"
                       class="form-control <?= isset($validation) && $validation->hasError('first_name') ? 'is-invalid' : '' ?>"
                       value="<?= esc($old['first_name'] ?? '') ?>">
                <?php if (isset($validation) && $validation->hasError('first_name')): ?>
                    <div class="field-error"><?= $validation->getError('first_name') ?></div>
                <?php endif; ?>
            </div>

            <div class="col-6">
                <label class="form-label">Last Name *</label>
                <input type="text" name="last_name"
                       class="form-control <?= isset($validation) && $validation->hasError('last_name') ? 'is-invalid' : '' ?>"
                       value="<?= esc($old['last_name'] ?? '') ?>">
                <?php if (isset($validation) && $validation->hasError('last_name')): ?>
                    <div class="field-error"><?= $validation->getError('last_name') ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12">
                <label class="form-label">Username *</label>
                <input type="text" name="username"
                       class="form-control <?= isset($validation) && $validation->hasError('username') ? 'is-invalid' : '' ?>"
                       value="<?= esc($old['username'] ?? '') ?>">
                <?php if (isset($validation) && $validation->hasError('username')): ?>
                    <div class="field-error"><?= $validation->getError('username') ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12">
                <label class="form-label">Email *</label>
                <input type="email" name="email"
                       class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>"
                       value="<?= esc($old['email'] ?? '') ?>">
                <?php if (isset($validation) && $validation->hasError('email')): ?>
                    <div class="field-error"><?= $validation->getError('email') ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12">
                <label class="form-label">Password *</label>
                <input type="password" name="password"
                       class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>">
                <?php if (isset($validation) && $validation->hasError('password')): ?>
                    <div class="field-error"><?= $validation->getError('password') ?></div>
                <?php endif; ?>
                <div class="form-text">At least 8 characters.</div>
            </div>

            <div class="col-12">
                <label class="form-label">Confirm Password *</label>
                <input type="password" name="password_confirm"
                       class="form-control <?= isset($validation) && $validation->hasError('password_confirm') ? 'is-invalid' : '' ?>">
                <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
                    <div class="field-error"><?= $validation->getError('password_confirm') ?></div>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-dark w-100 mt-4">Create Account</button>
        <p class="text-center text-muted mt-3 mb-0">
            Already have an account? <a href="<?= base_url('login') ?>">Log in</a>
        </p>
    </form>
</div>

<?= $this->endSection() ?>