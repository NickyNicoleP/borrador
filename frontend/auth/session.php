<?php
/**
 * Manejo de sesiones seguras
 */

session_start();

// Regenerar ID de sesión periódicamente para mayor seguridad
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutos
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Verificar si el usuario está logueado
function estaLogueado() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Verificar si el usuario es administrador
function esAdministrador() {
    return estaLogueado() && $_SESSION['user_rol'] === 'admin';
}

// Verificar si el usuario tiene acceso a una página
function verificarAcceso($rolRequerido = 'usuario') {
    if (!estaLogueado()) {
        header('Location: login.php');
        exit;
    }

    $roles = ['usuario' => 1, 'admin' => 2];
    
    if ($roles[$_SESSION['user_rol']] < $roles[$rolRequerido]) {
        header('Location: unauthorized.php');
        exit;
    }
}

// Obtener información del usuario logueado
function obtenerUsuario() {
    if (estaLogueado()) {
        return [
            'id' => $_SESSION['user_id'],
            'nombre' => $_SESSION['user_nombre'],
            'email' => $_SESSION['user_email'],
            'rol' => $_SESSION['user_rol']
        ];
    }
    return null;
}

// Cerrar sesión
function logout() {
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}
?>