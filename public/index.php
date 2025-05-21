<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

// CORS headers - MUST BE AT THE VERY TOP BEFORE ANY OUTPUT
header('Access-Control-Allow-Origin: https://games-ticket.netlify.app');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 3600');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 204 No Content');
    header('Content-Length: 0');
    exit;
}

require dirname(__DIR__).'/vendor/autoload.php';

// Load env vars
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

// Initialize kernel and handle request
$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);