<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">My Listings</h4>
    <a href="<?= base_url('my/listings/create') ?>" class="btn btn-dark btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Listing
    </a>
</div>

<?php if (empty($items)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bag-plus fs-1 d-block mb-2"></i>
        You haven't listed anything yet.
        <div class="mt-3">
            <a href="<?= base_url('my/listings/create') ?>" class="btn btn-dark">List Your First Item</a>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?= esc($item['title']) ?></div>
                            <div class="small text-muted"><?= esc($item['category_name']) ?></div>
                        </td>
                        <td>&euro;<?= number_format($item['price'], 2) ?></td>
                        <td><?= esc($item['size']) ?></td>
                        <td>
                            <span class="badge bg-<?= $item['status'] === 'active' ? 'success' : ($item['status'] === 'sold' ? 'secondary' : 'warning text-dark') ?>">
                                <?= ucfirst($item['status']) ?>
                            </span>
                        </td>
                        <td><?= number_format($item['views']) ?></td>
                        <td>
                            <a href="<?= base_url('items/' . $item['id']) ?>"
                               class="btn btn-sm btn-outline-secondary me-1">View</a>
                            <a href="<?= base_url('my/listings/edit/' . $item['id']) ?>"
                               class="btn btn-sm btn-outline-primary me-1">Edit</a>
                            <a href="<?= base_url('my/listings/delete/' . $item['id']) ?>"
                               onclick="return confirm('Delete this listing?')"
                               class="btn btn-sm btn-outline-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>