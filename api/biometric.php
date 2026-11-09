<?php
/**
 * API REST para Biometría - KRONO 2026
 * 
 * Endpoints:
 * POST /api/biometric.php?action=register-start      - Iniciar registro de huella
 * POST /api/biometric.php?action=register-confirm    - Confirmar registro de huella
 * POST /api/biometric.php?action=authenticate        - Autenticar por huella (marcar asistencia)
 * GET  /api/biometric.php?action=status&token=XXX    - Verificar estado de token
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/biometric_utils.php';

// Manejo de CORS OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$action = $_GET['action'] ?? null;

if (!$action) {
    ApiResponse::error('Acción no especificada', 400);
}

switch ($action) {
    case 'register-start':
        registerStart();
        break;
    
    case 'register-confirm':
        registerConfirm();
        break;
    
    case 'authenticate':
        authenticate();
        break;
    
    case 'status':
        checkTokenStatus();
        break;
    
    default:
        ApiResponse::error('Acción no válida', 400);
}

/**
 * ENDPOINT 1: Iniciar registro de huella
 * 
 * POST /api/biometric.php?action=register-start
 * 
 * Body:
 * {
 *   "alumno_id": 1,
 *   "dispositivo_id": "device_uuid_123"
 * }
 * 
 * Response:
 * {
 *   "success": true,
 *   "message": "Registro iniciado",
 *   "data": {
 *     "token": "abc123xyz...",
 *     "codigo_registro": "ABC-1234-XYZ",
 *     "expira_en_segundos": 300
 *   }
 * }
 */
function registerStart() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ApiResponse::error('Método no permitido', 405);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $alumno_id = $input['alumno_id'] ?? null;
    $dispositivo_id = $input['dispositivo_id'] ?? null;
    
    // Validaciones
    if (!$alumno_id) {
        ApiResponse::error('alumno_id es requerido', 400);
    }
    
    try {
        // Verificar que el alumno existe
        $stmt = $pdo->prepare('SELECT id, nombre FROM alumnos WHERE id = ? AND estado = "Activo"');
        $stmt->execute([$alumno_id]);
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$alumno) {
            BiometricUtils::logBiometricAttempt($pdo, $alumno_id, 'registro', 'fallido', 'Alumno no encontrado', $dispositivo_id);
            ApiResponse::error('Alumno no encontrado o inactivo', 404);
        }
        
        // Generar token y código
        $token = BiometricUtils::generateToken();
        $codigo = BiometricUtils::generateRegistrationCode();
        $expiracion = date('Y-m-d H:i:s', time() + 300); // 5 minutos
        
        // Guardar token en BD
        $stmt = $pdo->prepare('
            INSERT INTO biometric_tokens(alumno_id, token, codigo_registro, estado, fecha_expiracion)
            VALUES (?, ?, ?, "activo", ?)
        ');
        $stmt->execute([$alumno_id, $token, $codigo, $expiracion]);
        
        BiometricUtils::logBiometricAttempt($pdo, $alumno_id, 'registro', 'exitoso', 'Token generado', $dispositivo_id);
        
        ApiResponse::success([
            'token' => $token,
            'codigo_registro' => $codigo,
            'expira_en_segundos' => 300,
            'alumno_nombre' => $alumno['nombre']
        ], 'Registro iniciado. Prepárate para capturar tu huella.');
        
    } catch (Exception $e) {
        BiometricUtils::logBiometricAttempt($pdo, $alumno_id, 'registro', 'error', $e->getMessage(), $dispositivo_id);
        ApiResponse::error('Error al iniciar registro: ' . $e->getMessage(), 500);
    }
}

/**
 * ENDPOINT 2: Confirmar registro de huella
 * 
 * POST /api/biometric.php?action=register-confirm
 * 
 * Body:
 * {
 *   "token": "abc123xyz...",
 *   "huella_base64": "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==",
 *   "dispositivo_id": "device_uuid_123"
 * }
 * 
 * Response:
 * {
 *   "success": true,
 *   "message": "Huella registrada exitosamente",
 *   "data": {
 *     "registro_id": 42,
 *     "mensaje": "Tu huella ha sido guardada. Ya puedes usar la app."
 *   }
 * }
 */
function registerConfirm() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ApiResponse::error('Método no permitido', 405);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $token = $input['token'] ?? null;
    $huella_base64 = $input['huella_base64'] ?? null;
    $dispositivo_id = $input['dispositivo_id'] ?? null;
    
    // Validaciones
    if (!$token || !$huella_base64) {
        ApiResponse::error('Token y huella_base64 son requeridos', 400);
    }
    
    try {
        // Validar token
        $tokenValidation = BiometricUtils::validateToken($pdo, $token);
        if (!$tokenValidation['valid']) {
            ApiResponse::error('Token inválido: ' . $tokenValidation['reason'], 400);
        }
        
        $tokenData = $tokenValidation['data'];
        $alumno_id = $tokenData['alumno_id'];
        
        // Generar hash de la huella
        $huella_hash = BiometricUtils::generateFingerprintHash($huella_base64);
        if (!$huella_hash) {
            BiometricUtils::logBiometricAttempt($pdo, $alumno_id, 'registro', 'fallido', 'Formato de huella inválido', $dispositivo_id);
            ApiResponse::error('Formato de huella inválido', 400);
        }
        
        // Verificar si el alumno ya tiene huella registrada
        $stmt = $pdo->prepare('SELECT id FROM biometric_registrations WHERE alumno_id = ?');
        $stmt->execute([$alumno_id]);
        if ($stmt->fetch()) {
            // Actualizar huella existente
            $stmt = $pdo->prepare('
                UPDATE biometric_registrations 
                SET huella_hash = ?, huella_template = ?, dispositivo_id = ?, ultimo_acceso = NOW(), intentos_fallidos = 0
                WHERE alumno_id = ?
            ');
            $stmt->execute([$huella_hash, base64_decode($huella_base64), $dispositivo_id, $alumno_id]);
        } else {
            // Insertar nuevo registro
            $stmt = $pdo->prepare('
                INSERT INTO biometric_registrations(alumno_id, huella_hash, huella_template, dispositivo_id)
                VALUES (?, ?, ?, ?)
            ');
            $stmt->execute([$alumno_id, $huella_hash, base64_decode($huella_base64), $dispositivo_id]);
        }
        
        $registro_id = $pdo->lastInsertId() ?: $alumno_id;
        
        // Marcar token como usado
        $stmt = $pdo->prepare('UPDATE biometric_tokens SET estado = "usado" WHERE token = ?');
        $stmt->execute([$token]);
        
        BiometricUtils::logBiometricAttempt($pdo, $alumno_id, 'registro', 'exitoso', 'Huella registrada', $dispositivo_id);
        
        ApiResponse::success([
            'registro_id' => $registro_id,
            'mensaje' => '✅ Tu huella ha sido registrada exitosamente. Ya puedes usar la app para marcar asistencia.'
        ], 'Huella registrada exitosamente');
        
    } catch (Exception $e) {
        ApiResponse::error('Error al registrar huella: ' . $e->getMessage(), 500);
    }
}

/**
 * ENDPOINT 3: Autenticar por huella y marcar asistencia
 * 
 * POST /api/biometric.php?action=authenticate
 * 
 * Body:
 * {
 *   "alumno_codigo": "ABC-1234-XYZ",
 *   "huella_base64": "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==",
 *   "dispositivo_id": "device_uuid_123"
 * }
 * 
 * Response:
 * {
 *   "success": true,
 *   "message": "Asistencia registrada",
 *   "data": {
 *     "asistencia_id": 1234,
 *     "alumno_nombre": "Carlos Méndez",
 *     "hora": "08:30:45",
 *     "estado": "Presente",
 *     "curso": "1ro BTI"
 *   }
 * }
 */
function authenticate() {
    global $pdo;
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ApiResponse::error('Método no permitido', 405);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $alumno_codigo = $input['alumno_codigo'] ?? null;
    $huella_base64 = $input['huella_base64'] ?? null;
    $dispositivo_id = $input['dispositivo_id'] ?? null;
    
    // Validaciones
    if (!$alumno_codigo || !$huella_base64) {
        ApiResponse::error('alumno_codigo y huella_base64 son requeridos', 400);
    }
    
    try {
        // Buscar alumno por documento o ID
        $stmt = $pdo->prepare('
            SELECT a.id, a.nombre, a.curso_id, c.nombre as curso_nombre
            FROM alumnos a
            LEFT JOIN cursos c ON a.curso_id = c.id
            WHERE a.documento = ? AND a.estado = "Activo"
        ');
        $stmt->execute([$alumno_codigo]);
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$alumno) {
            ApiResponse::error('Alumno no encontrado', 404);
        }
        
        $alumno_id = $alumno['id'];
        
        // Obtener registro biométrico del alumno
        $stmt = $pdo->prepare('
            SELECT huella_hash, huella_template, bloqueado
            FROM biometric_registrations
            WHERE alumno_id = ?
        ');
        $stmt->execute([$alumno_id]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$registro) {
            BiometricUtils::logBiometricAttempt($pdo, $alumno_id, 'autenticacion', 'fallido', 'Sin huella registrada', $dispositivo_id);
            ApiResponse::error('Huella no registrada para este alumno', 404);
        }
        
        if ($registro['bloqueado']) {
            BiometricUtils::logBiometricAttempt($pdo, $alumno_id, 'autenticacion', 'fallido', 'Cuenta bloqueada', $dispositivo_id);
            ApiResponse::error('Tu cuenta ha sido bloqueada por demasiados intentos fallidos', 403);
        }
        
        // Comparar huella (en app móvil se haría, aquí solo validamos)
        $huella_hash_nuevx = BiometricUtils::generateFingerprintHash($huella_base64);
        $comparacion = BiometricUtils::compareFingerprintTemplates(
            $registro['huella_template'],
            base64_decode($huella_base64),
            0.95
        );
        
        // Para demo, validamos hash exacto
        $huellas_coinciden = $huella_hash_nuevx === $registro['huella_hash'];
        
        if (!$huellas_coinciden) {
            // Incrementar intentos fallidos
            $stmt = $pdo->prepare('
                UPDATE biometric_registrations 
                SET intentos_fallidos = intentos_fallidos + 1
                WHERE alumno_id = ?
            ');
            $stmt->execute([$alumno_id]);
            
            // Si hay 5+ intentos fallidos, bloquear
            $stmt = $pdo->prepare('
                SELECT intentos_fallidos FROM biometric_registrations WHERE alumno_id = ?
            ');
            $stmt->execute([$alumno_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['intentos_fallidos'] >= 5) {
                $stmt = $pdo->prepare('UPDATE biometric_registrations SET bloqueado = 1 WHERE alumno_id = ?');
                $stmt->execute([$alumno_id]);
            }
            
            BiometricUtils::logBiometricAttempt($pdo, $alumno_id, 'autenticacion', 'fallido', 'Huella no coincide', $dispositivo_id);
            ApiResponse::error('La huella no coincide. Intenta de nuevo.', 401);
        }
        
        // Huella válida - Marcar asistencia
        $fecha_hoy = date('Y-m-d');
        $hora_actual = date('H:i:s');
        
        // Verificar si ya existe asistencia hoy
        $stmt = $pdo->prepare('
            SELECT id, estado FROM asistencias 
            WHERE alumno_id = ? AND fecha = ?
        ');
        $stmt->execute([$alumno_id, $fecha_hoy]);
        $asistencia_existente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($asistencia_existente) {
            // Actualizar asistencia existente
            $estado = 'Presente';
            $stmt = $pdo->prepare('
                UPDATE asistencias 
                SET estado = ?, observacion = ?
                WHERE id = ?
            ');
            $stmt->execute([$estado, 'Marcado por huella digital a ' . $hora_actual, $asistencia_existente['id']]);
            $asistencia_id = $asistencia_existente['id'];
        } else {
            // Insertar nueva asistencia
            $estado = 'Presente';
            $stmt = $pdo->prepare('
                INSERT INTO asistencias(alumno_id, fecha, estado, observacion)
                VALUES (?, ?, ?, ?)
            ');
            $stmt->execute([$alumno_id, $fecha_hoy, $estado, 'Marcado por huella digital a ' . $hora_actual]);
            $asistencia_id = $pdo->lastInsertId();
        }
        
        // Resetear intentos fallidos y actualizar último acceso
        $stmt = $pdo->prepare('
            UPDATE biometric_registrations 
            SET intentos_fallidos = 0, ultimo_acceso = NOW()
            WHERE alumno_id = ?
        ');
        $stmt->execute([$alumno_id]);
        
        BiometricUtils::logBiometricAttempt($pdo, $alumno_id, 'asistencia', 'exitoso', 'Huella válida', $dispositivo_id);
        
        ApiResponse::success([
            'asistencia_id' => $asistencia_id,
            'alumno_nombre' => $alumno['nombre'],
            'alumno_id' => $alumno_id,
            'hora' => $hora_actual,
            'estado' => $estado,
            'curso' => $alumno['curso_nombre'] ?? 'N/A',
            'fecha' => $fecha_hoy,
            'mensaje' => '✅ ¡Asistencia registrada exitosamente!'
        ], 'Asistencia registrada correctamente');
        
    } catch (Exception $e) {
        ApiResponse::error('Error al autenticar: ' . $e->getMessage(), 500);
    }
}

/**
 * ENDPOINT 4: Verificar estado de token
 * 
 * GET /api/biometric.php?action=status&token=XXX
 * 
 * Response:
 * {
 *   "success": true,
 *   "data": {
 *     "token_valido": true,
 *     "estado": "activo",
 *     "expira_en_segundos": 250
 *   }
 * }
 */
function checkTokenStatus() {
    global $pdo;
    
    $token = $_GET['token'] ?? null;
    
    if (!$token) {
        ApiResponse::error('Token no especificado', 400);
    }
    
    try {
        $validation = BiometricUtils::validateToken($pdo, $token);
        
        if (!$validation['valid']) {
            ApiResponse::success([
                'token_valido' => false,
                'razon' => $validation['reason']
            ]);
        }
        
        $data = $validation['data'];
        $expiracion = strtotime($data['fecha_expiracion']);
        $ahora = time();
        $segundos_restantes = max(0, $expiracion - $ahora);
        
        ApiResponse::success([
            'token_valido' => true,
            'estado' => $data['estado'],
            'codigo_registro' => $data['codigo_registro'],
            'expira_en_segundos' => $segundos_restantes,
            'alumno_id' => $data['alumno_id']
        ]);
        
    } catch (Exception $e) {
        ApiResponse::error('Error: ' . $e->getMessage(), 500);
    }
}
?>
