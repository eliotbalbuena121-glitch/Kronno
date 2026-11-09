<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// Si ya está autenticado, redirige al inicio
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    $result = login_user($email, $password);
    
    if ($result['success']) {
        redirect('index.php');
    } else {
        $error = $result['message'];
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | KRONO 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .login-container {
            max-width: 450px;
            width: 100%;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            color: #666;
            font-size: 0.95rem;
        }
        .form-control, .form-control:focus {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .alert-danger {
            border-radius: 8px;
            border: none;
            background-color: #fee;
            color: #c33;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        .register-section {
            background: #f0f4ff;
            border: 1px solid #d0deff;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            text-align: center;
        }
        .register-section p {
            color: #555;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .register-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .register-buttons a {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fa-solid fa-graduation-cap" style="color: #667eea;"></i>
                </div>
                <h1 class="login-title">KRONO 2026</h1>
                <p class="login-subtitle">Sistema de Control de Asistencia</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex align-items-center mb-3">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label" for="email">
                        <i class="fa-solid fa-envelope me-2"></i>Email
                    </label>
                    <input class="form-control" type="email" id="email" name="email" placeholder="tu.email@ejemplo.com" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password">
                        <i class="fa-solid fa-lock me-2"></i>Contraseña
                    </label>
                    <input class="form-control" type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button class="btn btn-login w-100 text-white mb-3" type="submit">
                    <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Iniciar Sesión
                </button>
            </form>

            <div class="register-section">
                <p><strong>¿No tienes cuenta?</strong></p>
                <p class="text-muted small mb-0">Regístrate como profesor o alumno para acceder al sistema</p>
                <div class="register-buttons">
                    <a href="registro_profesor.php" class="btn btn-outline-warning btn-sm fw-bold">
                        <i class="fa-solid fa-chalkboard-user me-1"></i>Profesor
                    </a>
                    <a href="registro_alumno.php" class="btn btn-outline-info btn-sm fw-bold">
                        <i class="fa-solid fa-graduation-cap me-1"></i>Alumno
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
