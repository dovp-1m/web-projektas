<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-4">
    <div class="col-md-6">
        <?php if ($item['image']): ?>
            <img src="<?= base_url('uploads/' . $item['image']) ?>"
                 class="item-detail-img" alt="<?= esc($item['title']) ?>">
        <?php else: ?>
            <div class="item-detail-img d-flex align-items-center justify-content-center bg-light">
                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('items') ?>">Browse</a></li>
                <li class="breadcrumb-item active"><?= esc($item['category_name']) ?></li>
            </ol>
        </nav>

        <h2 class="fw-bold"><?= esc($item['title']) ?></h2>
        <div class="display-6 fw-bold text-success mb-3">
            &euro;<?= number_format($item['price'], 2) ?>
        </div>

        <div class="d-flex gap-2 mb-3 flex-wrap">
            <span class="badge bg-dark"><?= esc($item['size']) ?></span>
            <span class="badge badge-condition-<?= $item['condition'] ?> text-white">
                <?= ucfirst(str_replace('_', ' ', $item['condition'])) ?>
            </span>
            <span class="badge bg-secondary"><?= esc($item['brand']) ?></span>
            <span class="badge bg-info text-dark"><?= esc($item['category_name']) ?></span>
        </div>

        <p class="text-muted"><?= nl2br(esc($item['description'])) ?></p>

        <hr>

        <div class="d-flex align-items-center gap-3 mb-2">
            <i class="bi bi-person-circle fs-3 text-muted"></i>
            <div>
                <div class="fw-semibold">
                    <?= esc($item['first_name'] . ' ' . $item['last_name']) ?>
                </div>
                <div class="text-muted small">@<?= esc($item['username']) ?></div>
            </div>
        </div>

        <div class="text-muted small mb-3">
            <i class="bi bi-eye me-1"></i><?= number_format($item['views']) ?> views &nbsp;&middot;&nbsp;
            <i class="bi bi-calendar me-1"></i>Listed <?= date('M d, Y', strtotime($item['created_at'])) ?>
        </div>

        <?php if (session()->has('user') && (int)session('user')['id'] === (int)$item['user_id']): ?>
            <div class="d-flex gap-2">
                <a href="<?= base_url('my/listings/edit/' . $item['id']) ?>"
                   class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="<?= base_url('my/listings/delete/' . $item['id']) ?>"
                   class="btn btn-outline-danger"
                   onclick="return confirm('Delete this listing permanently?')">
                    <i class="bi bi-trash me-1"></i>Delete
                </a>
            </div>
        <?php elseif (!session()->has('user')): ?>
            <a href="<?= base_url('login') ?>" class="btn btn-dark">
                Log in to contact seller
            </a>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>