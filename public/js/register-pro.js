// JavaScript para el flujo de registro profesional

let currentStep = 1;
let selectedSpecialty = null;
let formData = new FormData();

// Navegar entre pasos
function nextStep(step) {
    document.getElementById(`step${currentStep}`).classList.remove('active');
    document.getElementById(`step${step}`).classList.add('active');
    currentStep = step;
    window.scrollTo(0, 0);
}

function prevStep(step) {
    document.getElementById(`step${currentStep}`).classList.remove('active');
    document.getElementById(`step${step}`).classList.add('active');
    currentStep = step;
    window.scrollTo(0, 0);
}

// Funciones para botones fijos
function goBack() {
    if (currentStep === 1) {
        window.location.href = '/saludgo/public/';
        return;
    }
    if (currentStep === 3) {
        prevStep(1);
        return;
    }
    if (currentStep === 4) {
        prevStep(3);
        return;
    }
    prevStep(currentStep - 1);
}

function goNext() {
    if (currentStep === 1) {
        alert('Por favor, selecciona una especialidad');
        return;
    }
    
    if (currentStep === 3) {
        if (!validateStep3()) {
            return;
        }
        saveFormData();
        nextStep(4);
        return;
    }
    
    if (currentStep === 4) {
        // Validar que se hayan cargado los documentos mínimos requeridos
        submitForm();
        return;
    }
}

// updateNavButtons eliminada - ya no se necesita

// Seleccionar especialidad
document.querySelectorAll('.specialty-btn-main').forEach(btn => {
    btn.addEventListener('click', function() {
        selectedSpecialty = this.dataset.specialty;
        const specialtyName = this.textContent;
        
        document.getElementById('especialidad_id').value = selectedSpecialty;
        
        // Ir directo al paso 3 (formulario)
        nextStep(3);
    });
});

// Preview de foto de perfil
document.getElementById('foto_perfil')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            preview.style.backgroundImage = `url(${e.target.result})`;
            preview.style.backgroundSize = 'cover';
            preview.style.backgroundPosition = 'center';
            preview.innerHTML = '';
        }
        reader.readAsDataURL(file);
    }
});

// Click en placeholder de foto
document.getElementById('photoPreview')?.addEventListener('click', function() {
    document.getElementById('foto_perfil').click();
});

// Validar paso 3 antes de continuar
const originalNextStep = window.nextStep;
window.nextStep = function(step) {
    if (currentStep === 3 && step === 4) {
        if (!validateStep3()) {
            return;
        }
        saveFormData();
    }
    originalNextStep(step);
};

function validateStep3() {
    const form = document.getElementById('professionalForm');
    const nombre = form.querySelector('[name="nombre"]').value;
    const cedula = form.querySelector('[name="cedula"]').value;
    const genero = form.querySelector('[name="genero"]').value;
    const edad = form.querySelector('[name="edad"]').value;
    const ciudad = form.querySelector('[name="ciudad"]').value;
    const email = form.querySelector('[name="email"]').value;
    const password = form.querySelector('[name="password"]').value;
    const transporte = form.querySelector('[name="medio_transporte"]:checked');
    const terminos = form.querySelector('[name="acepta_terminos"]').checked;

    if (!nombre || !cedula || !genero || !edad || !ciudad || !email || !password) {
        alert('Por favor, completa todos los campos obligatorios');
        return false;
    }

    if (!transporte) {
        alert('Por favor, selecciona un medio de transporte');
        return false;
    }

    if (!terminos) {
        alert('Debes aceptar los términos y condiciones');
        return false;
    }

    return true;
}

function saveFormData() {
    const form = document.getElementById('professionalForm');
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        if (input.type === 'file') {
            if (input.files[0]) {
                formData.append(input.name, input.files[0]);
            }
        } else if (input.type === 'radio') {
            if (input.checked) {
                formData.set(input.name, input.value);
            }
        } else if (input.type === 'checkbox') {
            formData.set(input.name, input.checked ? '1' : '0');
        } else {
            formData.set(input.name, input.value);
        }
    });
}

// Manejar carga de documentos personales
window.currentDocInput = null; // Referencia al input actual (global)

document.querySelectorAll('.doc-upload-card').forEach((card) => {
    const input = card.querySelector('input[type="file"]');
    
    card.addEventListener('click', function() {
        // Guardar referencia al input y la tarjeta
        window.currentDocInput = { input: input, card: card };
        // Abrir modal de opciones
        document.getElementById('photoOptionsModal').style.display = 'block';
    });
    
    if (input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                formData.set(input.name, file);
                card.style.borderColor = 'var(--primary-color)';
                card.style.background = '#e8f4f8';
                const icon = card.querySelector('.doc-icon');
                if (icon) icon.textContent = '✓';
                
                // Mostrar toast de confirmación
                showToast('Archivo cargado correctamente ✓');
            }
        });
    }
});

// Funciones auxiliares
function chooseFromGallery() {
    const input = document.querySelector(`#step${currentStep} input[type="file"]`);
    input?.click();
}

function closeModal() {
    if (confirm('¿Estás seguro de que quieres cancelar el registro?')) {
        window.location.href = '/saludgo/public/';
    }
}

// Enviar formulario final
function submitForm() {
    // Validar documentos mínimos requeridos
    const requiredDocs = [
        { name: 'foto_documento_identidad', label: 'Documento de identidad' },
        { name: 'foto_tarjeta_profesional', label: 'Tarjeta profesional' },
        { name: 'selfie_con_tarjeta', label: 'Selfie con tarjeta profesional' }
    ];
    
    for (let doc of requiredDocs) {
        const input = document.querySelector(`input[name="${doc.name}"]`);
        if (!input || !input.files[0]) {
            alert(`Por favor, carga tu ${doc.label}`);
            return;
        }
    }

    // Mostrar loader
    const btn = document.querySelector('#step4 .btn-next');
    if (btn) {
        btn.textContent = 'Enviando...';
        btn.disabled = true;
    }

    // Asegurar que todos los archivos estén en formData
    saveFormData();
    
    // Recolectar TODOS los archivos de todos los pasos
    document.querySelectorAll('input[type="file"]').forEach(input => {
        if (input.files && input.files[0]) {
            formData.set(input.name, input.files[0]);
            console.log('Agregando archivo:', input.name, input.files[0].name);
        }
    });

    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ':', pair[1]);
    }

    // Enviar formulario
    fetch('/saludgo/routes/router.php?action=register_process', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Registro exitoso. Tu cuenta está pendiente de verificación.');
            window.location.href = '/saludgo/public/';
        } else {
            alert('Error: ' + (data.message || 'No se pudo completar el registro'));
            if (btn) {
                btn.textContent = 'Enviar';
                btn.disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar el formulario. Intenta nuevamente.');
        if (btn) {
            btn.textContent = 'Enviar';
            btn.disabled = false;
        }
    });
}

// Función para mostrar toast de confirmación
function showToast(message) {
    // Crear el toast
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        background: #4CAF50;
        color: white;
        padding: 12px 24px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 500;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideUpFade 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    // Eliminar después de 2 segundos
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 2000);
}
