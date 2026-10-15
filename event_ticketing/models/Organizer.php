<?php
class Organizer {
    private $conn;
    private $table_name = "organizers";

    public $id;
    public $name;
    public $contact_email;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY name ASC";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>