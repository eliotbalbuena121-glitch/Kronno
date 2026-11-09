<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/auth.php';

function e($v) { 
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); 
}

function flash($message, $type = 'success') { 
    $_SESSION['flash'] = ['message' => $message, 'type' => $type]; 
}

function redirect($url) { 
    header("Location: $url"); 
    exit; 
}

function get_flash() { 
    $f = $_SESSION['flash'] ?? null; 
    unset($_SESSION['flash']); 
    return $f; 
}
?>