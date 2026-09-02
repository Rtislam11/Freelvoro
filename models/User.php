<?php
/**
 * User Model
 * Handles Authentication, User Management, and Profile queries.
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find a user by their unique email address
     */
    /** Find user record by email address **/
    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => trim($email)]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Find a user by their ID
     */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Create/Register a new user with bcrypt password hashing
     */
    public function create(string $name, string $email, string $password, string $role): int {
        $validRoles = ['admin', 'client', 'freelancer'];
        if (!in_array($role, $validRoles)) {
            throw new InvalidArgumentException("Invalid user role specified.");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role, created_at)
            VALUES (:name, :email, :password, :role, NOW())
        ");
        $stmt->execute([
            ':name'     => trim($name),
            ':email'    => strtolower(trim($email)),
            ':password' => $hashedPassword,
            ':role'     => $role
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Verify user password against bcrypt hash
     */
    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    /**
     * Get all users, with optional role filtering
     */
    public function getAllUsers(?string $role = null): array {
        if ($role && in_array($role, ['admin', 'client', 'freelancer'])) {
            $stmt = $this->db->prepare("
                SELECT u.id, u.name, u.email, u.role, u.created_at,
                       (SELECT COUNT(*) FROM jobs WHERE client_id = u.id) AS total_jobs,
                       (SELECT COUNT(*) FROM proposals WHERE freelancer_id = u.id) AS total_proposals,
                       (SELECT COUNT(*) FROM reports WHERE reported_user_id = u.id) AS reports_against
                FROM users u
                WHERE u.role = :role
                ORDER BY u.created_at DESC
            ");
            $stmt->execute([':role' => $role]);
        } else {
            $stmt = $this->db->prepare("
                SELECT u.id, u.name, u.email, u.role, u.created_at,
                       (SELECT COUNT(*) FROM jobs WHERE client_id = u.id) AS total_jobs,
                       (SELECT COUNT(*) FROM proposals WHERE freelancer_id = u.id) AS total_proposals,
                       (SELECT COUNT(*) FROM reports WHERE reported_user_id = u.id) AS reports_against
                FROM users u
                ORDER BY u.created_at DESC
            ");
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    /**
     * Delete / Terminate a user account by ID
     */
    public function deleteUser(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Fetch user statistics by role for Admin Analytics
     */
    public function getUserStats(): array {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) AS total_users,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) AS total_admins,
                SUM(CASE WHEN role = 'client' THEN 1 ELSE 0 END) AS total_clients,
                SUM(CASE WHEN role = 'freelancer' THEN 1 ELSE 0 END) AS total_freelancers
            FROM users
        ");
        $stats = $stmt->fetch();
        return [
            'total'       => (int) ($stats['total_users'] ?? 0),
            'admins'      => (int) ($stats['total_admins'] ?? 0),
            'clients'     => (int) ($stats['total_clients'] ?? 0),
            'freelancers' => (int) ($stats['total_freelancers'] ?? 0),
        ];
    }

    /**
     * Get candidate users to report (excluding current user)
     */
    public function getReportCandidates(int $excludeUserId): array {
        $stmt = $this->db->prepare("
            SELECT id, name, email, role 
            FROM users 
            WHERE id != :exclude_id 
            ORDER BY name ASC
        ");
        $stmt->execute([':exclude_id' => $excludeUserId]);
        return $stmt->fetchAll();
    }
}

