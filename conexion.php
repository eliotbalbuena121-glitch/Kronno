<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// CARGAR VARIABLES DE ENTORNO
// ============================================

// Si existe .env en desarrollo local, cargar variables
if (file_exists(__DIR__ . '/.env')) {
    $env_vars = parse_ini_file(__DIR__ . '/.env');
    foreach ($env_vars as $key => $value) {
        if (!getenv($key)) {
            putenv("$key=$value");
        }
    }
}

// ============================================
// OBTENER CREDENCIALES DE BD
// ============================================
// Prioridad: ENV de sistema → .env → valores por defecto

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_port = getenv('DB_PORT') ?: '3306';
$db_name = getenv('DB_NAME') ?: 'kronno2026';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: (getenv('DB_PASSWORD') ?: '');
$app_env = getenv('APP_ENV') ?: 'development';

$db_charset = 'utf8mb4';

// ============================================
// CONECTAR A BASE DE DATOS
// ============================================

try {
    // Construir DSN con timeout para conexiones remotas
    $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset={$db_charset}";
    
    // Opciones de PDO con SSL/TLS habilitado para TiDB Cloud
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        PDO::ATTR_TIMEOUT => 10,
        // SSL/TLS para TiDB Cloud (REQUERIDO)
        PDO::MYSQL_ATTR_SSL_CA => true,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
    
    $conexion = new PDO(
        $dsn,
        $db_user,
        $db_pass,
        $options
    );
    
    $pdo = $conexion;
    
} catch (PDOException $e) {
    // Determinar si es localhost o servidor remoto para el mensaje
    $is_local = (strpos($db_host, 'localhost') !== false || $db_host === '127.0.0.1');
    $host_display = $is_local ? 'LOCALHOST' : $db_host;
    
    if ($app_env === 'development') {
        // ✅ EN DESARROLLO: mostrar detalles completos
        echo "
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: Arial; margin: 20px; background: #f5f5f5; }
                .error-box { 
                    background: #ffe6e6; 
                    border: 2px solid #ff0000; 
                    padding: 20px; 
                    border-radius: 5px;
                    max-width: 600px;
                }
                h2 { color: #cc0000; }
                .config { 
                    background: #f0f0f0; 
                    padding: 10px; 
                    border-left: 3px solid #ff9999;
                    margin: 10px 0;
                }
                .error-detail { 
                    background: #fff3cd; 
                    padding: 10px; 
                    border-left: 3px solid #ffc107;
                    margin: 10px 0;
                    font-family: monospace;
                }
                .solution { 
                    background: #d4edda; 
                    padding: 10px; 
                    border-left: 3px solid #28a745;
                    margin: 5px 0;
                }
            </style>
        </head>
        <body>
        <div class='error-box'>
            <h2>❌ Error de Conexión a Base de Datos</h2>
            
            <h3>📋 Configuración Actual:</h3>
            <div class='config'>
                <strong>Host:</strong> {$host_display}:{$db_port}<br>
                <strong>Base de datos:</strong> {$db_name}<br>
                <strong>Usuario:</strong> {$db_user}<br>
                <strong>Ambiente:</strong> {$app_env}
            </div>
            
            <h3>⚠️ Error Detallado:</h3>
            <div class='error-detail'>
                {$e->getMessage()}
            </div>
            
            <h3>✅ Soluciones:</h3>";
            
            if ($is_local) {
                echo "
                <div class='solution'>
                    <strong>Para DESARROLLO LOCAL (XAMPP):</strong><br>
                    1. Verifica que MySQL está ejecutándose<br>
                    2. Verifica credenciales en <code>.env</code><br>
                    3. Verifica que la BD 'kronno2026' existe<br>
                    4. Prueba: <code>mysql -u root -p kronno2026</code>
                </div>";
            } else {
                echo "
                <div class='solution'>
                    <strong>Para PRODUCCIÓN (Render/Cloud):</strong><br>
                    1. Verifica variables de entorno en panel Render:<br>
                       • DB_HOST: {$host_display}<br>
                       • DB_PORT: {$db_port}<br>
                       • DB_NAME: {$db_name}<br>
                       • DB_USER: {$db_user}<br>
                    2. Verifica que la BD existe en servidor MySQL<br>
                    3. Verifica que las credenciales son correctas<br>
                    4. Verifica firewall/security groups<br>
                    5. Prueba conexión: <code>mysql -h {$db_host} -u {$db_user} -p</code>
                </div>";
            }
            
            echo "
            <h3>📖 Variables de Entorno (.env):</h3>
            <div class='error-detail'>
                DB_HOST={$db_host}<br>
                DB_PORT={$db_port}<br>
                DB_NAME={$db_name}<br>
                DB_USER={$db_user}<br>
                DB_PASS=***<br>
                APP_ENV={$app_env}
            </div>
        </div>
        </body>
        </html>";
    } else {
        // ❌ EN PRODUCCIÓN: no mostrar detalles sensibles
        die('❌ Error de conexión a la base de datos. El equipo de soporte ha sido notificado.');
    }
    
    exit;
}
?>
