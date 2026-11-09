<?php 
$page_title = 'Profesores'; 
require __DIR__ . '/header.php'; 
require_profesor();

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $especialidad = trim($_POST['especialidad']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono'] ?? '');
    
    if (!empty($nombre) && !empty($especialidad) && !empty($email)) {
        try {
            $s = $pdo->prepare('INSERT INTO profesores(nombre, especialidad, email, telefono) VALUES(?,?,?,?)');
            $s->execute([$nombre, $especialidad, $email, $telefono]);
            flash('Profesor registrado exitosamente.');
            redirect('profesores.php');
        } catch (PDOException $e) {
            flash('Error al registrar: ' . $e->getMessage(), 'error');
        }
    } else {
        flash('Nombre, especialidad y email son requeridos', 'error');
    }
} 

$rows = $pdo ? $pdo->query('SELECT id, nombre, especialidad, email, telefono FROM profesores ORDER BY nombre')->fetchAll() : [];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-6 fw-bold">
            <i class="fa-solid fa-chalkboard-user me-2"></i>Profesores
        </h1>
        <p class="text-muted">Plantel docente e información de contacto</p>
    </div>
    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#profesorModal">
        <i class="fa-solid fa-plus me-2"></i>Nuevo Profesor
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-light border-bottom">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Directorio de Profesores</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay profesores registrados
                        </td>
                    </tr>
                <?php else: foreach($rows as $r): ?>
                <tr>
                    <td class="small text-muted">#<?= $r['id'] ?></td>
                    <td class="fw-500"><?= e($r['nombre']) ?></td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            <?= e($r['especialidad']) ?>
                        </span>
                    </td>
                    <td class="small"><?= e($r['email']) ?></td>
                    <td class="small text-muted"><?= e($r['telefono'] ?? '-') ?></td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="profesorModal">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header border-bottom">
                <h2 class="modal-title h5">
                    <i class="fa-solid fa-user-plus me-2"></i>Registrar Nuevo Profesor
                </h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-500">Nombre Completo</label>
                    <input class="form-control" id="nombre" name="nombre" placeholder="Ej. María Isabel López" required>
                </div>
                <div class="mb-3">
                    <label for="especialidad" class="form-label fw-500">Especialidad</label>
                    <input class="form-control" id="especialidad" name="especialidad" placeholder="Ej. Programación Web" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-500">Email</label>
                    <input class="form-control" id="email" type="email" name="email" placeholder="profesor@colegio.edu.py" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label fw-500">Teléfono (Opcional)</label>
                    <input class="form-control" id="telefono" type="tel" name="telefono" placeholder="0981 123 456">
                </div>
            </div>
            <div class="modal-footer border-top">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-check me-1"></i>Guardar Profesor
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>