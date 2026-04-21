<div class="row">
    <!-- ── Post list ───────────────────────────────────────── -->
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold mb-0">
                <?php if (!empty($active_category)): ?>
                    <span class="badge me-2" style="background-color:<?= $active_category['color'] ?>">
                        <?= htmlspecialchars($active_category['name']) ?>
                    </span>Latest Posts
                <?php else: ?>
                    Latest Posts
                <?php endif; ?>
            </h2>
        </div>

        <?php if (empty($posts)): ?>
            <div class="alert alert-info">No posts found.</div>
        <?php else: ?>
            <?php foreach ($posts as $p): ?>
            <article class="card mb-4 shadow-sm border-0 post-card">
                <div class="card-body p-4">
                    <div class="mb-2">
                        <?php if (!empty($p['category_name'])): ?>
                        <a href="<?= site_url('blog/category/' . $p['category_slug'] ?? '') ?>"
                           class="badge text-decoration-none"
                           style="background-color:<?= htmlspecialchars($p['category_color']) ?>">
                            <?= htmlspecialchars($p['category_name']) ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <h3 class="card-title h5 fw-bold">
                        <a href="<?= site_url('blog/' . $p['slug']) ?>"
                           class="text-decoration-none text-dark stretched-link">
                            <?= htmlspecialchars($p['title']) ?>
                        </a>
                    </h3>
                    <p class="card-text text-muted">
                        <?= htmlspecialchars(substr($p['excerpt'] ?? '', 0, 180)) ?>…
                    </p>
                    <div class="d-flex align-items-center text-muted small mt-3 gap-3">
                        <span><i class="bi bi-person me-1"></i><?= htmlspecialchars($p['author'] ?? 'Unknown') ?></span>
                        <span><i class="bi bi-calendar3 me-1"></i>
                            <?= $p['published_at'] ? date('M j, Y', strtotime($p['published_at'])) : 'Draft' ?>
                        </span>
                        <span><i class="bi bi-eye me-1"></i><?= number_format($p['views']) ?></span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <?= $pagination ?>
        </div>
    </div>

    <!-- ── Sidebar ─────────────────────────────────────────── -->
    <aside class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-semibold">
                <i class="bi bi-tags me-2"></i>Categories
            </div>
            <div class="list-group list-group-flush">
                <a href="<?= site_url('blog') ?>"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    All Posts
                </a>
                <?php foreach ($categories as $c): ?>
                <a href="<?= site_url('blog/category/' . $c['slug']) ?>"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span>
                        <span class="badge me-2 rounded-circle p-2"
                              style="background-color:<?= htmlspecialchars($c['color']) ?>">&nbsp;</span>
                        <?= htmlspecialchars($c['name']) ?>
                    </span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!$logged_in): ?>
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body text-center p-4">
                <i class="bi bi-pencil-square fs-2 mb-3 d-block"></i>
                <h5 class="fw-bold">Start Writing</h5>
                <p class="small mb-3">Create an account to publish your own posts.</p>
                <a href="<?= site_url('register') ?>" class="btn btn-light fw-semibold w-100">
                    Register Free
                </a>
            </div>
        </div>
        <?php endif; ?>
    </aside>
</div>
