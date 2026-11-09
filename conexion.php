<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "kronno2026";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8mb4", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo = $conexion;
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>