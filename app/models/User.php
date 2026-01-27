<?php
// app/models/User.php - Modelo de Usuario

require_once __DIR__ . '/../../config/Database.php';

class User {
    private $db;
    private $table = 'usuarios';
    
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol;
    public $fecha_registro;
    public $activo;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener usuario por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener usuario por email
     */
    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Crear nuevo usuario
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (nombre, email, password, rol, fecha_registro, activo) 
            VALUES (:nombre, :email, :password, :rol, NOW(), 1)
        ");
        
        return $stmt->execute([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'rol' => $data['rol']
        ]);
    }
    
    /**
     * Actualizar usuario
     */
    public function update($id, $data) {
        $fields = [];
        $params = ['id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $key !== 'fecha_registro') {
                $fields[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Eliminar usuario (soft delete)
     */
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET activo = 0 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Obtener todos los usuarios por rol
     */
    public function getByRole($rol) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE rol = :rol AND activo = 1");
        $stmt->execute(['rol' => $rol]);
        return $stmt->fetchAll();
    }
    
    /**
     * Verificar credenciales
     */
    public function verifyCredentials($email, $password) {
        $user = $this->getByEmail($email);
        
        if ($user && password_verify($password, $user['password']) && $user['activo'] == 1) {
            return $user;
        }
        
        return false;
    }
}
