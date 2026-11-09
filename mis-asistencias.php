<?php 
$page_title = 'Mis Asistencias'; 
require 'header.php'; 
require_once 'auth.php';

require_alumno();

$alumno_data = get_user_full_data();
$rows = [];

if ($pdo && $alumno_data) {
    try {
        $stmt = $pdo->prepare('
            SELECT a.*, 
                   (SELECT COUNT(*) FROM asistencias WHERE alumno_id = ? AND estado = "Presente") as presentes,
                   (SELECT COUNT(*) FROM asistencias WHERE alumno_id = ? AND estado = "Ausente") as ausentes,
                   (SELECT COUNT(*) FROM asistencias WHERE alumno_id = ? AND estado = "Tardanza") as tardanzas
            FROM asistencias a
            WHERE a.alumno_id = ?
            ORDER BY a.fecha DESC
        ');
        $stmt->execute([$alumno_data['id'], $alumno_data['id'], $alumno_data['id'], $alumno_data['id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        flash('Error al obtener asistencias', 'danger');
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold">Mis Asistencias</h1>
        <p class="text-secondary">Registro completo de mi asistencia</p>
    </div>
</div>

<?php if ($rows && count($rows) > 0): ?>
    <?php 
    $stats = $rows[0];
    $presentes = $stats['presentes'] ?? 0;
    $ausentes = $stats['ausentes'] ?? 0;
    $tardanzas = $stats['tardanzas'] ?? 0;
    ?>
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Presentes</small>
                <div class="fs-3 fw-bold text-success"><?= e($presentes) ?></div>
                <span class="text-success small">Días asistidos</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Ausentes</small>
                <div class="fs-3 fw-bold text-danger"><?= e($ausentes) ?></div>
                <span class="text-danger small">Faltas</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0">
                <small class="text-secondary">Tardanzas</small>
                <div class="fs-3 fw-bold text-warning"><?= e($tardanzas) ?></div>
                <span class="text-warning small">Retrasos registrados</span>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card p-3 shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Observación</th>
                    <th>Justificativo</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($rows)): 
                    foreach($rows as $r): 
                        $badge = $r['estado'] === 'Presente' ? 'success' : ($r['estado'] === 'Ausente' ? 'danger' : 'warning text-dark');
                        
                        // Verifica si existe un justificativo para esta fecha
                        $justificativo = null;
                        if ($pdo) {
                            try {
                                $stmt = $pdo->prepare('
                                    SELECT * FROM justificativos 
                                    WHERE alumno_id = ? AND fecha = ? 
                                    ORDER BY id DESC LIMIT 1
                                ');
                                $stmt->execute([$alumno_data['id'], $r['fecha']]);
                                $justificativo = $stmt->fetch(PDO::FETCH_ASSOC);
                            } catch (PDOException $e) {}
                        }
                ?>
                <tr>
                    <td><?= e($r['fecha']) ?></td>
                    <td><span class="badge bg-<?= $badge ?>"><?= e($r['estado']) ?></span></td>
                    <td><?= e($r['observacion'] ?? '-') ?></td>
                    <td>
                        <?php if ($justificativo): ?>
                            <span class="badge bg-<?= 
                                $justificativo['estado'] === 'Aprobado' ? 'success' : 
                                ($justificativo['estado'] === 'Rechazado' ? 'danger' : 'warning text-dark')
                            ?>">
                                <?= e($justificativo['estado']) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted small">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="fa-solid fa-inbox fs-3 mb-2"></i>
                        <p>No hay registros de asistencia aún.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="alert alert-info mt-4 d-flex align-items-start">
    <i class="fa-solid fa-circle-info me-2 mt-1"></i>
    <div>
        <strong>Información importante</strong>
        <p class="mb-0 small">Si crees que hay un error en tu asistencia, puedes crear un justificativo con la documentación correspondiente en la sección "Mis Justificativos".</p>
    </div>
</div>

<?php require 'footer.php'; ?>
