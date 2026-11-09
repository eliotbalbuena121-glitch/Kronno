<?php 
$page_title = 'Mi Perfil'; 
require 'header.php'; 
require_once 'auth.php';

$user_data = get_user_full_data();
$current_user = get_user();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold">Mi Perfil</h1>
        <p class="text-secondary">Información personal de tu cuenta</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-user fs-3"></i>
                </div>
                <div class="ms-3">
                    <h2 class="h4 mb-0"><?= e($current_user['nombre']) ?></h2>
                    <p class="text-muted mb-0">
                        <span class="badge" style="background: <?= is_profesor() ? '#ffc107' : '#0d6efd' ?>">
                            <?= is_profesor() ? 'Profesor' : 'Alumno' ?>
                        </span>
                    </p>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Email</p>
                    <p class="fw-semibold"><?= e($current_user['email']) ?></p>
                </div>
                <?php if ($user_data): ?>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">
                            <?= is_profesor() ? 'Especialidad' : 'Documento' ?>
                        </p>
                        <p class="fw-semibold">
                            <?= is_profesor() ? e($user_data['especialidad'] ?? 'N/A') : e($user_data['documento'] ?? 'N/A') ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (is_profesor() && $user_data): ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Teléfono</p>
                        <p class="fw-semibold"><?= e($user_data['telefono'] ?? 'No registrado') ?></p>
                    </div>
                </div>
            <?php elseif (is_alumno() && $user_data): ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Curso</p>
                        <p class="fw-semibold"><?= e($user_data['curso_nombre'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Teléfono</p>
                        <p class="fw-semibold"><?= e($user_data['telefono'] ?? 'No registrado') ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <hr>

            <div class="alert alert-info d-flex align-items-start">
                <i class="fa-solid fa-circle-info me-2 mt-1"></i>
                <div>
                    <strong>Información de tu cuenta</strong>
                    <p class="mb-0 small">Tu cuenta fue creada el <?= date('d/m/Y', strtotime($current_user['fecha_creacion'])) ?>. Para cambiar tu contraseña o más información, contacta al administrador.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 p-4">
            <h5 class="fw-bold mb-3">Acciones Rápidas</h5>
            
            <?php if (is_profesor()): ?>
                <a href="asistencias.php" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fa-solid fa-clipboard-check me-2"></i>Registrar Asistencia
                </a>
                <a href="justificativos.php" class="btn btn-outline-success w-100 mb-2">
                    <i class="fa-solid fa-file-circle-check me-2"></i>Ver Justificativos
                </a>
                <a href="consultas.php" class="btn btn-outline-info w-100 mb-2">
                    <i class="fa-solid fa-filter me-2"></i>Consultas
                </a>
            <?php elseif (is_alumno()): ?>
                <a href="mis-asistencias.php" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fa-solid fa-calendar-check me-2"></i>Ver Mis Asistencias
                </a>
                <a href="mis-justificativos.php" class="btn btn-outline-success w-100 mb-2">
                    <i class="fa-solid fa-file-circle-check me-2"></i>Mis Justificativos
                </a>
            <?php endif; ?>
            
            <a href="logout.php" class="btn btn-outline-danger w-100 mt-3">
                <i class="fa-solid fa-sign-out-alt me-2"></i>Cerrar Sesión
            </a>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>
