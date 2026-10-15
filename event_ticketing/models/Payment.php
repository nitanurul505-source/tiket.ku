<?php
class Payment {
    private $conn;
    private $table_name = "payments";

    public $id;
    public $amount;
    public $method;
    public $status;
    public $ticket_id;
    public $ticket_code;
    public $payment_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT p.*, t.ticket_code, t.qty, t.status as ticket_status,
                        u.name as user_name, e.name as event_name
                FROM " . $this->table_name . " p
                LEFT JOIN tickets t  ON p.ticket_id = t.id
                LEFT JOIN users u    ON t.user_id = u.id
                LEFT JOIN events e   ON t.event_id = e.id
                ORDER BY p.payment_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET amount=:amount, method=:method, ticket_id=:ticket_id, status='unpaid'";
        $stmt  = $this->conn->prepare($query);

        $this->amount    = htmlspecialchars(strip_tags($this->amount));
        $this->method    = htmlspecialchars(strip_tags($this->method));
        $this->ticket_id = htmlspecialchars(strip_tags($this->ticket_id));

        $stmt->bindParam(":amount",    $this->amount);
        $stmt->bindParam(":method",    $this->method);
        $stmt->bindParam(":ticket_id", $this->ticket_id);

        return $stmt->execute();
    }

    public function readOne() {
        $query = "SELECT p.*, t.ticket_code, t.user_id, t.qty
                  FROM " . $this->table_name . " p
                  LEFT JOIN tickets t ON p.ticket_id = t.id
                  WHERE p.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->amount      = $row['amount'];
            $this->method      = $row['method'];
            $this->status      = $row['status'];
            $this->ticket_id   = $row['ticket_id'];
            $this->ticket_code = $row['ticket_code'];
            return true;
        }
        return false;
    }

    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id     = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id',     $this->id);
        return $stmt->execute();
    }
}
?>