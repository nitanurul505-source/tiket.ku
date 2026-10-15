<?php
class Ticket {
    private $conn;
    private $table_name = "tickets";

    public $id;
    public $ticket_code;
    public $status;
    public $qty;
    public $event_id;
    public $user_id;
    public $event_name;
    public $user_name;
    public $event_date;
    public $venue;
    public $price;
    public $organizer_name;
    public $purchase_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT t.*, e.name as event_name, e.event_date, e.venue, e.price,
                         o.name as organizer_name, u.name as user_name
                  FROM " . $this->table_name . " t
                  LEFT JOIN events e     ON t.event_id = e.id
                  LEFT JOIN organizers o ON e.organizer_id = o.id
                  LEFT JOIN users u      ON t.user_id = u.id
                  ORDER BY t.purchase_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByUser($user_id) {
        $query = "SELECT t.*, e.name as event_name, e.event_date, e.venue, e.price,
                         o.name as organizer_name, u.name as user_name
                  FROM " . $this->table_name . " t
                  LEFT JOIN events e     ON t.event_id = e.id
                  LEFT JOIN organizers o ON e.organizer_id = o.id
                  LEFT JOIN users u      ON t.user_id = u.id
                  WHERE t.user_id = ?
                  ORDER BY t.purchase_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function create($custom_prefix = null, $qty = 1) {
        $uniquePart = strtoupper(substr(uniqid(), -4));
        $date       = date('Ymd');
        $prefix     = 'TIX';

        if (!empty($custom_prefix)) {
            $clean = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $custom_prefix));
            if (!empty($clean)) $prefix = substr($clean, 0, 6);
        }

        $this->ticket_code = $prefix . '-' . $date . '-' . $uniquePart;
        $this->qty         = max(1, (int)$qty);

        $query = "INSERT INTO " . $this->table_name . "
                  SET ticket_code=:ticket_code, event_id=:event_id,
                      user_id=:user_id, qty=:qty, status='pending'";
        $stmt = $this->conn->prepare($query);

        $this->ticket_code = htmlspecialchars(strip_tags($this->ticket_code));
        $this->event_id    = htmlspecialchars(strip_tags($this->event_id));
        $this->user_id     = htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(":ticket_code", $this->ticket_code);
        $stmt->bindParam(":event_id",    $this->event_id);
        $stmt->bindParam(":user_id",     $this->user_id);
        $stmt->bindParam(":qty",         $this->qty);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function readOne() {
        $query = "SELECT t.*, e.name as event_name, e.event_date, e.venue, e.price,
                         o.name as organizer_name, u.name as user_name
                  FROM " . $this->table_name . " t
                  LEFT JOIN events e     ON t.event_id = e.id
                  LEFT JOIN organizers o ON e.organizer_id = o.id
                  LEFT JOIN users u      ON t.user_id = u.id
                  WHERE t.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->ticket_code    = $row['ticket_code'];
            $this->status         = $row['status'];
            $this->qty            = $row['qty'] ?? 1;
            $this->event_id       = $row['event_id'];
            $this->user_id        = $row['user_id'];
            $this->event_name     = $row['event_name'];
            $this->user_name      = $row['user_name'];
            $this->event_date     = $row['event_date'];
            $this->venue          = $row['venue'];
            $this->price          = $row['price'];
            $this->organizer_name = $row['organizer_name'];
            $this->purchase_date  = $row['purchase_date'];
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

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt  = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>