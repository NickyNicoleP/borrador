<?php
include_once 'auth/session.php';

// Cerrar sesión
logout();

// Redirigir al login
header('Location: login.php');
exit;
?>