<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-tags me-2"></i>Categories</h4>
    <a href="<?= base_url('admin/categories/create') ?>" class="btn btn-dark btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Category
    </a>
</div>

<div class="mb-3">
    <a href="<?= base_url('admin') ?>" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
</div>

<div class="card border-0 shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No categories yet.</td></tr>
            <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= $cat['id'] ?></td>
                        <td><?= esc($cat['name']) ?></td>
                        <td><code><?= esc($cat['slug']) ?></code></td>
                        <td>
                            <span class="badge <?= $cat['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $cat['is_active'] ? 'Active' : 'Hidden' ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/categories/edit/' . $cat['id']) ?>"
                               class="btn btn-sm btn-outline-primary me-1">Edit</a>
                            <a href="<?= base_url('admin/categories/delete/' . $cat['id']) ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete this category? Items in this category will also be deleted.')">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>