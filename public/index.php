<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load MongoDB compatibility classes before session_start()
require_once __DIR__ . '/../src/MongoDB/AtlasClient.php';

use App\Controllers\ListingController;
use App\Controllers\MortgageController;
use App\Controllers\OfferController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = uniqid('user_', true);
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    case '/':
    case '/listings':
        $controller = new ListingController();
        $controller->index();
        break;
        
    case '/listings/save':
        if ($method === 'POST') {
            $controller = new ListingController();
            $controller->save();
        }
        break;
        
    case '/listings/saved':
        $controller = new ListingController();
        $controller->saved();
        break;
        
    case '/listings/unsave':
        if ($method === 'POST') {
            $controller = new ListingController();
            $controller->unsave();
        }
        break;
        
    case '/mortgage':
    case '/mortgage/preapproval':
        $controller = new MortgageController();
        $controller->preapproval();
        break;
        
    case '/mortgage/calculate':
        if ($method === 'POST') {
            $controller = new MortgageController();
            $controller->calculate();
        }
        break;
        
    case '/offers':
        $controller = new OfferController();
        $controller->index();
        break;
        
    case '/offers/create':
        if ($method === 'POST') {
            $controller = new OfferController();
            $controller->create();
        }
        break;
        
    default:
        http_response_code(404);
        echo "404 - Page not found";
        break;
}