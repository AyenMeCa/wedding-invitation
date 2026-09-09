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
$cupos  = (int)($data['cupos'] ?? 1);

if ($nombre === '' || $cupos < 1 || $cupos > 20) {
    echo json_encode(['status' => 'error', 'message' => 'Nombre requerido y cupos entre 1 y 20']);
    exit();
}

$db = getDB();
try {
    $stmt = $db->prepare("INSERT INTO invitados (nombre, cupos) VALUES (?, ?)");
    $stmt->execute([$nombre, $cupos]);
    $id = (int)$db->lastInsertId();
    echo json_encode([
        'status'    => 'success',
        'invitado'  => ['id' => $id, 'nombre' => $nombre, 'cupos' => $cupos, 'estado' => 'pendiente']
    ]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'message' => 'Ya existe un invitado con ese nombre']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al agregar invitado']);
    }
}
