<?php
require_once __DIR__ . '/../models/Payment.php';
require_once __DIR__ . '/../models/Ticket.php';

class PaymentController {
    private $db;
    private $payment;
    private $ticket;

    public function __construct($db) {
        $this->db      = $db;
        $this->payment = new Payment($db);
        $this->ticket  = new Ticket($db);
    }

    public function index() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header("Location: /event_ticketing/public/index.php?controller=event&action=index");
            exit;
        }
        $stmt     = $this->payment->readAll();
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/payments/index.php';
    }

    public function confirm() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) exit;
        if (isset($_GET['id'])) {
            $this->payment->id = $_GET['id'];
            $this->payment->readOne();

            // Payment → paid
            $this->payment->status = 'paid';
            $this->payment->updateStatus();

            // Ticket → confirmed
            $this->ticket->id = $this->payment->ticket_id;
            if ($this->ticket->readOne()) {
                $this->ticket->status = 'confirmed';
                $this->ticket->updateStatus();
            }

            header("Location: /event_ticketing/public/index.php?controller=payment&action=index&success=confirmed");
            exit;
        }
    }

    public function reject() {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) exit;
        if (isset($_GET['id'])) {
            $this->payment->id = $_GET['id'];
            $this->payment->readOne();

            // Payment → failed
            $this->payment->status = 'failed';
            $this->payment->updateStatus();

            // FIX: Ticket → canceled (not still pending!)
            $this->ticket->id = $this->payment->ticket_id;
            if ($this->ticket->readOne()) {
                $this->ticket->status = 'cancelled';
                $this->ticket->updateStatus();
            }

            header("Location: /event_ticketing/public/index.php?controller=payment&action=index&success=rejected");
            exit;
        }
    }
}
?>