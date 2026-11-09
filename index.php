<?php 
$page_title = 'Panel principal'; 
require 'header.php'; 
require_once 'auth.php';

$stats = ['alumnos' => 0, 'cursos' => 0, 'profesores' => 0, 'asistencias' => 0]; 

if ($pdo) {
    try {
        $stats['alumnos'] = $pdo->query("SELECT COUNT(*) c FROM alumnos")->fetch()['c'];
        $stats['cursos'] = $pdo->query("SELECT COUNT(*) c FROM cursos")->fetch()['c'];
        $stats['profesores'] = $pdo->query("SELECT COUNT(*) c FROM profesores")->fetch()['c'];
        $stats['asistencias'] = $pdo->query("SELECT COUNT(*) c FROM asistencias")->fetch()['c'];
    } catch (PDOException $e) {
        // Silenciar errores de conteo si ocurren
    }
} 
?>

<?php if (is_profesor()): ?>
    <!-- PANEL PARA PROFESORES -->
    <section class="hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="text-uppercase small fw-bold text-primary">Sistema de Asistencia</span>
                <h1 class="display-6 fw-bold mt-2">KRONO 2026</h1>
                <p class="lead text-secondary mb-0">Control automatizado de asistencia y gestión académica.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a class="btn btn-primary btn-lg" href="asistencias.php"><i class="fa-solid fa-clipboard-check me-2"></i>Registrar Asistencias</a>
            </div>
        </div>
    </section>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Total Alumnos</small>
                <div class="fs-3 fw-bold text-primary"><?= e($stats['alumnos']) ?></div>
                <span class="text-success small">Registrados en BD</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Cursos</small>
                <div class="fs-3 fw-bold text-success"><?= e($stats['cursos']) ?></div>
                <span class="text-success small">Secciones habilitadas</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Profesores</small>
                <div class="fs-3 fw-bold text-warning"><?= e($stats['profesores']) ?></div>
                <span class="text-secondary small">Plantel docente</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Asistencias Totales</small>
                <div class="fs-3 fw-bold text-info"><?= e($stats['asistencias']) ?></div>
                <span class="text-info small">Registros guardados</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-12">
            <div class="card p-4 shadow-sm border-0">
                <h2 class="h5 mb-3">Accesos Directos a Módulos</h2>
                <div class="row g-3">
                    <div class="col-sm-3">
                        <a class="card bg-primary text-white p-3 text-decoration-none shadow-sm" href="alumnos.php">
                            <i class="fa-solid fa-users fs-3 mb-2"></i>
                            <h3 class="h6 mb-1">Alumnos</h3>
                            <span class="small opacity-75">Ver listado y matrícula</span>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a class="card bg-success text-white p-3 text-decoration-none shadow-sm" href="cursos.php">
                            <i class="fa-solid fa-school fs-3 mb-2"></i>
                            <h3 class="h6 mb-1">Cursos</h3>
                            <span class="small opacity-75">BTI, BATAN, BC</span>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a class="card bg-warning text-dark p-3 text-decoration-none shadow-sm" href="profesores.php">
                            <i class="fa-solid fa-chalkboard-user fs-3 mb-2"></i>
                            <h3 class="h6 mb-1">Profesores</h3>
                            <span class="small opacity-75">Plantel y materias</span>
                        </a>
                    </div>
                    <div class="col-sm-3">
                        <a class="card bg-dark text-white p-3 text-decoration-none shadow-sm" href="asistencias.php">
                            <i class="fa-solid fa-clipboard-check fs-3 mb-2"></i>
                            <h3 class="h6 mb-1">Asistencias</h3>
                            <span class="small opacity-75">Control diario</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php elseif (is_alumno()): ?>
    <!-- PANEL PARA ALUMNOS -->
    <section class="hero mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="text-uppercase small fw-bold text-success">Bienvenido al Portal del Estudiante</span>
                <h1 class="display-6 fw-bold mt-2">KRONO 2026</h1>
                <p class="lead text-secondary mb-0">Visualiza tu asistencia, justificativos y más.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a class="btn btn-success btn-lg" href="mis-asistencias.php"><i class="fa-solid fa-calendar-check me-2"></i>Ver Mis Asistencias</a>
            </div>
        </div>
    </section>

    <?php 
    $alumno_data = get_user_full_data();
    $asistencias_totales = 0;
    $asistencias_presentes = 0;
    $asistencias_ausentes = 0;
    
    if ($pdo && $alumno_data) {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM asistencias WHERE alumno_id = ?");
            $stmt->execute([$alumno_data['id']]);
            $asistencias_totales = $stmt->fetch()['total'];
            
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM asistencias WHERE alumno_id = ? AND estado = 'Presente'");
            $stmt->execute([$alumno_data['id']]);
            $asistencias_presentes = $stmt->fetch()['total'];
            
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM asistencias WHERE alumno_id = ? AND estado = 'Ausente'");
            $stmt->execute([$alumno_data['id']]);
            $asistencias_ausentes = $stmt->fetch()['total'];
        } catch (PDOException $e) {
            // Error silencioso
        }
    }
    ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Mi Curso</small>
                <div class="fs-5 fw-bold text-success"><?= e($alumno_data['curso_nombre'] ?? 'N/A') ?></div>
                <span class="text-success small">Sección actual</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Total Registros</small>
                <div class="fs-3 fw-bold text-primary"><?= e($asistencias_totales) ?></div>
                <span class="text-info small">Registros de asistencia</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Presentes</small>
                <div class="fs-3 fw-bold text-success"><?= e($asistencias_presentes) ?></div>
                <span class="text-success small">Días asistidos</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Ausentes</small>
                <div class="fs-3 fw-bold text-danger"><?= e($asistencias_ausentes) ?></div>
                <span class="text-danger small">Faltas registradas</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-12">
            <div class="card p-4 shadow-sm border-0">
                <h2 class="h5 mb-3">Mi Información</h2>
                <div class="row g-3">
                    <div class="col-sm-4">
                        <a class="card bg-info text-white p-3 text-decoration-none shadow-sm" href="mis-asistencias.php">
                            <i class="fa-solid fa-calendar-check fs-3 mb-2"></i>
                            <h3 class="h6 mb-1">Mis Asistencias</h3>
                            <span class="small opacity-75">Historial completo</span>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="card bg-warning text-dark p-3 text-decoration-none shadow-sm" href="mis-justificativos.php">
                            <i class="fa-solid fa-file-circle-check fs-3 mb-2"></i>
                            <h3 class="h6 mb-1">Mis Justificativos</h3>
                            <span class="small opacity-75">Solicitudes de ausencia</span>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="card bg-secondary text-white p-3 text-decoration-none shadow-sm" href="perfil.php">
                            <i class="fa-solid fa-id-card fs-3 mb-2"></i>
                            <h3 class="h6 mb-1">Mi Perfil</h3>
                            <span class="small opacity-75">Datos personales</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php require 'footer.php'; ?>