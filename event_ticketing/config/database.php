<?php
session_start();

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public  $conn;

    public function __construct() {
        $local = is_file(__DIR__ . '/local.php') ? require __DIR__ . '/local.php' : [];
        $this->host     = getenv('DB_HOST') ?: ($local['host'] ?? '127.0.0.1');
        $this->db_name  = getenv('DB_NAME') ?: ($local['name'] ?? 'tiketku');
        $this->username = getenv('DB_USER') ?: ($local['user'] ?? '');
        $this->password = getenv('DB_PASS') ?: ($local['pass'] ?? '');

        if ($this->username === '' || $this->password === '') {
            error_log('Database credentials are not configured');
            http_response_code(500);
            exit('Service unavailable.');
        }
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            http_response_code(500);
            exit('Service unavailable.');
        }
        return $this->conn;
    }
}
?>