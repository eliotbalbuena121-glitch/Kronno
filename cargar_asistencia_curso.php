<?php 
$page_title = 'Cargar Asistencia por Curso'; 
require __DIR__ . '/header.php'; 
require_profesor();

$curso_id = $_GET['curso_id'] ?? '';
$fecha = $_GET['fecha'] ?? date('Y-m-d');
$alumnos = [];

// Obtener cursos
try {
    $cursos = $pdo->query('SELECT id, nombre, turno FROM cursos WHERE activo = 1 ORDER BY nombre')->fetchAll();
} catch (PDOException $e) {
    $cursos = [];
    $db_error = 'Error al cargar cursos';
}

// Si se seleccionó un curso, traemos sus alumnos
if ($pdo && !empty($curso_id)) {
    try {
        $stmt = $pdo->prepare('SELECT id, nombre, documento FROM alumnos WHERE curso_id = ? AND estado = "Activo" ORDER BY nombre');
        $stmt->execute([$curso_id]);
        $alumnos = $stmt->fetchAll();
    } catch (PDOException $e) {
        $alumnos = [];
    }
}

// Procesar el formulario cuando el profesor guarda la asistencia del curso
if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $curso_id_post = $_POST['curso_id'] ?? '';
    $fecha_post = $_POST['fecha'] ?? date('Y-m-d');
    $estados = $_POST['estado'] ?? [];
    $observaciones = $_POST['observacion'] ?? [];

    if (!empty($curso_id_post) && !empty($fecha_post)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO asistencias (alumno_id, fecha, estado, observacion) VALUES (?, ?, ?, ?)');
            
            foreach ($estados as $alumno_id => $estado_val) {
                $obs = trim($observaciones[$alumno_id] ?? '');
                $stmt->execute([$alumno_id, $fecha_post, $estado_val, $obs]);
            }
            
            flash('¡Asistencia del curso registrada correctamente!');
            redirect('cargar_asistencia_curso.php?curso_id=' . $curso_id_post . '&fecha=' . $fecha_post);
        } catch (PDOException $e) {
            flash('Error al registrar asistencia: ' . $e->getMessage(), 'error');
        }
    }
}
?>

<div class="mb-4">
    <h1 class="display-6 fw-bold">
        <i class="fa-solid fa-list-check me-2"></i>Carga Masiva de Asistencia
    </h1>
    <p class="text-muted">Selecciona un curso y fecha para registrar asistencia de todo el grupo</p>
</div>

<!-- Formulario para seleccionar curso y fecha -->
<div class="card p-4 mb-4 shadow-sm border-0">
    <form class="row g-3 align-items-end" method="get">
        <div class="col-md-5">
            <label class="form-label fw-500">Seleccionar Curso</label>
            <select class="form-select" name="curso_id" required onchange="this.form.submit()">
                <option value="">-- Elige un curso --</option>
                <?php foreach($cursos as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $curso_id == $c['id'] ? 'selected' : '' ?>>
                    <?= e($c['nombre']) ?> (<?= e($c['turno']) ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-500">Fecha de Asistencia</label>
            <input class="form-control" type="date" name="fecha" value="<?= e($fecha) ?>" required>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success w-100" type="submit">
                <i class="fa-solid fa-users-viewfinder me-2"></i>Cargar Alumnos
            </button>
        </div>
    </form>
</div>

<!-- Tabla de alumnos si se ha seleccionado un curso -->
<?php if (!empty($curso_id) && !empty($alumnos)): ?>
<form method="POST">
    <input type="hidden" name="curso_id" value="<?= e($curso_id) ?>">
    <input type="hidden" name="fecha" value="<?= e($fecha) ?>">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-table me-2"></i>Lista de Alumnos
                <span class="badge bg-primary"><?= count($alumnos) ?> estudiantes</span>
            </h5>
            <button class="btn btn-primary btn-sm" type="submit">
                <i class="fa-solid fa-floppy-disk me-1"></i>Guardar Asistencia
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 25%">Nombre</th>
                        <th style="width: 20%">Documento</th>
                        <th style="width: 25%">Estado</th>
                        <th style="width: 25%">Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($alumnos as $index => $a): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td class="fw-500"><?= e($a['nombre']) ?></td>
                        <td class="small text-muted"><?= e($a['documento']) ?></td>
                        <td>
                            <select class="form-select form-select-sm" name="estado[<?= $a['id'] ?>]" required>
                                <option value="Presente" selected>✓ Presente</option>
                                <option value="Ausente">✕ Ausente</option>
                                <option value="Tardanza">⏱ Tardanza</option>
                            </select>
                        </td>
                        <td>
                            <input class="form-control form-control-sm" type="text" name="observacion[<?= $a['id'] ?>]" placeholder="Ej. Llegó tarde, etc.">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-light border-top">
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-secondary" type="reset">
                    <i class="fa-solid fa-rotate-left me-1"></i>Limpiar
                </button>
                <button class="btn btn-primary btn-lg px-5" type="submit">
                    <i class="fa-solid fa-floppy-disk me-1"></i>Guardar Asistencia
                </button>
            </div>
        </div>
    </div>
</form>

<?php elseif (!empty($curso_id)): ?>
<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5 text-muted">
        <i class="fa-solid fa-inbox" style="font-size: 2.5rem;"></i>
        <p class="mt-3">No hay alumnos activos en este curso</p>
    </div>
</div>
<?php else: ?>
<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5 text-muted">
        <i class="fa-solid fa-arrow-up" style="font-size: 2.5rem;"></i>
        <p class="mt-3">Selecciona un curso en el formulario anterior para continuar</p>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>