<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="hero-section">
    <div class="container text-center">
        <h1><i class="bi bi-bag-heart-fill text-warning"></i> ThreadSwap</h1>
        <p class="lead mt-2 mb-4">Buy and sell second-hand clothing. Sustainable. Simple. Local.</p>
        <a href="<?= base_url('items') ?>" class="btn btn-warning btn-lg me-2">Browse Items</a>
        <?php if (!session()->has('user')): ?>
            <a href="<?= base_url('register') ?>" class="btn btn-outline-light btn-lg">Start Selling</a>
        <?php else: ?>
            <a href="<?= base_url('my/listings/create') ?>" class="btn btn-outline-light btn-lg">
                <i class="bi bi-plus-circle me-1"></i>List an Item
            </a>
        <?php endif; ?>
    </div>
</section>

<div class="container">
    <h4 class="mb-3 fw-bold">Recently Listed</h4>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
        <?php foreach ($items as $item): ?>
            <div class="col">
                <a href="<?= base_url('items/' . $item['id']) ?>" class="text-decoration-none text-dark">
                    <div class="card item-card h-100">
                        <?php if ($item['image']): ?>
                            <img src="<?= base_url('uploads/' . $item['image']) ?>"
                                 class="card-img-top" alt="<?= esc($item['title']) ?>">
                        <?php else: ?>
                            <div class="card-img-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body p-2">
                            <div class="price-badge">&euro;<?= number_format($item['price'], 2) ?></div>
                            <div class="small text-muted text-truncate"><?= esc($item['title']) ?></div>
                            <div class="small text-muted"><?= esc($item['size']) ?></div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>