# SaludGo - PWA de Gestión de Salud

## Descripción
SaludGo es una Progressive Web App (PWA) que conecta pacientes con profesionales de la salud de manera segura y eficiente.

## Estructura del Proyecto

```
saludgo/
├── app/
│   ├── controllers/       # Controladores de la aplicación
│   │   └── AuthController.php
│   ├── models/           # Modelos de datos
│   │   └── User.php
│   └── views/            # Vistas
│       ├── auth/         # Vistas de autenticación
│       │   ├── login.php
│       │   └── register.php
│       └── dashboard/    # Dashboards
│           ├── paciente.php
│           └── profesional.php
├── config/              # Archivos de configuración
│   ├── config.php
│   └── Database.php
├── public/              # Archivos públicos
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── app.js
│   ├── images/
│   └── index.php        # Página principal
├── routes/              # Rutas de la aplicación
│   └── router.php
├── assets/              # Recursos adicionales
│   └── icons/           # Iconos para PWA
├── database/            # Scripts de base de datos
│   └── schema.sql
├── manifest.json        # Manifiesto PWA
├── service-worker.js    # Service Worker para PWA
└── .htaccess           # Configuración de Apache
```

## Características

- ✅ Arquitectura MVC clara y organizada
- ✅ Progressive Web App (instalable)
- ✅ Sistema de autenticación (login/registro)
- ✅ Roles de usuario (Paciente/Profesional)
- ✅ Diseño responsive
- ✅ Seguridad con password hashing
- ✅ Gestión de sesiones
- ✅ Base de datos MySQL

## Instalación

### Requisitos
- XAMPP (Apache + MySQL + PHP 7.4+)
- Navegador web moderno

### Pasos de Instalación

1. **Clonar/copiar el proyecto en htdocs**
   ```
   c:\xampp\htdocs\saludgo
   ```

2. **Crear la base de datos**
   - Abrir phpMyAdmin: http://localhost:8080/phpmyadmin/
   - Crear una nueva base de datos llamada `saludgo`
   - Importar el archivo `database/schema.sql`

3. **Configurar la aplicación**
   - Editar `config/config.php` si es necesario
   - Verificar credenciales de base de datos

4. **Acceder a la aplicación**
   - URL: http://localhost/saludgo/public/

## Usuarios de Prueba

**Paciente:**
- Email: paciente@test.com
- Contraseña: 123456

**Profesional:**
- Email: profesional@test.com
- Contraseña: 123456

## Tecnologías Utilizadas

- **Backend:** PHP (MVC puro)
- **Frontend:** HTML5, CSS3, JavaScript
- **Base de datos:** MySQL
- **PWA:** Service Workers, Web App Manifest
- **Servidor:** Apache (XAMPP)

## Funcionalidades Implementadas

### Autenticación
- ✅ Login de usuarios
- ✅ Registro de pacientes y profesionales
- ✅ Cierre de sesión
- ✅ Validación de formularios

### PWA
- ✅ Instalable en dispositivos
- ✅ Funciona offline (cache básico)
- ✅ Icono de aplicación
- ✅ Splash screen

### Seguridad
- ✅ Contraseñas hasheadas con bcrypt
- ✅ Prevención de SQL Injection (PDO)
- ✅ Validación de inputs
- ✅ Gestión segura de sesiones

## Próximos Pasos

- [ ] Implementar gestión de citas
- [ ] Sistema de mensajería
- [ ] Historial médico
- [ ] Notificaciones push
- [ ] Búsqueda de profesionales
- [ ] Calificaciones y reseñas

## Licencia

Proyecto educativo - SaludGo 2026
