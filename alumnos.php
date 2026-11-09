<?php
$page_title = 'Alumnos';
require __DIR__ . '/header.php';
require_profesor();

// Obtener lista de cursos
$cursos = [];
try {
    $stmt = $pdo->query('SELECT id, nombre FROM cursos WHERE activo = 1 ORDER BY nombre ASC');
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = 'Error al cargar cursos';
}

// Procesar formulario de nuevo alumno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_alumno'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $documento = trim($_POST['documento'] ?? '');
    $curso_id = (int)($_POST['curso_id'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    
    if (!empty($nombre) && !empty($documento) && !empty($curso_id)) {
        try {
            $stmt = $pdo->prepare('
                INSERT INTO alumnos(nombre, documento, curso_id, email, telefono, estado) 
                VALUES(?, ?, ?, ?, ?, "Activo")
            ');
            $stmt->execute([$nombre, $documento, $curso_id, $email, $telefono]);
            set_flash('success', 'Alumno agregado exitosamente.');
            redirect('alumnos.php');
        } catch (PDOException $e) {
            set_flash('error', 'Error al agregar alumno: ' . $e->getMessage());
        }
    } else {
        set_flash('error', 'Nombre, documento y curso son obligatorios');
    }
}

// Búsqueda y filtros
$busqueda = trim($_GET['busqueda'] ?? '');
$curso_filtro = $_GET['curso'] ?? '';
$ordenar_por = $_GET['ordenar'] ?? 'nombre';

// Construir query
$query = '
    SELECT a.id, a.nombre, a.documento, a.email, a.telefono, a.estado, c.nombre as curso
    FROM alumnos a
    LEFT JOIN cursos c ON a.curso_id = c.id
    WHERE 1=1
';

$params = [];

if (!empty($busqueda)) {
    $query .= ' AND (a.nombre LIKE ? OR a.documento LIKE ? OR a.email LIKE ?)';
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
}

if (!empty($curso_filtro)) {
    $query .= ' AND a.curso_id = ?';
    $params[] = $curso_filtro;
}

// Ordenamiento
$ordenar_permitido = ['nombre', 'documento', 'curso', 'estado'];
if (!in_array($ordenar_por, $ordenar_permitido)) {
    $ordenar_por = 'nombre';
}

$query .= " ORDER BY $ordenar_por ASC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = 'Error al cargar alumnos: ' . $e->getMessage();
    $alumnos = [];
}

// Helper para flash messages
// Nota: Se usan las funciones globales de config.php (no redeclarar aquí)
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-6 fw-bold">
            <i class="fa-solid fa-users me-2"></i>Alumnos
        </h1>
        <p class="text-muted">Listado general de estudiantes</p>
    </div>
    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#alumnoModal">
        <i class="fa-solid fa-plus me-2"></i>Nuevo Alumno
    </button>
</div>

<!-- Búsqueda y Filtros -->
<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" class="d-flex gap-2">
            <input 
                type="text" 
                class="form-control" 
                name="busqueda" 
                placeholder="Buscar por nombre, documento o email..."
                value="<?= e($busqueda) ?>"
            >
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <?php if (!empty($busqueda)): ?>
                <a href="alumnos.php" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-times"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
    <div class="col-md-6">
        <select class="form-select" name="curso" id="cursoFilter" onchange="document.location='alumnos.php?busqueda=<?= urlencode($busqueda) ?>&curso=' + this.value + '&ordenar=<?= $ordenar_por ?>'">
            <option value="">Todos los cursos</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= $curso['id'] ?>" <?= $curso_filtro == $curso['id'] ? 'selected' : '' ?>>
                    <?= e($curso['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<!-- Tabla de Alumnos -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-light border-bottom">
        <h5 class="mb-0">
            <i class="fa-solid fa-list me-2"></i>
            Alumnos Registrados
            <span class="badge bg-primary float-end"><?= count($alumnos) ?> registros</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($alumnos)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="sortable" onclick="sortBy('id')">
                                ID <?= $ordenar_por === 'id' ? '<i class="fa-solid fa-arrow-up"></i>' : '' ?>
                            </th>
                            <th class="sortable" onclick="sortBy('nombre')">
                                Nombre <?= $ordenar_por === 'nombre' ? '<i class="fa-solid fa-arrow-up"></i>' : '' ?>
                            </th>
                            <th class="sortable" onclick="sortBy('documento')">
                                Documento <?= $ordenar_por === 'documento' ? '<i class="fa-solid fa-arrow-up"></i>' : '' ?>
                            </th>
                            <th class="sortable" onclick="sortBy('curso')">
                                Curso <?= $ordenar_por === 'curso' ? '<i class="fa-solid fa-arrow-up"></i>' : '' ?>
                            </th>
                            <th>Email</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $alumno): ?>
                            <tr>
                                <td class="small text-muted">#<?= $alumno['id'] ?></td>
                                <td class="fw-500"><?= e($alumno['nombre']) ?></td>
                                <td><?= e($alumno['documento']) ?></td>
                                <td>
                                    <?php if ($alumno['curso']): ?>
                                        <span class="badge bg-info"><?= e($alumno['curso']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">Sin asignar</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small"><?= e($alumno['email']) ?></td>
                                <td class="text-center">
                                    <?php if ($alumno['estado'] === 'Activo'): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="p-5 text-center text-muted">
                <i class="fa-solid fa-inbox" style="font-size: 2.5rem;"></i>
                <p class="mt-3">No hay alumnos registrados con los criterios de búsqueda</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: Nuevo Alumno -->
<div class="modal fade" id="alumnoModal">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header border-bottom">
                <h2 class="modal-title h5">
                    <i class="fa-solid fa-user-plus me-2"></i>Registrar Nuevo Alumno
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-500">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Juan José Pérez" required>
                </div>
                <div class="mb-3">
                    <label for="documento" class="form-label fw-500">Documento</label>
                    <input type="text" class="form-control" id="documento" name="documento" placeholder="Ej. A-1234567" required>
                </div>
                <div class="mb-3">
                    <label for="curso_id" class="form-label fw-500">Curso</label>
                    <select class="form-select" id="curso_id" name="curso_id" required>
                        <option value="">Seleccionar curso</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id'] ?>"><?= e($curso['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-500">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label fw-500">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="0981 123 456">
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" name="agregar_alumno" value="1" class="btn btn-primary">
                    <i class="fa-solid fa-check me-1"></i>Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>

<style>
.sortable {
    cursor: pointer;
}
.sortable:hover {
    background-color: #f0f0f0;
}
</style>

<script>
function sortBy(columna) {
    const busqueda = new URLSearchParams(window.location.search).get('busqueda') || '';
    const curso = new URLSearchParams(window.location.search).get('curso') || '';
    window.location = `alumnos.php?busqueda=${encodeURIComponent(busqueda)}&curso=${curso}&ordenar=${columna}`;
}
</script>