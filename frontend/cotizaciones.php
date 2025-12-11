<?php
include_once 'auth/session.php';
verificarAcceso('usuario');
$usuario_actual = obtenerUsuario();
$apiBase = 'http://127.0.0.1:8000/api';
$adminToken = getenv('ADMIN_API_TOKEN') ?: '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizaciones - ConectaPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="bi bi-phone"></i> ConectaPlus
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php"><i class="bi bi-arrow-left"></i> Volver al dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary mb-0">Cotizaciones</h2>
                    <small class="text-muted">Lista y filtra las cotizaciones registradas</small>
                </div>
                <a href="comprar.php" class="btn btn-primary">
                    <i class="bi bi-bag-plus me-1"></i> Crear compra
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form id="filtros" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="filtroEmail" value="<?php echo htmlspecialchars($usuario_actual['email']); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo servicio</label>
                        <select class="form-select" id="filtroTipo">
                            <option value="">Todos</option>
                            <option value="prepago">Prepago</option>
                            <option value="pospago">Pospago</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Desde</label>
                        <input type="date" class="form-control" id="filtroDesde">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="filtroHasta">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Limite</label>
                        <input type="number" class="form-control" id="filtroLimit" value="50" min="1" max="100">
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-primary w-100" id="btnFiltrar">
                            <i class="bi bi-funnel me-1"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Datos</th>
                                <th>Minutos</th>
                                <th>SMS</th>
                                <th>Precio</th>
                                <th>Plan</th>
                                <th>Email</th>
                                <th>Telefono</th>
                                <th>Fecha</th>
                                <?php if (esAdministrador()): ?>
                                <th>Acciones</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="tablaCotizaciones">
                            <tr><td colspan="<?php echo esAdministrador() ? '11' : '10'; ?>" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE = "<?php echo $apiBase; ?>";
        const ADMIN_TOKEN = "<?php echo $adminToken; ?>";
        const esAdmin = <?php echo esAdministrador() ? 'true' : 'false'; ?>;

        const tabla = document.getElementById('tablaCotizaciones');
        document.getElementById('btnFiltrar').addEventListener('click', cargarCotizaciones);

        async function cargarCotizaciones() {
            tabla.innerHTML = `<tr><td colspan="${esAdmin ? 11 : 10}" class="text-center text-muted">Cargando...</td></tr>`;
            const params = new URLSearchParams();
            const email = document.getElementById('filtroEmail').value.trim();
            const tipo = document.getElementById('filtroTipo').value;
            const desde = document.getElementById('filtroDesde').value;
            const hasta = document.getElementById('filtroHasta').value;
            const limit = document.getElementById('filtroLimit').value || 50;

            if (email) params.append('email', email);
            if (tipo) params.append('tipo_servicio', tipo);
            if (desde) params.append('fecha_desde', desde);
            if (hasta) params.append('fecha_hasta', hasta);
            params.append('limit', limit);

            try {
                const res = await fetch(`${API_BASE}/cotizaciones?${params.toString()}`);
                if (!res.ok) throw new Error();
                const data = await res.json();
                if (!data.length) {
                    tabla.innerHTML = `<tr><td colspan="${esAdmin ? 11 : 10}" class="text-center text-muted">Sin resultados</td></tr>`;
                    return;
                }
                tabla.innerHTML = data.map(c => renderFila(c)).join('');
            } catch (e) {
                tabla.innerHTML = `<tr><td colspan="${esAdmin ? 11 : 10}" class="text-center text-danger">Error al cargar</td></tr>`;
            }
        }

        function renderFila(c) {
            const fecha = c.created_at ? new Date(c.created_at).toLocaleString() : '';
            const cols = [
                `<td>${c.id}</td>`,
                `<td>${c.tipo_servicio || ''}</td>`,
                `<td>${c.datos_gb ?? ''}</td>`,
                `<td>${c.minutos ?? ''}</td>`,
                `<td>${c.sms ?? ''}</td>`,
                `<td>Bs ${parseFloat(c.precio_final || 0).toFixed(2)}</td>`,
                `<td>${c.plan_recomendado || ''}</td>`,
                `<td>${c.email || ''}</td>`,
                `<td>${c.telefono || ''}</td>`,
                `<td>${fecha}</td>`
            ];
            if (esAdmin) {
                cols.push(`<td class="text-nowrap">
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarCotizacion(${c.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>`);
            }
            return `<tr>${cols.join('')}</tr>`;
        }

        async function eliminarCotizacion(id) {
            if (!ADMIN_TOKEN) {
                alert('Configura ADMIN_API_TOKEN para eliminar.');
                return;
            }
            if (!confirm('Eliminar esta cotizacion?')) return;
            try {
                const res = await fetch(`${API_BASE}/admin/cotizaciones/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-Admin-Token': ADMIN_TOKEN }
                });
                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    alert(err.message || 'No se pudo eliminar.');
                    return;
                }
                cargarCotizaciones();
            } catch (e) {
                alert('Error de conexion al eliminar.');
            }
        }

        document.addEventListener('DOMContentLoaded', cargarCotizaciones);
    </script>
</body>
</html>
