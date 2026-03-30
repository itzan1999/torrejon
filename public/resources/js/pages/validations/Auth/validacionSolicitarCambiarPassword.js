const btnForgot = document.getElementById("btnForgot");
const formForgot = document.getElementById("formForgot");
const erroresMsg = {
    campoVacio: "* Este campo es obligatorio.",
    emailInvalido: "* El email no tiene un formato válido.",
    servidor: "Error al solicitar el cambio de contraseña.",
    noExiste: "Este email no está registrado.",
    enviado: "Se ha enviado un email para restablecer la contraseña.",
};

// Evento click del botón de solicitud de cambio de contraseña
btnForgot.addEventListener("click", (event) => {
    event.preventDefault();

    const formdata = new FormData();
    formdata.append("email", formForgot.email.value.trim());

    validarFormulario({ formdata });
});

// Función principal de validación del formulario de solicitud de cambio de contraseña
function validarFormulario({ formdata }) {
    limpiarErrores();

    const email = formForgot.email.value.trim();
    const validaciones = [];

    validaciones.push(validarCampoVacio(email));
    validaciones.push(validarEmail(email));

    if (validaciones.every((v) => v === true)) {
        const msgDiv = document.getElementById("msg");
        fetch("/api/auth/reset-password", {
            method: "POST",
            credentials: "include", // para enviar cookies de sesión
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
            },
            body: formdata,
        })
            .then((res) => res.json())
            .then((data) => {
                msgDiv.classList.remove("error", "success");
                msgDiv.textContent = "";

                // Si el servidor responde OK
                if (data.status === 200) {
                    formForgot.reset(); //Limpieza de los imputs
                    msgDiv.classList.add("success");
                    msgDiv.textContent = erroresMsg.enviado;
                }

                // Si el email no existe o hay error de validación
                else if (data.status === 400 && data.errors) {
                    mostrarErroresServidor(data.errors);
                    msgDiv.classList.add("error");
                    msgDiv.textContent = data.message;
                }

                // Error genérico
                else {
                    msgDiv.classList.add("error");
                    msgDiv.textContent = erroresMsg.servidor;
                }
            })
            .catch((error) => {
                msgDiv.classList.add("error");
                msgDiv.textContent = erroresMsg.servidor;
                console.error(error);
            })
            .finally(() => {
                // Limpiar mensaje después de 4 segundos
                setTimeout(() => {
                    formForgot.email.value = "";
                    msgDiv.textContent = "";
                    msgDiv.classList.remove("error", "success");
                }, 4000);
            });
    }
}

// Funciones de validación y manejo de errores
function limpiarErrores() {
    const divs = document.querySelectorAll("div.error");
    divs.forEach((div) => (div.textContent = ""));
}

// Función para validar que un campo no esté vacío
function validarCampoVacio(valor) {
    const errorDiv = document.querySelector("#email + div");

    if (!valor) {
        errorDiv.textContent = erroresMsg.campoVacio;
        errorDiv.classList.remove("hidden");
        return false;
    }

    return true;
}

// Función para validar el formato del email
function validarEmail(email) {
    const errorDiv = document.querySelector("#email + div");
    const regEx = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (!regEx.test(email)) {
        errorDiv.textContent = erroresMsg.emailInvalido;
        errorDiv.classList.remove("hidden");
        return false;
    }

    return true;
}

// Función para mostrar errores específicos enviados por el servidor
function mostrarErroresServidor(errors) {
    if (errors.email) {
        const errorDiv = document.querySelector("#email + div");
        errorDiv.textContent = errors.email[0];
        errorDiv.classList.remove("hidden");
    }
}
