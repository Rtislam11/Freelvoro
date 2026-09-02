<?php
/**
 * Job Model
 * Handles Job Posting, Lifecycle Management, Moderation, and Filtering.
 */

require_once __DIR__ . '/../config/database.php';

class Job {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new job posting
     */
    public function create(int $clientId, string $title, string $description, string $category, float $budget, string $deadline): int {
        $stmt = $this->db->prepare("
            INSERT INTO jobs (client_id, title, description, category, budget, deadline, status, created_at)
            VALUES (:client_id, :title, :description, :category, :budget, :deadline, 'open', NOW())
        ");
        $stmt->execute([
            ':client_id'   => $clientId,
            ':title'       => trim($title),
            ':description' => trim($description),
            ':category'    => trim($category),
            ':budget'      => $budget,
            ':deadline'    => $deadline
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Retrieve a specific job by ID with client details and proposal count
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT j.*, u.name AS client_name, u.email AS client_email,
                   (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) AS proposal_count,
                   (SELECT p.id FROM proposals p WHERE p.job_id = j.id AND p.status = 'accepted' LIMIT 1) AS accepted_proposal_id,
                   (SELECT u2.name FROM proposals p JOIN users u2 ON p.freelancer_id = u2.id WHERE p.job_id = j.id AND p.status = 'accepted' LIMIT 1) AS hired_freelancer_name
            FROM jobs j
            JOIN users u ON j.client_id = u.id
            WHERE j.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $job = $stmt->fetch();
        return $job ?: null;
    }

    /**
     * Get all jobs created by a specific client
     */
    public function getByClientId(int $clientId): array {
        $stmt = $this->db->prepare("
            SELECT j.*, 
                   (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) AS proposal_count,
                   (SELECT p.id FROM proposals p WHERE p.job_id = j.id AND p.status = 'accepted' LIMIT 1) AS accepted_proposal_id,
                   (SELECT u2.name FROM proposals p JOIN users u2 ON p.freelancer_id = u2.id WHERE p.job_id = j.id AND p.status = 'accepted' LIMIT 1) AS hired_freelancer_name,
                   (SELECT p.bid_amount FROM proposals p WHERE p.job_id = j.id AND p.status = 'accepted' LIMIT 1) AS hired_bid_amount
            FROM jobs j
            WHERE j.client_id = :client_id
            ORDER BY j.created_at DESC
        ");
        $stmt->execute([':client_id' => $clientId]);
        return $stmt->fetchAll();
    }

    /**
     * Global Job Listing with optional filters (for Admin moderation and browsing)
     */
    public function getAllJobs(?string $status = null, ?string $category = null): array {
        $sql = "
            SELECT j.*, u.name AS client_name, u.email AS client_email,
                   (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) AS proposal_count,
                   (SELECT u2.name FROM proposals p JOIN users u2 ON p.freelancer_id = u2.id WHERE p.job_id = j.id AND p.status = 'accepted' LIMIT 1) AS hired_freelancer_name
            FROM jobs j
            JOIN users u ON j.client_id = u.id
            WHERE 1=1
        ";
        $params = [];

        if ($status && in_array($status, ['open', 'in_progress', 'completed', 'closed'])) {
            $sql .= " AND j.status = :status";
            $params[':status'] = $status;
        }

        if ($category) {
            $sql .= " AND j.category = :category";
            $params[':category'] = $category;
        }

        $sql .= " ORDER BY j.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Update job details by client owner
     */
    public function update(int $id, int $clientId, string $title, string $description, string $category, float $budget, string $deadline): bool {
        $stmt = $this->db->prepare("
            UPDATE jobs 
            SET title = :title, description = :description, category = :category, 
                budget = :budget, deadline = :deadline
            WHERE id = :id AND client_id = :client_id
        ");
        return $stmt->execute([
            ':id'          => $id,
            ':client_id'   => $clientId,
            ':title'       => trim($title),
            ':description' => trim($description),
            ':category'    => trim($category),
            ':budget'      => $budget,
            ':deadline'    => $deadline
        ]);
    }

    /**
     * Update job status (open, in_progress, completed, closed)
     */
    public function updateStatus(int $id, string $status, ?int $clientId = null): bool {
        $validStatuses = ['open', 'in_progress', 'completed', 'closed'];
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException("Invalid job status value.");
        }

        if ($clientId !== null) {
            $stmt = $this->db->prepare("UPDATE jobs SET status = :status WHERE id = :id AND client_id = :client_id");
            return $stmt->execute([':status' => $status, ':id' => $id, ':client_id' => $clientId]);
        } else {
            $stmt = $this->db->prepare("UPDATE jobs SET status = :status WHERE id = :id");
            return $stmt->execute([':status' => $status, ':id' => $id]);
        }
    }

    /**
     * Delete job posting (used by client or admin moderation)
     */
    public function deleteJob(int $id, ?int $clientId = null): bool {
        if ($clientId !== null) {
            $stmt = $this->db->prepare("DELETE FROM jobs WHERE id = :id AND client_id = :client_id");
            return $stmt->execute([':id' => $id, ':client_id' => $clientId]);
        } else {
            $stmt = $this->db->prepare("DELETE FROM jobs WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        }
    }

    /**
     * Get platform job statistics for Analytics
     */
    public function getJobStats(): array {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) AS total_jobs,
                SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) AS open_jobs,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) AS in_progress_jobs,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_jobs,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) AS closed_jobs,
                SUM(CASE WHEN status IN ('open', 'in_progress') THEN 1 ELSE 0 END) AS active_jobs,
                SUM(CASE WHEN status IN ('in_progress', 'completed') THEN 1 ELSE 0 END) AS total_hires,
                COALESCE(SUM(budget), 0) AS total_budget_volume
            FROM jobs
        ");
        $res = $stmt->fetch();
        return [
            'total_jobs'       => (int) ($res['total_jobs'] ?? 0),
            'open_jobs'        => (int) ($res['open_jobs'] ?? 0),
            'in_progress_jobs' => (int) ($res['in_progress_jobs'] ?? 0),
            'completed_jobs'   => (int) ($res['completed_jobs'] ?? 0),
            'closed_jobs'      => (int) ($res['closed_jobs'] ?? 0),
            'active_jobs'      => (int) ($res['active_jobs'] ?? 0),
            'total_hires'      => (int) ($res['total_hires'] ?? 0),
            'total_budget'     => (float) ($res['total_budget_volume'] ?? 0),
        ];
    }
}
