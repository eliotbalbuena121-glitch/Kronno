<?php 
$page_title = 'Consultas y Filtros'; 
require 'header.php'; 
require_profesor();

$where = [];
$params = [];

if (!empty($_GET['fecha'])) {
    $where[] = 'x.fecha = ?';
    $params[] = $_GET['fecha'];
}
if (!empty($_GET['curso_id'])) {
    $where[] = 'a.curso_id = ?';
    $params[] = $_GET['curso_id'];
}
if (!empty($_GET['alumno_id'])) {
    $where[] = 'x.alumno_id = ?';
    $params[] = $_GET['alumno_id'];
}
if (!empty($_GET['estado'])) {
    $where[] = 'x.estado = ?';
    $params[] = $_GET['estado'];
}

$cursos = $pdo ? $pdo->query('SELECT id, nombre FROM cursos ORDER BY nombre')->fetchAll() : [];
$alumnos = $pdo ? $pdo->query('SELECT id, nombre FROM alumnos ORDER BY nombre')->fetchAll() : [];
$rows = [];

if ($pdo) {
    $sql = '
        SELECT x.*, a.nombre as alumno_nombre, c.nombre as curso_nombre
        FROM asistencias x 
        JOIN alumnos a ON a.id = x.alumno_id 
        JOIN cursos c ON c.id = a.curso_id'
        . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . 
        ' ORDER BY x.fecha DESC, x.id DESC';
        
    $s = $pdo->prepare($sql);
    $s->execute($params);
    $rows = $s->fetchAll();
}
?>

<div class="mb-4">
    <h1 class="h3 fw-bold">Consultas y Filtros Avanzados</h1>
    <p class="text-secondary">Filtra las asistencias registradas por fecha, curso, alumno individual o estado.</p>
</div>

<div class="card p-4 mb-3 shadow-sm border-0">
    <form class="row g-3" method="get">
        <div class="col-md-3">
            <label class="form-label">Fecha</label>
            <input class="form-control" type="date" name="fecha" value="<?= e($_GET['fecha'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Curso</label>
            <select class="form-select" name="curso_id">
                <option value="">Todos los cursos</option>
                <?php foreach($cursos as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($_GET['curso_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                    <?= e($c['nombre']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Alumno</label>
            <select class="form-select" name="alumno_id">
                <option value="">Todos los alumnos</option>
                <?php foreach($alumnos as $a): ?>
                <option value="<?= $a['id'] ?>" <?= ($_GET['alumno_id'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                    <?= e($a['nombre']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado">
                <option value="">Todos</option>
                <option value="Presente" <?= ($_GET['estado'] ?? '') === 'Presente' ? 'selected' : '' ?>>Presente</option>
                <option value="Ausente" <?= ($_GET['estado'] ?? '') === 'Ausente' ? 'selected' : '' ?>>Ausente</option>
                <option value="Tardanza" <?= ($_GET['estado'] ?? '') === 'Tardanza' ? 'selected' : '' ?>>Tardanza</option>
            </select>
        </div>
        <div class="col-md-12 text-end">
            <button class="btn btn-primary px-4" type="submit"><i class="fa-solid fa-filter me-2"></i>Aplicar Filtros</button>
            <a href="consultas.php" class="btn btn-outline-secondary ms-2">Limpiar</a>
        </div>
    </form>
</div>

<div class="card p-3 shadow-sm border-0">
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
                <?php if(empty($rows)): ?>
                <tr><td colspan="5" class="text-center text-secondary py-4">No se encontraron registros con los filtros seleccionados.</td></tr>
                <?php else: foreach($rows as $r): 
                    $badge = $r['estado'] === 'Presente' ? 'success' : ($r['estado'] === 'Ausente' ? 'danger' : 'warning text-dark');
                ?>
                <tr>
                    <td><?= e($r['fecha']) ?></td>
                    <td class="fw-semibold"><?= e($r['alumno_nombre']) ?></td>
                    <td><?= e($r['curso_nombre']) ?></td>
                    <td><span class="badge bg-<?= $badge ?>"><?= e($r['estado']) ?></span></td>
                    <td><?= e($r['observacion'] ?? '-') ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'footer.php'; ?>