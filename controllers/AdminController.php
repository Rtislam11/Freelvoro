<?php
/**
 * AdminController
 * Handles Content Moderation, Dispute Resolution, User Account Management, and System Analytics.
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/Proposal.php';

class AdminController {
    private User $userModel;
    private Job $jobModel;
    private Report $reportModel;
    private Proposal $proposalModel;

    public function __construct() {
        // Enforce Admin role middleware guard
        AuthController::requireRole('admin');
        $this->userModel = new User();
        $this->jobModel = new Job();
        $this->reportModel = new Report();
        $this->proposalModel = new Proposal();
    }

    /**
     * Admin Dashboard: System Analytics & Content Moderation Overview (Features A1 & A4)
     */
    public function dashboard(): void {
        $currentUser = AuthController::currentUser();

        // 1. Fetch Real-time Analytics Counters
        $userStats     = $this->userModel->getUserStats();
        $jobStats      = $this->jobModel->getJobStats();
        $proposalStats = $this->proposalModel->getProposalStats();
        $pendingReports= $this->reportModel->getPendingCount();

        // 2. Fetch all platform jobs for Content Moderation
        $statusFilter = $_GET['status'] ?? null;
        $categoryFilter = $_GET['category'] ?? null;
        $jobs = $this->jobModel->getAllJobs($statusFilter, $categoryFilter);

        $pageTitle = "Admin Control Center | Analytics & Moderation";
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    /**
     * Moderation Action: Delete a violating Job listing (Feature A1)
     */
    public function deleteJob(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=admin_dashboard");
            exit;
        }

        $jobId = (int) ($_POST['job_id'] ?? 0);
        if (!$jobId) {
            AuthController::setFlash('danger', "Invalid job ID provided.");
            header("Location: index.php?action=admin_dashboard");
            exit;
        }

        try {
            $this->jobModel->deleteJob($jobId);
            AuthController::setFlash('success', "Job posting #{$jobId} has been moderated and removed from the platform.");
        } catch (Exception $e) {
            AuthController::setFlash('danger', "Failed to moderate job: " . $e->getMessage());
        }

        header("Location: index.php?action=admin_dashboard");
        exit;
    }

    /**
     * Dispute & Moderation Reports Center (Feature A2)
     */
    public function reports(): void {
        $statusFilter = $_GET['status'] ?? null;
        $reports = $this->reportModel->getAllReports($statusFilter);
        $pendingCount = $this->reportModel->getPendingCount();

        $pageTitle = "Dispute & Report Resolution Center";
        require __DIR__ . '/../views/admin/reports.php';
    }

    /**
     * Resolve a User Dispute / Flag (Feature A2)
     */
    public function resolveReport(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=admin_reports");
            exit;
        }

        $reportId = (int) ($_POST['report_id'] ?? 0);
        if (!$reportId) {
            AuthController::setFlash('danger', "Invalid report ID.");
            header("Location: index.php?action=admin_reports");
            exit;
        }

        try {
            $this->reportModel->updateStatus($reportId, 'resolved');
            AuthController::setFlash('success', "Report #{$reportId} has been marked as RESOLVED.");
        } catch (Exception $e) {
            AuthController::setFlash('danger', "Failed to update report: " . $e->getMessage());
        }

        header("Location: index.php?action=admin_reports");
        exit;
    }

    /**
     * Dismiss an Invalid Report (Feature A2)
     */
    public function dismissReport(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=admin_reports");
            exit;
        }

        $reportId = (int) ($_POST['report_id'] ?? 0);
        if (!$reportId) {
            AuthController::setFlash('danger', "Invalid report ID.");
            header("Location: index.php?action=admin_reports");
            exit;
        }

        try {
            $this->reportModel->updateStatus($reportId, 'dismissed');
            AuthController::setFlash('info', "Report #{$reportId} has been DISMISSED.");
        } catch (Exception $e) {
            AuthController::setFlash('danger', "Failed to dismiss report: " . $e->getMessage());
        }

        header("Location: index.php?action=admin_reports");
        exit;
    }

    /**
     * User Directory & Account Management (Feature A3)
     */
    public function users(): void {
        $roleFilter = $_GET['role'] ?? null;
        $users = $this->userModel->getAllUsers($roleFilter);
        $userStats = $this->userModel->getUserStats();

        $pageTitle = "User Account Management | Admin Control";
        require __DIR__ . '/../views/admin/users.php';
    }

    /**
     * Terminate / Delete User Account (Feature A3)
     */
    public function terminateUser(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=admin_users");
            exit;
        }

        $currentUser = AuthController::currentUser();
        $targetUserId = (int) ($_POST['user_id'] ?? 0);

        if (!$targetUserId) {
            AuthController::setFlash('danger', "Invalid user ID provided.");
            header("Location: index.php?action=admin_users");
            exit;
        }

        // Prevent admin from accidentally deleting their own account
        if ($targetUserId === (int) $currentUser['id']) {
            AuthController::setFlash('danger', "Safety Warning: You cannot terminate your own active administrator account.");
            header("Location: index.php?action=admin_users");
            exit;
        }

        try {
            $this->userModel->deleteUser($targetUserId);
            AuthController::setFlash('success', "User account #{$targetUserId} and all associated records have been terminated.");
        } catch (Exception $e) {
            AuthController::setFlash('danger', "Failed to terminate user account: " . $e->getMessage());
        }

        header("Location: index.php?action=admin_users");
        exit;
    }

    /**
     * Admin User Provisioning: Create new Admin, Client, or Freelancer Account (Feature A3)
     */
    public function createUser(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=admin_users");
            exit;
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $role     = $_POST['role'] ?? '';
        $password = $_POST['password'] ?? '';

        $errors = [];
        if (strlen($name) < 2) $errors[] = "Full Name must be at least 2 characters.";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email address is required.";
        if (!in_array($role, ['admin', 'client', 'freelancer'])) $errors[] = "Valid role must be selected.";
        if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";

        if (empty($errors) && $this->userModel->findByEmail($email)) {
            $errors[] = "An account with email '{$email}' already exists.";
        }

        if (!empty($errors)) {
            AuthController::setFlash('danger', implode('<br>', $errors));
            header("Location: index.php?action=admin_users");
            exit;
        }

        try {
            $newId = $this->userModel->create($name, $email, $password, $role);
            AuthController::setFlash('success', "🎉 New " . ucfirst($role) . " account for <strong>" . htmlspecialchars($name) . "</strong> created successfully (ID: #{$newId})!");
        } catch (Exception $e) {
            AuthController::setFlash('danger', "Failed to create account: " . $e->getMessage());
        }

        header("Location: index.php?action=admin_users");
        exit;
    }
}
