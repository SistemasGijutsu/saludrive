<?php
// routes/router.php - Enrutador principal de la aplicación

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener la acción solicitada
$action = $_GET['action'] ?? 'home';

// Instanciar controlador de autenticación
$authController = new AuthController();

// Enrutamiento
switch ($action) {
    case 'home':
        header('Location: ' . APP_URL . '/public/');
        break;
        
    case 'sendCode':
        $authController->sendVerificationCode();
        break;
        
    case 'verifyCode':
        $authController->verifyCode();
        break;
        
    case 'login':
        $authController->login();
        break;
        
    case 'register':
        $role = $_GET['role'] ?? 'paciente';
        if ($role === 'profesional') {
            require_once VIEWS_PATH . 'auth/register_profesional.php';
        } else {
            $authController->showRegister();
        }
        break;
        
    case 'check_existing_user':
        $authController->checkExistingUser();
        break;
        
    case 'register_process':
        $rol = $_POST['rol'] ?? 'paciente';
        if ($rol === 'profesional') {
            $authController->registerProfessional();
        } else {
            $authController->register();
        }
        break;
        
    case 'existing_user':
        require_once VIEWS_PATH . 'auth/existing_user.php';
        break;
        
    case 'logout':
        $authController->logout();
        break;
        
    case 'dashboard_paciente':
        if (!$authController->isAuthenticated()) {
            header('Location: ' . APP_URL . '/public/');
            exit;
        }
        require_once VIEWS_PATH . 'dashboard/paciente.php';
        break;
        
    case 'dashboard_profesional':
        if (!$authController->isAuthenticated()) {
            header('Location: ' . APP_URL . '/public/');
            exit;
        }
        require_once VIEWS_PATH . 'dashboard/profesional.php';
        break;
        
    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
