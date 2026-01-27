<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - SaluDrive</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="manifest" href="../../manifest.json">
</head>
<body>
    <div class="container">
        <div class="screen">
            <div class="logo-container small">
                <svg class="logo" viewBox="0 0 200 100" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 40 50 Q 50 35 70 35 L 130 35 Q 150 35 160 50" 
                          stroke="#3b82c4" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <ellipse cx="70" cy="50" rx="25" ry="15" 
                             stroke="#3b82c4" stroke-width="3" fill="none"/>
                    <ellipse cx="130" cy="50" rx="25" ry="15" 
                             stroke="#3b82c4" stroke-width="3" fill="none"/>
                    <circle cx="70" cy="60" r="8" fill="#3b82c4"/>
                    <circle cx="130" cy="60" r="8" fill="#3b82c4"/>
                </svg>
                <h1 class="logo-text">Salu<span>Drive</span></h1>
            </div>
            
            <form class="login-form" action="../../routes/router.php?action=register_process" method="POST">
                <h2>Registro de <?php echo ucfirst($role ?? 'paciente'); ?></h2>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php 
                        echo htmlspecialchars($_SESSION['error']); 
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <input type="hidden" name="rol" value="<?php echo htmlspecialchars($role ?? 'paciente'); ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Confirmar contraseña</label>
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="6">
                </div>
                
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </form>
            
            <button class="btn-back" onclick="window.location.href='../../public/'">← Volver</button>
        </div>
    </div>
    
    <style>
        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.9rem;
        }
        
        .alert-error {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
    </style>
</body>
</html>
