<?php
header('Content-Type: application/json');
require_once 'conexion.php';

try {
    $sql = "SELECT t.id, t.fecha, t.tabla_num, t.hora_salida, 
                   s.hoyo, s.campo, s.jugador_1, s.jugador_2, s.jugador_3, s.jugador_4, s.jugador_5
            FROM tee_times t
            LEFT JOIN tee_time_slots s ON t.id = s.tee_time_id
            ORDER BY t.hora_salida ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $salidas = $stmt->fetchAll();

    echo json_encode(["status" => "success", "data" => $salidas]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>