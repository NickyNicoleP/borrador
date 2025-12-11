<?php
$apiBase = 'http://127.0.0.1:8000/api';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - ConectaPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .register-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #0d6efd 0%, #4dabf7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }
        .register-header {
            background: #0d6efd;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .register-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h2><i class="bi bi-phone"></i> ConectaPlus</h2>
                <p class="mb-0">Crea tu cuenta</p>
            </div>
            <div class="register-body">
                <div id="alert" class="alert d-none" role="alert"></div>
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Tu nombre">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="tu@email.com">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contrasena</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Minimo 6 caracteres">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirm" class="form-label">Repetir contrasena</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required placeholder="Repite tu contrasena">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-person-plus-fill me-2"></i>Crear cuenta
                    </button>
                </form>
                <div class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none">Ya tienes cuenta? Inicia sesion</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = "<?php echo $apiBase; ?>";
        const form = document.getElementById('registerForm');
        const alertBox = document.getElementById('alert');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            alertBox.classList.add('d-none');

            const nombre = form.nombre.value.trim();
            const email = form.email.value.trim();
            const password = form.password.value;
            const passwordConfirm = form.password_confirm.value;

            if (password !== passwordConfirm) {
                showAlert('Las contrasenas no coinciden.', 'danger');
                return;
            }

            try {
                const res = await fetch(`${API_BASE}/register`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ nombre, email, password })
                });

                const data = await res.json();

                if (!res.ok) {
                    const msg = data.message || 'No se pudo registrar. Revisa los datos.';
                    showAlert(msg, 'danger');
                    return;
                }

                showAlert('Registro exitoso. Redirigiendo al login...', 'success');
                setTimeout(() => window.location.href = 'login.php', 1200);
            } catch (err) {
                showAlert('Error de conexion con el servidor.', 'danger');
            }
        });

        function showAlert(message, type) {
            alertBox.textContent = message;
            alertBox.className = `alert alert-${type}`;
            alertBox.classList.remove('d-none');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
