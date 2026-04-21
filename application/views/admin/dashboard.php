<h2 class="fw-bold mb-4"><i class="bi bi-speedometer2 me-2 text-primary"></i>Admin Dashboard</h2>

<!-- Stats row -->
<div class="row g-4 mb-5">
    <?php
    $stats = [
        ['label' => 'Total Posts',      'value' => $total_posts, 'icon' => 'file-earmark-text', 'color' => 'primary', 'link' => 'posts'],
        ['label' => 'Categories',       'value' => $total_cats,  'icon' => 'tags',               'color' => 'success', 'link' => 'categories'],
        ['label' => 'Registered Users', 'value' => $total_users, 'icon' => 'people',             'color' => 'info',    'link' => 'admin/users'],
        ['label' => 'Log Entries',      'value' => $total_logs,  'icon' => 'journal-code',       'color' => 'warning', 'link' => 'admin/logs'],
    ];
    foreach ($stats as $s):
    ?>
    <div class="col-sm-6 col-xl-3">
        <a href="<?= site_url($s['link']) ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle bg-<?= $s['color'] ?> bg-opacity-10 p-3 me-3">
                        <i class="bi bi-<?= $s['icon'] ?> text-<?= $s['color'] ?> fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-3"><?= number_format($s['value']) ?></div>
                        <div class="text-muted small"><?= $s['label'] ?></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">
    <!-- Recent Posts -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Recent Posts</h5>
                <a href="<?= site_url('posts') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th class="pe-4">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_posts as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <a href="<?= site_url('posts/edit/' . $p['id']) ?>"
                                   class="text-decoration-none fw-semibold text-dark small">
                                    <?= htmlspecialchars(substr($p['title'], 0, 45)) ?>…
                                </a>
                            </td>
                            <td class="small text-muted"><?= htmlspecialchars($p['author'] ?? '—') ?></td>
                            <td>
                                <span class="badge <?= $p['status'] === 'published' ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>
                            <td class="pe-4 small text-muted"><?= date('M j', strtotime($p['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-journal-code me-2"></i>Recent Logs</h5>
                <a href="<?= site_url('admin/logs') ?>" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="card-body p-3">
                <?php foreach ($recent_logs as $log): ?>
                <?php
                $levelClass = match($log['level']) {
                    'ERROR'   => 'danger',
                    'WARNING' => 'warning',
                    default   => 'info',
                };
                ?>
                <div class="d-flex align-items-start mb-3">
                    <span class="badge bg-<?= $levelClass ?> me-2 mt-1 flex-shrink-0">
                        <?= $log['level'] ?>
                    </span>
                    <div>
                        <div class="small fw-semibold">
                            <?= htmlspecialchars($log['class_name']) ?>::<?= htmlspecialchars($log['method_name']) ?>
                        </div>
                        <div class="small text-muted">
                            <?= htmlspecialchars(substr($log['message'], 0, 80)) ?>
                        </div>
                        <div class="text-muted" style="font-size:.7rem;">
                            <?= date('M j H:i', strtotime($log['created_at'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
