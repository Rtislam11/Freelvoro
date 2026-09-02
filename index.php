<?php
/**
 * Front Controller & Action Router
 * Integrated Freelance Marketplace & Peer-to-Peer Assistance Platform
 */

// Error reporting for local development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Autoload controllers & configuration
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ClientController.php';
require_once __DIR__ . '/controllers/AdminController.php';

// Initialize session
AuthController::startSession();

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        // ==========================================
        // Auth Routes
        // ==========================================
        case 'login':
            $auth = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth->login();
            } else {
                $auth->showLogin();
            }
            break;

        case 'register':
            $auth = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth->register();
            } else {
                $auth->showRegister();
            }
            break;

        case 'logout':
            $auth = new AuthController();
            $auth->logout();
            break;

        // ==========================================
        // Client Module Routes (Features C1, C2, C3)
        // ==========================================
        case 'client_dashboard':
            $client = new ClientController();
            $client->dashboard();
            break;

        case 'create_job':
            $client = new ClientController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $client->createJob();
            } else {
                $client->showCreateJob();
            }
            break;

        case 'edit_job':
            $client = new ClientController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $client->updateJob();
            } else {
                $client->showEditJob();
            }
            break;

        case 'close_job':
            $client = new ClientController();
            $client->closeJob();
            break;

        case 'delete_job':
            $client = new ClientController();
            $client->deleteJob();
            break;

        case 'view_bids':
            $client = new ClientController();
            $client->viewBids();
            break;

        case 'hire_freelancer':
            $client = new ClientController();
            $client->hireFreelancer();
            break;

        case 'file_report':
            $client = new ClientController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $client->submitReport();
            } else {
                $client->showFileReport();
            }
            break;

        // ==========================================
        // Admin Module Routes (Features A1, A2, A3, A4)
        // ==========================================
        case 'admin_dashboard':
            $admin = new AdminController();
            $admin->dashboard();
            break;

        case 'admin_delete_job':
            $admin = new AdminController();
            $admin->deleteJob();
            break;

        case 'admin_reports':
            $admin = new AdminController();
            $admin->reports();
            break;

        case 'resolve_report':
            $admin = new AdminController();
            $admin->resolveReport();
            break;

        case 'dismiss_report':
            $admin = new AdminController();
            $admin->dismissReport();
            break;

        case 'admin_users':
            $admin = new AdminController();
            $admin->users();
            break;

        case 'terminate_user':
            $admin = new AdminController();
            $admin->terminateUser();
            break;

        case 'admin_create_user':
            $admin = new AdminController();
            $admin->createUser();
            break;

        // ==========================================
        // Database Setup / Reset Utility Route
        // ==========================================
        case 'setup_db':
            Database::getInstance()->autoInitDatabase();
            AuthController::setFlash('success', 'Database tables and seed data have been initialized successfully!');
            header("Location: index.php?action=login");
            exit;

        // ==========================================
        // Default Landing Dispatcher
        // ==========================================
        default:
            if (AuthController::isAuthenticated()) {
                $role = $_SESSION['user_role'] ?? '';
                if ($role === 'admin') {
                    header("Location: index.php?action=admin_dashboard");
                } elseif ($role === 'client') {
                    header("Location: index.php?action=client_dashboard");
                } else {
                    $auth = new AuthController();
                    $auth->showLogin();
                }
            } else {
                // Always open login page first for unauthenticated visitors
                $auth = new AuthController();
                $auth->showLogin();
            }
            break;
    }
} catch (Exception $e) {
    echo "<div style='font-family:sans-serif; max-width:600px; margin:4rem auto; padding:2rem; border:1px solid #fecaca; background:#fef2f2; border-radius:12px; color:#991b1b;'>";
    echo "<h2 style='margin-top:0;'>⚠️ Application Exception</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='index.php' style='display:inline-block; margin-top:1rem; padding:0.5rem 1rem; background:#dc2626; color:white; text-decoration:none; border-radius:6px;'>Return to Safety</a>";
    echo "</div>";
}
