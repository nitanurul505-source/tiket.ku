<?php
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/User.php';

class TicketController {
    private $db;
    private $ticket;
    private $event;
    private $payment;

    public function __construct($db) {
        $this->db      = $db;
        $this->ticket  = new Ticket($db);
        $this->event   = new Event($db);
        $this->payment = new Payment($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /event_ticketing/public/index.php?controller=auth&action=login");
            exit;
        }
        $stmt    = $this->ticket->readByUser($_SESSION['user_id']);
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/tickets/index.php';
    }

    public function detail() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /event_ticketing/public/index.php?controller=auth&action=login");
            exit;
        }
        if (!isset($_GET['id'])) {
            header("Location: /event_ticketing/public/index.php?controller=ticket&action=index");
            exit;
        }
        $this->ticket->id = $_GET['id'];
        if (!$this->ticket->readOne()) {
            header("Location: /event_ticketing/public/index.php?controller=ticket&action=index");
            exit;
        }
        if ($this->ticket->user_id != $_SESSION['user_id'] && !$_SESSION['is_admin']) {
            header("Location: /event_ticketing/public/index.php?controller=ticket&action=index");
            exit;
        }
        $ticket = $this->ticket;
        require_once __DIR__ . '/../views/tickets/detail.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /event_ticketing/public/index.php?controller=auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->ticket->event_id = $_POST['event_id'];
            $this->ticket->user_id  = $_SESSION['user_id'];
            $custom_prefix          = $_POST['custom_prefix'] ?? null;
            $qty                    = max(1, min(10, (int)($_POST['qty'] ?? 1)));

            $this->event->id = $this->ticket->event_id;
            $this->event->readOne();

            // Check capacity by quantity
            $confirmed_count = $this->event->getConfirmedTicketsCount();
            if (($confirmed_count + $qty) > $this->event->capacity) {
                $error = "There isn't enough remaining capacity for $qty tickets.";
                $evStmt   = $this->event->readAll();
                $events   = $evStmt->fetchAll(PDO::FETCH_ASSOC);
                $event_id = $this->ticket->event_id;

                // Calculate maxQty for the view
                $sold   = $this->event->getConfirmedTicketsCount();
                $sisa   = $this->event->capacity - $sold;
                $maxQty = max(0, min(10, $sisa));

                require_once __DIR__ . '/../views/tickets/create.php';
                return;
            }

            $ticket_id = $this->ticket->create($custom_prefix, $qty);
            if ($ticket_id) {
                $this->payment->amount    = $this->event->price * $qty;
                $this->payment->method    = $_POST['payment_method'];
                $this->payment->ticket_id = $ticket_id;
                $this->payment->create();

                header("Location: /event_ticketing/public/index.php?controller=ticket&action=detail&id=" . $ticket_id);
                exit;
            }
        }

        $event_id = $_GET['event_id'] ?? null;
        $evStmt   = $this->event->readAll();
        $events   = $evStmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate maxQty based on the remaining capacity of the selected event
        $maxQty = 10;
        if ($event_id) {
            $this->event->id = $event_id;
            $this->event->readOne();
            $sold   = $this->event->getConfirmedTicketsCount();
            $sisa   = $this->event->capacity - $sold;
            $maxQty = max(0, min(10, $sisa));
        }

        require_once __DIR__ . '/../views/tickets/create.php';
    }

    public function cancel() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /event_ticketing/public/index.php?controller=auth&action=login");
            exit;
        }
        if (isset($_GET['id'])) {
            $this->ticket->id = $_GET['id'];
            $this->ticket->readOne();
            if ($this->ticket->user_id == $_SESSION['user_id']) {
                $this->ticket->status = 'cancelled';
                $this->ticket->updateStatus();
            }
            header("Location: /event_ticketing/public/index.php?controller=ticket&action=index&success=cancelled");
            exit;
        }
    }
}
?>