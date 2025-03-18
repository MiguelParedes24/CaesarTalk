//Messages.js

function caesarCipher(text, shift) {
    const alphabetUpper = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
    const alphabetLower = "abcdefghijklmnñopqrstuvwxyz";
    const numbers = "0123456789";
    let result = "";

    for (let i = 0; i < text.length; i++) {
        let char = text[i];

        if (alphabetUpper.includes(char)) {
            let index =
                (alphabetUpper.indexOf(char) + shift) % alphabetUpper.length;
            if (index < 0) index += alphabetUpper.length;
            char = alphabetUpper[index];
        } else if (alphabetLower.includes(char)) {
            let index =
                (alphabetLower.indexOf(char) + shift) % alphabetLower.length;
            if (index < 0) index += alphabetLower.length;
            char = alphabetLower[index];
        } else if (numbers.includes(char)) {
            let index = (numbers.indexOf(char) + shift) % numbers.length;
            if (index < 0) index += numbers.length;
            char = numbers[index];
        }

        result += char;
    }

    return result;
}

//contador de Textareas
function configurarContador(textareaId, contadorId) {
    const textarea = document.getElementById(textareaId);
    const contador = document.getElementById(contadorId);

    if (textarea && contador) {
        textarea.addEventListener("keyup", () => {
            const caracteresEscritos = textarea.value.length;
            const caracteresRestantes = textarea.maxLength - caracteresEscritos;
            contador.textContent = `${caracteresRestantes} /1000`;

            if (caracteresRestantes < 100) {
                contador.style.color = "red";
            } else if (
                caracteresRestantes <= 300 &&
                caracteresRestantes >= 100
            ) {
                contador.style.color = "yellow";
            } else {
                contador.style.color = "#888";
            }
        });
    }
}

//Función para cerrar modales
function closeModal(modalId, success = null, message = null) {
    // Agregamos parámetros para controlar Swal
    let modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add("hidden");
    modal.classList.remove("flex");
    modal.setAttribute("aria-hidden", "true");

    if (document.querySelectorAll(".modal:not(.hidden)").length === 0) {
        document.body.classList.remove("overflow-hidden");
    }

    // Manejo de SweetAlert fuera de closeModal
    if (success !== null) {
        Swal.fire({
            icon: success ? "success" : "info", // Icono dinámico
            title: success ? "Mensaje enviado" : "Envio Cancelado", // Título dinámico
            text:
                message ||
                (success
                    ? "Tu mensaje ha sido enviado correctamente."
                    : "Has cancelado el mensaje."), // Mensaje dinámico
            confirmButtonText: "Entendido",
            allowOutsideClick: false,
            customClass: {
                title: "text-lg font-bold",
                content: "text-sm",
                confirmButton:
                    "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded",
            },
        }).then(() => {
            if (success) {
                location.reload();
            } else {
                location.reload();
            }
        });
    }
}

//Función para abrir modales
function openModal(modalId) {
    let modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.remove("hidden");
    modal.classList.add("flex");
    modal.setAttribute("aria-hidden", "false");

    // Mover el foco al primer elemento interactivo dentro del modal
    setTimeout(() => {
        modal.querySelector("button, input, textarea, a")?.focus();
    }, 10);

    // Bloquear scroll del fondo
    document.body.classList.add("overflow-hidden");
}

// Esperar a que el DOM cargue
document.addEventListener("DOMContentLoaded", function () {
    configurarContador("newMessageBody", "contadorNewMessageBody");
    configurarContador("replyMessageBody", "contadorReplyMessageBody");

    // Cerrar modal solo al hacer clic en la cruz (botones con data-modal-toggle)
    document.querySelectorAll("[data-modal-toggle]").forEach((button) => {
        button.addEventListener("click", function (event) {
            let modalId = this.getAttribute("data-modal-toggle");
            closeModal(modalId);
            event.stopPropagation(); // Evitar propagación accidental
        });
    });

    document.querySelectorAll(".modal").forEach((modal) => {
        modal.addEventListener("click", function (event) {
            const modalContent = modal.querySelector(".modal-content");

            // Si se hace clic en el modal (fondo oscuro), evitar el cierre
            if (!modalContent.contains(event.target)) {
                event.stopPropagation(); // Evita que el evento se propague
                event.preventDefault(); // Evita que se ejecute cualquier otro evento que pueda cerrar el modal
            }
        });
    });

    // Evento para abrir el modal de "Nuevo Mensaje"
    document
        .getElementById("newMessageBtn")
        ?.addEventListener("click", function () {
            openModal("new-message-modal");
        });

    //Abre un mensaje y muestra su contenido (tambien lo marca como leido)
    document.querySelectorAll(".view-message-btn").forEach((button) => {
        button.addEventListener("click", function () {
            let messageId = this.getAttribute("data-message-id");
            let messageElement = document.getElementById(messageId); // Obtén el elemento li del mensaje

            // Verifica si los elementos existen antes de modificarlos
            let hiddenSenderId = document.querySelector(
                "input[name='hidden_sender_id']"
            );
            let hiddenSenderEmail = document.getElementById(
                "hidden-sender-email"
            );
            let senderField = document.getElementById("messageSender");
            let subjectField = document.getElementById("messageSubject");
            let bodyField = document.getElementById("messageBody");

            // Limpia los campos antes de hacer la petición para evitar mostrar el texto cifrado
            if (senderField) senderField.value = "Cargando...";
            if (subjectField) subjectField.value = "Cargando...";
            if (bodyField) bodyField.value = "Cargando...";

            openModal("view-message-modal"); // Abre el modal

            fetch(`/messages/received/${messageId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Rellena los campos con los datos desencriptados
                        console.log(data);
                        if (senderField) {
                            senderField.value = `${data.message.sender.name} ${data.message.sender.lastName}`;
                            console.log(data.message.sender.name);
                        }
                        if (subjectField) {
                            subjectField.value = caesarCipher(
                                data.message.subject,
                                -data.message.shift
                            ); //Decifro el asunto que viene encriptado del servidor
                            console.log(data.message.subject);
                        }

                        if (bodyField) {
                            bodyField.value = caesarCipher(
                                data.message.body,
                                -data.message.shift
                            ); //Decifro el cuerpo que viene encriptado del servidor;
                            console.log(data.message.body);
                        }
                        if (hiddenSenderId) {
                            hiddenSenderId.value = data.message.sender.id;
                            console.log(data.message.sender.id);
                        }
                        if (hiddenSenderEmail) {
                            hiddenSenderEmail.value = data.message.sender.email;
                            console.log(data.message.sender.email);
                        }

                        // Marca el mensaje como leído si fue abierto por primera vez
                        fetch(`/messages/received/${messageId}/mark-as-read`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content"),
                                "Content-Type": "application/json",
                            },
                        })
                            .then((response) => response.json())
                            .then((readData) => {
                                if (readData.success) {
                                    console.log("Mensaje marcado como leído");

                                    // Actualiza la clase del elemento del mensaje para reflejar el estado "leído"
                                    if (messageElement) {
                                        messageElement.classList.remove(
                                            "bg-gray-100"
                                        );
                                        messageElement.classList.add(
                                            "bg-gray-300"
                                        );

                                        if (
                                            !messageElement.querySelector(
                                                ".read-status"
                                            )
                                        ) {
                                            if (!readSpan) {
                                                let readSpan =
                                                    document.createElement(
                                                        "span"
                                                    );
                                                readSpan.classList.add(
                                                    "ml-4",
                                                    "text-md",
                                                    "text-green-600",
                                                    "read-status"
                                                );
                                                readSpan.textContent =
                                                    "(Leído)";
                                                messageElement
                                                    .querySelector(
                                                        ".messageCard"
                                                    )
                                                    .appendChild(readSpan);
                                            }
                                        }
                                    }
                                } else {
                                    console.error(
                                        "Error al marcar como leído:",
                                        readData.error
                                    );
                                }
                            })
                            .catch((error) =>
                                console.error(
                                    "Error en la petición de marcar como leído:",
                                    error
                                )
                            );
                    } else {
                        console.error("Error: No se encontró el mensaje.");
                        document.getElementById("messageSubject").value =
                            "Error al cargar";
                        document.getElementById("messageBody").value =
                            "Error al cargar";
                    }
                })
                .catch((error) => {
                    console.error("Error al cargar el mensaje:", error);
                    document.getElementById("messageSubject").value =
                        "Error al cargar";
                    document.getElementById("messageBody").value =
                        "Error al cargar";
                });
        });
    });

    document
        .getElementById("replyMessageBtn")
        .addEventListener("click", function () {
            let receiverId = document.querySelector(
                "input[name='hidden_sender_id']"
            ).value;
            let receiverEmail = document.getElementById(
                "hidden-sender-email"
            ).value;
            console.log("Responder a:", receiverId, receiverEmail);
            if (!receiverId || !receiverEmail) {
                console.error("No se encontraron datos del receptor.");
                return;
            }

            document.querySelector("input[name='hidden_receiver_id']").value =
                receiverId;
            document.getElementById("replyMessageReceiver").value =
                receiverEmail;
            document
                .getElementById("replyMessageReceiver")
                .setAttribute("readonly", true);

            openModal("reply-message-modal");
        });

    //MENSAJE ENVIADO (por el usuario)
    document.querySelectorAll(".view-sentMessage-btn").forEach((button) => {
        button.addEventListener("click", function () {
            let messageId = this.getAttribute("data-sentMessage-id");
            let modal = document.getElementById("view-sentMessage-modal");

            // Verifica si los elementos existen antes de modificarlos
            let receiverField = document.getElementById("messageSentReceiver");
            let subjectField = document.getElementById("messageSentSubject");
            let bodyField = document.getElementById("messageSentBody");

            // Limpia los campos antes de hacer la petición para evitar mostrar el texto cifrado
            // Muestra el modal inmediatamente pero con los valores en "Cargando..."
            if (receiverField) receiverField.value = "Cargando...";
            if (subjectField) subjectField.value = "Cargando...";
            if (bodyField) bodyField.value = "Cargando...";

            openModal("view-sentMessage-modal"); // Abre el modal

            fetch(`/messages/sent/${messageId}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Solo llena los campos cuando los datos desencriptados lleguen
                        if (receiverField)
                            receiverField.value = `${data.message.receiver.name} ${data.message.receiver.lastName}`;
                        if (subjectField)
                            subjectField.value = data.message.subject;
                        if (bodyField) bodyField.value = data.message.body;

                        // Guarda el botón que activó el modal
                        modal.dataset.triggerButton = `[data-message-id="${messageId}"]`;

                        // Hace foco en el campo del remitente
                        receiverField.focus();
                    } else {
                        console.error("Error: No se encontró el mensaje.");
                        subjectField.value = "Error al cargar";
                        bodyField.value = "Error al cargar";
                    }
                })
                .catch((error) => {
                    console.error("Error al cargar el mensaje:", error);
                    subjectField.value = "Error al cargar";
                    bodyField.value = "Error al cargar";
                });
        });
    });

    // Selecciona los elementos
    const recipientInput = document.getElementById("newMessageReceiver");
    const recommendationDiv = document.getElementById("recommendations");

    // Función para buscar el usuario
    function buscarUsuario() {
        let query = recipientInput.value.trim();

        // Si el campo está vacío, oculta las recomendaciones
        if (query.length < 1) {
            recommendationDiv.classList.add("hidden");
            return;
        }

        fetch(`/search-users?query=${query}`)
            .then((response) => response.json())
            .then((data) => {
                recommendationDiv.innerHTML = "";

                if (data.length === 0) {
                    recommendationDiv.classList.add("hidden");
                    return;
                }

                data.forEach((user) => {
                    let div = document.createElement("div");
                    div.classList.add(
                        "p-2.5",
                        "text-md",
                        "cursor-pointer",
                        "hover:bg-gray-200",
                        "block",
                        "w-full",
                        "rounded-b-lg"
                    );
                    div.textContent = `${user.name} ${user.lastName} (${user.email})`;

                    div.addEventListener("click", function () {
                        seleccionarUsuario(user);
                        recommendationDiv.classList.add("hidden");
                    });

                    recommendationDiv.appendChild(div);
                });

                recommendationDiv.classList.remove("hidden");
            });
    }
    // Función para asignar el usuario seleccionado
    function seleccionarUsuario(user) {
        // Llenar el input con el email
        recipientInput.value = user.email;

        // Crea o actualiza el input hidden con el ID del usuario
        let userIdInput = document.querySelector("input[name='receiver_id']");
        if (!userIdInput) {
            userIdInput = document.createElement("input");
            userIdInput.type = "hidden";
            userIdInput.name = "receiver_id";
            userIdInput.id = "hidden-newmessage-receiver-id";
        }
        userIdInput.value = user.id;
        recipientInput.parentNode.appendChild(userIdInput);

        // Limpiar y ocultar recomendaciones
        recommendationDiv.innerHTML = "";
        recommendationDiv.classList.add("hidden");
    }

    // Eventos para buscar usuarios
    recipientInput.addEventListener("keyup", buscarUsuario);

    // Cerrar las recomendaciones si se hace clic fuera del input
    document.addEventListener("click", function (e) {
        if (
            !recipientInput.contains(e.target) &&
            !recommendationDiv.contains(e.target)
        ) {
            recommendationDiv.classList.add("hidden");
        }
    });

    document
        .querySelectorAll("#sendNewMessage, #sendReplyMessage")
        .forEach((button) => {
            button.addEventListener("click", function (e) {
                e.preventDefault();

                let isReply = this.id === "sendReplyMessage"; // Detecta si es una respuesta o un mensaje nuevo
                let formPrefix = isReply ? "replyMessage" : "newMessage"; // Define el prefijo correcto
                let modalId = isReply
                    ? "reply-message-modal"
                    : "new-message-modal"; // Determina qué modal cerrar

                // Obtiene el receiver_id según el formulario que se usa
                let receiver_id = isReply
                    ? document
                          .querySelector("input[name='hidden_receiver_id']")
                          ?.value.trim() // ID correcto para el modal de respuesta
                    : document
                          .querySelector("input[name='receiver_id']")
                          ?.value.trim(); // ID correcto para el nuevo mensaje

                let receiver = document
                    .getElementById(`${formPrefix}Receiver`)
                    ?.value.trim();
                let subject = document
                    .getElementById(`${formPrefix}Subject`)
                    ?.value.trim();
                let body = document
                    .getElementById(`${formPrefix}Body`)
                    ?.value.trim();
                let shift = document
                    .getElementById(`${formPrefix}Shift`)
                    ?.value.trim();
                let sender_id = document
                    .getElementById("hidden-sender-id")
                    ?.value.trim();

                // Validación de campos vacíos
                if (!receiver_id || !receiver || !subject || !body || !shift) {
                    Swal.fire({
                        icon: "warning",
                        title: "Campos Vacíos",
                        text: "Todos los campos son obligatorios.",
                        confirmButtonText: "Entendido",
                        allowOutsideClick: false,
                        customClass: {
                            title: "text-lg font-bold",
                            content: "text-sm",
                            confirmButton:
                                "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded",
                        },
                    });
                    return;
                }

                if (isNaN(shift) || shift <= 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Desplazamiento Inválido",
                        text: "Debe ser mayor a 0.",
                        confirmButtonText: "Entendido",
                        allowOutsideClick: false,
                        customClass: {
                            title: "text-lg font-bold",
                            content: "text-sm",
                            confirmButton:
                                "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded",
                        },
                    });
                    return;
                }

                if (shift > 9) {
                    Swal.fire({
                        icon: "warning",
                        title: "Desplazamiento Incorrecto",
                        text: "Se recomienda usar un valor entre 1 y 9.",
                        confirmButtonText: "Entendido",
                        allowOutsideClick: false,
                        customClass: {
                            title: "text-lg font-bold",
                            content: "text-sm",
                            confirmButton:
                                "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded",
                        },
                    });
                    return;
                }

                let formData = {
                    receiver_id: parseInt(receiver_id),
                    subject: subject,
                    body: body,
                    shift: parseInt(shift),
                    sender_id: parseInt(sender_id),
                };

                fetch("/encrypt-message", {
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
                            document.getElementById(
                                "modalSubject"
                            ).textContent = data.subject;
                            document.getElementById("modalBody").textContent =
                                data.body;
                            document.getElementById("modalShift").textContent =
                                data.shift;
                            document.getElementById(
                                "modalReceiver"
                            ).textContent = data.receiver_info;
                            document.getElementById(
                                "hidden-receiver-id"
                            ).value = data.receiver_id;

                            document.getElementById(
                                "confirmMessage"
                            ).dataset.message = JSON.stringify(data);

                            openModal("confirmModal");
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text:
                                    data.error ||
                                    "Error al encriptar el mensaje.",
                                confirmButtonText: "Entendido",
                                allowOutsideClick: false,
                                customClass: {
                                    title: "text-lg font-bold",
                                    content: "text-sm",
                                    confirmButton:
                                        "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded",
                                },
                            });
                        }
                    })
                    .catch((error) => {
                        console.error("Error en la solicitud:", error);
                        Swal.fire({
                            icon: "error",
                            title: "Error en la solicitud",
                            text: error.message,
                            confirmButtonText: "Entendido",
                            allowOutsideClick: false,
                            customClass: {
                                title: "text-lg font-bold",
                                content: "text-sm",
                                confirmButton:
                                    "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded",
                            },
                        });
                    });
            });
        });

    // Evento para confirmar mensaje
    document
        .getElementById("confirmMessage")
        ?.addEventListener("click", function () {
            let encryptedData = JSON.parse(this.dataset.message);
            encryptedData.receiver_id = parseInt(encryptedData.receiver_id);
            encryptedData.shift = parseInt(encryptedData.shift);

            fetch("/messages", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify(encryptedData),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        closeModal("confirmModal", true);
                    } else {
                        closeModal(
                            "confirmModal",
                            false,
                            data.error || "Mensaje cancelado correctamente."
                        ); //SweetAlert de error con mensaje del servidor
                    }
                })
                .catch((error) => {
                    console.error("Error en la solicitud:", error);
                    closeModal(
                        "confirmModal",
                        false,
                        error.message || "Error en la solicitud."
                    );
                });
        });

    document
        .getElementById("cancelMessage")
        ?.addEventListener("click", () =>
            closeModal("confirmModal", false, "Envío cancelado.")
        );
});
