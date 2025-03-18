// Guarda los errores de los campos
let fieldErrors = {};

function checkField(input) {
    let field = input.name;
    let value = input.value;
    let errorSpan = document.getElementById(`error-${field}`);

    if (!value.trim()) return; // No consultar si el campo está vacío

    fetch(`/check-field?field=${field}&value=${value}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.exists) {
                errorSpan.textContent = `El ${
                    field === "email"
                        ? "correo electrónico"
                        : "nombre de usuario"
                } ya está en uso.`;
                errorSpan.classList.remove("hidden");
                fieldErrors[field] = true; // Marca error
            } else {
                errorSpan.textContent = "";
                errorSpan.classList.add("hidden");
                fieldErrors[field] = false; // Marca como válido
            }
        });
}

function setupFieldValidation(fieldNames) {
    fieldNames.forEach((name) => {
        let input = document.querySelector(`input[name="${name}"]`);
        if (input) {
            input.addEventListener("blur", function () {
                checkField(this);
            });
        }
    });
}

function validateRegister(event, form) {
    event.preventDefault();
    let isEmpty = false;
    let inputs = form.querySelectorAll(".formField");
    let errorMessage = "<ul style='text-align: left;'>Campos faltantes:";

    // Validación de campos vacíos
    inputs.forEach((input) => {
        if (!input.value.trim()) {
            let container = input.closest("div");
            isEmpty = true;
            input.nextElementSibling?.classList.remove("hidden");
            container?.classList.remove("hidden");
            errorMessage += `<li>${input.placeholder}</li>`;
        } else {
            input.nextElementSibling?.classList.add("hidden");
        }
    });

    errorMessage += "</ul>";

    if (isEmpty) {
        Swal.fire({
            icon: "warning",
            html: errorMessage,
            title: "Completa los campos",
            allowOutsideClick: false,
            customClass: {
                title: "text-lg font-bold",
                content: "text-sm",
            },
        });
        return;
    }

    // Si hay errores en los campos validados con blur (email, userName, etc.)
    if (Object.values(fieldErrors).some((hasError) => hasError)) {
        Swal.fire({
            icon: "warning",
            title: "Error",
            text: "Corrige los errores antes de enviar el formulario.",
            allowOutsideClick: false,
            customClass: {
                title: "text-lg font-bold",
                content: "text-sm",
            },
        });
        return;
    }

    // Confirmación antes de enviar
    Swal.fire({
        title: "¿Desea Confirmar?",
        text: "Está por realizar un registro",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Registrarme",
        cancelButtonText: "Cancelar",
        allowOutsideClick: false,
        customClass: {
            title: "text-lg font-bold",
            content: "text-sm",
            confirmButton:
                "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded",
            cancelButton:
                "bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded",
        },
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: "Registrado",
                text: "Registro exitoso",
                icon: "success",
                customClass: {
                    title: "text-lg font-bold",
                    content: "text-sm",
                },
            }).then(() => {
                form.submit();
            });
        }
    });
}

// Llama a setupFieldValidation para configurar validaciones de los campos email y userName
window.setupFieldValidation = setupFieldValidation;
window.validateRegister = validateRegister;
