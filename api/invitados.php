<?php
require 'config.php';
setCORSHeaders();

$db   = getDB();
$stmt = $db->query("SELECT id, nombre, cupos, estado, created_at, responded_at FROM invitados ORDER BY created_at DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as &$r) {
    $r['cupos'] = (int)$r['cupos'];
}

echo json_encode($rows);
