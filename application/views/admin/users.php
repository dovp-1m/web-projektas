<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-people me-2 text-primary"></i>Users
        <span class="badge bg-secondary fs-6 ms-2"><?= number_format($total) ?></span>
    </h2>
    <a href="<?= site_url('admin') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Dashboard
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="pe-4">Registered</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td class="ps-4 text-muted small"><?= $u['id'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($u['username']) ?></td>
                    <td class="text-muted small"><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <?php if ($u['role'] === 'admin'): ?>
                            <span class="badge bg-warning text-dark">Admin</span>
                        <?php else: ?>
                            <span class="badge bg-light text-dark border">Editor</span>
                        <?php endif; ?>
                    </td>
                    <td class="pe-4 text-muted small">
                        <?= date('M j, Y', strtotime($u['created_at'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
