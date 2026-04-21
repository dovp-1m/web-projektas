<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Draft notice banner -->
        <?php if ($post['status'] === 'draft'): ?>
        <div class="alert alert-warning d-flex align-items-center mb-4">
            <i class="bi bi-eye-slash me-2 fs-5"></i>
            <div><strong>Draft Preview</strong> — This post is not visible to the public.</div>
        </div>
        <?php endif; ?>

        <!-- Category badge -->
        <?php if (!empty($post['category_name'])): ?>
        <div class="mb-3">
            <span class="badge fs-6 px-3 py-2"
                  style="background-color:<?= htmlspecialchars($post['category_color']) ?>">
                <?= htmlspecialchars($post['category_name']) ?>
            </span>
        </div>
        <?php endif; ?>

        <h1 class="fw-bold display-6 mb-3"><?= htmlspecialchars($post['title']) ?></h1>

        <div class="d-flex align-items-center gap-3 text-muted mb-4 pb-4 border-bottom">
            <span><i class="bi bi-person me-1"></i><?= htmlspecialchars($post['author'] ?? 'Unknown') ?></span>
            <span><i class="bi bi-calendar3 me-1"></i>
                <?= $post['published_at'] ? date('F j, Y', strtotime($post['published_at'])) : 'Not published yet' ?>
            </span>
            <span class="ms-auto">
                <span class="badge <?= $post['status'] === 'published' ? 'bg-success' : 'bg-secondary' ?>">
                    <?= ucfirst($post['status']) ?>
                </span>
            </span>
        </div>

        <?php if (!empty($post['excerpt'])): ?>
        <p class="lead text-muted fst-italic border-start border-4 border-primary ps-3 mb-4">
            <?= htmlspecialchars($post['excerpt']) ?>
        </p>
        <?php endif; ?>

        <div class="post-body">
            <?php
            $paragraphs = explode("\n\n", htmlspecialchars($post['body']));
            foreach ($paragraphs as $para):
                if (trim($para) !== ''):
            ?>
            <p><?= nl2br($para) ?></p>
            <?php
                endif;
            endforeach;
            ?>
        </div>

        <div class="d-flex justify-content-between mt-5 pt-4 border-top">
            <a href="<?= site_url('posts') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Posts
            </a>
            <div class="d-flex gap-2">
                <a href="<?= site_url('posts/edit/' . $post['id']) ?>" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                <a href="<?= site_url('posts/delete/' . $post['id']) ?>"
                   class="btn btn-outline-danger"
                   onclick="return confirm('Delete this post permanently?')">
                    <i class="bi bi-trash me-2"></i>Delete
                </a>
            </div>
        </div>
    </div>
</div>
