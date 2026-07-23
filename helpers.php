<?php
// Incluir SIEMPRE al inicio de cada endpoint (login.php, obtener_salidas.php, etc.)
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexion.php';

function responder($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

function error($mensaje, $code = 400) {
    responder(["status" => "error", "message" => $mensaje], $code);
}

function usuarioSesion() {
    return $_SESSION['usuario'] ?? null;
}

// Cualquier operador logueado (Admin u Operador)
function requiereSesion() {
    $u = usuarioSesion();
    if (!$u) {
        error('Debes iniciar sesión.', 401);
    }
    return $u;
}

// Solo Administradores
function requiereAdmin() {
    $u = requiereSesion();
    if ($u['role'] !== 'admin') {
        error('No autorizado. Se requiere rol de administrador.', 403);
    }
    return $u;
}

function leerJson() {
    $data = json_decode(file_get_contents('php://input'), true);
    return $data ?? [];
}
