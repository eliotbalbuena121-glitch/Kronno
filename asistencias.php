<?php 
$page_title = 'Asistencias'; 
require 'header.php'; 
require_once 'auth.php';

require_profesor();

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumno_id = (int)$_POST['alumno_id'];
    $fecha = $_POST['fecha'];
    $estado = $_POST['estado'];
    $observacion = trim($_POST['observacion']);

    if ($alumno_id && $fecha && $estado) {
        $s = $pdo->prepare('INSERT INTO asistencias(alumno_id, fecha, estado, observacion) VALUES(?,?,?,?)');
        $s->execute([$alumno_id, $fecha, $estado, $observacion]);
        flash('Asistencia registrada correctamente.');
        redirect('asistencias.php');
    }
} 

// Obtener cursos
$cursos = $pdo ? $pdo->query('SELECT id, nombre FROM cursos WHERE activo = 1 ORDER BY nombre')->fetchAll() : [];

// Obtener alumnos (todos o filtrados por curso)
$curso_filtro = $_GET['curso'] ?? '';
if (!empty($curso_filtro) && $pdo) {
    $stmt = $pdo->prepare('SELECT id, nombre FROM alumnos WHERE curso_id = ? ORDER BY nombre');
    $stmt->execute([$curso_filtro]);
    $alumnos = $stmt->fetchAll();
} else {
    $alumnos = $pdo ? $pdo->query('SELECT id, nombre FROM alumnos ORDER BY nombre')->fetchAll() : [];
}

// Obtener asistencias
$rows = [];
if ($pdo) {
    $sql = '
        SELECT x.*, a.nombre as alumno_nombre, c.nombre as curso_nombre
        FROM asistencias x 
        JOIN alumnos a ON a.id = x.alumno_id 
        JOIN cursos c ON c.id = a.curso_id';
    
    if (!empty($curso_filtro)) {
        $sql .= ' WHERE c.id = ' . (int)$curso_filtro;
    }
    
    $sql .= ' ORDER BY x.fecha DESC, x.id DESC';
    
    $rows = $pdo->query($sql)->fetchAll();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold">Asistencias</h1>
        <p class="text-secondary">Control diario de presencia de alumnos</p>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#asistenciaModal">
        <i class="fa-solid fa-check me-2"></i>Registrar Asistencia
    </button>
</div>

<!-- Filtro por Curso -->
<div class="card p-3 mb-4 shadow-sm border-0">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-6">
            <label class="form-label fw-500">Filtrar por Curso</label>
            <select class="form-select" name="curso" onchange="this.form.submit()">
                <option value="">Todos los cursos</option>
                <?php foreach($cursos as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $curso_filtro == $c['id'] ? 'selected' : '' ?>>
                    <?= e($c['nombre']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-filter me-2"></i>Aplicar Filtro
            </button>
            <?php if (!empty($curso_filtro)): ?>
                <a href="asistencias.php" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="fa-solid fa-xmark me-2"></i>Limpiar Filtro
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="card p-3 shadow-sm border-0">
    <div class="card-header bg-light border-bottom mb-3">
        <h5 class="mb-0">
            <i class="fa-solid fa-list me-2"></i>
            Asistencias Registradas
            <?php if (!empty($curso_filtro)): 
                $curso_nombre = array_column($cursos, 'nombre', 'id')[$curso_filtro] ?? '';
            ?>
                <span class="badge bg-info"><?= e($curso_nombre) ?></span>
            <?php endif; ?>
            <span class="badge bg-primary float-end"><?= count($rows) ?> registros</span>
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Alumno</th>
                    <th>Curso</th>
                    <th>Estado</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay asistencias registradas
                        </td>
                    </tr>
                <?php else: foreach($rows as $r): 
                    $badge = $r['estado'] === 'Presente' ? 'success' : ($r['estado'] === 'Ausente' ? 'danger' : 'warning text-dark');
                ?>
                <tr>
                    <td><?= e($r['fecha']) ?></td>
                    <td class="fw-semibold"><?= e($r['alumno_nombre']) ?></td>
                    <td><?= e($r['curso_nombre']) ?></td>
                    <td><span class="badge bg-<?= $badge ?>"><?= e($r['estado']) ?></span></td>
                    <td><?= e($r['observacion']) ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="asistenciaModal">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5">Registrar asistencia</h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-500">Curso</label>
                    <select class="form-select" id="modalCurso" name="curso_modal" onchange="filtrarAlumnosModal()">
                        <option value="">Seleccionar curso</option>
                        <?php foreach($cursos as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $curso_filtro == $c['id'] ? 'selected' : '' ?>>
                            <?= e($c['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500">Alumno</label>
                    <select class="form-select" id="modalAlumno" name="alumno_id" required>
                        <option value="">Seleccionar alumno</option>
                        <?php foreach($alumnos as $a): ?>
                        <option value="<?= $a['id'] ?>"><?= e($a['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500">Fecha</label>
                    <input class="form-control" type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500">Estado</label>
                    <select class="form-select" name="estado" required>
                        <option value="Presente">✓ Presente</option>
                        <option value="Ausente">✕ Ausente</option>
                        <option value="Tardanza">⏱ Tardanza</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500">Observación</label>
                    <textarea class="form-control" name="observacion" placeholder="Detalle adicional..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" type="submit">
                    <i class="fa-solid fa-check me-1"></i>Guardar Asistencia
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Datos de alumnos por curso (JSON)
const alumnosPorCurso = {
    <?php foreach($cursos as $c): 
        $stmt = $pdo->prepare('SELECT id, nombre FROM alumnos WHERE curso_id = ? ORDER BY nombre');
        $stmt->execute([$c['id']]);
        $alumnos_curso = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?= $c['id'] ?>: [
        <?php foreach($alumnos_curso as $a): ?>
        { id: <?= $a['id'] ?>, nombre: '<?= addslashes($a['nombre']) ?>' },
        <?php endforeach; ?>
    ],
    <?php endforeach; ?>
};

function filtrarAlumnosModal() {
    const cursoId = document.getElementById('modalCurso').value;
    const alumnoSelect = document.getElementById('modalAlumno');
    
    // Limpiar opciones
    alumnoSelect.innerHTML = '<option value="">Seleccionar alumno</option>';
    
    if (cursoId && alumnosPorCurso[cursoId]) {
        alumnosPorCurso[cursoId].forEach(alumno => {
            const option = document.createElement('option');
            option.value = alumno.id;
            option.textContent = alumno.nombre;
            alumnoSelect.appendChild(option);
        });
    } else if (cursoId === '') {
        // Si no hay curso seleccionado, mostrar todos
        <?php foreach($alumnos as $a): ?>
        const option = document.createElement('option');
        option.value = <?= $a['id'] ?>;
        option.textContent = '<?= addslashes($a['nombre']) ?>';
        alumnoSelect.appendChild(option);
        <?php endforeach; ?>
    }
}
</script>

<?php require 'footer.php'; ?>