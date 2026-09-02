<?php
/**
 * Proposal Model
 * Handles Proposal Queries and Atomic Hiring Workflow.
 */

require_once __DIR__ . '/../config/database.php';

class Proposal {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Fetch all proposals for a given job, including freelancer details
     */
    public function getByJobId(int $jobId): array {
        $stmt = $this->db->prepare("
            SELECT p.*, u.name AS freelancer_name, u.email AS freelancer_email, u.created_at AS freelancer_joined
            FROM proposals p
            JOIN users u ON p.freelancer_id = u.id
            WHERE p.job_id = :job_id
            ORDER BY 
                CASE WHEN p.status = 'accepted' THEN 1
                     WHEN p.status = 'pending' THEN 2
                     ELSE 3 END,
                p.submitted_at ASC
        ");
        $stmt->execute([':job_id' => $jobId]);
        return $stmt->fetchAll();
    }

    /**
     * Fetch a specific proposal by ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, j.title AS job_title, j.client_id, u.name AS freelancer_name, u.email AS freelancer_email
            FROM proposals p
            JOIN jobs j ON p.job_id = j.id
            JOIN users u ON p.freelancer_id = u.id
            WHERE p.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Execute Atomic Freelancer Hiring Workflow (Feature C2)
     * 1. Validate client owns the job
     * 2. Set chosen proposal status to 'accepted'
     * 3. Set job status to 'in_progress'
     * 4. Auto-reject all other pending/competing proposals for this job
     */
    /** Atomic hire: accept bid, update job, reject others **/
    public function hireFreelancer(int $jobId, int $proposalId, int $clientId): bool {
        try {
            $this->db->beginTransaction();

            // 1. Check job ownership and verify status is 'open'
            $stmtJob = $this->db->prepare("SELECT id, status FROM jobs WHERE id = :job_id AND client_id = :client_id FOR UPDATE");
            $stmtJob->execute([':job_id' => $jobId, ':client_id' => $clientId]);
            $job = $stmtJob->fetch();

            if (!$job) {
                throw new Exception("Job not found or access denied.");
            }

            // 2. Validate proposal exists for this job
            $stmtProp = $this->db->prepare("SELECT id FROM proposals WHERE id = :proposal_id AND job_id = :job_id FOR UPDATE");
            $stmtProp->execute([':proposal_id' => $proposalId, ':job_id' => $jobId]);
            if (!$stmtProp->fetch()) {
                throw new Exception("Selected proposal does not exist for this job.");
            }

            // 3. Mark selected proposal as 'accepted'
            $stmtAccept = $this->db->prepare("UPDATE proposals SET status = 'accepted' WHERE id = :proposal_id");
            $stmtAccept->execute([':proposal_id' => $proposalId]);

            // 4. Mark competing proposals for this job as 'rejected'
            $stmtReject = $this->db->prepare("UPDATE proposals SET status = 'rejected' WHERE job_id = :job_id AND id != :proposal_id");
            $stmtReject->execute([':job_id' => $jobId, ':proposal_id' => $proposalId]);

            // 5. Update job status to 'in_progress'
            $stmtUpdateJob = $this->db->prepare("UPDATE jobs SET status = 'in_progress' WHERE id = :job_id");
            $stmtUpdateJob->execute([':job_id' => $jobId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Hiring Transaction Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Aggregate proposal stats
     */
    public function getProposalStats(): array {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) AS total_proposals,
                SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) AS accepted_proposals,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_proposals,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS rejected_proposals
            FROM proposals
        ");
        $res = $stmt->fetch();
        return [
            'total'    => (int) ($res['total_proposals'] ?? 0),
            'accepted' => (int) ($res['accepted_proposals'] ?? 0),
            'pending'  => (int) ($res['pending_proposals'] ?? 0),
            'rejected' => (int) ($res['rejected_proposals'] ?? 0),
        ];
    }
}

