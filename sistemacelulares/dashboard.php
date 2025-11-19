<?php
// Dashboard principal después del login
include_once 'auth/session.php';
verificarAcceso('usuario'); // Requiere al menos rol de usuario

$usuario_actual = obtenerUsuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ConectaPlus</title>
    
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
            
            <div class="navbar-nav ms-auto">
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

    <!-- Contenido principal -->
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-5 fw-bold text-primary">Dashboard</h1>
                <p class="lead">Bienvenido, <?php echo $usuario_actual['nombre']; ?></p>
            </div>
        </div>
        
        <!-- Tarjetas de acceso rápido -->
        <div class="row g-4">
            <?php if (esAdministrador()): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card admin-card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-megaphone display-4 text-primary mb-3"></i>
                        <h5 class="card-title">Gestión de Promociones</h5>
                        <p class="card-text">Crea, edita y gestiona las promociones activas.</p>
                        <a href="admin.php" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card admin-card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-graph-up display-4 text-success mb-3"></i>
                        <h5 class="card-title">Estadísticas</h5>
                        <p class="card-text">Visualiza reportes y métricas del sistema.</p>
                        <a href="estadisticas.php" class="btn btn-success">Ver Reportes</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="col-md-6 col-lg-4">
                <div class="card admin-card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-people display-4 text-info mb-3"></i>
                        <h5 class="card-title">Clientes Potenciales</h5>
                        <p class="card-text">Revisa las cotizaciones realizadas.</p>
                        <a href="cotizaciones.php" class="btn btn-info">Ver Cotizaciones</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card admin-card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-envelope display-4 text-warning mb-3"></i>
                        <h5 class="card-title">Newsletter</h5>
                        <p class="card-text">Gestiona suscriptores y campañas.</p>
                        <a href="newsletter.php" class="btn btn-warning">Gestionar</a>
                    </div>
                </div>
            </div>
            
            <?php if (esAdministrador()): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card admin-card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-person-gear display-4 text-secondary mb-3"></i>
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Administra usuarios y permisos.</p>
                        <a href="usuarios.php" class="btn btn-secondary">Gestionar</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Información rápida -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Información del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Usuario:</strong> <?php echo $usuario_actual['nombre']; ?></p>
                                <p><strong>Email:</strong> <?php echo $usuario_actual['email']; ?></p>
                                <p><strong>Rol:</strong> <span class="badge bg-<?php echo $usuario_actual['rol'] === 'admin' ? 'primary' : 'secondary'; ?>">
                                    <?php echo ucfirst($usuario_actual['rol']); ?>
                                </span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Último acceso:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
                                <p><strong>IP de conexión:</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>