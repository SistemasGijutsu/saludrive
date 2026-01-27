<?php
// config/config.php - Configuración principal de la aplicación

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'saludrive');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'SaluDrive');
define('APP_URL', 'http://localhost:8080/saludrive');
define('BASE_PATH', dirname(__DIR__));

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Mostrar errores (desactivar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de seguridad
define('SECRET_KEY', 'tu_clave_secreta_aqui_cambiar_en_produccion');
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);

// Rutas de la aplicación
define('CONTROLLERS_PATH', BASE_PATH . '/app/controllers/');
define('MODELS_PATH', BASE_PATH . '/app/models/');
define('VIEWS_PATH', BASE_PATH . '/app/views/');

// Autoload simple
spl_autoload_register(function($className) {
    $paths = [
        CONTROLLERS_PATH . $className . '.php',
        MODELS_PATH . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
