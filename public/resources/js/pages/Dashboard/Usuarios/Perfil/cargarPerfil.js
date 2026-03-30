const btnChangePassword = document.getElementById("btnChangePassword");
const formChangePassword = document.getElementById("formChangePassword");

const erroresMsg = {
    servidor: "Error en el servidor.",
    campoVacio: "* Este campo es obligatorio.",
    password: {
        conplejidadPassword:
            "* La contraseña no cumple con los requisitos mínimos:",
        longitudMinima: "Mínimo 8 caracteres.",
        minuscula: "Debe tener una minúscula.",
        mayuscula: "Debe tener una mayúscula.",
        numero: "Debe tener un número.",
        caracterEspecial: "Debe tener un carácter especial.",
        confirm: "* Las contraseñas no coinciden",
    },
};

const svgOculto = `
<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
<path fill="#000000" d="M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6"/>
</svg>`;

const svgVisible = `
<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
<path fill="#000000" d="m18.67 16.973l2.755 2.755l-.849.848L3.85 3.85L4.697 3l2.855 2.855C8.932 5.303 10.432 5 12 5c4.808 0 8.972 2.848 11 7a12.65 12.65 0 0 1-4.33 4.973"/>
</svg>`;

// Inicializar iconos
document.querySelectorAll(".icon-eye").forEach((icon) => {
    icon.innerHTML = svgOculto;
});

// Evento botón principal
btnChangePassword.addEventListener("click", (event) => {
    event.preventDefault();

    const formdata = new FormData(formChangePassword);

    validarFormulario({ formdata });
});

// VALIDACIÓN PRINCIPAL
function validarFormulario({ formdata }) {
    const validaciones = [];

    limpiarErrores();

    validaciones.push(validarCamposVacios({ formdata }));
    validaciones.push(
        validarPassword({
            password: formChangePassword.new_password.value.trim(),
        }),
    );
    validaciones.push(
        passwordConfirm({
            password: formChangePassword.new_password.value.trim(),
            confirm: formChangePassword.new_password_confirmation.value.trim(),
        }),
    );

    if (validaciones.every((v) => v === true)) {
        enviarFormulario(formdata);
    }
}

// PETICIÓN FETCH
function enviarFormulario(formdata) {
    fetch("/api/usuarios/cambiarPassword", {
        method: "PATCH",
        credentials: "include",
        headers: {
            "X-CSRF-TOKEN": obtenerTokenCSRF(),
            Accept: "application/json",
        },
        body: formdata,
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.status === 200) {
                formChangePassword.reset();
                limpiarErrores();
                mostrarAlert(data.message, "success");
                setTimeout(() => {
                    fetch("/logout", {
                        method: "POST",
                        credentials: "include",
                        headers: {
                            "X-CSRF-TOKEN": obtenerTokenCSRF(),
                        },
                    }).then(() => {
                        window.location.href = "/login";
                    });
                }, 1200);
                return;
            } else if (data.status === 422 && data.errors) {
                mostrarErroresServidor(data.errors);
                mostrarAlert(data.message, "danger");
            } else {
                mostrarAlert(data.message || erroresMsg.servidor, "danger");
            }
        })
        .catch((error) => {
            console.error("Error al cambiar la contraseña", error);
            mostrarAlert(erroresMsg.servidor, "danger");
        });
}

// LIMPIAR ERRORES
function limpiarErrores() {
    document.querySelectorAll("div.error").forEach((div) => {
        div.innerHTML = "";
        div.classList.add("hidden");
    });
}

// CAMPOS VACÍOS
function validarCamposVacios({ formdata }) {
    let valido = true;

    formdata.forEach((valor, nombre) => {
        const input = document.getElementById(nombre);
        const errorDiv = input.closest(".mb-3").querySelector(".error");

        if (!valor.trim()) {
            errorDiv.textContent = erroresMsg.campoVacio;
            errorDiv.classList.remove("hidden");
            valido = false;
        }
    });

    return valido;
}

// Función para validar la contraseña
function validarPassword({ password }) {
    const errorDiv = document
        .querySelector("#new_password")
        .closest(".mb-3")
        .querySelector(".error");

    errorDiv.innerHTML = "";
    errorDiv.classList.add("hidden");

    const listaRequisitos = document.createElement("ul");
    let passwordValida = true;

    // Longitud mínima
    if (password.length < 8) {
        const li = document.createElement("li");
        li.textContent = erroresMsg.password.longitudMinima;
        listaRequisitos.appendChild(li);
        passwordValida = false;
    }

    // Minúscula
    if (!/[a-z]/.test(password)) {
        const li = document.createElement("li");
        li.textContent = erroresMsg.password.minuscula;
        listaRequisitos.appendChild(li);
        passwordValida = false;
    }

    // Mayúscula
    if (!/[A-Z]/.test(password)) {
        const li = document.createElement("li");
        li.textContent = erroresMsg.password.mayuscula;
        listaRequisitos.appendChild(li);
        passwordValida = false;
    }

    // Número
    if (!/[0-9]/.test(password)) {
        const li = document.createElement("li");
        li.textContent = erroresMsg.password.numero;
        listaRequisitos.appendChild(li);
        passwordValida = false;
    }

    // Caracter especial (MISMA REGEX QUE LARAVEL Password::symbols())
    if (!/[!"#$%&'()*+,\-./:;<=>?@\[\]\\^_`{|}~]/.test(password)) {
        const li = document.createElement("li");
        li.textContent = erroresMsg.password.caracterEspecial;
        listaRequisitos.appendChild(li);
        passwordValida = false;
    }

    // Mostrar errores si hay alguno
    if (!passwordValida) {
        const titulo = document.createElement("span");
        titulo.textContent = erroresMsg.password.conplejidadPassword;

        errorDiv.appendChild(titulo);
        errorDiv.appendChild(listaRequisitos);
        errorDiv.classList.remove("hidden");
    }

    return passwordValida;
}

// Función para confirmar la contraseña
function passwordConfirm({ password, confirm }) {
    const errorDiv = document
        .querySelector("#new_password_confirmation")
        .closest(".mb-3")
        .querySelector(".error");

    if (password !== confirm) {
        errorDiv.textContent = erroresMsg.password.confirm;
        errorDiv.classList.remove("hidden");
        return false;
    }

    return true;
}

// ERRORES BACKEND
function mostrarErroresServidor(errors) {
    for (const campo in errors) {
        const input = document.getElementById(campo);
        if (!input) continue;

        const errorDiv = input.closest(".mb-3").querySelector(".error");
        errorDiv.textContent = errors[campo].join(", ");
        errorDiv.classList.remove("hidden");
    }
}

// TOGGLE PASSWORD
document.querySelectorAll(".toggle-password").forEach((btn) => {
    const input = document.getElementById(btn.dataset.target);
    const icon = btn.querySelector(".icon-eye");

    btn.addEventListener("click", () => {
        const isHidden = input.type === "password";

        input.type = isHidden ? "text" : "password";
        icon.innerHTML = isHidden ? svgVisible : svgOculto;

        btn.classList.toggle("visible", isHidden);
    });
});
