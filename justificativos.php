<?php 
$page_title = 'Justificativos'; 
require __DIR__ . '/header.php'; 
require_profesor();

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumno_id = (int)($_POST['alumno_id'] ?? 0);
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $motivo = trim($_POST['motivo'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    
    if ($alumno_id && $fecha && $motivo) {
        try {
            $stmt = $pdo->prepare('INSERT INTO justificativos(alumno_id, fecha, motivo, documento, estado) VALUES(?,?,?,?,"Pendiente")');
            $stmt->execute([$alumno_id, $fecha, $motivo, $documento]);
            flash('Justificativo registrado exitosamente.');
            redirect('justificativos.php');
        } catch (PDOException $e) {
            flash('Error al registrar: ' . $e->getMessage(), 'error');
        }
    } else {
        flash('Alumno, fecha y motivo son requeridos', 'error');
    }
} 

$alumnos = $pdo ? $pdo->query('SELECT id, nombre FROM alumnos ORDER BY nombre')->fetchAll() : [];
$rows = [];
if ($pdo) {
    try {
        $rows = $pdo->query('
            SELECT j.id, j.alumno_id, j.fecha, j.motivo, j.documento, j.estado, a.nombre, c.nombre as curso
            FROM justificativos j 
            JOIN alumnos a ON a.id = j.alumno_id 
            JOIN cursos c ON c.id = a.curso_id 
            ORDER BY j.fecha DESC, j.id DESC
        ')->fetchAll();
    } catch (PDOException $e) {
        $db_error = 'Error al cargar justificativos';
        $rows = [];
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-6 fw-bold">
            <i class="fa-solid fa-file-circle-check me-2"></i>Justificativos de Inasistencia
        </h1>
        <p class="text-muted">Gestión de solicitudes y aprobación de motivos</p>
    </div>
    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#justificativoModal">
        <i class="fa-solid fa-plus me-2"></i>Nuevo Justificativo
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-light border-bottom">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Justificativos Registrados</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Alumno</th>
                    <th>Curso</th>
                    <th>Motivo</th>
                    <th class="text-center">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($rows)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No hay justificativos registrados
                    </td>
                </tr>
                <?php else: foreach($rows as $r):
                    $badge = $r['estado'] === 'Aprobado' ? 'success' : ($r['estado'] === 'Rechazado' ? 'danger' : 'warning text-dark');
                ?>
                <tr>
                    <td class="small"><?= e($r['fecha']) ?></td>
                    <td class="fw-500"><?= e($r['nombre']) ?></td>
                    <td><?= e($r['curso']) ?></td>
                    <td><?= e($r['motivo']) ?></td>
                    <td class="text-center">
                        <span class="badge bg-<?= $badge ?>">
                            <?= e($r['estado']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="justificativoModal">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header border-bottom">
                <h2 class="modal-title h5">
                    <i class="fa-solid fa-file-plus me-2"></i>Registrar Nuevo Justificativo
                </h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="alumno_id" class="form-label fw-500">Alumno</label>
                    <select class="form-select" id="alumno_id" name="alumno_id" required>
                        <option value="">Seleccionar alumno</option>
                        <?php foreach($alumnos as $a): ?>
                        <option value="<?= $a['id'] ?>"><?= e($a['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label fw-500">Fecha de la Falta</label>
                    <input class="form-control" id="fecha" type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="motivo" class="form-label fw-500">Motivo</label>
                    <textarea class="form-control" id="motivo" name="motivo" rows="3" placeholder="Ej. Reposo médico por 24 horas..." required></textarea>
                </div>
                <div class="mb-3">
                    <label for="documento" class="form-label fw-500">Documento Adjunto (Opcional)</label>
                    <input class="form-control" id="documento" name="documento" placeholder="Ej. reposo-medico.pdf">
                </div>
            </div>
            <div class="modal-footer border-top">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-check me-1"></i>Guardar Justificativo
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>