<?php
require_once 'helpers.php';

$fecha = $_GET['fecha'] ?? date('Y-m-d');

$sql = "SELECT t.id, t.tabla_num, t.hoyo, t.hora_salida,
               s.campo, s.jugador_1, s.jugador_2, s.jugador_3, s.jugador_4, s.jugador_5
        FROM tee_times t
        LEFT JOIN tee_time_slots s ON t.id = s.tee_time_id
        WHERE t.fecha = :fecha
        ORDER BY t.tabla_num ASC, t.hoyo ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['fecha' => $fecha]);
$filas = $stmt->fetchAll();

$resultado = ['1' => [], '2' => []];
foreach ($filas as $fila) {
    $resultado[(string) $fila['tabla_num']][] = [
        'tee_time_id' => $fila['id'],
        'hoyo'        => (int) $fila['hoyo'],
        'horario'     => substr($fila['hora_salida'], 0, 5), // HH:MM
        'campo'       => $fila['campo'] ?? 'Norte',
        'jugadores'   => [
            $fila['jugador_1'], $fila['jugador_2'], $fila['jugador_3'],
            $fila['jugador_4'], $fila['jugador_5']
        ]
    ];
}

responder(['status' => 'success', 'fecha' => $fecha, 'data' => $resultado]);