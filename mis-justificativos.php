<?php 
$page_title = 'Mis Justificativos'; 
require 'header.php'; 
require_once 'auth.php';

require_alumno();

$alumno_data = get_user_full_data();
$rows = [];
$message = '';

// Procesar nuevo justificativo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'crear') {
        $fecha = $_POST['fecha'] ?? '';
        $motivo = trim($_POST['motivo'] ?? '');
        
        if ($fecha && $motivo && $alumno_data) {
            try {
                $stmt = $pdo->prepare('
                    INSERT INTO justificativos(alumno_id, fecha, motivo, estado) 
                    VALUES(?, ?, ?, "Pendiente")
                ');
                $stmt->execute([$alumno_data['id'], $fecha, $motivo]);
                flash('Justificativo enviado correctamente. Pendiente de aprobación.', 'success');
                redirect('mis-justificativos.php');
            } catch (PDOException $e) {
                $message = 'Error al enviar el justificativo.';
            }
        }
    }
}

// Obtener justificativos del alumno
if ($pdo && $alumno_data) {
    try {
        $stmt = $pdo->prepare('
            SELECT j.*, a.fecha, a.estado as asistencia_estado
            FROM justificativos j
            LEFT JOIN asistencias a ON a.alumno_id = j.alumno_id AND a.fecha = j.fecha
            WHERE j.alumno_id = ?
            ORDER BY j.fecha DESC
        ');
        $stmt->execute([$alumno_data['id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        flash('Error al obtener justificativos', 'danger');
    }
}

// Obtener asistencias sin justificativo para mostrar
$asistencias_sin_justificativo = [];
if ($pdo && $alumno_data) {
    try {
        $stmt = $pdo->prepare('
            SELECT DISTINCT a.fecha, a.estado 
            FROM asistencias a
            WHERE a.alumno_id = ? 
            AND a.estado IN ("Ausente", "Tardanza")
            AND NOT EXISTS (
                SELECT 1 FROM justificativos j 
                WHERE j.alumno_id = a.alumno_id 
                AND j.fecha = a.fecha
            )
            ORDER BY a.fecha DESC
            LIMIT 10
        ');
        $stmt->execute([$alumno_data['id']]);
        $asistencias_sin_justificativo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold">Mis Justificativos</h1>
        <p class="text-secondary">Gestiona tus justificativos de ausencia</p>
    </div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevoJustificativoModal">
        <i class="fa-solid fa-plus me-2"></i>Nuevo Justificativo
    </button>
</div>

<div class="card p-3 shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Asistencia</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($rows)): 
                    foreach($rows as $r): 
                        $estado_badge = $r['estado'] === 'Aprobado' ? 'success' : 
                                       ($r['estado'] === 'Rechazado' ? 'danger' : 'warning text-dark');
                        $asistencia_badge = $r['asistencia_estado'] === 'Presente' ? 'success' : 
                                           ($r['asistencia_estado'] === 'Ausente' ? 'danger' : 'warning text-dark');
                ?>
                <tr>
                    <td><?= e($r['fecha']) ?></td>
                    <td><?= e($r['motivo']) ?></td>
                    <td><span class="badge bg-<?= $estado_badge ?>"><?= e($r['estado']) ?></span></td>
                    <td>
                        <?php if ($r['asistencia_estado']): ?>
                            <span class="badge bg-<?= $asistencia_badge ?>"><?= e($r['asistencia_estado']) ?></span>
                        <?php else: ?>
                            <span class="text-muted small">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($r['estado'] === 'Pendiente'): ?>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reclamo<?= $r['id'] ?>Modal">
                                <i class="fa-solid fa-comment me-1"></i>Debatir
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fa-solid fa-inbox fs-3 mb-2"></i>
                        <p>No hay justificativos registrados aún.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para nuevo justificativo -->
<div class="modal fade" id="nuevoJustificativoModal">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <input type="hidden" name="action" value="crear">
            <div class="modal-header">
                <h2 class="modal-title h5">Nuevo Justificativo</h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Fecha de la Ausencia/Tardanza</label>
                    <select class="form-select" name="fecha" required>
                        <option value="">Seleccionar una fecha</option>
                        <?php foreach($asistencias_sin_justificativo as $a): ?>
                            <option value="<?= $a['fecha'] ?>">
                                <?= e($a['fecha']) ?> - <?= e($a['estado']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($asistencias_sin_justificativo)): ?>
                        <small class="text-muted">No hay ausencias o tardanzas sin justificativo.</small>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">Motivo del Justificativo</label>
                    <textarea class="form-control" name="motivo" placeholder="Explica el motivo de tu ausencia..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" type="submit" <?= empty($asistencias_sin_justificativo) ? 'disabled' : '' ?>>
                    <i class="fa-solid fa-check me-2"></i>Enviar Justificativo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modales para debatir justificativos -->
<?php foreach($rows as $r): ?>
    <?php if ($r['estado'] === 'Pendiente'): ?>
    <div class="modal fade" id="reclamo<?= $r['id'] ?>Modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5">Debatir Justificativo</h2>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">
                        <strong>Fecha:</strong> <?= e($r['fecha']) ?><br>
                        <strong>Motivo:</strong> <?= e($r['motivo']) ?><br>
                        <strong>Estado:</strong> <span class="badge bg-warning">Pendiente de aprobación</span>
                    </p>
                    <hr>
                    <p>Puedes dejar un comentario adicional para el profesor:</p>
                    <textarea class="form-control" placeholder="Escribe aquí si tienes más detalles que agregar..."></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="button">
                        <i class="fa-solid fa-send me-2"></i>Enviar Comentario
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php endforeach; ?>

<div class="alert alert-info mt-4 d-flex align-items-start">
    <i class="fa-solid fa-circle-info me-2 mt-1"></i>
    <div>
        <strong>Sobre los justificativos</strong>
        <p class="mb-0 small">Envía tus justificativos de forma rápida cuando tengas ausencias. El profesor podrá aprobarlos, rechazarlos o solicitar más información. Los justificativos pendientes pueden ser debatidos si crees que hay un error.</p>
    </div>
</div>

<?php require 'footer.php'; ?>
