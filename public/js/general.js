// Esperar a que el DOM cargue
document.addEventListener("DOMContentLoaded", function () {
    fetch("/welcome-message", {
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "Content-Type": "application/json",
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.message) {
                const message = data.message;

                let title = "¡Bienvenido de nuevo!";
                let html = `Tu último acceso fue ${message.last_login_at_friendly} <br>`;

                // Verifica si es un nuevo usuario
                if (message.welcome_message) {
                    title = message.welcome_message;
                    html = "¡Esperamos que disfrutes de tu experiencia!";
                } else {
                    // Formatea la fecha y hora
                    const lastLoginDate = new Date(
                        message.last_login_at_detailed
                    );
                    const formattedDate = lastLoginDate.toLocaleDateString(
                        "es-ES",
                        {
                            // Ajusta el idioma si es necesario
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                        }
                    );
                    const formattedTime = lastLoginDate.toLocaleTimeString(
                        "es-ES",
                        {
                            // Ajusta el idioma si es necesario
                            hour: "numeric",
                            minute: "numeric",
                            second: "numeric",
                        }
                    );

                    html += `(fecha: ${formattedDate} a las ${formattedTime})`;
                    html += "<br>";
                    if (message.new_messages_count > 0) {
                        html += `Tienes ${message.new_messages_count} ${
                            message.new_messages_count > 1
                                ? "mensajes"
                                : "mensaje"
                        } ${
                            message.new_messages_count > 1 ? "nuevos" : "nuevo"
                        }`;
                    } else {
                        html +=
                            " De momento no tienes mensajes, ¡comienza a chatear!";
                    }
                }

                Swal.fire({
                    title: title,
                    html: html,
                    icon: "success",
                    confirmButtonText: "¡Entendido!",
                    allowOutsideClick: false,
                    customClass: {
                        title: "text-lg font-bold",
                        content: "text-sm",
                        confirmButton:
                            "bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded",
                    },
                });
            }
        });

    console.log("Funcion dropdown");
    //Funcion para mostrar la notificacion y el contador de mensajes recibidos sin abrir en el Aside
    const notification = document.getElementById("dropdown-message");
    const closeButton = notification.querySelector(
        '[data-dismiss-target="#dropdown-message"]'
    );
    const notificationDismissedKey = "notificationDismissed"; // Clave para sessionStorage
    function updateMessageCount() {
        fetch("/unread-count", {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
                Accept: "application/json",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(
                        `HTTP error! status: ${response.status}, message: ${response.statusText}`
                    );
                }
                return response.json();
            })
            .then((data) => {
                let badge = document.getElementById("message-count");
                if (badge) {
                    badge.textContent = data.count;
                    badge.classList.toggle("hidden", data.count === 0);
                    badge.classList.toggle("inline-flex", data.count > 0);
                }
                if (notification) {
                    // Obtiene el valor de sessionStorage
                    let notificationDismissed =
                        sessionStorage.getItem(notificationDismissedKey) ===
                        "true";

                    // Muestra la notificación si hay mensajes y no haya sido cerrada
                    if (data.count > 0 && !notificationDismissed) {
                        notification.classList.remove("hidden");
                    } else {
                        notification.classList.add("hidden");
                    }

                    if (closeButton) {
                        closeButton.addEventListener("click", () => {
                            // Guarda en sessionStorage que la notificación fue cerrada
                            sessionStorage.setItem(
                                notificationDismissedKey,
                                "true"
                            );
                            notification.classList.add("hidden");
                        });
                    }
                }
            })
            .catch((error) => {
                console.error("Error obteniendo los mensajes:", error);
            });
    }

    // Llamaa a updateMessageCount al cargar la página
    updateMessageCount();

    // Escucha el evento de inicio de sesión
    window.addEventListener("userLoggedIn", () => {
        // Elimina el valor de sessionStorage al iniciar sesión
        sessionStorage.removeItem(notificationDismissedKey);
        updateMessageCount(); // Volver a verificar los mensajes
    });
});
