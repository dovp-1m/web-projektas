<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h4 class="fw-bold mb-4">
    <i class="bi bi-shield-lock text-warning me-2"></i>Admin Dashboard
</h4>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="display-5 fw-bold text-primary"><?= number_format($userCount) ?></div>
            <div class="text-muted small">Registered Users</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="display-5 fw-bold text-success"><?= number_format($itemCount) ?></div>
            <div class="text-muted small">Total Listings</div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="<?= base_url('admin/categories') ?>" class="btn btn-dark btn-sm">
        <i class="bi bi-tags me-1"></i>Categories
    </a>
    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-people me-1"></i>Users
    </a>
    <a href="<?= base_url('admin/items') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-bag me-1"></i>All Items
    </a>
    <a href="<?= base_url('admin/logs') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-journal-text me-1"></i>System Logs
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header fw-semibold">
        <i class="bi bi-activity me-1"></i>Recent Log Entries
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>Time</th>
                    <th>Level</th>
                    <th>Class::Method</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentLogs)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">No logs yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td class="small text-muted"><?= $log['created_at'] ?></td>
                            <td>
                                <span class="badge bg-<?= $log['level'] === 'ERROR' ? 'danger' : ($log['level'] === 'WARNING' ? 'warning text-dark' : 'info text-dark') ?>">
                                    <?= $log['level'] ?>
                                </span>
                            </td>
                            <td class="small">
                                <code><?= esc($log['class']) ?>::<?= esc($log['method']) ?></code>
                            </td>
                            <td class="small"><?= esc($log['message']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>