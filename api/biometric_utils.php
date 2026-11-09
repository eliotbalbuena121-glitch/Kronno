<?php
/**
 * Utilidades para manejo de biometría
 */
class BiometricUtils {
    
    /**
     * Genera un código único de registro (ej: ABC-1234-XYZ)
     */
    public static function generateRegistrationCode() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $code .= $chars[rand(0, strlen($chars) - 1)];
            }
            if ($i < 2) $code .= '-';
        }
        
        return $code;
    }
    
    /**
     * Genera un token seguro para comunicación API
     */
    public static function generateToken($length = 64) {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Hash de huella digital (simula procesamiento biométrico)
     * En producción, usarías bibliotecas especializadas de biometría
     */
    public static function generateFingerprintHash($fingerprintData) {
        // Convertir base64 a datos binarios
        $binaryData = base64_decode($fingerprintData, true);
        
        if ($binaryData === false) {
            return null;
        }
        
        // Usar SHA-256 para generar hash
        return hash('sha256', $binaryData);
    }
    
    /**
     * Comparar dos huellas digitales (similarity score)
     * En producción, usarías bibliotecas especializadas
     * Este es un ejemplo simplificado
     */
    public static function compareFingerprintTemplates($template1, $template2, $threshold = 0.95) {
        // En una solución real, aquí usarías matching algorithms
        // especializados como SIFT, SURF, o bibliotecas como VeriFinger
        
        // Para demo, comparamos los hashes directamente
        $hash1 = hash('sha256', $template1);
        $hash2 = hash('sha256', $template2);
        
        // Similitud simple (en producción sería más sofisticado)
        $similarity = $hash1 === $hash2 ? 1.0 : 0.0;
        
        return [
            'match' => $similarity >= $threshold,
            'score' => $similarity,
            'threshold' => $threshold
        ];
    }
    
    /**
     * Validar que el token sea válido y no esté expirado
     */
    public static function validateToken($pdo, $token) {
        try {
            $stmt = $pdo->prepare('
                SELECT id, alumno_id, codigo_registro, estado, fecha_expiracion
                FROM biometric_tokens 
                WHERE token = ? AND estado IN ("activo", "usado")
            ');
            $stmt->execute([$token]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return ['valid' => false, 'reason' => 'Token no encontrado'];
            }
            
            if ($result['estado'] === 'usado') {
                return ['valid' => false, 'reason' => 'Token ya utilizado'];
            }
            
            if ($result['fecha_expiracion'] && strtotime($result['fecha_expiracion']) < time()) {
                return ['valid' => false, 'reason' => 'Token expirado'];
            }
            
            return ['valid' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['valid' => false, 'reason' => $e->getMessage()];
        }
    }
    
    /**
     * Registrar intento biométrico (para auditoría)
     */
    public static function logBiometricAttempt($pdo, $alumno_id, $tipo, $resultado, $razon = null, $dispositivo_id = null) {
        try {
            $stmt = $pdo->prepare('
                INSERT INTO biometric_attempts(alumno_id, tipo, resultado, razon_fallo, ip_address, dispositivo_id)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
            $stmt->execute([$alumno_id, $tipo, $resultado, $razon, $ip, $dispositivo_id]);
            
            return true;
        } catch (Exception $e) {
            error_log('Error logging biometric attempt: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener IP del cliente (considera proxies)
     */
    public static function getClientIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        }
        
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : 'desconocida';
    }
    
    /**
     * Sanitizar entrada JSON
     */
    public static function getSafeInput($key, $default = null) {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true) ?? [];
        
        $value = $data[$key] ?? $default;
        
        // Validar que sea string o array
        if (is_string($value)) {
            return trim($value);
        }
        
        return $value;
    }
}
?>
