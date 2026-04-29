<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-4">
    <!-- Filter Sidebar -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm p-3">
            <h6 class="fw-bold mb-3"><i class="bi bi-funnel me-1"></i>Filter Items</h6>
            <form method="GET" action="<?= base_url('items') ?>">
                <div class="mb-3">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Search title or brand..."
                           value="<?= esc($filters['q'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Category</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Condition</label>
                    <select name="condition" class="form-select form-select-sm">
                        <option value="">Any Condition</option>
                        <?php foreach (['new' => 'New', 'like_new' => 'Like New', 'good' => 'Good', 'fair' => 'Fair'] as $v => $l): ?>
                            <option value="<?= $v ?>"
                                <?= ($filters['condition'] ?? '') === $v ? 'selected' : '' ?>>
                                <?= $l ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Max Price (€)</label>
                    <input type="number" name="max_price" class="form-control form-control-sm"
                           min="0" step="0.01"
                           value="<?= esc($filters['max_price'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-dark btn-sm w-100">Apply Filters</button>
                <a href="<?= base_url('items') ?>" class="btn btn-outline-secondary btn-sm w-100 mt-2">Clear All</a>
            </form>
        </div>
    </div>

    <!-- Items Grid -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small"><?= number_format($total) ?> item(s) found</span>
            <?php if (session()->has('user')): ?>
                <a href="<?= base_url('my/listings/create') ?>" class="btn btn-dark btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Sell an Item
                </a>
            <?php endif; ?>
        </div>

        <?php if (empty($items)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bag-x fs-1 d-block mb-2"></i>
                No items match your filters. Try broadening your search.
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-md-3 g-3">
                <?php foreach ($items as $item): ?>
                    <div class="col">
                        <a href="<?= base_url('items/' . $item['id']) ?>" class="text-decoration-none text-dark">
                            <div class="card item-card h-100">
                                <?php if ($item['image']): ?>
                                    <?php
                                        $imgSrc = (str_starts_with($item['image'], 'http://') || str_starts_with($item['image'], 'https://'))
                                            ? $item['image']
                                            : base_url('uploads/' . $item['image']);
                                    ?>
                                    <img src="<?= $imgSrc ?>"
                                         class="card-img-top" alt="<?= esc($item['title']) ?>">
                                <?php else: ?>
                                    <div class="card-img-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body p-2">
                                    <div class="price-badge">&euro;<?= number_format($item['price'], 2) ?></div>
                                    <div class="small fw-semibold text-truncate"><?= esc($item['title']) ?></div>
                                    <div class="d-flex justify-content-between">
                                        <span class="small text-muted"><?= esc($item['size']) ?></span>
                                        <span class="small text-muted"><?= esc($item['brand']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center flex-wrap">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge(array_filter($filters), ['page' => $page - 1])) ?>">
                                    &laquo; Prev
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $page - 2);
                        $end   = min($totalPages, $page + 2);
                        ?>
                        <?php for ($i = $start; $i <= $end; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge(array_filter($filters), ['page' => $i])) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?<?= http_build_query(array_merge(array_filter($filters), ['page' => $page + 1])) ?>">
                                    Next &raquo;
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>