<?php
require 'config.php';
setCORSHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

$data = jsonInput();
$id   = (int)($data['id'] ?? 0);

if ($id < 1) {
    echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
    exit();
}

$db   = getDB();
$stmt = $db->prepare("DELETE FROM invitados WHERE id = ?");
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invitado no encontrado']);
}
