<?php
/**
 * Funciones para exportar datos a diferentes formatos (CSV, HTML, etc)
 */

require_once __DIR__ . '/../conexion.php';

/**
 * Exporta asistencias a CSV
 */
function export_asistencias_csv($curso_id = null, $fecha_desde = null, $fecha_hasta = null) {
    global $pdo;
    
    try {
        $query = "
            SELECT 
                a.nombre,
                a.documento,
                c.nombre as curso,
                ast.fecha,
                ast.estado,
                ast.observacion
            FROM asistencias ast
            JOIN alumnos a ON ast.alumno_id = a.id
            JOIN cursos c ON a.curso_id = c.id
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($curso_id) {
            $query .= " AND c.id = ?";
            $params[] = $curso_id;
        }
        
        if ($fecha_desde) {
            $query .= " AND ast.fecha >= ?";
            $params[] = $fecha_desde;
        }
        
        if ($fecha_hasta) {
            $query .= " AND ast.fecha <= ?";
            $params[] = $fecha_hasta;
        }
        
        $query .= " ORDER BY ast.fecha DESC, a.nombre ASC";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $datos;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Exporta justificativos a CSV
 */
function export_justificativos_csv($curso_id = null, $estado = null, $fecha_desde = null, $fecha_hasta = null) {
    global $pdo;
    
    try {
        $query = "
            SELECT 
                a.nombre,
                a.documento,
                c.nombre as curso,
                j.fecha,
                j.motivo,
                j.documento as archivo_documento,
                j.estado
            FROM justificativos j
            JOIN alumnos a ON j.alumno_id = a.id
            JOIN cursos c ON a.curso_id = c.id
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($curso_id) {
            $query .= " AND c.id = ?";
            $params[] = $curso_id;
        }
        
        if ($estado) {
            $query .= " AND j.estado = ?";
            $params[] = $estado;
        }
        
        if ($fecha_desde) {
            $query .= " AND j.fecha >= ?";
            $params[] = $fecha_desde;
        }
        
        if ($fecha_hasta) {
            $query .= " AND j.fecha <= ?";
            $params[] = $fecha_hasta;
        }
        
        $query .= " ORDER BY j.fecha DESC, a.nombre ASC";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $datos;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Exporta alumnos a CSV
 */
function export_alumnos_csv($curso_id = null) {
    global $pdo;
    
    try {
        $query = "
            SELECT 
                a.nombre,
                a.documento,
                c.nombre as curso,
                a.email,
                a.telefono,
                a.estado
            FROM alumnos a
            JOIN cursos c ON a.curso_id = c.id
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($curso_id) {
            $query .= " AND a.curso_id = ?";
            $params[] = $curso_id;
        }
        
        $query .= " ORDER BY c.nombre, a.nombre ASC";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $datos;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Descarga datos como CSV
 */
function download_csv($datos, $nombre_archivo) {
    if (empty($datos)) {
        return false;
    }
    
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=\"$nombre_archivo.csv\"");
    
    $output = fopen('php://output', 'w');
    
    // BOM para UTF-8 en Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Encabezados
    fputcsv($output, array_keys($datos[0]), ';');
    
    // Datos
    foreach ($datos as $fila) {
        fputcsv($output, $fila, ';');
    }
    
    fclose($output);
    return true;
}

/**
 * Genera HTML para previsualización de reportes
 */
function generate_reporte_html($tipo, $datos, $titulo) {
    if (empty($datos)) {
        return '<div class="alert alert-warning">No hay datos para mostrar</div>';
    }
    
    $html = '<div class="table-responsive"><table class="table table-striped table-sm">';
    $html .= '<thead class="table-dark"><tr>';
    
    // Encabezados
    foreach (array_keys($datos[0]) as $columna) {
        $html .= '<th>' . htmlspecialchars($columna) . '</th>';
    }
    $html .= '</tr></thead><tbody>';
    
    // Datos
    foreach ($datos as $fila) {
        $html .= '<tr>';
        foreach ($fila as $valor) {
            $html .= '<td>' . htmlspecialchars($valor) . '</td>';
        }
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table></div>';
    
    return $html;
}
?>
