<?php
require_once 'helpers.php';
requiereSesion();

// Body esperado: { "fecha": "2026-07-22", "tabla_num": 1 }
$body     = leerJson();
$fecha    = $body['fecha'] ?? null;
$tablaNum = $body['tabla_num'] ?? null;

if (!$fecha || !$tablaNum) {
    error('Fecha y tabla_num son requeridos.');
}

$sql = "UPDATE tee_time_slots s
        SET jugador_1 = NULL, jugador_2 = NULL, jugador_3 = NULL, jugador_4 = NULL, jugador_5 = NULL
        FROM tee_times t
        WHERE s.tee_time_id = t.id AND t.fecha = :fecha AND t.tabla_num = :tabla";

$stmt = $pdo->prepare($sql);
$stmt->execute(['fecha' => $fecha, 'tabla' => $tablaNum]);

responder(['status' => 'success']);