<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="auth-card">
    <h3 class="fw-bold mb-1">Welcome Back</h3>
    <p class="text-muted mb-4">Log in to your ThreadSwap account.</p>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= esc($error) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email"
                   class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>"
                   value="<?= esc($old['email'] ?? '') ?>">
            <?php if (isset($validation) && $validation->hasError('email')): ?>
                <div class="field-error"><?= $validation->getError('email') ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Password *</label>
            <input type="password" name="password"
                   class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>">
            <?php if (isset($validation) && $validation->hasError('password')): ?>
                <div class="field-error"><?= $validation->getError('password') ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-dark w-100 mt-2">Log In</button>
        <p class="text-center text-muted mt-3 mb-0">
            No account? <a href="<?= base_url('register') ?>">Sign up free</a>
        </p>
    </form>
</div>

<?= $this->endSection() ?>