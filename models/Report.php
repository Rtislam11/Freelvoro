<?php
/**
 * Report Model
 * Handles Dispute Filing, Content Flags, and Admin Moderation Actions.
 */

require_once __DIR__ . '/../config/database.php';

class Report {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * File a new dispute or moderation report
     */
    public function create(int $reporterId, int $reportedUserId, ?int $jobId, string $reason, string $details): int {
        $stmt = $this->db->prepare("
            INSERT INTO reports (reporter_id, reported_user_id, job_id, reason, details, status, created_at)
            VALUES (:reporter_id, :reported_user_id, :job_id, :reason, :details, 'pending', NOW())
        ");
        $stmt->execute([
            ':reporter_id'      => $reporterId,
            ':reported_user_id' => $reportedUserId,
            ':job_id'           => $jobId ?: null,
            ':reason'           => trim($reason),
            ':details'          => trim($details)
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Retrieve all reports with user & job metadata, optionally filtered by status
     */
    public function getAllReports(?string $status = null): array {
        $sql = "
            SELECT r.*,
                   u_rep.name AS reporter_name, u_rep.email AS reporter_email, u_rep.role AS reporter_role,
                   u_tgt.name AS reported_name, u_tgt.email AS reported_email, u_tgt.role AS reported_role,
                   j.title AS job_title
            FROM reports r
            JOIN users u_rep ON r.reporter_id = u_rep.id
            JOIN users u_tgt ON r.reported_user_id = u_tgt.id
            LEFT JOIN jobs j ON r.job_id = j.id
            WHERE 1=1
        ";
        $params = [];

        if ($status && in_array($status, ['pending', 'resolved', 'dismissed'])) {
            $sql .= " AND r.status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY CASE WHEN r.status = 'pending' THEN 1 ELSE 2 END, r.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Retrieve a specific report by ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT r.*,
                   u_rep.name AS reporter_name, u_rep.email AS reporter_email,
                   u_tgt.name AS reported_name, u_tgt.email AS reported_email,
                   j.title AS job_title
            FROM reports r
            JOIN users u_rep ON r.reporter_id = u_rep.id
            JOIN users u_tgt ON r.reported_user_id = u_tgt.id
            LEFT JOIN jobs j ON r.job_id = j.id
            WHERE r.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Update report resolution status ('resolved' or 'dismissed')
     */
    public function updateStatus(int $id, string $status): bool {
        $validStatuses = ['pending', 'resolved', 'dismissed'];
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException("Invalid report status.");
        }

        $stmt = $this->db->prepare("UPDATE reports SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Delete report entry
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM reports WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Fetch pending report count for admin badges and analytics
     */
    public function getPendingCount(): int {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM reports WHERE status = 'pending'");
        $res = $stmt->fetch();
        return (int) ($res['total'] ?? 0);
    }
}

