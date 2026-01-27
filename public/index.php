<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#3b82c4">
    <title>SaluDrive - Bienvenido</title>
    <link rel="manifest" href="../manifest.json">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="../assets/icons/icon-72x72.png">
</head>
<body>
    <div class="container">
        <!-- Frame 1: Pantalla inicial con Registro/Login -->
        <div class="screen" id="welcomeScreen">
            <div class="logo-container">
                <img src="images/saludrive.png" alt="SaluDrive Logo" class="logo-image">
            </div>
            
            <div class="button-container">
                <button class="btn btn-primary" onclick="showRoleScreen()">Regístrate</button>
                <button class="btn btn-secondary" onclick="showLoginForm()">Inicia sesión</button>
            </div>
        </div>

        <!-- Frame 2: Selección de rol -->
        <div class="screen hidden" id="roleScreen">
            <div class="logo-container">
                <img src="images/saludrive.png" alt="SaluDrive Logo" class="logo-image">
            </div>
            
            <div class="button-container">
                <button class="btn btn-role btn-patient" onclick="selectRole('paciente')">PACIENTE</button>
                <button class="btn btn-role btn-professional" onclick="selectRole('profesional')">PROFESIONAL</button>
                <a href="#" class="link-info" onclick="showAppInfo(event)">Saber sobre la app</a>
            </div>
            
            <button class="btn-back" onclick="showWelcomeScreen()">← Volver</button>
        </div>

        <!-- Pantalla de Login - Ingreso de teléfono (Frame 3) -->
        <div class="screen hidden" id="loginScreen">
            <div class="logo-container">
                <img src="images/saludrive.png" alt="SaluDrive Logo" class="logo-image">
            </div>
            
            <div class="phone-input-container">
                <div class="phone-group">
                    <div class="country-code">
                        <img src="https://flagcdn.com/w40/co.png" alt="Colombia" class="flag-icon">
                        <span>+57</span>
                    </div>
                    <input type="tel" id="phone" name="phone" placeholder="Número de teléfono" maxlength="10" pattern="[0-9]{10}" required>
                </div>
            </div>
            
            <button class="btn btn-primary" onclick="sendVerificationCode()">SIGUIENTE</button>
            
            <button class="btn-back" onclick="showWelcomeScreen()">← Volver</button>
        </div>

        <!-- Pantalla de código de verificación (Frame 4) -->
        <div class="screen hidden" id="verificationScreen">
            <div class="logo-container small">
                <img src="images/saludrive.png" alt="SaluDrive Logo" class="logo-image">
            </div>
            
            <div class="verification-container">
                <h3>Ingresa el código de verificación</h3>
                <div class="code-inputs">
                    <input type="text" maxlength="1" class="code-input" data-index="0">
                    <input type="text" maxlength="1" class="code-input" data-index="1">
                    <input type="text" maxlength="1" class="code-input" data-index="2">
                    <input type="text" maxlength="1" class="code-input" data-index="3">
                </div>
                <button class="btn btn-link" onclick="resendCode()">Reenviar</button>
            </div>
            
            <button class="btn btn-primary" onclick="verifyCode()">SIGUIENTE</button>
            
            <button class="btn-back" onclick="showLoginScreen()">← Volver</button>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>
