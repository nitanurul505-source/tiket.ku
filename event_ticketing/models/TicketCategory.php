<?php
class TicketCategory {
    private $conn;
    private $table_name = "ticket_categories";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readByEvent($event_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE event_id = ? ORDER BY price ASC";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(1, $event_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>