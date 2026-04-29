<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-bag me-2"></i>All Listings</h4>
    <a href="<?= base_url('admin') ?>" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
</div>

<div class="card border-0 shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Seller</th>
                <th>Price</th>
                <th>Status</th>
                <th>Views</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td class="fw-semibold"><?= esc($item['title']) ?></td>
                    <td>@<?= esc($item['username']) ?></td>
                    <td>&euro;<?= number_format($item['price'], 2) ?></td>
                    <td>
                        <span class="badge bg-<?= $item['status'] === 'active' ? 'success' : ($item['status'] === 'sold' ? 'secondary' : 'warning text-dark') ?>">
                            <?= ucfirst($item['status']) ?>
                        </span>
                    </td>
                    <td><?= number_format($item['views']) ?></td>
                    <td>
                        <a href="<?= base_url('items/' . $item['id']) ?>"
                           class="btn btn-sm btn-outline-secondary me-1">View</a>
                        <a href="<?= base_url('admin/items/delete/' . $item['id']) ?>"
                           onclick="return confirm('Delete this item as admin?')"
                           class="btn btn-sm btn-outline-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>