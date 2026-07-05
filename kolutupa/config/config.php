<?php
// config/config.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'kolutupa');
define('DB_CHARSET', 'utf8mb4');

define('BASE_URL', 'http://localhost/kolutupa/public/');
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}
define('UPLOAD_PATH', BASE_PATH . '/public/uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

define('APP_NAME', 'KOLUTUPA');
define('APP_VERSION', '1.0.0');
define('SESSION_NAME', 'kolutupa_sess');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
