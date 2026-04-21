<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-file-earmark-text me-2 text-primary"></i>
        <?= $is_admin ? 'All Posts' : 'My Posts' ?>
        <span class="badge bg-secondary fs-6 ms-2"><?= number_format($total) ?></span>
    </h2>
    <a href="<?= site_url('posts/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Post
    </a>
</div>

<?php if (empty($posts)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>No posts yet.
        <a href="<?= site_url('posts/create') ?>">Write your first post!</a>
    </div>
<?php else: ?>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <?php if ($is_admin): ?><th>Author</th><?php endif; ?>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $p): ?>
                <tr>
                    <td>
                        <a href="<?= site_url('posts/view/' . $p['id']) ?>"
                           class="fw-semibold text-decoration-none text-dark">
                            <?= htmlspecialchars(substr($p['title'], 0, 60)) ?>
                            <?= strlen($p['title']) > 60 ? '…' : '' ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($p['category_name']): ?>
                        <span class="badge" style="background-color:<?= htmlspecialchars($p['category_color']) ?>">
                            <?= htmlspecialchars($p['category_name']) ?>
                        </span>
                        <?php else: ?>
                        <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                    <?php if ($is_admin): ?>
                    <td class="text-muted small"><?= htmlspecialchars($p['author'] ?? '—') ?></td>
                    <?php endif; ?>
                    <td>
                        <?php if ($p['status'] === 'published'): ?>
                            <span class="badge bg-success">Published</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?= number_format($p['views']) ?></td>
                    <td class="text-muted small"><?= date('M j, Y', strtotime($p['created_at'])) ?></td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="<?= site_url('posts/view/' . $p['id']) ?>"
                               class="btn btn-outline-secondary" title="Preview">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= site_url('posts/edit/' . $p['id']) ?>"
                               class="btn btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= site_url('posts/delete/' . $p['id']) ?>"
                               class="btn btn-outline-danger"
                               title="Delete"
                               onclick="return confirm('Delete this post? This cannot be undone.')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    <?= $pagination ?>
</div>
<?php endif; ?>
