<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Administration' ?> | QuizMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h3>QuizMaster</h3>
                <span>Administration</span>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item <?= current_url() == site_url('admin') || current_url() == site_url('admin/dashboard') ? 'active' : '' ?>">
                    <a href="<?= site_url('admin/dashboard') ?>">
                        <i class="fa fa-dashboard"></i> Tableau de bord
                    </a>
                </li>
                <li class="menu-item <?= strpos(current_url(), site_url('admin/quizzes')) === 0 ? 'active' : '' ?>">
                    <a href="<?= site_url('admin/quizzes') ?>">
                        <i class="fa fa-question-circle"></i> Quiz
                    </a>
                </li>
                <li class="menu-item <?= strpos(current_url(), site_url('admin/live')) === 0 ? 'active' : '' ?>">
                    <a href="<?= site_url('admin/live') ?>">
                        <i class="fa fa-broadcast-tower"></i> Quiz en direct
                    </a>
                </li>
                <li class="menu-item <?= strpos(current_url(), site_url('admin/users')) === 0 ? 'active' : '' ?>">
                    <a href="<?= site_url('admin/users') ?>">
                        <i class="fa fa-users"></i> Utilisateurs
                    </a>
                </li>
                <li class="menu-item <?= strpos(current_url(), site_url('admin/statistics')) === 0 ? 'active' : '' ?>">
                    <a href="<?= site_url('admin/statistics') ?>">
                        <i class="fa fa-chart-bar"></i> Statistiques
                    </a>
                </li>
                <li class="menu-divider"></li>
                <li class="menu-item">
                    <a href="<?= site_url('/') ?>">
                        <i class="fa fa-home"></i> Retour au site
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= site_url('logout') ?>">
                        <i class="fa fa-sign-out-alt"></i> Déconnexion
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="admin-content">
            <div class="admin-topbar">
                <button class="sidebar-toggle">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="user-info">
                    <span>Connecté en tant que : <?= session()->get('username') ?></span>
                </div>
            </div>
            
            <div class="content-wrapper">
                <div class="content-header">
                    <h1><?= $title ?? 'Administration' ?></h1>
                </div>
                
                <?php if (session()->has('message')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <div class="content-body"> 