<?php
session_start();

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public  $conn;

    public function __construct() {
        $this->host     = getenv('DB_HOST') ?: '127.0.0.1';
        $this->db_name  = getenv('DB_NAME') ?: 'tiketku';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
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
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>