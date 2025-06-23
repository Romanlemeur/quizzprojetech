<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Invader Bar' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
</head>
<body class="admin-page">

<header class="site-header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="<?= base_url('admin/dashboard') ?>">Invader Admin</a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                    <li><a href="<?= base_url('admin/quizzes') ?>">Quizzes</a></li>
                    <li><a href="<?= base_url('admin/users') ?>">Users</a></li>
                    <li><a href="<?= base_url('admin/statistics') ?>">Statistics</a></li>
                    <li><a href="<?= base_url('/') ?>" target="_blank">View Site</a></li>
                    <li><a href="<?= base_url('logout') ?>">Logout</a></li>
                </ul>
            </nav>
            <button class="mobile-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>
<div class="mobile-menu">
    <ul>
        <li><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
        <li><a href="<?= base_url('admin/quizzes') ?>">Quizzes</a></li>
        <li><a href="<?= base_url('admin/users') ?>">Users</a></li>
        <li><a href="<?= base_url('admin/statistics') ?>">Statistics</a></li>
        <li><a href="<?= base_url('/') ?>" target="_blank">View Site</a></li>
        <li><a href="<?= base_url('logout') ?>">Logout</a></li>
    </ul>
</div>

<main class="container">
    <div class="admin-container">
        <!-- Sidebar -->
        
        
        <!-- Main Content -->
        <div class="admin-content">
            <div class="admin-topbar">
                
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