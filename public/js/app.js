// Registro del Service Worker para PWA
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/saludgo/service-worker.js')
            .then(registration => {
                console.log('Service Worker registrado con éxito:', registration);
            })
            .catch(error => {
                console.log('Error al registrar el Service Worker:', error);
            });
    });
}

// Funciones de navegación entre pantallas
function showWelcomeScreen() {
    document.getElementById('welcomeScreen').classList.remove('hidden');
    document.getElementById('roleScreen').classList.add('hidden');
    document.getElementById('loginScreen').classList.add('hidden');
    document.getElementById('verificationScreen')?.classList.add('hidden');
}

function showRoleScreen() {
    document.getElementById('welcomeScreen').classList.add('hidden');
    document.getElementById('roleScreen').classList.remove('hidden');
    document.getElementById('loginScreen').classList.add('hidden');
    document.getElementById('verificationScreen')?.classList.add('hidden');
}

function showLoginForm() {
    document.getElementById('welcomeScreen').classList.add('hidden');
    document.getElementById('roleScreen').classList.add('hidden');
    document.getElementById('loginScreen').classList.remove('hidden');
    document.getElementById('verificationScreen')?.classList.add('hidden');
}

function showLoginScreen() {
    document.getElementById('welcomeScreen').classList.add('hidden');
    document.getElementById('roleScreen').classList.add('hidden');
    document.getElementById('loginScreen').classList.remove('hidden');
    document.getElementById('emailLoginScreen')?.classList.add('hidden');
    document.getElementById('verificationScreen')?.classList.add('hidden');
}

function showEmailLoginScreen() {
    document.getElementById('welcomeScreen').classList.add('hidden');
    document.getElementById('roleScreen').classList.add('hidden');
    document.getElementById('loginScreen').classList.add('hidden');
    document.getElementById('emailLoginScreen').classList.remove('hidden');
    document.getElementById('verificationScreen')?.classList.add('hidden');
}

function showVerificationScreen() {
    document.getElementById('loginScreen').classList.add('hidden');
    document.getElementById('emailLoginScreen')?.classList.add('hidden');
    document.getElementById('verificationScreen').classList.remove('hidden');
}

// Selección de rol para registro
function selectRole(role) {
    // Guardar el rol seleccionado en sessionStorage
    sessionStorage.setItem('selectedRole', role);
    
    // Redirigir a la página de registro con el rol
    window.location.href = `/saludgo/routes/router.php?action=register&role=${role}`;
}

// Mostrar información de la app
function showAppInfo(event) {
    event.preventDefault();
    alert('SaludGo es una aplicación que conecta pacientes con profesionales de la salud de manera segura y eficiente.');
}

// Variables para el sistema de autenticación por teléfono
let verificationCode = '';
let phoneNumber = '';

// Enviar código de verificación
function sendVerificationCode() {
    const phoneInput = document.getElementById('phone');
    const phone = phoneInput.value.trim();
    
    if (!phone || phone.length !== 10) {
        alert('Por favor, ingresa un número de teléfono válido de 10 dígitos');
        return;
    }
    
    phoneNumber = '+57' + phone;
    
    // Enviar solicitud al backend
    fetch('/saludgo/routes/router.php?action=sendCode', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ phone: phoneNumber })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            verificationCode = data.code; // En producción esto vendría por SMS
            showVerificationScreen();
            setupCodeInputs();
        } else {
            alert(data.message || 'Error al enviar el código');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar el código. Intenta nuevamente.');
    });
}

// Configurar inputs de código
function setupCodeInputs() {
    const inputs = document.querySelectorAll('.code-input');
    
    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Solo permitir números
            if (!/^\d$/.test(value)) {
                e.target.value = '';
                return;
            }
            
            // Auto-focus al siguiente input
            if (value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });
        
        input.addEventListener('keydown', function(e) {
            // Retroceder al input anterior con Backspace
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
        
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').slice(0, 4);
            
            if (/^\d+$/.test(pasteData)) {
                pasteData.split('').forEach((char, i) => {
                    if (inputs[index + i]) {
                        inputs[index + i].value = char;
                    }
                });
                inputs[Math.min(index + pasteData.length, 3)].focus();
            }
        });
    });
}

// Verificar código ingresado
function verifyCode() {
    const inputs = document.querySelectorAll('.code-input');
    const code = Array.from(inputs).map(input => input.value).join('');
    
    if (code.length !== 4) {
        alert('Por favor, ingresa el código completo de 4 dígitos');
        return;
    }
    
    // Verificar el código
    fetch('/saludgo/routes/router.php?action=verifyCode', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            phone: phoneNumber,
            code: code 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirigir al dashboard correspondiente
            window.location.href = data.redirectUrl;
        } else {
            alert(data.message || 'Código incorrecto. Intenta nuevamente.');
            inputs.forEach(input => input.value = '');
            inputs[0].focus();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al verificar el código. Intenta nuevamente.');
    });
}

// Reenviar código
function resendCode() {
    sendVerificationCode();
    alert('Se ha reenviado el código de verificación');
}

// Validación del formulario de login
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const emailLoginForm = document.getElementById('emailLoginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Por favor, completa todos los campos');
                return false;
            }
            
            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Por favor, ingresa un correo electrónico válido');
                return false;
            }
        });
    }
    
    // Manejar login con email
    if (emailLoginForm) {
        emailLoginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                alert('Por favor, completa todos los campos');
                return;
            }
            
            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor, ingresa un correo electrónico válido');
                return;
            }
            
            // Enviar solicitud de login
            fetch('/saludgo/routes/router.php?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(response => {
                // Manejar redirección
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.text();
            })
            .then(text => {
                if (text && text.includes('error')) {
                    alert('Credenciales incorrectas. Intenta nuevamente.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al iniciar sesión. Intenta nuevamente.');
            });
        });
    }
});

// Detectar si la app está instalada como PWA
function isPWA() {
    return window.matchMedia('(display-mode: standalone)').matches || 
           window.navigator.standalone === true;
}

// Mostrar prompt de instalación
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    
    // Aquí podrías mostrar un botón para instalar la app
    console.log('La app puede ser instalada');
});

// Cuando la app se instala
window.addEventListener('appinstalled', () => {
    console.log('SaludGo se ha instalado correctamente');
    deferredPrompt = null;
});
