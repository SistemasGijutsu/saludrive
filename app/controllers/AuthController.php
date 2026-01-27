<?php
// app/controllers/AuthController.php - Controlador de autenticación

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/Database.php';

class AuthController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Mostrar formulario de login
     */
    public function showLogin() {
        require_once VIEWS_PATH . 'auth/login.php';
    }
    
    /**
     * Procesar login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/public/');
            exit;
        }
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor, completa todos los campos';
            header('Location: ' . APP_URL . '/public/');
            exit;
        }
        
        try {
            // Buscar usuario en la base de datos
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email AND activo = 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login exitoso
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['rol'];
                $_SESSION['user_name'] = $user['nombre'];
                
                // Redirigir según el rol
                if ($user['rol'] === 'paciente') {
                    header('Location: ' . APP_URL . '/routes/router.php?action=dashboard_paciente');
                } else {
                    header('Location: ' . APP_URL . '/routes/router.php?action=dashboard_profesional');
                }
                exit;
            } else {
                $_SESSION['error'] = 'Credenciales incorrectas';
                header('Location: ' . APP_URL . '/public/');
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error en el sistema. Intenta nuevamente.';
            header('Location: ' . APP_URL . '/public/');
            exit;
        }
    }
    
    /**
     * Mostrar formulario de registro
     */
    public function showRegister() {
        $role = $_GET['role'] ?? 'paciente';
        require_once VIEWS_PATH . 'auth/register.php';
    }
    
    /**
     * Procesar registro
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/public/');
            exit;
        }
        
        $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $rol = isset($_POST['rol']) ? trim($_POST['rol']) : '';
        
        // Validaciones
        if (empty($nombre) || empty($email) || empty($password) || empty($rol)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            header('Location: ' . APP_URL . '/routes/router.php?action=register&role=' . $rol);
            exit;
        }
        
        if ($password !== $password_confirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
            header('Location: ' . APP_URL . '/routes/router.php?action=register&role=' . $rol);
            exit;
        }
        
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
            header('Location: ' . APP_URL . '/routes/router.php?action=register&role=' . $rol);
            exit;
        }
        
        try {
            // Verificar si el email ya existe
            $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'El correo electrónico ya está registrado';
                header('Location: ' . APP_URL . '/routes/router.php?action=register&role=' . $rol);
                exit;
            }
            
            // Crear usuario
            $hashedPassword = password_hash($password, PASSWORD_HASH_ALGO);
            $stmt = $this->db->prepare("
                INSERT INTO usuarios (nombre, email, password, rol, fecha_registro, activo) 
                VALUES (:nombre, :email, :password, :rol, NOW(), 1)
            ");
            
            $stmt->execute([
                'nombre' => $nombre,
                'email' => $email,
                'password' => $hashedPassword,
                'rol' => $rol
            ]);
            
            $_SESSION['success'] = 'Registro exitoso. Por favor, inicia sesión.';
            header('Location: ' . APP_URL . '/public/');
            exit;
            
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al registrar usuario. Intenta nuevamente.';
            header('Location: ' . APP_URL . '/routes/router.php?action=register&role=' . $rol);
            exit;
        }
    }
    
    /**
     * Procesar registro de profesional con documentos
     */
    public function registerProfessional() {
        // Capturar todos los errores y warnings
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }
        
        try {
            // Validar campos requeridos
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
            $genero = isset($_POST['genero']) ? trim($_POST['genero']) : '';
            $edad = isset($_POST['edad']) ? intval($_POST['edad']) : 0;
            $ciudad = isset($_POST['ciudad']) ? trim($_POST['ciudad']) : '';
            $medio_transporte = isset($_POST['medio_transporte']) ? trim($_POST['medio_transporte']) : '';
            $especialidad_id = isset($_POST['especialidad_id']) ? intval($_POST['especialidad_id']) : 0;
            $acepta_terminos = isset($_POST['acepta_terminos']) ? intval($_POST['acepta_terminos']) : 0;
            
            // Log para debug
            error_log("Registro profesional - Datos recibidos: nombre=$nombre, email=$email, cedula=$cedula");
            
            // Validaciones básicas
            if (empty($nombre) || empty($email) || empty($password) || empty($cedula)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados']);
                exit;
            }
            
            if (!$acepta_terminos) {
                echo json_encode(['success' => false, 'message' => 'Debes aceptar los términos y condiciones']);
                exit;
            }
            
            // Verificar si el email ya existe
            $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado']);
                exit;
            }
            
            // Crear directorio para archivos si no existe
            $uploadDir = BASE_PATH . '/uploads/profesionales/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Debug: Log archivos recibidos
            error_log("Archivos recibidos: " . json_encode(array_keys($_FILES)));
            
            // Procesar archivos subidos
            $archivos = [];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'application/pdf'];
            
            foreach ($_FILES as $key => $file) {
                error_log("Procesando archivo: $key, error: {$file['error']}, type: {$file['type']}");
                
                if ($file['error'] === UPLOAD_ERR_OK) {
                    if (!in_array($file['type'], $allowedTypes)) {
                        error_log("Tipo de archivo no permitido: {$file['type']}");
                        echo json_encode(['success' => false, 'message' => "Tipo de archivo no permitido: {$file['type']}"]);
                        exit;
                    }
                    
                    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = uniqid() . '_' . time() . '.' . $extension;
                    $filepath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $filepath)) {
                        $archivos[$key] = $filename;
                        error_log("Archivo guardado: $key => $filename");
                    } else {
                        error_log("Error al mover archivo: $key");
                    }
                } else {
                    error_log("Error en archivo $key: {$file['error']}");
                }
            }
            
            error_log("Archivos procesados: " . json_encode($archivos));
            
            // Iniciar transacción
            $this->db->beginTransaction();
            
            // Crear usuario
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("
                INSERT INTO usuarios (nombre, email, password, rol, genero, edad, ciudad, foto_perfil, fecha_registro, activo, estado_cuenta) 
                VALUES (:nombre, :email, :password, 'profesional', :genero, :edad, :ciudad, :foto_perfil, NOW(), 1, 'pendiente_verificacion')
            ");
            
            $stmt->execute([
                'nombre' => $nombre,
                'email' => $email,
                'password' => $hashedPassword,
                'genero' => $genero,
                'edad' => $edad,
                'ciudad' => $ciudad,
                'foto_perfil' => $archivos['foto_perfil'] ?? null
            ]);
            
            $userId = $this->db->lastInsertId();
            
            // Crear perfil profesional
            $stmt = $this->db->prepare("
                INSERT INTO profesionales (
                    usuario_id, especialidad_id, cedula, medio_transporte,
                    foto_documento_identidad, foto_tarjeta_profesional, selfie_con_tarjeta,
                    documento_adicional_1, documento_adicional_2, documento_adicional_3,
                    acepta_terminos, fecha_acepta_terminos, estado_verificacion, verificado
                ) VALUES (
                    :usuario_id, :especialidad_id, :cedula, :medio_transporte,
                    :foto_documento_identidad, :foto_tarjeta_profesional, :selfie_con_tarjeta,
                    :documento_adicional_1, :documento_adicional_2, :documento_adicional_3,
                    1, NOW(), 'pendiente', 0
                )
            ");
            
            $stmt->execute([
                'usuario_id' => $userId,
                'especialidad_id' => $especialidad_id,
                'cedula' => $cedula,
                'medio_transporte' => $medio_transporte ?? 'ninguno',
                'foto_documento_identidad' => $archivos['foto_documento_identidad'] ?? null,
                'foto_tarjeta_profesional' => $archivos['foto_tarjeta_profesional'] ?? null,
                'selfie_con_tarjeta' => $archivos['selfie_con_tarjeta'] ?? null,
                'documento_adicional_1' => $archivos['documento_adicional_1'] ?? null,
                'documento_adicional_2' => $archivos['documento_adicional_2'] ?? null,
                'documento_adicional_3' => $archivos['documento_adicional_3'] ?? null
            ]);
            
            $this->db->commit();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Registro exitoso. Tu cuenta está pendiente de verificación.'
            ]);
            
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error PDO en registro: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error en registro: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al registrar: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        session_start();
        session_destroy();
        header('Location: ' . APP_URL . '/public/');
        exit;
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    public function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
}
