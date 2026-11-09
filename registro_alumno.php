<?php
$page_title = 'Registro de Alumnos';
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// Si ya está autenticado como alumno, redirige al inicio
if (is_logged_in() && is_alumno()) {
    redirect('index.php');
}

$error = null;
$success = null;

// Obtener cursos disponibles
$cursos = [];
try {
    $stmt = $pdo->query('SELECT id, nombre FROM cursos WHERE activo = 1 ORDER BY nombre');
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Error al cargar cursos';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $curso_id = (int)($_POST['curso_id'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password_confirm = trim($_POST['password_confirm'] ?? '');

    // Validaciones
    if (empty($nombre) || empty($documento) || empty($curso_id) || empty($email) || empty($password)) {
        $error = 'Todos los campos obligatorios deben estar completos';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email inválido';
    } else {
        try {
            // Verificar que el documento no exista
            $stmt = $pdo->prepare('SELECT id FROM alumnos WHERE documento = ?');
            $stmt->execute([$documento]);
            if ($stmt->fetch()) {
                $error = 'Este documento ya está registrado';
            } else {
                // Verificar que el email no exista
                $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'Este email ya está en uso';
                } else {
                    // Insertar en tabla alumnos
                    $stmt = $pdo->prepare('INSERT INTO alumnos(nombre, documento, curso_id, email, telefono, estado) VALUES (?, ?, ?, ?, ?, ?)');
                    $stmt->execute([$nombre, $documento, $curso_id, $email, $telefono, 'Activo']);
                    $alumno_id = $pdo->lastInsertId();

                    // Insertar en tabla usuarios
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $pdo->prepare('INSERT INTO usuarios(email, password, nombre, rol, alumno_id, activo) VALUES (?, ?, ?, ?, ?, ?)');
                    $stmt->execute([$email, $password_hash, $nombre, 'alumno', $alumno_id, 1]);

                    $success = '✅ Alumno registrado exitosamente. Ya puede iniciar sesión.';
                    
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
    <title>Registro de Alumno | KRONO 2026</title>
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
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fa-solid fa-graduation-cap me-2"></i>Registro de Alumno</h4>
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
                                <label for="documento" class="form-label fw-500">Documento *</label>
                                <input type="text" class="form-control" id="documento" name="documento" 
                                       placeholder="Ej: A-1234567"
                                       value="<?= e($_POST['documento'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="curso_id" class="form-label fw-500">Curso y Bachiller *</label>
                                <select class="form-select" id="curso_id" name="curso_id" required>
                                    <option value="">Seleccionar curso...</option>
                                    <?php foreach ($cursos as $curso): ?>
                                        <option value="<?= $curso['id'] ?>" 
                                                <?= ($_POST['curso_id'] ?? '') == $curso['id'] ? 'selected' : '' ?>>
                                            <?= e($curso['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Ej: 1ro BTI, 2do BATAN, 3ro BC</small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-500">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= e($_POST['email'] ?? '') ?>" required>
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
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fa-solid fa-user-plus me-2"></i>Registrarse como Alumno
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
