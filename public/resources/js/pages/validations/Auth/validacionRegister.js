const btnRegister = document.getElementById("btnRegister");
const formRegister = document.getElementById("formRegister");
const erroresMsg = {
    servidor: "Error al registrar el usuario en el servidor.",
    campoVacio: "* Este campo es obligatorio.",
    usernameUsado: "* Este nombre de usuario no esta disponible.",
    emailInvalido: "* El email no tiene un formato valido.",
    password: {
        conplejidadPassword:
            "* La contraseña no cumple con los requisitos minimos de complejidad:",
        longitudMinima: "La contraseña debe tener como minimo 8 carácteres.",
        minuscula: "La contraseña debe contener al menos una minuscula.",
        mayuscula: "La contraseña debe contener al menos una mayúscula.",
        numero: "La contraseña debe contener al menos un número.",
        caracterEspecial:
            "La contraseña debe contener al menos un caracter especial (!,$,#,&,...).",
        confirm: "* Las contraseñas no coinciden",
    },
    politicaPrivacidad: "* Debe aceptar la política de privacidad",
    register: "Error al crear la cuenta",
};
const svgOculto = ` <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"> 
<path fill="#000000" fill-rule="evenodd" d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8"/> 
</svg>`;
const svgVisible = ` <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"> 
<path fill="#000000" fill-rule="evenodd" d="m18.67 16.973l2.755 2.755l-.849.848L3.85 3.85L4.697 3l2.855 2.855C8.932 5.303 10.432 5 12 5c4.808 0 8.972 2.848 11 7a12.65 12.65 0 0 1-4.33 4.973M8.486 6.79l1.664 1.664a4 4 0 0 1 5.398 5.398l2.255 2.255c1.574-1 2.904-2.403 3.845-4.106C19.686 8.45 16.034 6.2 12 6.2a10.8 10.8 0 0 0-3.514.59m6.152 6.152a2.8 2.8 0 0 0-3.579-3.579zm1.81 5.204c-1.38.552-2.88.855-4.448.855c-4.808 0-8.972-2.848-11-7a12.65 12.65 0 0 1 4.33-4.973l.867.867A11.36 11.36 0 0 0 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8a10.8 10.8 0 0 0 3.514-.59l.934.935zM8.453 10.15l.909.91a2.8 2.8 0 0 0 3.579 3.579l.91.908a4 4 0 0 1-5.398-5.398z"/> 
</svg>`;

// Evento click del botón de registro
btnRegister.addEventListener("click", (event) => {
    event.preventDefault();
    const formdata = new FormData();

    formdata.append(
        "username",
        formRegister.username.value.trim().toLowerCase(),
    );
    formdata.append("nombre", formRegister.nombre.value.trim());
    formdata.append("apellidos", formRegister.apellidos.value.trim());
    formdata.append("email", formRegister.email.value.trim());
    formdata.append("direccion", formRegister.direccion.value.trim());
    formdata.append("password", formRegister.password.value.trim());
    formdata.append(
        "password_confirmation",
        formRegister.password_confirmation.value.trim(),
    );
    validarFormulario({ formdata });
});

// Función principal de validación del formulario de registro
function validarFormulario({ formdata }) {
    const validaciones = [];

    limpiarErrores();
    validaciones.push(validarCamposVacios({ formdata }));
    validaciones.push(validarEmail({ email: formRegister.email.value.trim() }));
    validaciones.push(
        validarPassword({ password: formRegister.password.value.trim() }),
    );
    validaciones.push(
        passwordConfirm({
            password: formRegister.password.value.trim(),
            confirm: formRegister.password_confirmation.value.trim(),
        }),
    );
    validaciones.push(
        validarCheckbox({
            check: formRegister.politicaPrivacidad.checked,
        }),
    );

    if (validaciones.every((val) => val === true)) {
        const msgDiv = document.getElementById("msg");
        fetch("/api/auth/register", {
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
                // LIMPIO PREVIO DE CLASES
                msgDiv.classList.remove("error", "success");
                msgDiv.textContent = "";

                if (data.status === 201) {
                    formRegister.reset(); // Limpiar campos
                    limpiarErrores(); // Limpiar errores previos
                    msgDiv.classList.add("success");
                    msgDiv.textContent = data.message;
                } else if (data.status === 422 && data.errors) {
                    mostrarErroresServidor(data.errors);
                    msgDiv.classList.add("error");
                    msgDiv.textContent = data.message;
                } else {
                    msgDiv.classList.add("error");
                    msgDiv.textContent = erroresMsg.register;
                }
            })
            .catch((error) => {
                msgDiv.classList.add("error");
                msgDiv.classList.remove("success");
                msgDiv.textContent = erroresMsg.servidor;
                console.error(error);
            })
            .finally(() => {
                // Limpiar mensaje después de 4 segundos
                setTimeout(() => {
                    msgDiv.textContent = "";
                    msgDiv.classList.remove("error", "success");
                }, 4000);
            });
    }
}

// Funciones de limpiar errores, validar campos, mostrar errores del servidor, validar password, confirmar password, validar email y validar checkbox
function limpiarErrores() {
    const divs = document.querySelectorAll("div.error");

    for (const div of divs) {
        div.innerHTML = "";
    }
}

// Función para validar campos vacíos
function validarCamposVacios({ formdata }) {
    const datos = Array.from(formdata.entries());
    let valido = true;

    datos.forEach((campo) => {
        const nombre = campo[0];
        const dato = campo[1];

        const input = document.querySelector(`#${nombre}`);
        if (!input) return;

        // Buscamos el .error dentro del contenedor del campo
        const errorDiv = input
            .closest(".mb-3, .form-check, .col-12")
            ?.querySelector(".error");

        if (!errorDiv) return;

        if (!dato) {
            errorDiv.textContent = erroresMsg.campoVacio;
            errorDiv.classList.remove("hidden");
            valido = false;
        } else {
            errorDiv.classList.add("hidden");
        }
    });

    return valido;
}

// Función para mostrar errores de validación del servidor
function mostrarErroresServidor(errors) {
    // Recorremos cada campo que tenga errores
    for (const campo in errors) {
        const errorDiv =
            document.querySelector(`#${campo} ~ div.error`) ||
            document.querySelector(`#${campo} + div.error`);
        if (errorDiv) {
            errorDiv.textContent = errors[campo].join(", ");
            errorDiv.classList.remove("hidden");
        }
    }
}

// Función para validar la complejidad de la contraseña
function validarPassword({ password }) {
    const errorDiv = document
        .querySelector("#password")
        .closest(".mb-3")
        .querySelector(".error");
    errorDiv.innerHTML = ""; // Limpiar errores anteriores
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

    // Caracter especial
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

// Función para confirmar que las contraseñas coinciden
function passwordConfirm({ password, confirm }) {
    const confirmValido = password === confirm;
    const errorDiv = document
        .querySelector("#password_confirmation")
        .closest(".mb-3")
        .querySelector(".error");

    if (!confirmValido) {
        errorDiv.innerHTML += "<br>" + erroresMsg.password.confirm;
        errorDiv.classList.remove("hidden");
    }

    return confirmValido;
}

// Función para validar el formato del email
function validarEmail({ email }) {
    const errorDiv = document.querySelector("#email + div");
    const regEx = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let emailValido = true;

    if (!regEx.test(email)) {
        emailValido = false;
        errorDiv.innerHTML += "<br>" + erroresMsg.emailInvalido;

        errorDiv.classList.remove("hidden");
    }

    return emailValido;
}

// Función para validar que el checkbox de política de privacidad esté marcado
function validarCheckbox({ check }) {
    const errorDiv = document
        .querySelector("#politicaPrivacidad")
        .closest(".form-check")
        .querySelector(".error");
    let checkValido = true;

    if (!check) {
        errorDiv.textContent = erroresMsg.politicaPrivacidad;
        errorDiv.classList.remove("hidden");
        checkValido = false;
    } else {
        errorDiv.classList.add("hidden");
    }

    return checkValido;
}

// Para cambiar la visibilidad de la contraseña
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
