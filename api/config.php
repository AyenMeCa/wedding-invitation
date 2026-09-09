<?php
// Suprimir errores PHP para que no rompan el JSON
error_reporting(0);
ini_set('display_errors', 0);

define('DB_HOST', 'localhost');
define('DB_NAME', 'boda');
define('DB_USER', 'root');
define('DB_PASS', '');

define('INVITATION_URL', 'http://boda.test/wedding-invitation_1.html');
define('ALLOWED_ORIGINS', ['http://boda.test', 'http://admin.test']);
define('TOKEN_TTL', 86400);

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

function getBearerToken() {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/^Bearer\s+(.+)$/i', $header, $m)) return $m[1];
    return null;
}

function requireAdmin() {
    $token = getBearerToken();
    if (!$token) {
        http_response_code(401);
        echo json_encode(['error' => 'No autorizado']);
        exit();
    }
    $db   = getDB();
    $stmt = $db->prepare(
        "SELECT admin_id FROM admin_tokens WHERE token = ? AND expires_at > NOW()"
    );
    $stmt->execute([$token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido o expirado']);
        exit();
    }
    return (int)$row['admin_id'];
}

function jsonInput() {
    return json_decode(file_get_contents('php://input'), true) ?? [];
}
