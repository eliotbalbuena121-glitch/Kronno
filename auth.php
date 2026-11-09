<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conexion.php';

/**
 * Obtiene el usuario actualmente autenticado desde la sesión
 */
function get_user() {
    return $_SESSION['user'] ?? null;
}

/**
 * Obtiene el rol del usuario autenticado
 */
function get_rol() {
    $user = get_user();
    return $user['rol'] ?? null;
}

/**
 * Obtiene el ID del usuario autenticado
 */
function get_user_id() {
    $user = get_user();
    return $user['id'] ?? null;
}

/**
 * Verifica si el usuario está autenticado
 */
function is_logged_in() {
    return !empty($_SESSION['user']);
}

/**
 * Verifica si el usuario tiene un rol específico
 */
function has_role($role) {
    return get_rol() === $role;
}

/**
 * Verifica si el usuario es profesor
 */
function is_profesor() {
    return has_role('profesor');
}

/**
 * Verifica si el usuario es alumno
 */
function is_alumno() {
    return has_role('alumno');
}

/**
 * Requiere que el usuario esté autenticado, sino lo redirige a login
 */
function require_login() {
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

/**
 * Requiere que el usuario sea profesor, sino muestra error
 */
function require_profesor() {
    require_login();
    if (!is_profesor()) {
        die('<h1>Acceso denegado</h1><p>Solo los profesores pueden acceder a esta página.</p>');
    }
}

/**
 * Requiere que el usuario sea alumno, sino muestra error
 */
function require_alumno() {
    require_login();
    if (!is_alumno()) {
        die('<h1>Acceso denegado</h1><p>Solo los alumnos pueden acceder a esta página.</p>');
    }
}

/**
 * Intenta autenticar un usuario con email y contraseña
 */
function login_user($email, $password) {
    global $pdo;
    
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email y contraseña son requeridos'];
    }
    
    try {
        $stmt = $pdo->prepare('SELECT id, email, nombre, rol, alumno_id, profesor_id, password, activo FROM usuarios WHERE email = ? AND activo = 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
        }
        
        // Verificar contraseña con BCRYPT
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
        }
        
        // Remover contraseña de la sesión por seguridad
        unset($user['password']);
        $_SESSION['user'] = $user;
        return ['success' => true, 'message' => 'Autenticación exitosa'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error en la autenticación: ' . $e->getMessage()];
    }
}

/**
 * Registra un nuevo usuario profesor
 */
function register_profesor($email, $password, $nombre) {
    global $pdo;
    
    if (empty($email) || empty($password) || empty($nombre)) {
        return ['success' => false, 'message' => 'Todos los campos son requeridos'];
    }
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Email inválido'];
    }
    
    // Verificar email no duplicado
    try {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Este email ya está registrado'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error al verificar email'];
    }
    
    // Crear usuario sin profesor_id (se puede asignar después)
    try {
        $stmt = $pdo->prepare('
            INSERT INTO usuarios(email, password, nombre, rol, activo) 
            VALUES(?, ?, ?, "profesor", 1)
        ');
        $stmt->execute([$email, $password, $nombre]);
        return ['success' => true, 'message' => 'Registro exitoso. Puedes iniciar sesión.'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error al registrar usuario: ' . $e->getMessage()];
    }
}

/**
 * Registra un nuevo usuario alumno
 */
function register_alumno($email, $password, $nombre) {
    global $pdo;
    
    if (empty($email) || empty($password) || empty($nombre)) {
        return ['success' => false, 'message' => 'Todos los campos son requeridos'];
    }
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Email inválido'];
    }
    
    // Verificar email no duplicado
    try {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Este email ya está registrado'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error al verificar email'];
    }
    
    // Crear usuario sin alumno_id (se puede asignar después)
    try {
        $stmt = $pdo->prepare('
            INSERT INTO usuarios(email, password, nombre, rol, activo) 
            VALUES(?, ?, ?, "alumno", 1)
        ');
        $stmt->execute([$email, $password, $nombre]);
        return ['success' => true, 'message' => 'Registro exitoso. Puedes iniciar sesión.'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error al registrar usuario: ' . $e->getMessage()];
    }
}

/**
 * Cierra la sesión del usuario
 */
function logout_user() {
    unset($_SESSION['user']);
    session_destroy();
}

/**
 * Obtiene los datos completos del usuario (incluyendo info de profesor o alumno)
 */
function get_user_full_data() {
    global $pdo;
    $user = get_user();
    
    if (!$user) {
        return null;
    }
    
    try {
        if ($user['rol'] === 'profesor') {
            $stmt = $pdo->prepare('SELECT * FROM profesores WHERE id = ?');
            $stmt->execute([$user['profesor_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else if ($user['rol'] === 'alumno') {
            $stmt = $pdo->prepare('SELECT a.*, c.nombre as curso_nombre FROM alumnos a LEFT JOIN cursos c ON a.curso_id = c.id WHERE a.id = ?');
            $stmt->execute([$user['alumno_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        return null;
    }
}
?>
