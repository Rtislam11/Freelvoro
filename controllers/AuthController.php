<?php
/**
 * AuthController
 * Handles Session Management, User Authentication, Registration, and Access Control Guards.
 */

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private User $userModel;

    public function __construct() {
        self::startSession();
        $this->userModel = new User();
    }

    /**
     * Start session if not already active
     */
    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Store flash message in session
     */
    public static function setFlash(string $type, string $message): void {
        self::startSession();
        $_SESSION['flash'] = [
            'type'    => $type, // 'success', 'danger', 'warning', 'info'
            'message' => $message
        ];
    }

    /**
     * Retrieve and clear flash message
     */
    public static function getFlash(): ?array {
        self::startSession();
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated(): bool {
        self::startSession();
        return !empty($_SESSION['user_id']);
    }

    /**
     * Get current user session payload
     */
    public static function currentUser(): ?array {
        self::startSession();
        if (!self::isAuthenticated()) {
            return null;
        }
        return [
            'id'    => $_SESSION['user_id'],
            'name'  => $_SESSION['user_name'] ?? 'User',
            'email' => $_SESSION['user_email'] ?? '',
            'role'  => $_SESSION['user_role'] ?? 'guest'
        ];
    }

    /**
     * Access guard: Requires user to be logged in
     */
    public static function requireLogin(): void {
        if (!self::isAuthenticated()) {
            self::setFlash('warning', 'Please sign in to access this page.');
            header("Location: index.php?action=login");
            exit;
        }
    }

    /**
     * Access guard: Requires user to have a specific role
     */
    public static function requireRole(string $requiredRole): void {
        self::requireLogin();
        if (($_SESSION['user_role'] ?? '') !== $requiredRole) {
            self::setFlash('danger', 'Access denied. You do not have permission to view this section.');
            if (($_SESSION['user_role'] ?? '') === 'admin') {
                header("Location: index.php?action=admin_dashboard");
            } elseif (($_SESSION['user_role'] ?? '') === 'client') {
                header("Location: index.php?action=client_dashboard");
            } else {
                header("Location: index.php?action=login");
            }
            exit;
        }
    }

    /**
     * Render Login View
     */
    public function showLogin(): void {
        if (self::isAuthenticated()) {
            $this->redirectByRole($_SESSION['user_role']);
            return;
        }
        $pageTitle = "Sign In | Freelancing Marketplace";
        require __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Process Login Form Submission
     */
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=login");
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            self::setFlash('danger', 'Please enter both your email and password.');
            header("Location: index.php?action=login");
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            self::setFlash('danger', 'Invalid email credentials or incorrect password.');
            header("Location: index.php?action=login");
            exit;
        }

        // Authentication Success - regenerate session for security
        session_regenerate_id(true);
        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];

        self::setFlash('success', "Welcome back, " . htmlspecialchars($user['name']) . "!");
        $this->redirectByRole($user['role']);
    }

    /**
     * Render Registration View
     */
    public function showRegister(): void {
        if (self::isAuthenticated()) {
            $this->redirectByRole($_SESSION['user_role']);
            return;
        }
        $pageTitle = "Create Account | Freelancing Marketplace";
        require __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Process User Registration Form Submission
     */
    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=register");
            exit;
        }

        $name            = trim($_POST['name'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role            = $_POST['role'] ?? 'client';

        $errors = [];

        if (strlen($name) < 2) {
            $errors[] = "Full Name must be at least 2 characters.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please provide a valid email address.";
        }

        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters in length.";
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        // Restrict registration to client or freelancer (admins are created via seed/database)
        if (!in_array($role, ['client', 'freelancer'])) {
            $errors[] = "Invalid account role selected.";
        }

        // Check for duplicate email
        if (empty($errors) && $this->userModel->findByEmail($email)) {
            $errors[] = "An account with this email address already exists.";
        }

        if (!empty($errors)) {
            self::setFlash('danger', implode('<br>', $errors));
            header("Location: index.php?action=register");
            exit;
        }

        try {
            $userId = $this->userModel->create($name, $email, $password, $role);

            // Auto-login registered user
            session_regenerate_id(true);
            $_SESSION['user_id']    = $userId;
            $_SESSION['user_name']  = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role']  = $role;

            $roleLabel = ($role === 'client') ? '💼 Client (Employer & Job Poster)' : '💻 Freelancer (Talent & Bidding)';
            self::setFlash('success', "🎉 Account created successfully! You are logged in as <strong>" . htmlspecialchars($name) . "</strong> with <strong>" . htmlspecialchars($roleLabel) . "</strong> permissions.");
            $this->redirectByRole($role);
        } catch (Exception $e) {
            self::setFlash('danger', "Registration error: " . $e->getMessage());
            header("Location: index.php?action=register");
            exit;
        }
    }

    /**
     * Process Logout Action
     */
    public function logout(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        self::startSession();
        self::setFlash('info', 'You have been successfully signed out.');
        header("Location: index.php?action=login");
        exit;
    }

    /**
     * Redirect helper based on role
     */
    private function redirectByRole(string $role): void {
        if ($role === 'admin') {
            header("Location: index.php?action=admin_dashboard");
        } elseif ($role === 'client') {
            header("Location: index.php?action=client_dashboard");
        } else {
            // Freelancer hook
            self::setFlash('info', 'Welcome Freelancer! Freelancer-specific bidding portal is connected via the shared SQL schema.');
            header("Location: index.php?action=login");
        }
        exit;
    }
}
