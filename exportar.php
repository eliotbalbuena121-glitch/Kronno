<?php
$page_title = 'Exportar Reportes';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/lib/export.php';

require_profesor();

// Obtener lista de cursos para el filtro
$cursos = [];
try {
    $stmt = $pdo->query("SELECT id, nombre FROM cursos WHERE activo = 1 ORDER BY nombre ASC");
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    set_flash('error', 'Error al cargar cursos: ' . $e->getMessage());
}

// Procesar formulario de exportación
$datos_preview = [];
$tipo_export = $_POST['tipo_export'] ?? 'asistencias';
$curso_id = $_POST['curso_id'] ?? null;
$fecha_desde = $_POST['fecha_desde'] ?? null;
$fecha_hasta = $_POST['fecha_hasta'] ?? null;
$estado_justificativo = $_POST['estado'] ?? null;
$descargar = $_POST['descargar'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$descargar) {
    // Generar preview
    switch ($tipo_export) {
        case 'asistencias':
            $datos_preview = export_asistencias_csv($curso_id, $fecha_desde, $fecha_hasta);
            break;
        case 'justificativos':
            $datos_preview = export_justificativos_csv($curso_id, $estado_justificativo, $fecha_desde, $fecha_hasta);
            break;
        case 'alumnos':
            $datos_preview = export_alumnos_csv($curso_id);
            break;
    }
}

// Procesar descarga de CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $descargar) {
    switch ($tipo_export) {
        case 'asistencias':
            $datos = export_asistencias_csv($curso_id, $fecha_desde, $fecha_hasta);
            $nombre = 'asistencias_' . date('Y-m-d');
            break;
        case 'justificativos':
            $datos = export_justificativos_csv($curso_id, $estado_justificativo, $fecha_desde, $fecha_hasta);
            $nombre = 'justificativos_' . date('Y-m-d');
            break;
        case 'alumnos':
            $datos = export_alumnos_csv($curso_id);
            $nombre = 'alumnos_' . date('Y-m-d');
            break;
    }
    
    if (!empty($datos)) {
        download_csv($datos, $nombre);
        exit;
    } else {
        flash('No hay datos para descargar con los filtros seleccionados', 'warning');
    }
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-6 fw-bold">
            <i class="fa-solid fa-download me-2"></i>Exportar Reportes
        </h1>
        <p class="text-muted">Descarga reportes en formato CSV (Excel)</p>
    </div>
</div>

<div class="row">
    <!-- Formulario de Exportación -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-lg-top">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">Opciones de Exportación</h5>
            </div>
            <div class="card-body">
                <form method="POST" id="exportForm">
                    <!-- Tipo de Exportación -->
                    <div class="mb-3">
                        <label class="form-label fw-500">Tipo de Reporte</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="tipo_export" id="tipo1" value="asistencias" <?= $tipo_export === 'asistencias' ? 'checked' : '' ?> onchange="updateFilters()">
                            <label class="btn btn-outline-primary" for="tipo1">
                                <i class="fa-solid fa-clipboard-check me-1"></i>Asistencias
                            </label>

                            <input type="radio" class="btn-check" name="tipo_export" id="tipo2" value="justificativos" <?= $tipo_export === 'justificativos' ? 'checked' : '' ?> onchange="updateFilters()">
                            <label class="btn btn-outline-primary" for="tipo2">
                                <i class="fa-solid fa-file-check me-1"></i>Justificativos
                            </label>

                            <input type="radio" class="btn-check" name="tipo_export" id="tipo3" value="alumnos" <?= $tipo_export === 'alumnos' ? 'checked' : '' ?> onchange="updateFilters()">
                            <label class="btn btn-outline-primary" for="tipo3">
                                <i class="fa-solid fa-users me-1"></i>Alumnos
                            </label>
                        </div>
                    </div>

                    <hr>

                    <!-- Filtro: Curso -->
                    <div class="mb-3">
                        <label for="curso_id" class="form-label fw-500">Curso</label>
                        <select class="form-select" id="curso_id" name="curso_id">
                            <option value="">Todos los cursos</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?= $curso['id'] ?>" <?= $curso_id == $curso['id'] ? 'selected' : '' ?>>
                                    <?= e($curso['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filtro: Fechas (para Asistencias y Justificativos) -->
                    <div id="fechasFilter" class="mb-3" style="display: <?= in_array($tipo_export, ['asistencias', 'justificativos']) ? 'block' : 'none' ?>;">
                        <label for="fecha_desde" class="form-label fw-500">Fecha Desde</label>
                        <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="<?= $fecha_desde ?>">
                        
                        <label for="fecha_hasta" class="form-label fw-500 mt-2">Fecha Hasta</label>
                        <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="<?= $fecha_hasta ?>">
                    </div>

                    <!-- Filtro: Estado (para Justificativos) -->
                    <div id="estadoFilter" class="mb-3" style="display: <?= $tipo_export === 'justificativos' ? 'block' : 'none' ?>;">
                        <label for="estado" class="form-label fw-500">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="">Todos los estados</option>
                            <option value="Pendiente" <?= $estado_justificativo === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="Aprobado" <?= $estado_justificativo === 'Aprobado' ? 'selected' : '' ?>>Aprobado</option>
                            <option value="Rechazado" <?= $estado_justificativo === 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
                        </select>
                    </div>

                    <hr>

                    <!-- Botones de Acción -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" name="descargar" value="0">
                            <i class="fa-solid fa-eye me-1"></i>Vista Previa
                        </button>
                        <button type="submit" class="btn btn-success" name="descargar" value="1">
                            <i class="fa-solid fa-download me-1"></i>Descargar CSV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Área de Preview -->
    <div class="col-lg-8">
        <?php if (!empty($datos_preview)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-table me-2"></i>
                        Vista Previa
                        <span class="badge bg-primary"><?= count($datos_preview) ?> registros</span>
                    </h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    <?php
                    $html_preview = generate_reporte_html($tipo_export, array_slice($datos_preview, 0, 20), 'Vista Previa');
                    echo $html_preview;
                    ?>
                    <?php if (count($datos_preview) > 20): ?>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            Mostrando 20 de <?= count($datos_preview) ?> registros. Descarga el archivo para ver todos.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="fa-solid fa-chart-simple text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Selecciona opciones y haz clic en "Vista Previa" para ver los datos</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

<script>
function updateFilters() {
    const tipoExport = document.querySelector('input[name="tipo_export"]:checked').value;
    const fechasFilter = document.getElementById('fechasFilter');
    const estadoFilter = document.getElementById('estadoFilter');
    
    // Mostrar/ocultar filtros según tipo
    fechasFilter.style.display = ['asistencias', 'justificativos'].includes(tipoExport) ? 'block' : 'none';
    estadoFilter.style.display = tipoExport === 'justificativos' ? 'block' : 'none';
}

// Inicializar filtros en carga
document.addEventListener('DOMContentLoaded', updateFilters);
</script>
