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
        nextStep(5);
        return;
    }
    
    if (currentStep === 5) {
        nextStep(6);
        return;
    }
    
    if (currentStep === 6) {
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
document.querySelectorAll('.doc-upload-card').forEach((card) => {
    const input = card.querySelector('input[type="file"]');
    
    card.addEventListener('click', function() {
        input.click();
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
            }
        });
    }
});

// Documento de identidad - Eliminado listener automático para usar modal
const idPlaceholder = document.querySelector('#step5 .id-placeholder');
const idInput = document.querySelector('#step5 [name="foto_documento_identidad"]');

// Selfie con tarjeta - Eliminado listener automático para usar modal
const selfiePlaceholder = document.querySelector('#step6 .selfie-placeholder');
const selfieInput = document.querySelector('#step6 [name="selfie_con_tarjeta"]');

// Funciones auxiliares
function skipDocs() {
    nextStep(5);
}

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
    const selfieInput = document.querySelector('input[name="selfie_con_tarjeta"]');
    if (!selfieInput || !selfieInput.files[0]) {
        alert('Por favor, toma una selfie con tu tarjeta profesional');
        return;
    }

    // Mostrar loader
    const btn = document.querySelector('#step6 .btn-next');
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
