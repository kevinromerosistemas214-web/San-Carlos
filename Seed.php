<?php
// Ejecutar UNA sola vez, abriendo este archivo directamente en el navegador
// (ej. http://localhost/api/seed.php). Después bórralo o protégelo por seguridad.
require_once __DIR__ . '/conexion.php';

$horarios = [
    '07:00', '07:20', '07:40', '07:40', '07:50', '08:00', '08:00', '08:10', '12:20',
    '12:20', '12:20', '12:20', '12:20', '08:10', '08:20', '08:30', '08:50', '09:00'
];

// Usuario admin inicial: kevin / 1221 (cámbiala luego desde el panel de Usuarios)
$hashAdmin = password_hash('1221', PASSWORD_BCRYPT);
$pdo->prepare("INSERT INTO users (username, password_hash, role)
               VALUES ('kevin', :h, 'admin')
               ON CONFLICT (username) DO NOTHING")
    ->execute(['h' => $hashAdmin]);

$fecha = date('Y-m-d');

foreach ([1, 2] as $tabla) {
    foreach ($horarios as $i => $hora) {
        $hoyo = $i + 1;

        $stmt = $pdo->prepare("INSERT INTO tee_times (fecha, tabla_num, hoyo, hora_salida)
                                VALUES (:f, :t, :h, :hh)
                                ON CONFLICT (fecha, tabla_num, hoyo) DO NOTHING
                                RETURNING id");
        $stmt->execute(['f' => $fecha, 't' => $tabla, 'h' => $hoyo, 'hh' => $hora]);
        $fila = $stmt->fetch();

        if ($fila) {
            $pdo->prepare("INSERT INTO tee_time_slots (tee_time_id, campo) VALUES (:id, 'Norte')")
                ->execute(['id' => $fila['id']]);
        }
    }
}

header('Content-Type: text/plain; charset=utf-8');
echo "Listo.\n";
echo "Usuario admin: kevin / contraseña: 1221\n";
echo "Salidas creadas para la fecha: $fecha (tabla 1 y tabla 2, 18 hoyos c/u)\n";