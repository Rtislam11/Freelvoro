<?php
/**
 * Database Connection Handler (PDO Singleton Pattern)
 * Integrated Freelance Marketplace & Peer-to-Peer Assistance Platform
 */

class Database {
    private static ?Database $instance = null;
    private ?PDO $pdo = null;

    private string $host = '127.0.0.1';
    private string $db   = 'freelance_platform';
    private string $user = 'root';
    private string $pass = '';
    private string $charset = 'utf8mb4';

    /**
     * Private constructor to enforce Singleton pattern
     */
    private function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // If the database does not exist yet, attempt automatic creation and schema import
            if ($e->getCode() == 1049 || str_contains($e->getMessage(), 'Unknown database')) {
                $this->autoInitDatabase();
                $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            } else {
                throw new Exception("Database Connection Error: " . $e->getMessage());
            }
        }
    }

    /**
     * Returns the singleton Database instance
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Returns the active PDO connection
     */
    public function getConnection(): PDO {
        return $this->pdo;
    }

    /**
     * Helper to automatically initialize database and tables using schema.sql
     */
    public function autoInitDatabase(): bool {
        try {
            $rootDsn = "mysql:host={$this->host};charset={$this->charset}";
            $rootPdo = new PDO($rootDsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $schemaFile = __DIR__ . '/schema.sql';
            if (!file_exists($schemaFile)) {
                throw new Exception("Schema file not found at: {$schemaFile}");
            }

            $sql = file_get_contents($schemaFile);
            $rootPdo->exec($sql);
            return true;
        } catch (Exception $ex) {
            throw new Exception("Database Auto-Initialization Failed: " . $ex->getMessage());
        }
    }

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
