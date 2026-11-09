<?php 
$page_title = 'Cursos'; 
require __DIR__ . '/header.php'; 
require_profesor();

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $nivel = trim($_POST['nivel']);
    $turno = trim($_POST['turno']);
    
    if (!empty($nombre) && !empty($nivel) && !empty($turno)) {
        try {
            $s = $pdo->prepare('INSERT INTO cursos(nombre, nivel, turno, anio, activo) VALUES(?,?,?,2026,1)');
            $s->execute([$nombre, $nivel, $turno]);
            flash('Curso agregado exitosamente.');
            redirect('cursos.php');
        } catch (PDOException $e) {
            flash('Error al agregar curso: ' . $e->getMessage(), 'error');
        }
    } else {
        flash('Todos los campos son requeridos', 'error');
    }
} 

$rows = $pdo ? $pdo->query('SELECT id, nombre, nivel, turno, activo FROM cursos ORDER BY nombre')->fetchAll() : [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-6 fw-bold">
            <i class="fa-solid fa-book-open me-2"></i>Cursos
        </h1>
        <p class="text-muted">Grados y secciones institucionales</p>
    </div>
    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#cursoModal">
        <i class="fa-solid fa-plus me-2"></i>Nuevo Curso
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-light border-bottom">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Cursos Activos</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Curso</th>
                    <th>Nivel</th>
                    <th>Turno</th>
                    <th class="text-center">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay cursos registrados
                        </td>
                    </tr>
                <?php else: foreach($rows as $r): ?>
                <tr>
                    <td class="small text-muted">#<?= $r['id'] ?></td>
                    <td class="fw-500"><?= e($r['nombre']) ?></td>
                    <td><?= e($r['nivel']) ?></td>
                    <td>
                        <span class="badge bg-info">
                            <?= $r['turno'] === 'Mañana' ? '🌅 Mañana' : '🌆 Tarde' ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <?php if ($r['activo']): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="cursoModal">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header border-bottom">
                <h2 class="modal-title h5">
                    <i class="fa-solid fa-book-plus me-2"></i>Registrar Nuevo Curso
                </h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-500">Nombre del Curso</label>
                    <input class="form-control" id="nombre" name="nombre" placeholder="Ej. 3ro Bachillerato Técnico" required>
                </div>
                <div class="mb-3">
                    <label for="nivel" class="form-label fw-500">Nivel Educativo</label>
                    <input class="form-control" id="nivel" name="nivel" placeholder="Ej. 3ro Bachillerato" required>
                </div>
                <div class="mb-3">
                    <label for="turno" class="form-label fw-500">Turno</label>
                    <select class="form-select" id="turno" name="turno" required>
                        <option value="">Seleccionar turno</option>
                        <option value="Mañana">🌅 Mañana</option>
                        <option value="Tarde">🌆 Tarde</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-check me-1"></i>Guardar Curso
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>