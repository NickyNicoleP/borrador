<?php
include_once 'auth/session.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso No Autorizado - ConectaPlus</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="text-center">
            <i class="bi bi-shield-exclamation display-1 text-warning"></i>
            <h1 class="display-4 fw-bold text-dark">Acceso No Autorizado</h1>
            <p class="lead mb-4">No tienes permisos para acceder a esta sección.</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="dashboard.php" class="btn btn-primary">Volver al Dashboard</a>
                <a href="logout.php" class="btn btn-outline-secondary">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>