<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ya eres miembro - SaludGo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
            animation: slideIn 0.4s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .btn-back {
            position: fixed;
            top: 20px;
            left: 20px;
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            font-size: 24px;
            color: #333;
            transition: all 0.2s;
        }
        
        .btn-back:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
        }
        
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        h1 {
            color: #2196F3;
            font-size: 26px;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .info-box {
            background: #E3F2FD;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .info-box h2 {
            color: #1976D2;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .info-box p {
            color: #1565C0;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 8px;
        }
        
        .phone-number {
            font-weight: 600;
            color: #0D47A1;
            font-size: 16px;
            letter-spacing: 1px;
        }
        
        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-yes {
            background: #2196F3;
            color: white;
        }
        
        .btn-yes:hover {
            background: #1976D2;
            transform: scale(1.02);
        }
        
        .btn-yes:active {
            transform: scale(0.98);
        }
        
        .btn-no {
            background: #B0BEC5;
            color: white;
        }
        
        .btn-no:hover {
            background: #90A4AE;
            transform: scale(1.02);
        }
        
        .btn-no:active {
            transform: scale(0.98);
        }
        
        .help-text {
            margin-top: 20px;
            font-size: 13px;
            color: #757575;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 22px;
            }
            
            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <button class="btn-back" onclick="goBack()">←</button>
    
    <div class="container">
        <div class="logo">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="45" fill="#2196F3" opacity="0.1"/>
                <path d="M50 20C33.4315 20 20 33.4315 20 50C20 66.5685 33.4315 80 50 80C66.5685 80 80 66.5685 80 50C80 33.4315 66.5685 20 50 20ZM50 25C63.8071 25 75 36.1929 75 50C75 63.8071 63.8071 75 50 75C36.1929 75 25 63.8071 25 50C25 36.1929 36.1929 25 50 25Z" fill="#2196F3"/>
                <path d="M50 35C50 35 42 38 42 45C42 52 50 55 50 55C50 55 58 52 58 45C58 38 50 35 50 35Z" fill="#2196F3"/>
                <path d="M50 55C50 55 38 58 38 68C38 70 40 72 42 72H58C60 72 62 70 62 68C62 58 50 55 50 55Z" fill="#2196F3"/>
            </svg>
        </div>
        
        <h1>¡Ya eres miembro de SaludGo!</h1>
        
        <div class="info-box">
            <h2>Encontramos tus documentos registrados en una cuenta antigua</h2>
            <p>¿Puedes confirmar si el número</p>
            <p class="phone-number" id="phoneNumber">Loading...</p>
            <p>para recuperar tu cuenta?</p>
        </div>
        
        <div class="buttons">
            <button class="btn btn-yes" onclick="confirmRecovery()">Sí</button>
            <button class="btn btn-no" onclick="denyRecovery()">No</button>
        </div>
        
        <p class="help-text">
            Si no reconoces este número o tienes problemas, 
            <a href="#" style="color: #2196F3; text-decoration: none;" onclick="contactSupport()">contacta con soporte</a>
        </p>
    </div>
    
    <script>
        // Obtener datos del usuario desde sessionStorage
        const userData = JSON.parse(sessionStorage.getItem('existingUserData') || '{}');
        
        // Mostrar teléfono oculto
        const phoneElement = document.getElementById('phoneNumber');
        if (userData.telefono_oculto) {
            phoneElement.textContent = userData.telefono_oculto;
        } else {
            phoneElement.textContent = '(teléfono no disponible)';
        }
        
        function goBack() {
            sessionStorage.removeItem('existingUserData');
            window.location.href = '/saludgo/public/';
        }
        
        function confirmRecovery() {
            // Limpiar datos y redirigir a login
            sessionStorage.removeItem('existingUserData');
            window.location.href = '/saludgo/public/';
        }
        
        function denyRecovery() {
            alert('Si no reconoces esta cuenta, por favor contacta con soporte técnico.');
            sessionStorage.removeItem('existingUserData');
            window.location.href = '/saludgo/public/';
        }
        
        function contactSupport() {
            alert('Soporte: contacto@saludgo.com\nTeléfono: +57 300 123 4567');
            return false;
        }
        
        // Limpiar datos al salir de la página
        window.addEventListener('beforeunload', function() {
            sessionStorage.removeItem('existingUserData');
        });
    </script>
</body>
</html>
