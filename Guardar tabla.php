<?php
require_once 'helpers.php';
requiereSesion();

// Body esperado:
// { "filas": [ { "tee_time_id": 12, "campo": "Norte", "jugadores": ["a","b","","",""] }, ... ] }
$body  = leerJson();
$filas = $body['filas'] ?? [];

if (!$filas) {
    error('No se recibieron filas para guardar.');
}

$sql = "UPDATE tee_time_slots
        SET campo = :campo,
            jugador_1 = :j1, jugador_2 = :j2, jugador_3 = :j3, jugador_4 = :j4, jugador_5 = :j5
        WHERE tee_time_id = :id";
$stmt = $pdo->prepare($sql);

$pdo->beginTransaction();
try {
    foreach ($filas as $fila) {
        $j = $fila['jugadores'] ?? [];
        $stmt->execute([
            'campo' => $fila['campo'] ?? 'Norte',
            'j1' => $j[0] ?? null,
            'j2' => $j[1] ?? null,
            'j3' => $j[2] ?? null,
            'j4' => $j[3] ?? null,
            'j5' => $j[4] ?? null,
            'id' => $fila['tee_time_id']
        ]);
    }
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    error('Error al guardar: ' . $e->getMessage(), 500);
}

responder(['status' => 'success']);