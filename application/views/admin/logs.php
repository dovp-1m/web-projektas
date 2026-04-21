<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-journal-code me-2 text-primary"></i>Application Logs
        <span class="badge bg-secondary fs-6 ms-2"><?= number_format($total) ?></span>
    </h2>
    <a href="<?= site_url('admin') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Dashboard
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th class="ps-4" style="width:90px;">Level</th>
                    <th>Class :: Method</th>
                    <th>Message</th>
                    <th style="width:60px;">User</th>
                    <th style="width:60px;">IP</th>
                    <th class="pe-4" style="width:130px;">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No log entries found.</td></tr>
                <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <?php
                $levelClass = match($log['level']) {
                    'ERROR'   => 'danger',
                    'WARNING' => 'warning',
                    default   => 'info',
                };
                ?>
                <tr>
                    <td class="ps-4">
                        <span class="badge bg-<?= $levelClass ?>"><?= htmlspecialchars($log['level']) ?></span>
                    </td>
                    <td class="font-monospace text-muted">
                        <?= htmlspecialchars($log['class_name']) ?>::<?= htmlspecialchars($log['method_name']) ?>
                    </td>
                    <td><?= htmlspecialchars($log['message']) ?></td>
                    <td class="text-muted"><?= $log['user_id'] ?? '—' ?></td>
                    <td class="text-muted"><?= htmlspecialchars($log['ip_address'] ?? '—') ?></td>
                    <td class="pe-4 text-muted">
                        <?= date('Y-m-d H:i:s', strtotime($log['created_at'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    <?= $pagination ?>
</div>
