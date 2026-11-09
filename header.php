<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

$page_title = $page_title ?? 'KRONO';
$flash = get_flash();

// Verifica si el usuario está autenticado
if (!is_logged_in()) {
    redirect('login.php');
}

$current_user = get_user();
$user_data = get_user_full_data();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title) ?> | KRONO 2026</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="styles.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Barra de Navegación Principal -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <!-- Logotipo y Nombre en la parte superior izquierda -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <img src="logo.png" onerror="this.style.display='none'" width="38" height="38" class="rounded-circle bg-white p-1 object-fit-contain" alt="Logo KRONO">
                <span class="fw-bold tracking-wide">KRONO</span>
            </a>
            
            <!-- Botón responsive para móviles -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menú de navegación alineado a la derecha -->
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <?php if (is_profesor()): ?>
                        <!-- Menú para Profesores -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php"><i class="fa-solid fa-chart-pie me-1"></i>Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-chart-line me-1"></i>Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="alumnos.php"><i class="fa-solid fa-users me-1"></i>Alumnos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cursos.php"><i class="fa-solid fa-book-open me-1"></i>Cursos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profesores.php"><i class="fa-solid fa-chalkboard-user me-1"></i>Profesores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="asistencias.php"><i class="fa-solid fa-clipboard-check me-1"></i>Asistencias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cargar_asistencia_curso.php"><i class="fa-solid fa-list-check me-1"></i>Cargar por Curso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="justificativos.php"><i class="fa-solid fa-file-circle-check me-1"></i>Justificativos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="exportar.php"><i class="fa-solid fa-download me-1"></i>Exportar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="consultas.php"><i class="fa-solid fa-filter me-1"></i>Consultas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reportes.php"><i class="fa-solid fa-chart-column me-1"></i>Reportes</a>
                        </li>
                    <?php elseif (is_alumno()): ?>
                        <!-- Menú para Alumnos -->
                        <li class="nav-item">
                            <a class="nav-link" href="index.php"><i class="fa-solid fa-home me-1"></i>Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="mis-asistencias.php"><i class="fa-solid fa-calendar-check me-1"></i>Mis Asistencias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="mis-justificativos.php"><i class="fa-solid fa-file-circle-check me-1"></i>Mis Justificativos</a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Menú de Usuario (Igual para ambos) -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-circle me-1"></i><?= e($current_user['nombre']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="perfil.php"><i class="fa-solid fa-id-card me-2"></i>Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <main class="container py-4">
        <?php if (!empty($db_error)): ?>
            <div class="alert alert-warning shadow-sm">
                <i class="fa-solid fa-database me-2"></i><?= e($db_error) ?>
            </div>
        <?php endif; ?>

        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-solid fa-circle-info me-2"></i><?= e($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>