<?php
/**
 * ClientController
 * Handles Client Job Lifecycle, Proposal Review & Atomic Hiring, and Dispute Filing.
 */

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Proposal.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/User.php';

class ClientController {
    private Job $jobModel;
    private Proposal $proposalModel;
    private Report $reportModel;
    private User $userModel;

    public function __construct() {
        // Enforce client role middleware guard
        AuthController::requireRole('client');
        $this->jobModel = new Job();
        $this->proposalModel = new Proposal();
        $this->reportModel = new Report();
        $this->userModel = new User();
    }

    /**
     * Client Overview Dashboard (Feature C1)
     */
    public function dashboard(): void {
        $currentUser = AuthController::currentUser();
        $clientId = $currentUser['id'];

        $jobs = $this->jobModel->getByClientId($clientId);

        // Calculate summary counters
        $totalJobs = count($jobs);
        $activeJobs = 0;
        $totalBidsReceived = 0;
        $activeHires = 0;

        foreach ($jobs as $j) {
            if (in_array($j['status'], ['open', 'in_progress'])) {
                $activeJobs++;
            }
            if ($j['status'] === 'in_progress' || $j['status'] === 'completed') {
                $activeHires++;
            }
            $totalBidsReceived += (int) $j['proposal_count'];
        }

        $pageTitle = "Client Dashboard | Manage Jobs & Proposals";
        require __DIR__ . '/../views/client/dashboard.php';
    }

    /**
     * Show Job Creation Form (Feature C1)
     */
    public function showCreateJob(): void {
        $currentUser = AuthController::currentUser();
        $pageTitle = "Post a New Job | Freelancing Marketplace";
        $categories = ['Web Development', 'Mobile App Development', 'UI/UX Design', 'Digital Marketing', 'Content Writing', 'Graphics & Video', 'Data & AI'];
        require __DIR__ . '/../views/client/create_job.php';
    }

    /**
     * Process Job Creation (Feature C1)
     */
    public function createJob(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=create_job");
            exit;
        }

        $currentUser = AuthController::currentUser();
        $clientId    = $currentUser['id'];

        $title       = trim($_POST['title'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $budget      = (float) ($_POST['budget'] ?? 0);
        $deadline    = trim($_POST['deadline'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];

        if (strlen($title) < 5) {
            $errors[] = "Job Title must be at least 5 characters.";
        }
        if (empty($category)) {
            $errors[] = "Please select a valid job category.";
        }
        if ($budget <= 0) {
            $errors[] = "Budget must be greater than 0 BDT.";
        }
        if (empty($deadline) || strtotime($deadline) <= strtotime(date('Y-m-d'))) {
            $errors[] = "Deadline must be a valid future date.";
        }
        if (strlen($description) < 20) {
            $errors[] = "Please provide a detailed job description (minimum 20 characters).";
        }

        if (!empty($errors)) {
            AuthController::setFlash('danger', implode('<br>', $errors));
            header("Location: index.php?action=create_job");
            exit;
        }

        try {
            $jobId = $this->jobModel->create($clientId, $title, $description, $category, $budget, $deadline);
            AuthController::setFlash('success', "Job posting created successfully! Freelancers can now submit bids.");
            header("Location: index.php?action=client_dashboard");
            exit;
        } catch (Exception $e) {
            AuthController::setFlash('danger', "Error posting job: " . $e->getMessage());
            header("Location: index.php?action=create_job");
            exit;
        }
    }

    /**
     * Show Job Edit Form (Feature C1)
     */
    public function showEditJob(): void {
        $currentUser = AuthController::currentUser();
        $clientId    = $currentUser['id'];
        $jobId       = (int) ($_GET['id'] ?? 0);

        $job = $this->jobModel->getById($jobId);

        if (!$job || (int)$job['client_id'] !== $clientId) {
            AuthController::setFlash('danger', "Job not found or unauthorized access.");
            header("Location: index.php?action=client_dashboard");
            exit;
        }

        $pageTitle = "Edit Job: " . htmlspecialchars($job['title']);
        $categories = ['Web Development', 'Mobile App Development', 'UI/UX Design', 'Digital Marketing', 'Content Writing', 'Graphics & Video', 'Data & AI'];
        require __DIR__ . '/../views/client/edit_job.php';
    }

    /**
     * Process Job Update (Feature C1)
     */
    public function updateJob(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=client_dashboard");
            exit;
        }

        $currentUser = AuthController::currentUser();
        $clientId    = $currentUser['id'];
        $jobId       = (int) ($_POST['id'] ?? 0);

        $title       = trim($_POST['title'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $budget      = (float) ($_POST['budget'] ?? 0);
        $deadline    = trim($_POST['deadline'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];
        if (strlen($title) < 5) $errors[] = "Job Title must be at least 5 characters.";
        if (empty($category)) $errors[] = "Please select a category.";
        if ($budget <= 0) $errors[] = "Budget must be greater than 0.";
        if (empty($deadline)) $errors[] = "Please choose a deadline.";
        if (strlen($description) < 20) $errors[] = "Description must be at least 20 characters.";

        if (!empty($errors)) {
            AuthController::setFlash('danger', implode('<br>', $errors));
            header("Location: index.php?action=edit_job&id={$jobId}");
            exit;
        }

        try {
            $updated = $this->jobModel->update($jobId, $clientId, $title, $description, $category, $budget, $deadline);
            if ($updated) {
                AuthController::setFlash('success', "Job updated successfully.");
            } else {
                AuthController::setFlash('danger', "Unable to update job.");
            }
        } catch (Exception $e) {
            AuthController::setFlash('danger', "Error: " . $e->getMessage());
        }

        header("Location: index.php?action=client_dashboard");
        exit;
    }

    /**
     * Close a Job Listing (Feature C1)
     */
    public function closeJob(): void {
        $currentUser = AuthController::currentUser();
        $clientId    = $currentUser['id'];
        $jobId       = (int) ($_GET['id'] ?? 0);

        $job = $this->jobModel->getById($jobId);
        if (!$job || (int)$job['client_id'] !== $clientId) {
            AuthController::setFlash('danger', "Job not found or unauthorized.");
            header("Location: index.php?action=client_dashboard");
            exit;
        }

        $this->jobModel->updateStatus($jobId, 'closed', $clientId);
        AuthController::setFlash('info', "Job listing has been marked as closed.");
        header("Location: index.php?action=client_dashboard");
        exit;
    }

    /**
     * Delete a Job Listing (Feature C1)
     */
    public function deleteJob(): void {
        $currentUser = AuthController::currentUser();
        $clientId    = $currentUser['id'];
        $jobId       = (int) ($_GET['id'] ?? 0);

        $job = $this->jobModel->getById($jobId);
        if (!$job || (int)$job['client_id'] !== $clientId) {
            AuthController::setFlash('danger', "Job not found or unauthorized.");
            header("Location: index.php?action=client_dashboard");
            exit;
        }

        $this->jobModel->deleteJob($jobId, $clientId);
        AuthController::setFlash('success', "Job deleted successfully.");
        header("Location: index.php?action=client_dashboard");
        exit;
    }

    /**
     * View Proposals / Bids for a Job (Feature C2)
     */
    public function viewBids(): void {
        $currentUser = AuthController::currentUser();
        $clientId    = $currentUser['id'];
        $jobId       = (int) ($_GET['job_id'] ?? 0);

        $job = $this->jobModel->getById($jobId);

        if (!$job || (int)$job['client_id'] !== $clientId) {
            AuthController::setFlash('danger', "Job not found or you do not have permission to view its proposals.");
            header("Location: index.php?action=client_dashboard");
            exit;
        }

        $proposals = $this->proposalModel->getByJobId($jobId);
        $pageTitle = "Proposals for: " . htmlspecialchars($job['title']);
        require __DIR__ . '/../views/client/view_bids.php';
    }

    /**
     * Execute Freelancer Hiring Action (Feature C2)
     */
    public function hireFreelancer(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=client_dashboard");
            exit;
        }

        $currentUser = AuthController::currentUser();
        $clientId    = $currentUser['id'];
        $jobId       = (int) ($_POST['job_id'] ?? 0);
        $proposalId  = (int) ($_POST['proposal_id'] ?? 0);

        if (!$jobId || !$proposalId) {
            AuthController::setFlash('danger', "Invalid hiring request parameters.");
            header("Location: index.php?action=client_dashboard");
            exit;
        }

        try {
            $this->proposalModel->hireFreelancer($jobId, $proposalId, $clientId);
            AuthController::setFlash('success', "🎉 Freelancer hired successfully! The job is now in progress and other bids have been updated.");
            header("Location: index.php?action=view_bids&job_id={$jobId}");
            exit;
        } catch (Exception $e) {
            AuthController::setFlash('danger', "Hiring failed: " . $e->getMessage());
            header("Location: index.php?action=view_bids&job_id={$jobId}");
            exit;
        }
    }

    /**
     * Show Dispute / Report Filing Form (Feature C3)
     */
    public function showFileReport(): void {
        $currentUser = AuthController::currentUser();
        $clientId    = $currentUser['id'];

        $targetUserId = (int) ($_GET['user_id'] ?? 0);
        $jobId        = (int) ($_GET['job_id'] ?? 0);

        // Fetch candidate users to report
        $candidates = $this->userModel->getReportCandidates($clientId);
        $clientJobs = $this->jobModel->getByClientId($clientId);

        $reasons = [
            'Non-responsive freelancer after award',
            'Incomplete or sub-standard work delivery',
            'Missed critical project deadline',
            'Off-platform payment solicitation',
            'Harassment or unprofessional conduct',
            'Other policy violation'
        ];

        $pageTitle = "File a Dispute or Report | Trust & Safety";
        require __DIR__ . '/../views/client/file_report.php';
    }

    /**
     * Process Dispute Submission (Feature C3)
     */
    public function submitReport(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=client_dashboard");
            exit;
        }

        $currentUser    = AuthController::currentUser();
        $reporterId     = $currentUser['id'];
        $reportedUserId = (int) ($_POST['reported_user_id'] ?? 0);
        $jobId          = !empty($_POST['job_id']) ? (int)$_POST['job_id'] : null;
        $reason         = trim($_POST['reason'] ?? '');
        $details        = trim($_POST['details'] ?? '');

        $errors = [];
        if (!$reportedUserId) $errors[] = "Please select the offending user to report.";
        if (empty($reason)) $errors[] = "Please select a dispute reason.";
        if (strlen($details) < 15) $errors[] = "Please provide detailed narrative of the issue (minimum 15 characters).";

        if (!empty($errors)) {
            AuthController::setFlash('danger', implode('<br>', $errors));
            header("Location: index.php?action=file_report&user_id={$reportedUserId}&job_id={$jobId}");
            exit;
        }

        try {
            $this->reportModel->create($reporterId, $reportedUserId, $jobId, $reason, $details);
            AuthController::setFlash('success', "Your dispute has been submitted to platform administrators for investigation.");
            header("Location: index.php?action=client_dashboard");
            exit;
        } catch (Exception $e) {
            AuthController::setFlash('danger', "Failed to file report: " . $e->getMessage());
            header("Location: index.php?action=file_report");
            exit;
        }
    }
}
