<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>All Users</h4>
    <a href="<?= base_url('admin') ?>" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
</div>

<div class="card border-0 shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Email</th>
                <th>Name</th>
                <th>Role</th>
                <th>Status</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><strong><?= esc($user['username']) ?></strong></td>
                    <td><?= esc($user['email']) ?></td>
                    <td><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></td>
                    <td>
                        <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $user['is_active'] ? 'bg-success' : 'bg-dark' ?>">
                            <?= $user['is_active'] ? 'Active' : 'Disabled' ?>
                        </span>
                    </td>
                    <td class="small text-muted"><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>