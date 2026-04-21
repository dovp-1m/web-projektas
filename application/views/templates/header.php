<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' | BlogCMS' : 'BlogCMS' ?></title>

    <!-- Bootstrap 5 CSS (CSS framework – requirement) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url('public/css/style.css') ?>" rel="stylesheet">
</head>
<body>

<!-- ── Navigation ──────────────────────────────────────────── -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="<?= site_url('blog') ?>">
            <i class="bi bi-journal-richtext me-2"></i>BlogCMS
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('blog') ?>">
                        <i class="bi bi-house me-1"></i>Blog
                    </a>
                </li>

                <?php if ($logged_in): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('posts') ?>">
                        <i class="bi bi-file-earmark-text me-1"></i>My Posts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('posts/create') ?>">
                        <i class="bi bi-plus-circle me-1"></i>New Post
                    </a>
                </li>

                <?php if ($is_admin): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-shield-lock me-1"></i>Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= site_url('admin') ?>">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('categories') ?>">
                            <i class="bi bi-tags me-2"></i>Categories</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('admin/users') ?>">
                            <i class="bi bi-people me-2"></i>Users</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('admin/logs') ?>">
                            <i class="bi bi-journal-code me-2"></i>Logs</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <?php if ($logged_in): ?>
                <li class="nav-item">
                    <span class="navbar-text me-3 text-white-50">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= htmlspecialchars($current_user['username']) ?>
                        <?php if ($is_admin): ?>
                            <span class="badge bg-warning text-dark ms-1">Admin</span>
                        <?php else: ?>
                            <span class="badge bg-light text-dark ms-1">Editor</span>
                        <?php endif; ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm" href="<?= site_url('logout') ?>">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item me-2">
                    <a class="btn btn-outline-light btn-sm" href="<?= site_url('login') ?>">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light btn-sm text-primary fw-semibold" href="<?= site_url('register') ?>">Register</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ── Flash messages ───────────────────────────────────────── -->
<div class="container mt-3">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?= htmlspecialchars($this->session->flashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($this->session->flashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<!-- ── Page content starts here ─────────────────────────────── -->
<main class="container my-4">
