<?php
require 'config.php';
setCORSHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

$data   = jsonInput();
$nombre = trim($data['nombre'] ?? '');
$estado = strtolower(trim($data['estado'] ?? ''));

if ($nombre === '' || !in_array($estado, ['aceptada', 'rechazada'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    exit();
}

$db   = getDB();
$stmt = $db->prepare("SELECT estado FROM invitados WHERE nombre = ?");
$stmt->execute([$nombre]);
$row  = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(['status' => 'error', 'message' => 'Invitado no encontrado']);
    exit();
}

if ($row['estado'] !== 'pendiente') {
    echo json_encode(['status' => 'error', 'message' => 'Ya respondiste anteriormente', 'estado' => $row['estado']]);
    exit();
}

$stmt = $db->prepare("UPDATE invitados SET estado = ?, responded_at = NOW() WHERE nombre = ?");
$stmt->execute([$estado, $nombre]);

echo json_encode(['status' => 'success', 'estado' => $estado]);
