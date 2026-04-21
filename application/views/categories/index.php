<!-- categories/index.php -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-tags me-2 text-primary"></i>Categories
        <span class="badge bg-secondary fs-6 ms-2"><?= count($categories) ?></span>
    </h2>
    <a href="<?= site_url('categories/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Category
    </a>
</div>

<?php if (empty($categories)): ?>
<div class="alert alert-info">No categories yet. <a href="<?= site_url('categories/create') ?>">Create one!</a></div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($categories as $cat): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="rounded-circle me-3 flex-shrink-0"
                          style="width:14px;height:14px;background:<?= htmlspecialchars($cat['color']) ?>;display:inline-block;"></span>
                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($cat['name']) ?></h5>
                </div>
                <p class="text-muted small mb-2">
                    <code>/blog/category/<?= htmlspecialchars($cat['slug']) ?></code>
                </p>
                <p class="text-muted small mb-3">
                    <?= $cat['description'] ? htmlspecialchars(substr($cat['description'], 0, 100)) : '<em>No description</em>' ?>
                </p>
            </div>
            <div class="card-footer bg-transparent d-flex gap-2">
                <a href="<?= site_url('categories/edit/' . $cat['id']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="<?= site_url('categories/delete/' . $cat['id']) ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Delete category \'<?= addslashes($cat['name']) ?>\'?')">
                    <i class="bi bi-trash me-1"></i>Delete
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
