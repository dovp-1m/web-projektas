<div class="row justify-content-center">
    <div class="col-lg-8">
        <article>
            <!-- Category badge -->
            <?php if (!empty($post['category_name'])): ?>
            <div class="mb-3">
                <span class="badge fs-6 px-3 py-2"
                      style="background-color:<?= htmlspecialchars($post['category_color']) ?>">
                    <?= htmlspecialchars($post['category_name']) ?>
                </span>
            </div>
            <?php endif; ?>

            <!-- Title -->
            <h1 class="fw-bold display-6 mb-3"><?= htmlspecialchars($post['title']) ?></h1>

            <!-- Meta row -->
            <div class="d-flex align-items-center gap-3 text-muted mb-4 pb-4 border-bottom">
                <span><i class="bi bi-person me-1"></i><?= htmlspecialchars($post['author'] ?? 'Unknown') ?></span>
                <span><i class="bi bi-calendar3 me-1"></i>
                    <?= $post['published_at'] ? date('F j, Y', strtotime($post['published_at'])) : 'Draft' ?>
                </span>
                <span><i class="bi bi-eye me-1"></i><?= number_format($post['views']) ?> views</span>
            </div>

            <!-- Excerpt -->
            <?php if (!empty($post['excerpt'])): ?>
            <p class="lead text-muted fst-italic border-start border-4 border-primary ps-3 mb-4">
                <?= htmlspecialchars($post['excerpt']) ?>
            </p>
            <?php endif; ?>

            <!-- Body -->
            <div class="post-body">
                <?php
                // Render body paragraphs safely
                $paragraphs = explode("\n\n", htmlspecialchars($post['body']));
                foreach ($paragraphs as $p):
                    if (trim($p) !== ''):
                ?>
                <p><?= nl2br($p) ?></p>
                <?php
                    endif;
                endforeach;
                ?>
            </div>
        </article>

        <!-- Navigation -->
        <div class="d-flex justify-content-between mt-5 pt-4 border-top">
            <a href="<?= site_url('blog') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Blog
            </a>
            <?php if ($logged_in && ($is_admin || $post['user_id'] == $current_user['id'])): ?>
            <a href="<?= site_url('posts/edit/' . $post['id']) ?>" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>Edit Post
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
