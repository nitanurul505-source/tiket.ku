<?php
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Organizer.php';
require_once __DIR__ . '/../models/TicketCategory.php';

class EventController {
    private $db;
    private $event;
    private $organizer;
    private $category;

    // Path folder upload relatif dari public/
    private $uploadDir = __DIR__ . '/../public/uploads/events/';
    private $uploadUrl = '/event_ticketing/public/uploads/events/';

    public function __construct($db) {
        $this->db        = $db;
        $this->event     = new Event($db);
        $this->organizer = new Organizer($db);
        $this->category  = new TicketCategory($db);
    }

    public function index() {
        $search = trim($_GET['search'] ?? '');
        $stmt   = $this->event->readAll($search);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/events/index.php';
    }

    public function detail() {
        if (!isset($_GET['id'])) {
            header("Location: /event_ticketing/public/index.php?controller=event&action=index");
            exit;
        }
        $this->event->id = $_GET['id'];
        if (!$this->event->readOne()) {
            header("Location: /event_ticketing/public/index.php?controller=event&action=index");
            exit;
        }
        $event      = $this->event;
        $categories = $this->category->readByEvent($this->event->id);
        require_once __DIR__ . '/../views/events/detail.php';
    }

    public function create() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header("Location: /event_ticketing/public/index.php?controller=event&action=index");
            exit;
        }

        $uploadError = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->event->name            = $_POST['name'];
            $this->event->event_date      = $_POST['event_date'];
            $this->event->venue           = $_POST['venue'];
            $this->event->description     = $_POST['description']     ?? '';
            $this->event->location_detail = $_POST['location_detail'] ?? '';
            $this->event->capacity        = $_POST['capacity'];
            $this->event->price           = $_POST['price'];
            $this->event->organizer_id    = $_POST['organizer_id'];

            // Handle upload foto
            $this->event->image_url = $_POST['image_url'] ?? '';
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $result = $this->handleUpload($_FILES['image_file']);
                if ($result['success']) {
                    $this->event->image_url = $result['url'];
                } else {
                    $uploadError = $result['error'];
                }
            }

            if (!$uploadError && $this->event->create()) {
                header("Location: /event_ticketing/public/index.php?controller=event&action=index&success=added");
                exit;
            }
        }

        $organizers = $this->organizer->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/events/create.php';
    }

    public function edit() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header("Location: /event_ticketing/public/index.php?controller=event&action=index");
            exit;
        }

        $uploadError = null;

        if (isset($_GET['id'])) {
            $this->event->id = $_GET['id'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->event->name            = $_POST['name'];
                $this->event->event_date      = $_POST['event_date'];
                $this->event->venue           = $_POST['venue'];
                $this->event->description     = $_POST['description']     ?? '';
                $this->event->location_detail = $_POST['location_detail'] ?? '';
                $this->event->capacity        = $_POST['capacity'];
                $this->event->price           = $_POST['price'];
                $this->event->organizer_id    = $_POST['organizer_id'];

                // Mulai dari foto yang sudah ada
                $this->event->image_url = $_POST['image_url_existing'] ?? '';

                // Kalau ada file baru di-upload, pakai itu
                if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                    $result = $this->handleUpload($_FILES['image_file']);
                    if ($result['success']) {
                        $this->event->image_url = $result['url'];
                    } else {
                        $uploadError = $result['error'];
                    }
                }
                // Kalau ada URL baru diketik, pakai itu (override file)
                if (!empty(trim($_POST['image_url'] ?? ''))) {
                    $this->event->image_url = trim($_POST['image_url']);
                }

                if (!$uploadError && $this->event->update()) {
                    header("Location: /event_ticketing/public/index.php?controller=event&action=detail&id={$this->event->id}&success=updated");
                    exit;
                }
            }

            $this->event->readOne();
            $organizers = $this->organizer->readAll()->fetchAll(PDO::FETCH_ASSOC);
            require_once __DIR__ . '/../views/events/edit.php';
        }
    }

    public function delete() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header("Location: /event_ticketing/public/index.php?controller=event&action=index");
            exit;
        }
        if (isset($_GET['id'])) {
            $this->event->id = $_GET['id'];
            $this->event->delete();
            header("Location: /event_ticketing/public/index.php?controller=event&action=index&success=deleted");
            exit;
        }
    }

    // ── Handle file upload ──────────────────────────────────
    private function handleUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxSize      = 5 * 1024 * 1024; // 5 MB

        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Format file tidak didukung. Gunakan JPG, PNG, atau WebP.'];
        }
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Ukuran file terlalu besar. Maksimal 5 MB.'];
        }

        // Create a folder if one doesn't already exist
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        // Unique filename
        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'event_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        $destPath = $this->uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return ['success' => true, 'url' => $this->uploadUrl . $filename];
        }

        return ['success' => false, 'error' => 'Gagal menyimpan file. Pastikan folder uploads/events/ bisa ditulis.'];
    }
}
?>