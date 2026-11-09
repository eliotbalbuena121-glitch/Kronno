<?php
$page_title = 'Dashboard';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/lib/charts.php';

require_profesor();

// Obtener datos para gráficos
$asistencia_fechas = get_asistencia_por_fecha();
$distribucion = get_distribucion_asistencias();
$alumnos_criticos = get_alumnos_asistencia_critica();
$estadisticas = get_estadisticas_generales();
$asistencia_cursos = get_asistencia_por_curso();

// Preparar datos para Chart.js (asistencias por fecha)
$fechas_labels = [];
$presentes_data = [];
$ausentes_data = [];
$tardanzas_data = [];

foreach ($asistencia_fechas as $registro) {
    $fechas_labels[] = $registro['fecha'];
    $presentes_data[] = $registro['presentes'];
    $ausentes_data[] = $registro['ausentes'];
    $tardanzas_data[] = $registro['tardanzas'];
}

// Preparar datos para gráfico de pastel (distribución)
$distribucion_labels = [];
$distribucion_data = [];
$distribucion_colores = ['#28a745', '#dc3545', '#ffc107'];

foreach ($distribucion as $item) {
    $distribucion_labels[] = $item['estado'];
    $distribucion_data[] = $item['total'];
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-6 fw-bold">
            <i class="fa-solid fa-chart-line me-2"></i>Dashboard
        </h1>
        <p class="text-muted">Visualización general del sistema de asistencias</p>
    </div>
</div>

<!-- Tarjetas de Estadísticas -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small"><i class="fa-solid fa-users me-1"></i>Total Alumnos</p>
                        <h3 class="fw-bold"><?= $estadisticas['total_alumnos'] ?? 0 ?></h3>
                    </div>
                    <span class="text-primary fs-2"><i class="fa-solid fa-graduation-cap"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small"><i class="fa-solid fa-book-open me-1"></i>Total Cursos</p>
                        <h3 class="fw-bold"><?= $estadisticas['total_cursos'] ?? 0 ?></h3>
                    </div>
                    <span class="text-info fs-2"><i class="fa-solid fa-book"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small"><i class="fa-solid fa-chalkboard-user me-1"></i>Profesores</p>
                        <h3 class="fw-bold"><?= $estadisticas['total_profesores'] ?? 0 ?></h3>
                    </div>
                    <span class="text-warning fs-2"><i class="fa-solid fa-person-chalkboard"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1 small"><i class="fa-solid fa-clipboard-check me-1"></i>Hoy</p>
                        <h3 class="fw-bold"><?= $estadisticas['asistencias_hoy'] ?? 0 ?></h3>
                    </div>
                    <span class="text-success fs-2"><i class="fa-solid fa-calendar-check"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row mb-4">
    <!-- Gráfico de Línea: Asistencias por Fecha -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0"><i class="fa-solid fa-chart-line me-2"></i>Asistencias - Últimos 30 Días</h5>
            </div>
            <div class="card-body">
                <canvas id="asistenciaChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico de Pastel: Distribución -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0"><i class="fa-solid fa-chart-pie me-2"></i>Distribución de Estados</h5>
            </div>
            <div class="card-body d-flex justify-content-center">
                <canvas id="distribucionChart" class="w-100" style="max-width: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Cursos -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0"><i class="fa-solid fa-book-open me-2"></i>Asistencia por Curso</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Curso</th>
                                <th class="text-center">Alumnos</th>
                                <th class="text-center">Presentes</th>
                                <th class="text-center">Ausentes</th>
                                <th class="text-center">Tardanzas</th>
                                <th class="text-center">% Asistencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($asistencia_cursos as $curso): ?>
                                <tr>
                                    <td class="fw-500"><?= e($curso['nombre']) ?></td>
                                    <td class="text-center"><?= $curso['total_alumnos'] ?? 0 ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= $curso['presentes'] ?? 0 ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger"><?= $curso['ausentes'] ?? 0 ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark"><?= $curso['tardanzas'] ?? 0 ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: <?= $curso['porcentaje_asistencia'] ?? 0 ?>%">
                                                <?= round($curso['porcentaje_asistencia'] ?? 0, 1) ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alumnos con Asistencia Crítica -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-danger bg-opacity-10 border-bottom border-danger">
                <h5 class="mb-0 text-danger"><i class="fa-solid fa-exclamation-triangle me-2"></i>Alumnos con Asistencia Crítica (< 80%)</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($alumnos_criticos)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Alumno</th>
                                    <th>Documento</th>
                                    <th>Curso</th>
                                    <th class="text-center">Registros</th>
                                    <th class="text-center">Presentes</th>
                                    <th class="text-center">% Asistencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alumnos_criticos as $alumno): ?>
                                    <tr class="table-danger">
                                        <td class="fw-500"><?= e($alumno['nombre']) ?></td>
                                        <td><?= e($alumno['documento']) ?></td>
                                        <td><?= e($alumno['curso']) ?></td>
                                        <td class="text-center"><?= $alumno['total_registros'] ?></td>
                                        <td class="text-center"><?= $alumno['presentes'] ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-danger"><?= round($alumno['porcentaje_asistencia'], 1) ?>%</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success mb-0">
                        <i class="fa-solid fa-circle-check me-2"></i>¡Excelente! No hay alumnos con asistencia crítica.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
    // Gráfico de Línea: Asistencias por Fecha
    const asistenciaCtx = document.getElementById('asistenciaChart').getContext('2d');
    new Chart(asistenciaCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($fechas_labels) ?>,
            datasets: [
                {
                    label: 'Presentes',
                    data: <?= json_encode($presentes_data) ?>,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#28a745'
                },
                {
                    label: 'Ausentes',
                    data: <?= json_encode($ausentes_data) ?>,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#dc3545'
                },
                {
                    label: 'Tardanzas',
                    data: <?= json_encode($tardanzas_data) ?>,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffc107'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad'
                    }
                }
            }
        }
    });

    // Gráfico de Pastel: Distribución
    const distribucionCtx = document.getElementById('distribucionChart').getContext('2d');
    new Chart(distribucionCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($distribucion_labels) ?>,
            datasets: [{
                data: <?= json_encode($distribucion_data) ?>,
                backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
