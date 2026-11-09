<?php
session_start();
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

logout_user();
redirect('login.php');
?>
