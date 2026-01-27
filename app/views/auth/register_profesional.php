<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Profesional - SaluDrive</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/css/register-pro.css">
    <link rel="manifest" href="<?php echo APP_URL; ?>/manifest.json">
</head>
<body>
    <div class="container-pro">
        <div class="register-pro-screen">
            <!-- Paso 1: Bienvenida -->
            <div class="step-content active" id="step1">
                <button class="btn-back-top" onclick="goBack()">←</button>
                
                <div class="welcome-info-box">
                    <h2>Obtén ingresos con nosotros</h2>
                    <div class="info-items">
                        <div class="info-item">
                            <span class="icon">🕐</span>
                            <span>Horarios flexibles</span>
                        </div>
                        <div class="info-item">
                            <span class="icon">💰</span>
                            <span>Tus precios</span>
                        </div>
                        <div class="info-item">
                            <span class="icon">💳</span>
                            <span>Pagos bajos por servicio</span>
                        </div>
                    </div>
                </div>
                
                <h3 class="section-title">Profesional en salud en</h3>
                
                <div class="specialties-grid-main">
                    <button class="specialty-btn-main" data-specialty="1">Médico general</button>
                    <button class="specialty-btn-main" data-specialty="2">Pediatra</button>
                    <button class="specialty-btn-main" data-specialty="3">Psicólogo</button>
                    <button class="specialty-btn-main" data-specialty="4">Psiquiatra</button>
                    <button class="specialty-btn-main" data-specialty="5">Nutricionista</button>
                    <button class="specialty-btn-main" data-specialty="6">Fisioterapeuta</button>
                    <button class="specialty-btn-main" data-specialty="7">Ortopedista</button>
                    <button class="specialty-btn-main" data-specialty="8">Enfermera superior</button>
                    <button class="specialty-btn-main" data-specialty="9">Auxiliar de enfermería</button>
                    <button class="specialty-btn-main" data-specialty="10">Veterinario</button>
                    <button class="specialty-btn-main" data-specialty="11">Ambulancia</button>
                    <button class="specialty-btn-main" data-specialty="12">Otro</button>
                </div>
                
                <div class="bottom-links">
                    <p class="info-link">Ya tengo una cuenta</p>
                    <p class="info-link">Ir al modo paciente</p>
                </div>
                
                <div class="nav-buttons">
                    <button class="btn btn-next" onclick="goNext()">Siguiente</button>
                </div>
            </div>

            <!-- Paso 2: Eliminado - va directo al paso 3 -->
            <div class="step-content" id="step2" style="display: none;">
            </div>

            <!-- Paso 3: Información básica -->
            <div class="step-content" id="step3">
                <button class="btn-back-top" onclick="goBack()">←</button>
                
                <h2>Ingresa tu información profesional</h2>
                
                <form id="professionalForm" enctype="multipart/form-data">
                    <input type="hidden" name="especialidad_id" id="especialidad_id">
                    <input type="hidden" name="rol" value="profesional">
                    
                    <div class="form-group photo-upload">
                        <label>Foto de perfil</label>
                        <div class="photo-placeholder" id="photoPreview">
                            <span class="icon">📷</span>
                            <span>Toca para agregar foto</span>
                            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" hidden>
                        </div>
                        <p class="helper-text">Toca Foto</p>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>NOMBRE COMPLETO</label>
                            <input type="text" name="nombre" required>
                        </div>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label>DNI</label>
                            <input type="text" name="cedula" required>
                        </div>
                        <div class="form-group">
                            <label>GÉNERO</label>
                            <select name="genero" required>
                                <option value="">Seleccionar</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label>EDAD</label>
                            <input type="number" name="edad" required min="18" max="99">
                        </div>
                        <div class="form-group">
                            <label>CIUDAD</label>
                            <select name="ciudad" required>
                                <option value="">Seleccionar</option>
                                <option value="Barranquilla">Barranquilla</option>
                                <option value="Bogotá">Bogotá</option>
                                <option value="Medellín">Medellín</option>
                                <option value="Cali">Cali</option>
                                <option value="Cartagena">Cartagena</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row two-cols">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Contraseña</label>
                            <input type="password" name="password" required minlength="6">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>MEDIO DE TRANSPORTE</label>
                        <div class="transport-options">
                            <label class="transport-card">
                                <input type="radio" name="medio_transporte" value="motocicleta">
                                <span>🏍️ Motocicleta</span>
                            </label>
                            <label class="transport-card">
                                <input type="radio" name="medio_transporte" value="automovil">
                                <span>🚗 Automóvil</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" name="acepta_terminos" required>
                            Acepto los <a href="#" id="openTerminos" style="color: #2196F3; text-decoration: none;">términos y condiciones</a>.
                        </label>
                    </div>
                </form>
                
                <div class="nav-buttons">
                    <button class="btn btn-next" onclick="goNext()">Siguiente</button>
                </div>
            </div>

            <!-- Paso 4: Documentos personales -->
            <div class="step-content" id="step4">
                <button class="btn-back-top" onclick="goBack()">←</button>
                <button class="btn-close" onclick="closeModal()">×</button>
                <h2>Documentos personales</h2>
                
                <div class="documents-grid">
                    <div class="doc-upload-card" data-doc-name="Documento de identidad">
                        <div class="doc-icon">📤</div>
                        <input type="file" name="foto_documento_identidad" accept="image/*,application/pdf" hidden>
                        <p>Documento de identidad</p>
                    </div>
                    <div class="doc-upload-card" data-doc-name="Tarjeta profesional">
                        <div class="doc-icon">📤</div>
                        <input type="file" name="foto_tarjeta_profesional" accept="image/*,application/pdf" hidden>
                        <p>Tarjeta profesional - Licencia médica</p>
                    </div>
                    <div class="doc-upload-card" data-doc-name="Selfie con tarjeta">
                        <div class="doc-icon">📤</div>
                        <input type="file" name="selfie_con_tarjeta" accept="image/*" hidden>
                        <p>Selfie con tarjeta profesional</p>
                    </div>
                    <div class="doc-upload-card" data-doc-name="Registro profesional">
                        <div class="doc-icon">📤</div>
                        <input type="file" name="documento_adicional_1" accept="image/*,application/pdf" hidden>
                        <p>Registro profesional</p>
                    </div>
                    <div class="doc-upload-card" data-doc-name="Acta de grado">
                        <div class="doc-icon">📤</div>
                        <input type="file" name="documento_adicional_2" accept="image/*,application/pdf" hidden>
                        <p>Acta de grado</p>
                    </div>
                    <div class="doc-upload-card" data-doc-name="Título de especialidad">
                        <div class="doc-icon">📤</div>
                        <input type="file" name="documento_adicional_3" accept="image/*,application/pdf" hidden>
                        <p>Título de especialidad</p>
                    </div>
                </div>
                
                <div class="nav-buttons">
                    <button class="btn btn-next" onclick="goNext()">Siguiente</button>
                </div>
            </div>

            <!-- Paso 5: Documento de identidad -->
            <div class="step-content" id="step5">
                <button class="btn-back-top" onclick="goBack()">←</button>
                
                <h2>Documento de identidad</h2>
                
                <div class="id-card-preview">
                    <div class="id-placeholder">
                        <span class="icon">🪪</span>
                        <p>Toma o elige una foto de tu documento de identidad original que utilizo junto a tu rostro, permitiendo visualizar tu rostro y la documentación</p>
                    </div>
                    <input type="file" name="foto_documento_identidad" accept="image/*" hidden>
                </div>
                
                <div class="nav-buttons">
                    <button class="btn btn-next" onclick="goNext()">Siguiente</button>
                </div>
            </div>

            <!-- Paso 6: Selfie con tarjeta -->
            <div class="step-content" id="step6">
                <button class="btn-back-top" onclick="goBack()">←</button>
                
                <h2>Selfie con tarjeta profesional</h2>
                
                <div class="selfie-preview">
                    <div class="selfie-placeholder">
                        <span class="icon">🤳</span>
                        <p>Tómate una selfie sosteniéndote tu tarjeta profesional junto a tu rostro, permitiéndonos visualizar tu rostro y la documentación</p>
                    </div>
                    <input type="file" name="selfie_con_tarjeta" accept="image/*" hidden>
                </div>
                
                <div class="nav-buttons">
                    <button class="btn btn-next" onclick="goNext()">Enviar</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal de Términos y Condiciones -->
    <div id="terminosModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; overflow: auto; padding: 20px;">
        <div style="position: relative; max-width: 600px; margin: 40px auto; background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); max-height: 80vh; overflow-y: auto;">
            <button onclick="document.getElementById('terminosModal').style.display='none'" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 28px; cursor: pointer; color: #666; line-height: 1;">×</button>
            
            <h2 style="color: #2196F3; margin-bottom: 20px; font-size: 24px; text-align: center;">Términos y Condiciones</h2>
            
            <div style="color: #333; line-height: 1.6; font-size: 14px;">
                <p style="margin-bottom: 15px;">• El médico acepta que ejerce su profesión de manera independiente y por su propia cuenta y riesgo.</p>
                
                <p style="margin-bottom: 15px;">• SaluDrive es exclusivamente un portal de contacto. La plataforma no asume responsabilidad alguna por el acto médico, diagnósticos o tratamientos. El médico asume cualquier reclamación legal derivada de su ejercicio.</p>
                
                <p style="margin-bottom: 15px;">• El pago de la recarga otorga el derecho de uso de la herramienta tecnológica, no constituye una relación laboral ni un seguro de cobertura.</p>
                
                <p style="margin-bottom: 15px;">• El profesional debe verificar la identidad del paciente antes de brindar atención.</p>
                
                <p style="margin-bottom: 15px;">• El profesional acepta las políticas de privacidad y manejo de datos personales de SaluDrive.</p>
                
                <p style="margin-bottom: 15px;">• SaluDrive se reserva el derecho de suspender o cancelar cuentas que incumplan estos términos.</p>
                
                <p style="margin-bottom: 15px;">• Al registrarse, el profesional certifica que cuenta con todas las certificaciones, licencias y seguros necesarios para ejercer su profesión.</p>
            </div>
            
            <button onclick="document.getElementById('terminosModal').style.display='none'" style="width: 100%; padding: 12px; background: #2196F3; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; margin-top: 20px;">Entendido</button>
        </div>
    </div>

    <script src="<?php echo APP_URL; ?>/public/js/register-pro.js"></script>
    <script>
        // Abrir modal de términos
        document.getElementById('openTerminos')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('terminosModal').style.display = 'block';
        });
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('terminosModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
            }
        });
    </script>
</body>
</html>
