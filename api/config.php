<?php
// Suprimir errores PHP para que no rompan el JSON
error_reporting(0);
ini_set('display_errors', 0);

define('DB_HOST', 'localhost');
define('DB_NAME', 'boda');
define('DB_USER', 'root');
define('DB_PASS', '');

function setCORSHeaders() {
    // Apache (vhost) ya pone Access-Control-Allow-Origin.
    // PHP solo pone Content-Type y atiende el preflight OPTIONS.
    header('Content-Type: application/json; charset=utf-8');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit();
    }
}

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    return $pdo;
}

function jsonInput() {
    return json_decode(file_get_contents('php://input'), true) ?? [];
}
