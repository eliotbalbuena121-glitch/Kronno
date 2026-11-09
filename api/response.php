<?php
/**
 * Clase para respuestas JSON estándar de la API
 */
class ApiResponse {
    
    public static function success($data = null, $message = 'Operación exitosa', $statusCode = 200) {
        self::setHeaders();
        http_response_code($statusCode);
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('c')
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    public static function error($message = 'Error en la operación', $statusCode = 400, $data = null) {
        self::setHeaders();
        http_response_code($statusCode);
        echo json_encode([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('c')
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    public static function unauthorized($message = 'No autorizado') {
        self::error($message, 401);
    }
    
    public static function forbidden($message = 'Acceso denegado') {
        self::error($message, 403);
    }
    
    public static function notFound($message = 'Recurso no encontrado') {
        self::error($message, 404);
    }
    
    private static function setHeaders() {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }
}
?>
