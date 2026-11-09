<?php
$page_title = 'Registro de Profesores';
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// Si ya está autenticado como profesor, redirige al inicio
if (is_logged_in() && is_profesor()) {
    redirect('index.php');
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $especialidad = trim($_POST['especialidad'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');

    // Validaciones
    if (empty($nombre) || empty($email) || empty($especialidad) || empty($password)) {
        $error = 'Todos los campos obligatorios deben estar completos';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido';
    } else {
        try {
            // Verificar que el email no exista en profesores
            $stmt = $pdo->prepare('SELECT id FROM profesores WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Este email ya está registrado como profesor';
            } else {
                // Verificar que el email no exista en usuarios
                $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'Este email ya está en uso';
                } else {
                    // Insertar en tabla profesores
                    $stmt = $pdo->prepare('INSERT INTO profesores(nombre, email, especialidad, telefono) VALUES (?, ?, ?, ?)');
                    $stmt->execute([$nombre, $email, $especialidad, $telefono]);
                    $profesor_id = $pdo->lastInsertId();

                    // Insertar en tabla usuarios
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare('INSERT INTO usuarios(email, password, nombre, rol, profesor_id, activo) VALUES (?, ?, ?, ?, ?, ?)');
                    $stmt->execute([$email, $password_hash, $nombre, 'profesor', $profesor_id, 1]);

                    $success = '✅ Profesor registrado exitosamente. Ya puede iniciar sesión.';
                    
                    // Limpiar formulario
                    $_POST = [];
                }
            }
        } catch (PDOException $e) {
            $error = 'Error al registrar: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Profesor | KRONO 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fa-solid fa-graduation-cap me-2"></i>KRONO 2026
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fa-solid fa-user-tie me-2"></i>Registro de Profesor</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fa-solid fa-check-circle me-2"></i><?= e($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fa-solid fa-exclamation-circle me-2"></i><?= e($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-500">Nombre Completo *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?= e($_POST['nombre'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-500">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= e($_POST['email'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="especialidad" class="form-label fw-500">Especialidad *</label>
                                <input type="text" class="form-control" id="especialidad" name="especialidad" 
                                       placeholder="Ej: Matemática, Informática, Lengua Castellana..."
                                       value="<?= e($_POST['especialidad'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label fw-500">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       placeholder="Ej: 0981 123 456"
                                       value="<?= e($_POST['telefono'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-500">Contraseña *</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       minlength="6" required>
                                <small class="text-muted">Mínimo 6 caracteres</small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirm" class="form-label fw-500">Confirmar Contraseña *</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" 
                                       minlength="6" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa-solid fa-user-plus me-2"></i>Registrarse como Profesor
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                ¿Ya tiene cuenta? <a href="login.php">Iniciar sesión</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
