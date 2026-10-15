<?php
require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db       = $database->getConnection();

$controllerName = $_GET['controller'] ?? 'event';
$actionName     = $_GET['action']     ?? 'index';

switch ($controllerName) {
    case 'auth':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController($db);
        break;
    case 'event':
        require_once __DIR__ . '/../controllers/EventController.php';
        $controller = new EventController($db);
        break;
    case 'ticket':
        require_once __DIR__ . '/../controllers/TicketController.php';
        $controller = new TicketController($db);
        break;
    case 'payment':
        require_once __DIR__ . '/../controllers/PaymentController.php';
        $controller = new PaymentController($db);
        break;
    case 'user':
        require_once __DIR__ . '/../controllers/UserController.php';
        $controller = new UserController($db);
        break;
    default:
        require_once __DIR__ . '/../controllers/EventController.php';
        $controller = new EventController($db);
}

if (method_exists($controller, $actionName)) {
    $controller->{$actionName}();
} else {
    echo "Action not found.";
}
?>