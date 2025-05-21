<?php


// CORS headers for all responses
header('Access-Control-Allow-Origin: https://games-ticket.netlify.app');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('HTTP/1.1 204 No Content');
    exit;
}

// Reste du fichier original...
