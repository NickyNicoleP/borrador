<?php
// Dashboard principal despues del login
include_once 'auth/session.php';
verificarAcceso('usuario'); // Requiere al menos rol de usuario

$usuario_actual = obtenerUsuario();
$apiBase = 'http://127.0.0.1:8000/api';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ConectaPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Barra de navegacion -->
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
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text">
                            <small class="text-muted">Rol: <?php echo ucfirst($usuario_actual['rol']); ?></small>
                        </span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesion
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

        <!-- Resumen rapido -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h5 class="card-title mb-0">Mi Saldo</h5>
                                <small class="text-muted" id="saldo-actualizado"></small>
                            </div>
                            <i class="bi bi-wallet2 fs-3 text-primary"></i>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted small">Disponible</div>
                            <div class="h4 mb-0" id="saldo-disponible">-</div>
                        </div>
                        <div>
                            <div class="text-muted small">Pendiente</div>
                            <div class="fw-semibold text-warning" id="saldo-pendiente">-</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h5 class="card-title mb-0">Compras recientes</h5>
                                <small class="text-muted">Basado en tu email de contacto</small>
                            </div>
                            <i class="bi bi-receipt-cutoff fs-4 text-info"></i>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Estado</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody id="compras-recientes">
                                    <tr><td colspan="4" class="text-muted text-center">Cargando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <a href="comprar.php" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Nueva compra
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de acceso rapido -->
        <div class="row g-4">
            <?php if (esAdministrador()): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card admin-card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-megaphone display-4 text-primary mb-3"></i>
                        <h5 class="card-title">Gestion de Promociones</h5>
                        <p class="card-text">Crea, edita y gestiona las promociones activas.</p>
                        <a href="admin.php" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card admin-card h-100 text-center">
                    <div class="card-body p-4">
                        <i class="bi bi-graph-up display-4 text-success mb-3"></i>
                        <h5 class="card-title">Estadisticas</h5>
                        <p class="card-text">Visualiza reportes y metricas del sistema.</p>
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
                        <p class="card-text">Gestiona suscriptores y campanas.</p>
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

        <!-- Informacion rapida -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Informacion del Sistema</h5>
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
                                <p><strong>Ultimo acceso:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
                                <p><strong>IP de conexion:</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE = "<?php echo $apiBase; ?>";
        const userEmail = "<?php echo $usuario_actual['email']; ?>";

        async function cargarDashboard() {
            await Promise.all([
                cargarResumen(),
                cargarCompras()
            ]);
        }

        async function cargarResumen() {
            const saldoDisponible = document.getElementById('saldo-disponible');
            const saldoPendiente = document.getElementById('saldo-pendiente');
            const saldoActualizado = document.getElementById('saldo-actualizado');
            try {
                const res = await fetch(`${API_BASE}/dashboard?email=${encodeURIComponent(userEmail)}`);
                if (!res.ok) throw new Error('No se pudo obtener el resumen');
                const data = await res.json();
                saldoDisponible.textContent = `Bs ${Number(data.saldo?.disponible || 0).toFixed(2)}`;
                saldoPendiente.textContent = `Bs ${Number(data.saldo?.pendiente || 0).toFixed(2)}`;
                saldoActualizado.textContent = new Date().toLocaleString();
            } catch (e) {
                saldoDisponible.textContent = '-';
                saldoPendiente.textContent = '-';
                saldoActualizado.textContent = 'Error al cargar';
            }
        }

        async function cargarCompras() {
            const cuerpo = document.getElementById('compras-recientes');
            cuerpo.innerHTML = '<tr><td colspan="4" class="text-muted text-center">Cargando...</td></tr>';
            try {
                const res = await fetch(`${API_BASE}/compras?email=${encodeURIComponent(userEmail)}&limit=5`);
                if (!res.ok) throw new Error();
                const compras = await res.json();
                if (!compras.length) {
                    cuerpo.innerHTML = '<tr><td colspan="4" class="text-muted text-center">Sin compras registradas</td></tr>';
                    return;
                }
                cuerpo.innerHTML = compras.map(c => {
                    const fecha = c.created_at ? new Date(c.created_at).toLocaleString() : '';
                    return `<tr>
                        <td>${c.plan_id ?? '-'}</td>
                        <td><span class="badge bg-${estadoBadge(c.estado)}">${c.estado}</span></td>
                        <td>Bs ${Number(c.monto).toFixed(2)}</td>
                        <td>${fecha}</td>
                    </tr>`;
                }).join('');
            } catch (e) {
                cuerpo.innerHTML = '<tr><td colspan="4" class="text-danger text-center">Error al cargar compras</td></tr>';
            }
        }

        function estadoBadge(estado) {
            switch (estado) {
                case 'pagado': return 'success';
                case 'pendiente': return 'warning';
                case 'fallido': return 'danger';
                case 'reembolsado': return 'secondary';
                default: return 'info';
            }
        }

        document.addEventListener('DOMContentLoaded', cargarDashboard);
    </script>
</body>
</html>
