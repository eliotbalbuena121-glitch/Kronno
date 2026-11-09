<?php
/**
 * Script de Configuración Inicial
 * Ejecuta el SQL para crear la base de datos y tablas
 */

$servidor = "localhost";
$usuario = "root";
$password = "";

try {
    // Conexión inicial sin seleccionar BD
    $pdo = new PDO("mysql:host=$servidor;charset=utf8mb4", $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Lee el archivo SQL
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // Ejecuta cada sentencia SQL por separado
    $statements = explode(';', $sql);
    foreach ($statements as $statement) {
        $trimmed = trim($statement);
        if (!empty($trimmed)) {
            $pdo->exec($trimmed . ';');
        }
    }
    
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Setup KRONO 2026</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container py-5'>
            <div class='row justify-content-center'>
                <div class='col-lg-8'>
                    <div class='card border-0 shadow'>
                        <div class='card-body p-5'>
                            <div class='alert alert-success d-flex align-items-center' role='alert'>
                                <i class='fa-solid fa-check-circle me-3' style='font-size: 2rem;'></i>
                                <div>
                                    <h4 class='alert-heading mb-2'>¡Configuración Exitosa!</h4>
                                    <p class='mb-0'>La base de datos ha sido creada correctamente.</p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h5 class='fw-bold mb-3'>✅ Se han creado:</h5>
                            <ul>
                                <li>Base de datos: <code>kronno2026</code></li>
                                <li>Tabla: <code>cursos</code> (10 registros)</li>
                                <li>Tabla: <code>profesores</code> (10 registros)</li>
                                <li>Tabla: <code>alumnos</code> (10 registros)</li>
                                <li>Tabla: <code>asistencias</code> (10 registros)</li>
                                <li>Tabla: <code>justificativos</code> (10 registros)</li>
                                <li>Tabla: <code>usuarios</code> (4 usuarios de prueba)</li>
                            </ul>
                            
                            <hr>
                            
                            <h5 class='fw-bold mb-3'>🔐 Cuentas de Prueba:</h5>
                            <div class='row mb-3'>
                                <div class='col-md-6'>
                                    <strong>Profesores:</strong>
                                    <p>
                                        <code>eliot@krono.edu.py</code><br>
                                        <code>giovanna@krono.edu.py</code>
                                    </p>
                                </div>
                                <div class='col-md-6'>
                                    <strong>Alumnos:</strong>
                                    <p>
                                        <code>eliot.alumno@correo.com</code><br>
                                        <code>giovanna.alumno@correo.com</code>
                                    </p>
                                </div>
                            </div>
                            <p class='small text-muted'>Contraseña: <code>cualquier</code> (en modo desarrollo)</p>
                            
                            <hr>
                            
                            <div class='d-grid gap-2'>
                                <a href='login.php' class='btn btn-primary btn-lg'>
                                    <i class='fa-solid fa-arrow-right me-2'></i>Ir a Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>";
    
} catch (PDOException $e) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error en Setup</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container py-5'>
            <div class='row justify-content-center'>
                <div class='col-lg-8'>
                    <div class='card border-0 shadow'>
                        <div class='card-body p-5'>
                            <div class='alert alert-danger' role='alert'>
                                <h4 class='alert-heading'>
                                    <i class='fa-solid fa-circle-exclamation me-2'></i>Error en la Configuración
                                </h4>
                                <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                                <hr>
                                <p class='mb-0'><strong>Soluciones:</strong></p>
                                <ul>
                                    <li>Verifica que MySQL esté corriendo</li>
                                    <li>Comprueba el usuario y contraseña en conexion.php</li>
                                    <li>Asegúrate de tener permisos para crear bases de datos</li>
                                    <li>Intenta conectar a MySQL desde otro cliente para verificar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>";
}
?>
