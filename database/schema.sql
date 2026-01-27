-- Script SQL para crear la base de datos de SaluDrive
-- Ejecutar este script en phpMyAdmin (http://localhost:8080/phpmyadmin/)

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS saludrive CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE saludrive;

-- Tabla de usuarios (base para pacientes y profesionales)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('paciente', 'profesional') NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1,
    telefono VARCHAR(20),
    fecha_nacimiento DATE,
    edad INT,
    genero ENUM('masculino', 'femenino', 'otro'),
    ciudad VARCHAR(100),
    direccion TEXT,
    foto_perfil VARCHAR(255),
    estado_cuenta ENUM('activo', 'pendiente_verificacion', 'suspendido', 'rechazado') DEFAULT 'activo',
    INDEX idx_email (email),
    INDEX idx_rol (rol),
    INDEX idx_estado (estado_cuenta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de especialidades (para profesionales)
CREATE TABLE IF NOT EXISTS especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de perfiles profesionales (verificación completa tipo Indriver)
CREATE TABLE IF NOT EXISTS profesionales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    especialidad_id INT,
    cedula VARCHAR(50) NOT NULL,
    tarjeta_profesional VARCHAR(50),
    medio_transporte ENUM('motocicleta', 'automovil', 'ninguno') DEFAULT 'ninguno',
    años_experiencia INT,
    tarifa_consulta DECIMAL(10,2),
    descripcion TEXT,
    -- Documentos para verificación
    foto_documento_identidad VARCHAR(255),
    foto_tarjeta_profesional VARCHAR(255),
    selfie_con_tarjeta VARCHAR(255),
    documento_adicional_1 VARCHAR(255),
    documento_adicional_2 VARCHAR(255),
    documento_adicional_3 VARCHAR(255),
    -- Estado de verificación
    verificado TINYINT(1) DEFAULT 0,
    estado_verificacion ENUM('pendiente', 'en_revision', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    fecha_verificacion DATETIME,
    notas_verificacion TEXT,
    verificado_por INT,
    -- Términos y condiciones
    acepta_terminos TINYINT(1) DEFAULT 0,
    fecha_acepta_terminos DATETIME,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (especialidad_id) REFERENCES especialidades(id) ON DELETE SET NULL,
    INDEX idx_verificacion (estado_verificacion),
    INDEX idx_cedula (cedula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de citas
CREATE TABLE IF NOT EXISTS citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    profesional_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    duracion INT DEFAULT 30, -- en minutos
    estado ENUM('pendiente', 'confirmada', 'completada', 'cancelada') DEFAULT 'pendiente',
    motivo TEXT,
    notas TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (profesional_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_fecha (fecha_hora),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de historiales médicos
CREATE TABLE IF NOT EXISTS historiales_medicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    profesional_id INT NOT NULL,
    cita_id INT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    diagnostico TEXT,
    tratamiento TEXT,
    receta TEXT,
    observaciones TEXT,
    FOREIGN KEY (paciente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (profesional_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (cita_id) REFERENCES citas(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar especialidades de ejemplo
INSERT INTO especialidades (nombre, descripcion) VALUES
('Médico general', 'Atención médica general y preventiva'),
('Pediatra', 'Especialista en atención infantil'),
('Fisiólogo', 'Especialista en fisiología y ejercicio'),
('Psicólogo', 'Atención en salud mental'),
('Nutricionista', 'Especialista en nutrición y dietética'),
('Terapeuta ocupacional', 'Terapia ocupacional y rehabilitación'),
('Ortopedista', 'Especialista en ortopedia'),
('Enfermería especial', 'Enfermería especializada'),
('Cardiología', 'Especialista en enfermedades del corazón'),
('Dermatología', 'Especialista en enfermedades de la piel'),
('Veterinario', 'Atención veterinaria'),
('Ambulancia', 'Servicio de ambulancia');

-- Insertar usuarios de prueba (contraseña: 123456)
INSERT INTO usuarios (nombre, email, password, rol, genero, ciudad, edad) VALUES
('Juan Pérez', 'paciente@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'paciente', 'masculino', 'Bogotá', 30),
('Dr. Luis Padilla Cruz', 'profesional@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'profesional', 'masculino', 'Barranquilla', 28);

-- Crear perfil profesional para el usuario profesional (ya verificado para pruebas)
INSERT INTO profesionales (usuario_id, especialidad_id, cedula, tarjeta_profesional, medio_transporte, años_experiencia, tarifa_consulta, descripcion, verificado, estado_verificacion, acepta_terminos, fecha_acepta_terminos) VALUES
(2, 1, '1234567890', 'TP-12345', 'automovil', 5, 80000.00, 'Médico general con experiencia en atención domiciliaria', 1, 'aprobado', 1, NOW());
