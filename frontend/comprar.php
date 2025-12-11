<?php
include_once 'auth/session.php';
verificarAcceso('usuario');
$usuario_actual = obtenerUsuario();
$apiBase = 'http://127.0.0.1:8000/api';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar plan - ConectaPlus</title>
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
        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Nueva compra</h4>
                        <p class="text-muted">Selecciona un plan y completa tus datos de contacto.</p>
                        <div id="alert" class="alert d-none" role="alert"></div>
                        <form id="compraForm">
                            <div class="mb-3">
                                <label for="plan" class="form-label">Plan</label>
                                <select class="form-select" id="plan" name="plan" required>
                                    <option value="">Cargando planes...</option>
                                </select>
                                <div class="form-text" id="planDescripcion"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Monto</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="monto" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Moneda</label>
                                    <select class="form-select" id="moneda">
                                        <option value="USD" selected>USD</option>
                                        <option value="Bs">BS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email de contacto</label>
                                <input type="email" class="form-control" id="email" required value="<?php echo htmlspecialchars($usuario_actual['email']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telefono</label>
                                <input type="text" class="form-control" id="telefono" placeholder="Opcional">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notas</label>
                                <textarea class="form-control" id="notas" rows="2" placeholder="Alguna aclaracion extra"></textarea>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" id="usaBlockchain">
                                <label class="form-check-label" for="usaBlockchain">
                                    Incluir datos de transaccion blockchain
                                </label>
                            </div>
                            <div id="blockchainFields" class="border rounded p-3 mb-3 d-none">
                                <div class="mb-3">
                                    <label class="form-label">Tx Hash</label>
                                    <input type="text" class="form-control" id="tx_hash" placeholder="0x...">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Red</label>
                                        <input type="text" class="form-control" id="red" placeholder="ethereum / polygon">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Proveedor</label>
                                        <input type="text" class="form-control" id="proveedor" placeholder="alchemy / infura">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Wallet origen</label>
                                        <input type="text" class="form-control" id="wallet_origen" placeholder="0x...">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Wallet destino</label>
                                        <input type="text" class="form-control" id="wallet_destino" placeholder="0x...">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-bag-plus me-1"></i> Confirmar compra
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Resumen del plan</h5>
                        <p class="text-muted">Selecciona un plan para ver detalles.</p>
                        <ul class="list-group list-group-flush" id="planResumen">
                            <li class="list-group-item text-muted text-center">Sin seleccionar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE = "<?php echo $apiBase; ?>";
        const userEmail = "<?php echo $usuario_actual['email']; ?>";

        const planSelect = document.getElementById('plan');
        const planDescripcion = document.getElementById('planDescripcion');
        const planResumen = document.getElementById('planResumen');
        const montoInput = document.getElementById('monto');
        const monedaInput = document.getElementById('moneda');
        const usaBlockchain = document.getElementById('usaBlockchain');
        const bcFields = document.getElementById('blockchainFields');
        const alertBox = document.getElementById('alert');

        usaBlockchain.addEventListener('change', () => {
            bcFields.classList.toggle('d-none', !usaBlockchain.checked);
        });

        async function cargarPlanes() {
            try {
                const res = await fetch(`${API_BASE}/planes`);
                if (!res.ok) throw new Error();
                const planes = await res.json();
                if (!planes.length) {
                    planSelect.innerHTML = '<option value="">No hay planes disponibles</option>';
                    return;
                }
                planSelect.innerHTML = '<option value="">Selecciona un plan</option>';
                planes.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.dataset.precio = p.precio_mensual;
                    opt.dataset.datos = p.datos_gb;
                    opt.dataset.minutos = p.minutos;
                    opt.dataset.sms = p.sms;
                    opt.textContent = `${p.nombre} - ${p.tipo_servicio} (Bs ${parseFloat(p.precio_mensual).toFixed(2)})`;
                    planSelect.appendChild(opt);
                });
            } catch (e) {
                planSelect.innerHTML = '<option value="">Error al cargar planes</option>';
            }
        }

        planSelect.addEventListener('change', () => {
            const opt = planSelect.selectedOptions[0];
            if (!opt || !opt.value) {
                planDescripcion.textContent = '';
                planResumen.innerHTML = '<li class="list-group-item text-muted text-center">Sin seleccionar</li>';
                return;
            }
            const precio = opt.dataset.precio || 0;
            montoInput.value = precio;
            const datos = opt.dataset.datos || '-';
            const minutos = opt.dataset.minutos || '-';
            const sms = opt.dataset.sms || '-';
            planDescripcion.textContent = `Precio sugerido: Bs ${parseFloat(precio).toFixed(2)}`;
            planResumen.innerHTML = `
                <li class="list-group-item"><strong>Plan:</strong> ${opt.textContent}</li>
                <li class="list-group-item"><strong>Datos:</strong> ${datos} GB</li>
                <li class="list-group-item"><strong>Minutos:</strong> ${minutos}</li>
                <li class="list-group-item"><strong>SMS:</strong> ${sms}</li>
                <li class="list-group-item"><strong>Precio:</strong> Bs ${parseFloat(precio).toFixed(2)}</li>
            `;
        });

        function showAlert(msg, type='danger') {
            alertBox.textContent = msg;
            alertBox.className = `alert alert-${type}`;
            alertBox.classList.remove('d-none');
        }

        document.getElementById('compraForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            alertBox.classList.add('d-none');
            const planId = planSelect.value;
            if (!planId) { showAlert('Selecciona un plan'); return; }

            const payload = {
                plan_id: parseInt(planId, 10),
                email_contacto: document.getElementById('email').value.trim(),
                telefono: document.getElementById('telefono').value.trim() || null,
                monto: parseFloat(montoInput.value || 0),
                moneda: monedaInput.value.trim() || 'USD',
                notas: document.getElementById('notas').value.trim() || null
            };

            if (usaBlockchain.checked) {
                const txHash = document.getElementById('tx_hash').value.trim();
                if (!txHash) {
                    showAlert('Ingresa el Tx Hash para la transaccion blockchain.');
                    return;
                }
                payload.blockchain = {
                    tx_hash: txHash,
                    red: document.getElementById('red').value.trim() || null,
                    proveedor: document.getElementById('proveedor').value.trim() || null,
                    wallet_origen: document.getElementById('wallet_origen').value.trim() || null,
                    wallet_destino: document.getElementById('wallet_destino').value.trim() || null,
                    monto: payload.monto,
                    moneda: payload.moneda
                };
            }

            try {
                const res = await fetch(`${API_BASE}/compras`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (!res.ok) {
                    showAlert(data.message || 'No se pudo registrar la compra.');
                    return;
                }
                showAlert('Compra registrada exitosamente.', 'success');
                setTimeout(() => window.location.href = 'dashboard.php', 1200);
            } catch (err) {
                showAlert('Error de conexion con el servidor.');
            }
        });

        document.addEventListener('DOMContentLoaded', cargarPlanes);
    </script>
</body>
</html>
