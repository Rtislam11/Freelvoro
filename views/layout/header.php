<?php
/**
 * Header Layout Template
 */
require_once __DIR__ . '/../../controllers/AuthController.php';
$currentUser = AuthController::currentUser();
$flash = AuthController::getFlash();
$currentAction = $_GET['action'] ?? 'login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Freelancing Marketplace & Peer-to-Peer Platform') ?></title>
    <meta name="description" content="Integrated Freelance Marketplace & Peer-to-Peer Digital Assistance Platform for the Bangladeshi Global Community.">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220%22%22><text y=%22.9em%22 font-size=%2290%22>🇧🇩</text></svg>">
</head>
<body>
<div class="app-container">
    <!-- Main Navigation Bar -->
    <nav class="navbar">
        <div class="nav-wrapper">
            <a href="index.php" class="brand-logo">
                <div class="logo-icon">BD</div>
                <span>Freelance<strong style="color:var(--primary);">BD</strong></span>
            </a>

            <div class="nav-links">
                <?php if ($currentUser): ?>
                    <?php if ($currentUser['role'] === 'client'): ?>
                        <a href="index.php?action=client_dashboard" class="nav-link <?= ($currentAction === 'client_dashboard') ? 'active' : '' ?>">My Jobs</a>
                        <a href="index.php?action=create_job" class="nav-link <?= ($currentAction === 'create_job') ? 'active' : '' ?>">+ Post New Job</a>
                        <a href="index.php?action=file_report" class="nav-link <?= ($currentAction === 'file_report') ? 'active' : '' ?>">File Dispute</a>
                    <?php elseif ($currentUser['role'] === 'admin'): ?>
                        <a href="index.php?action=admin_dashboard" class="nav-link <?= ($currentAction === 'admin_dashboard') ? 'active' : '' ?>">Overview & Moderation</a>
                        <a href="index.php?action=admin_reports" class="nav-link <?= ($currentAction === 'admin_reports') ? 'active' : '' ?>">Dispute Queue</a>
                        <a href="index.php?action=admin_users" class="nav-link <?= ($currentAction === 'admin_users') ? 'active' : '' ?>">User Management</a>
                    <?php endif; ?>

                    <div class="user-badge-menu">
                        <span class="role-tag <?= htmlspecialchars($currentUser['role']) ?>" title="Permission Level: <?= htmlspecialchars(ucfirst($currentUser['role'])) ?>">
                            <?= ($currentUser['role'] === 'admin') ? '🔑 Admin' : (($currentUser['role'] === 'client') ? '💼 Client' : '💻 Freelancer') ?>
                        </span>
                        <div style="display:flex; flex-direction:column; line-height:1.2;">
                            <span style="font-weight:700; font-size:0.875rem; color:var(--text-main);">
                                <?= htmlspecialchars($currentUser['name']) ?>
                            </span>
                            <span style="font-size:0.75rem; color:var(--text-muted);">
                                <?= htmlspecialchars($currentUser['email']) ?>
                            </span>
                        </div>
                        <a href="index.php?action=logout" class="btn btn-secondary btn-sm" title="Sign Out">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="index.php?action=login" class="nav-link <?= ($currentAction === 'login') ? 'active' : '' ?>">Sign In</a>
                    <a href="index.php?action=register" class="btn btn-primary btn-sm">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main View Container -->
    <main class="main-content">
        <!-- Flash Notification Toast Banner -->
        <?php if ($flash): ?>
            <div class="toast-alert <?= htmlspecialchars($flash['type']) ?>">
                <div>
                    <strong><?= ($flash['type'] === 'success') ? '✓ Success: ' : (($flash['type'] === 'danger') ? '✕ Error: ' : 'ℹ Notice: ') ?></strong>
                    <?= $flash['message'] ?>
                </div>
                <button type="button" class="toast-close">&times;</button>
            </div>
        <?php endif; ?>
