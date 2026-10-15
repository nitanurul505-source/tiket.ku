<?php
class Event {
    private $conn;
    private $table_name = "events";

    public $id;
    public $name;
    public $event_date;
    public $venue;
    public $description;
    public $image_url;
    public $location_detail;
    public $capacity;
    public $price;
    public $organizer_id;
    public $organizer_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll($search = '') {
        $query = "SELECT e.*, o.name as organizer_name
                  FROM " . $this->table_name . " e
                  LEFT JOIN organizers o ON e.organizer_id = o.id";
        if (!empty($search)) {
            $query .= " WHERE e.name LIKE :search OR e.venue LIKE :search OR o.name LIKE :search";
        }
        $query .= " ORDER BY e.event_date ASC";
        $stmt = $this->conn->prepare($query);
        if (!empty($search)) {
            $like = '%' . $search . '%';
            $stmt->bindParam(':search', $like);
        }
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET name=:name, event_date=:event_date, venue=:venue,
                      description=:description, image_url=:image_url,
                      location_detail=:location_detail,
                      capacity=:capacity, price=:price, organizer_id=:organizer_id";
        $stmt = $this->conn->prepare($query);

        $this->name            = htmlspecialchars(strip_tags($this->name));
        $this->event_date      = htmlspecialchars(strip_tags($this->event_date));
        $this->venue           = htmlspecialchars(strip_tags($this->venue));
        $this->description     = htmlspecialchars(strip_tags($this->description));
        $this->image_url       = htmlspecialchars(strip_tags($this->image_url));
        $this->location_detail = htmlspecialchars(strip_tags($this->location_detail));
        $this->capacity        = htmlspecialchars(strip_tags($this->capacity));
        $this->price           = htmlspecialchars(strip_tags($this->price));
        $this->organizer_id    = htmlspecialchars(strip_tags($this->organizer_id));

        $stmt->bindParam(':name',            $this->name);
        $stmt->bindParam(':event_date',      $this->event_date);
        $stmt->bindParam(':venue',           $this->venue);
        $stmt->bindParam(':description',     $this->description);
        $stmt->bindParam(':image_url',       $this->image_url);
        $stmt->bindParam(':location_detail', $this->location_detail);
        $stmt->bindParam(':capacity',        $this->capacity);
        $stmt->bindParam(':price',           $this->price);
        $stmt->bindParam(':organizer_id',    $this->organizer_id);

        return $stmt->execute();
    }

    public function readOne() {
        $query = "SELECT e.*, o.name as organizer_name
                  FROM " . $this->table_name . " e
                  LEFT JOIN organizers o ON e.organizer_id = o.id
                  WHERE e.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->name            = $row['name'];
            $this->event_date      = $row['event_date'];
            $this->venue           = $row['venue'];
            $this->description     = $row['description']     ?? '';
            $this->image_url       = $row['image_url']       ?? '';
            $this->location_detail = $row['location_detail'] ?? '';
            $this->capacity        = $row['capacity'];
            $this->price           = $row['price'];
            $this->organizer_id    = $row['organizer_id'];
            $this->organizer_name  = $row['organizer_name'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET name=:name, event_date=:event_date, venue=:venue,
                      description=:description, image_url=:image_url,
                      location_detail=:location_detail,
                      capacity=:capacity, price=:price, organizer_id=:organizer_id
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->name            = htmlspecialchars(strip_tags($this->name));
        $this->event_date      = htmlspecialchars(strip_tags($this->event_date));
        $this->venue           = htmlspecialchars(strip_tags($this->venue));
        $this->description     = htmlspecialchars(strip_tags($this->description));
        $this->image_url       = htmlspecialchars(strip_tags($this->image_url));
        $this->location_detail = htmlspecialchars(strip_tags($this->location_detail));
        $this->capacity        = htmlspecialchars(strip_tags($this->capacity));
        $this->price           = htmlspecialchars(strip_tags($this->price));
        $this->organizer_id    = htmlspecialchars(strip_tags($this->organizer_id));
        $this->id              = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name',            $this->name);
        $stmt->bindParam(':event_date',      $this->event_date);
        $stmt->bindParam(':venue',           $this->venue);
        $stmt->bindParam(':description',     $this->description);
        $stmt->bindParam(':image_url',       $this->image_url);
        $stmt->bindParam(':location_detail', $this->location_detail);
        $stmt->bindParam(':capacity',        $this->capacity);
        $stmt->bindParam(':price',           $this->price);
        $stmt->bindParam(':organizer_id',    $this->organizer_id);
        $stmt->bindParam(':id',              $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt  = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }

    public function getConfirmedTicketsCount() {
        $query = "SELECT COALESCE(SUM(qty), 0) as count FROM tickets 
                WHERE event_id = ? AND status IN ('confirmed', 'pending')";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    public function getCategories() {
        $query = "SELECT * FROM ticket_categories WHERE event_id = ? ORDER BY price ASC";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>