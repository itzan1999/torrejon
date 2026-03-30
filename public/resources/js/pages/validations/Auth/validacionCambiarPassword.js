const btnChangePassword = document.getElementById("btnChangePassword");
const formChangePassword = document.getElementById("formChangePassword");
const erroresMsg = {
    campoVacio: "* Este campo es obligatorio.",
    password: {
        conplejidadPassword:
            "* La contraseña no cumple con los requisitos mínimos:",
        longitudMinima: "La contraseña debe tener como mínimo 8 caracteres.",
        minuscula: "Debe contener al menos una letra minúscula.",
        mayuscula: "Debe contener al menos una letra mayúscula.",
        numero: "Debe contener al menos un número.",
        caracterEspecial:
            "Debe contener al menos un carácter especial (!,$,#,&,...).",
        confirm: "* Las contraseñas no coinciden.",
    },
    servidor: "Error al cambiar la contraseña.",
    success: "Contraseña cambiada correctamente, ya tienes acceso a tu cuenta.",
};

// Boton para mostrar la contraseña de los dos inputs a la vez
const toggleAll = document.getElementById("toggleAllPasswords");
const inputsPasswords = [
    document.getElementById("password"),
    document.getElementById("password_confirmation"),
];
toggleAll.addEventListener("change", () => {
    const mostrar = toggleAll.checked;
    inputsPasswords.forEach((input) => {
        input.type = mostrar ? "text" : "password";
    });
});

// Evento que se le añade al boton de cambiar contraseña
btnChangePassword.addEventListener("click", (event) => {
    event.preventDefault();

    const formdata = new FormData();
    formdata.append("token", obtenerToken());
    formdata.append("password", formChangePassword.password.value.trim());
    formdata.append(
        "password_confirmation",
        formChangePassword.password_confirmation.value.trim(),
    );

    validarFormulario({ formdata });
});

// Función para validar el formulario
function validarFormulario({ formdata }) {
    limpiarErrores();

    const password = formChangePassword.password.value.trim();
    const confirm = formChangePassword.password_confirmation.value.trim();

    const validaciones = [];

    validaciones.push(validarCamposVacios({ formdata }));
    validaciones.push(validarPassword({ password }));
    validaciones.push(passwordConfirm({ password, confirm }));

    if (validaciones.every((val) => val === true)) {
        const msgDiv = document.getElementById("msg");
        fetch("/api/auth/cambiar-password", {
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

                // Contraseña cambiada correctamente
                if (data.status === 200) {
                    formChangePassword.reset();
                    msgDiv.classList.add("success");
                    msgDiv.textContent = erroresMsg.success;
                }

                // Errores de validación del servidor
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
                    // Quita los datos del campo de la contraseñas y el texto del div
                    formChangePassword.password.value = "";
                    formChangePassword.password_confirmation.value = "";
                    msgDiv.textContent = "";

                    // Pone los botones por defecto y oculta la contraseña del campo
                    toggleAll.checked = false;
                    inputsPasswords.forEach((input) => {
                        input.type = "password";
                    });

                    // Quita la clases del div
                    msgDiv.classList.remove("error", "success");
                }, 4000);
            });
    }
}

// Función para optener los tokens
function obtenerToken() {
    const params = new URLSearchParams(window.location.search);
    return String(params.get("token").trim() || "");
}

// Función para limpiarErrores
function limpiarErrores() {
    const divs = document.querySelectorAll("div.error");
    divs.forEach((div) => {
        div.textContent = "";
        div.classList.add("hidden");
    });
}

// Función para validar los campos vacios
function validarCamposVacios({ formdata }) {
    let valido = true;

    for (const [campo, valor] of formdata.entries()) {
        // Evitar validar campos ocultos como email o token
        if (campo === "token" || campo === "email") continue;

        const errorDiv = document.querySelector(`#${campo} + div.error`);

        if (!valor) {
            if (errorDiv) {
                errorDiv.textContent = erroresMsg.campoVacio;
                errorDiv.classList.remove("hidden");
            }
            valido = false;
        }
    }

    return valido;
}

// Función para validar la contraseña
function validarPassword({ password }) {
    const errorDiv = document.querySelector("#password + div.error");
    errorDiv.innerHTML = "";
    errorDiv.classList.add("hidden");

    const lista = document.createElement("ul");
    let valido = true;

    if (password.length < 8) {
        lista.append(crearLi(erroresMsg.password.longitudMinima));
        valido = false;
    }
    if (!/[a-z]/.test(password)) {
        lista.append(crearLi(erroresMsg.password.minuscula));
        valido = false;
    }
    if (!/[A-Z]/.test(password)) {
        lista.append(crearLi(erroresMsg.password.mayuscula));
        valido = false;
    }
    if (!/[0-9]/.test(password)) {
        lista.append(crearLi(erroresMsg.password.numero));
        valido = false;
    }
    if (!/[!"#$%&'()*+,\-./:;<=>?@\[\]\\^_`{|}~]/.test(password)) {
        lista.append(crearLi(erroresMsg.password.caracterEspecial));
        valido = false;
    }

    if (!valido) {
        const titulo = document.createElement("span");
        titulo.textContent = erroresMsg.password.conplejidadPassword;
        errorDiv.append(titulo, lista);
        errorDiv.classList.remove("hidden");
    }

    return valido;
}

// Función para confirmar la contraseña
function passwordConfirm({ password, confirm }) {
    const errorDiv = document.querySelector(
        "#password_confirmation + div.error",
    );

    // Campo vacío
    if (!confirm) {
        errorDiv.textContent = erroresMsg.campoVacio;
        errorDiv.classList.remove("hidden");
        return false;
    }

    // No coinciden
    if (password !== confirm) {
        errorDiv.textContent = erroresMsg.password.confirm;
        errorDiv.classList.remove("hidden");
        return false;
    }

    return true;
}

// Función para mostrar los errores del servidor
function mostrarErroresServidor(errors) {
    for (const campo in errors) {
        const errorDiv = document.querySelector(`#${campo} + div.error`);

        if (errorDiv) {
            errorDiv.textContent = errors[campo].join(", ");
            errorDiv.classList.remove("hidden");
        }
    }
}

// Función para crear los elemenos de la lista
function crearLi(texto) {
    const li = document.createElement("li");
    li.textContent = texto;
    return li;
}
