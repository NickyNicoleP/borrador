<?php
/**
 * Interfaz de administración con autenticación
 */

// Incluir autenticación - requiere rol de administrador
include_once 'auth/session.php';
verificarAcceso('admin'); // Solo administradores pueden acceder

// El resto del código de admin.php permanece igual...
include_once 'config/database.php';
include_once 'models/Promocion.php';
include_once 'models/Cotizacion.php';
include_once 'models/Newsletter.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Instanciar objetos
$promocion = new Promocion($db);
$cotizacion = new Cotizacion($db);
$newsletter = new Newsletter($db);

// Procesar formulario para crear nueva promoción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_promocion'])) {
    $promocion->nombre = $_POST['nombre'] ?? '';
    $promocion->descripcion = $_POST['descripcion'] ?? '';
    $promocion->precio = floatval($_POST['precio'] ?? 0);
    $promocion->vigencia = $_POST['vigencia'] ?? '';
    $promocion->activa = isset($_POST['activa']) ? 1 : 0;

    if($promocion->crear()) {
        $mensaje_exito = "¡Promoción creada exitosamente!";
    } else {
        $mensaje_error = "Error al crear la promoción.";
    }
}

// Obtener promociones desde la base de datos
$stmt_promociones = $promocion->leer();
$promociones = $stmt_promociones->fetchAll(PDO::FETCH_ASSOC);

// Obtener estadísticas de cotizaciones
$stmt_estadisticas = $cotizacion->obtenerEstadisticas();
$estadisticas = $stmt_estadisticas->fetchAll(PDO::FETCH_ASSOC);

// Obtener suscriptores del newsletter
$stmt_suscriptores = $newsletter->obtenerSuscriptoresActivos();
$suscriptores = $stmt_suscriptores->fetchAll(PDO::FETCH_ASSOC);

$usuario_actual = obtenerUsuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - ConectaPlus</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="bi bi-phone"></i> ConectaPlus
            </a>
            
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin.php">Admin</a>
                    </li>
                </ul>
            </div>
            
            <div class="navbar-nav">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i><?php echo $usuario_actual['nombre']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><span class="dropdown-item-text">
                            <small class="text-muted">Rol: <?php echo ucfirst($usuario_actual['rol']); ?></small>
                        </span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- El resto del contenido de admin.php permanece igual -->
    <div class="container my-5">
        <!-- ... contenido existente de admin.php ... -->
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>