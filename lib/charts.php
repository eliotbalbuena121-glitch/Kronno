<?php
/**
 * Funciones para generar datos para gráficos del dashboard
 */

require_once __DIR__ . '/../conexion.php';

/**
 * Obtiene asistencias agrupadas por fecha (últimos 30 días)
 */
function get_asistencia_por_fecha() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                fecha,
                COUNT(CASE WHEN estado = 'Presente' THEN 1 END) as presentes,
                COUNT(CASE WHEN estado = 'Ausente' THEN 1 END) as ausentes,
                COUNT(CASE WHEN estado = 'Tardanza' THEN 1 END) as tardanzas,
                COUNT(*) as total
            FROM asistencias
            WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY fecha
            ORDER BY fecha ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Obtiene distribución general de asistencias
 */
function get_distribucion_asistencias() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                estado,
                COUNT(*) as total,
                ROUND(COUNT(*) * 100 / (SELECT COUNT(*) FROM asistencias), 1) as porcentaje
            FROM asistencias
            GROUP BY estado
            ORDER BY total DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Obtiene alumnos con asistencia crítica (menos del 80%)
 */
function get_alumnos_asistencia_critica() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                a.id,
                a.nombre,
                a.documento,
                c.nombre as curso,
                COUNT(ast.id) as total_registros,
                COUNT(CASE WHEN ast.estado = 'Presente' THEN 1 END) as presentes,
                ROUND(COUNT(CASE WHEN ast.estado = 'Presente' THEN 1 END) * 100 / COUNT(ast.id), 1) as porcentaje_asistencia
            FROM alumnos a
            JOIN cursos c ON a.curso_id = c.id
            LEFT JOIN asistencias ast ON a.id = ast.alumno_id
            GROUP BY a.id, a.nombre, a.documento, c.nombre
            HAVING porcentaje_asistencia < 80
            ORDER BY porcentaje_asistencia ASC
            LIMIT 10
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Obtiene estadísticas generales del sistema
 */
function get_estadisticas_generales() {
    global $pdo;
    
    try {
        $stats = [];
        
        // Total de alumnos
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM alumnos");
        $stats['total_alumnos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        // Total de cursos
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM cursos WHERE activo = 1");
        $stats['total_cursos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        // Total de profesores
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM profesores");
        $stats['total_profesores'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        // Asistencias registradas hoy
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM asistencias WHERE fecha = CURDATE()");
        $stats['asistencias_hoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        // Justificativos pendientes
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM justificativos WHERE estado = 'Pendiente'");
        $stats['justificativos_pendientes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        return $stats;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Obtiene asistencia agrupada por curso
 */
function get_asistencia_por_curso() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("
            SELECT 
                c.nombre,
                COUNT(DISTINCT a.id) as total_alumnos,
                COUNT(ast.id) as total_registros,
                COUNT(CASE WHEN ast.estado = 'Presente' THEN 1 END) as presentes,
                COUNT(CASE WHEN ast.estado = 'Ausente' THEN 1 END) as ausentes,
                COUNT(CASE WHEN ast.estado = 'Tardanza' THEN 1 END) as tardanzas,
                ROUND(COUNT(CASE WHEN ast.estado = 'Presente' THEN 1 END) * 100 / COUNT(ast.id), 1) as porcentaje_asistencia
            FROM cursos c
            LEFT JOIN alumnos a ON c.id = a.curso_id
            LEFT JOIN asistencias ast ON a.id = ast.alumno_id
            WHERE c.activo = 1
            GROUP BY c.id, c.nombre
            ORDER BY porcentaje_asistencia DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}
?>
