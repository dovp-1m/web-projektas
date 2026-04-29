<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-journal-text me-2"></i>System Logs</h4>
    <a href="<?= base_url('admin') ?>" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Time</th>
                    <th>Level</th>
                    <th>User</th>
                    <th>Class::Method</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No log entries yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-muted small"><?= $log['id'] ?></td>
                            <td class="text-muted small" style="white-space:nowrap"><?= $log['created_at'] ?></td>
                            <td>
                                <span class="badge bg-<?= $log['level'] === 'ERROR' ? 'danger' : ($log['level'] === 'WARNING' ? 'warning text-dark' : 'info text-dark') ?>">
                                    <?= $log['level'] ?>
                                </span>
                            </td>
                            <td class="small"><?= $log['user_id'] ?? '—' ?></td>
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