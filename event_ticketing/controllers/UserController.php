<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db   = $db;
        $this->user = new User($db);
    }

    public function index() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header("Location: /event_ticketing/public/index.php?controller=event&action=index");
            exit;
        }
        $stmt  = $this->user->readAll();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/users/index.php';
    }

    public function delete() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) exit;
        if (isset($_GET['id'])) {
            $this->user->id = $_GET['id'];
            $this->user->delete();
            header("Location: /event_ticketing/public/index.php?controller=user&action=index&success=user_deleted");
            exit;
        }
    }
}
?>