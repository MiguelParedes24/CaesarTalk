//Users.js
//Hice este script muy similar a Messages.js porque habian event listeners que producian errores.

document.getElementById("searchUser").addEventListener("input", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#userTableBody tr");
    let found = false;

    rows.forEach((row) => {
        let name = row
            .querySelector("td:nth-child(1)")
            .textContent.toLowerCase();
        if (name.includes(filter)) {
            row.style.display = "";
            found = true;
        } else {
            row.style.display = "none";
        }
    });

    let noResultRow = document.querySelector("#userTableBody tr.no-result");
    let userTableBody = document.getElementById("userTableBody");
    if (!found) {
        if (!noResultRow) {
            noResultRow = document.createElement("tr");
            noResultRow.classList.add("no-result");
            noResultRow.innerHTML = `<td colspan="5" class="text-center p-5">No se encontraron usuarios</td>`;
            userTableBody.appendChild(noResultRow);
        } else {
            noResultRow.style.display = "";
        }
    } else {
        if (noResultRow) {
            noResultRow.style.display = "none";
        }
    }
});
// Función para cerrar el modal de usuarios
function closeUserModal(modalId) {
    let modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add("hidden");
    modal.classList.remove("flex");
    modal.setAttribute("aria-hidden", "true");

    // Permitir el scroll si no hay otros modales abiertos
    if (document.querySelectorAll(".modal:not(.hidden)").length === 0) {
        document.body.classList.remove("overflow-hidden");
    }

    location.reload(); //Se ejecuta SOLO después de que el usuario cierre el modal
}

// Función para abrir el modal de usuarios
function openUserModal(modalId) {
    let modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.remove("hidden");
    modal.classList.add("flex");
    modal.setAttribute("aria-hidden", "false");

    // Mueve el foco al primer elemento interactivo dentro del modal
    setTimeout(() => {
        modal.querySelector("button, input, textarea, a")?.focus();
    }, 10);

    // Bloquea el scroll del fondo
    document.body.classList.add("overflow-hidden");
}

// Eventos para abrir el modal al hacer clic en los botones
document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll("button[data-user-id]");

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            const userId = button.dataset.userId;
            const userEmail = button.dataset.userEmail;

            document.getElementById("hidden-user-id").value = userId;
            document.getElementById("messageUser").value = userEmail;

            openUserModal(); // Abre el modal
        });
    });

    // Evento para cerrar el modal (usando el botón de cerrar)
    const closeModalButton = document.querySelector(
        '#send-userMessage-modal [data-modal-toggle="send-userMessage-modal"]'
    );
    if (closeModalButton) {
        closeModalButton.addEventListener("click", closeUserModal(this.id));
    }

    const sendMessageForm = document.getElementById("userNewMessageForm");
    const confirmUserModal = document.getElementById("confirmUserModal");
    const modalUser = document.getElementById("modalUserReceiver");
    const modalUserShift = document.getElementById("modalUserShift");
    const modalUserSubject = document.getElementById("modalUserSubject");
    const modalUserBody = document.getElementById("modalUserBody");
    const confirUsermMessageButton =
        document.getElementById("confirmUserMessage");
    const cancelUserMessageButton =
        document.getElementById("cancelUserMessage");

    sendMessageForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const receiverId = document
            .getElementById("hidden-user-id")
            ?.value.trim();
        const receiverEmail = document
            .getElementById("messageUser")
            ?.value.trim();
        const subject = document
            .getElementById("messageUserSubject")
            ?.value.trim();
        const body = document.getElementById("messageUserBody")?.value.trim();
        const shift = document.getElementById("messageUserShift")?.value.trim();
        const sender_id = document
            .getElementById("hidden-userSender-id")
            ?.value.trim();

        // Validación de campos vacíos
        if (!receiverId || !receiverEmail || !subject || !body || !shift) {
            Swal.fire({
                icon: "warning",
                title: "Campos Vacíos",
                text: "Todos los campos son obligatorios.",
            });
            return;
        }

        if (isNaN(shift) || shift <= 0) {
            Swal.fire({
                icon: "error",
                title: "Desplazamiento Inválido",
                text: "Debe ser mayor a 0.",
            });
            return;
        }

        let formData = {
            receiver_id: parseInt(receiverId),
            subject: subject,
            body: body,
            shift: parseInt(shift),
            sender_id: parseInt(sender_id),
        };

        fetch("/users/encrypt-message", {
            // Ruta para cifrar el mensaje desde user
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify(formData),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    console.log(data);
                    modalUser.textContent = data.receiver_info;
                    modalUserShift.textContent = data.shift;
                    modalUserSubject.textContent = data.subject;
                    modalUserBody.textContent = data.body;

                    document.querySelector(
                        "input[name='user_receiver_id']"
                    ).value = data.receiver_id; // Actualiza el hidden con el ID real

                    openUserModal("confirmUserModal");
                } else {
                    console.error("Error al cifrar el mensaje:", data);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message || "Error al cifrar el mensaje.",
                    });
                }
            })
            .catch((error) => {
                console.error("Error en la solicitud fetch:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Ocurrió un error. Por favor, inténtalo de nuevo más tarde.", // Mensaje genérico de error
                });
            });
    });

    confirUsermMessageButton.addEventListener("click", () => {
        const receiverId = document.getElementById(
            "hidden-userReceiver-id"
        ).value;
        const subject = document.getElementById("modalUserSubject").textContent;
        const body = document.getElementById("modalUserBody").textContent;
        const shift = document.getElementById("modalUserShift").textContent;

        fetch("/users/save-message", {
            // Ruta para guardar el mensaje
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                receiver_id: receiverId,
                subject: subject,
                body: body,
                shift: shift,
            }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Error al enviar el mensaje"); // Lanza error si la respuesta no es ok
                }
                return response.json(); // Si la respuesta es ok, parsea el JSON
            })
            .then((data) => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Mensaje enviado",
                        text: "Tu mensaje ha sido enviado correctamente.",
                        confirmButtonText: "OK",
                    }).then(() => {
                        location.reload(); //Se ejecuta SOLO después de que el usuario cierre la alerta
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message || "Error al enviar el mensaje.",
                    }).then(() => {
                        location.reload(); //Se ejecuta SOLO después de que el usuario cierre la alerta
                    });
                }
            })
            .catch((error) => {
                console.error("Error en la solicitud fetch:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error en la solicitud",
                    text: error.message,
                });
            });
    });

    cancelUserMessageButton.addEventListener("click", () => {
        closeUserModal("send-userMessage-modal"); // Cerrar el modal de envío
        closeUserModal("confirmUserModal"); // Cerrar el modal de confirmación
        closeUserModal("userNewMessageForm"); // Cerrar el modal de confirm
    });
});
