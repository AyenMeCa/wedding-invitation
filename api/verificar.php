<?php
require 'config.php';
setCORSHeaders();

$nombre = trim($_GET['nombre'] ?? '');

if ($nombre === '') {
    echo json_encode(['error' => 'Nombre requerido']);
    exit();
}

$db   = getDB();
$stmt = $db->prepare("SELECT cupos, estado FROM invitados WHERE nombre = ?");
$stmt->execute([$nombre]);
$row  = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode(['found' => true, 'estado' => $row['estado'], 'cupos' => (int)$row['cupos']]);
} else {
    echo json_encode(['found' => false]);
}
