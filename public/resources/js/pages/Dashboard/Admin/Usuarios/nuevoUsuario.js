document.addEventListener("DOMContentLoaded", () => {
    const btnCrearUsuario = document.getElementById("btnCrearUsuario");
    const form = document.getElementById("formNuevoUsuario");
    const btnVolver = document.getElementById("btnVolver");

    const erroresMsg = {
        servidor: "Error al crear el usuario en el servidor.",
        campoVacio: "* Este campo es obligatorio.",
        usernameUsado: "* Este nombre de usuario no está disponible.",
        emailInvalido: "* El email no tiene un formato válido.",
        saldoInvalido:
            "* El saldo debe ser un número válido mayor o igual a 0.",
        rolInvalido: "* Debe seleccionar un rol.",
        password: {
            conplejidadPassword:
                "* La contraseña no cumple con los requisitos mínimos de complejidad:",
            longitudMinima:
                "La contraseña debe tener como mínimo 8 carácteres.",
            minuscula: "La contraseña debe contener al menos una minúscula.",
            mayuscula: "La contraseña debe contener al menos una mayúscula.",
            numero: "La contraseña debe contener al menos un número.",
            caracterEspecial:
                "La contraseña debe contener al menos un carácter especial (!,$,#,&,...).",
            confirm: "* Las contraseñas no coinciden",
        },
        crear: "Error al crear el usuario",
    };

    const svgOculto = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
<path fill="#000000" fill-rule="evenodd"
d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8"/>
</svg>`;

    const svgVisible = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
<path fill="#000000" fill-rule="evenodd"
d="m18.67 16.973l2.755 2.755l-.849.848L3.85 3.85L4.697 3l2.855 2.855C8.932 5.303 10.432 5 12 5c4.808 0 8.972 2.848 11 7a12.65 12.65 0 0 1-4.33 4.973"/>
</svg>`;

    // Evento click para crear usuario
    btnCrearUsuario.addEventListener("click", (event) => {
        event.preventDefault();

        const formdata = new FormData();
        formdata.append("username", form.username.value.trim().toLowerCase());
        formdata.append("nombre", form.nombre.value.trim());
        formdata.append("apellidos", form.apellidos.value.trim());
        formdata.append("email", form.email.value.trim());
        formdata.append("direccion", form.direccion.value.trim());
        formdata.append("password", form.password.value.trim());
        formdata.append(
            "password_confirmation",
            form.password_confirmation.value.trim(),
        );
        formdata.append("rol", form.rol.value);
        formdata.append("saldo", form.saldo.value.trim());

        validarFormulario({ formdata });
    });

    // Validación general
    function validarFormulario({ formdata }) {
        limpiarErrores();

        const validaciones = [
            validarCamposVacios({ formdata }),
            validarEmail(),
            validarPassword(),
            passwordConfirm(),
            validarSaldo(),
            validarRol(),
        ];

        if (validaciones.every((v) => v === true)) {
            enviarFormulario(formdata);
        }
    }

    // Envío de formulario con fetch
    function enviarFormulario(formdata) {
        fetch("/api/admin/usuarios", {
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
                limpiarErrores();

                if (data.status === 201) {
                    form.reset();
                    mostrarAlert(
                        data.message || "Usuario creado correctamente",
                        "success",
                    );
                } else if (data.status === 422 && data.errors) {
                    mostrarErroresServidor(data.errors);
                    mostrarAlert(
                        data.message || "Hay errores en el formulario",
                        "danger",
                    );
                } else {
                    mostrarAlert(erroresMsg.crear, "danger");
                }
            })
            .catch(() => {
                mostrarAlert(erroresMsg.servidor, "danger");
            });
    }

    // Limpiar errores visuales
    function limpiarErrores() {
        document.querySelectorAll("div.error").forEach((div) => {
            div.innerHTML = "";
            div.classList.add("hidden");
        });
    }

    function validarCamposVacios({ formdata }) {
        let valido = true;

        formdata.forEach((value, key) => {
            const input = document.getElementById(key);
            if (!input) return;

            const errorDiv = input.closest(".mb-3")?.querySelector(".error");
            if (!errorDiv) return;

            if (!value && key !== "saldo") {
                errorDiv.textContent = erroresMsg.campoVacio;
                errorDiv.classList.remove("hidden");
                valido = false;
            }
        });

        return valido;
    }

    function validarEmail() {
        const email = form.email.value.trim();
        const errorDiv = form.email.closest(".mb-3").querySelector(".error");
        const regEx = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!regEx.test(email)) {
            errorDiv.textContent = erroresMsg.emailInvalido;
            errorDiv.classList.remove("hidden");
            return false;
        }

        return true;
    }

    function validarPassword() {
        const password = form.password.value.trim();
        const errorDiv = form.password.closest(".mb-3").querySelector(".error");

        errorDiv.innerHTML = "";
        errorDiv.classList.add("hidden");

        const lista = document.createElement("ul");
        let valido = true;

        if (password.length < 8) {
            agregarError(lista, erroresMsg.password.longitudMinima);
            valido = false;
        }
        if (!/[a-z]/.test(password)) {
            agregarError(lista, erroresMsg.password.minuscula);
            valido = false;
        }
        if (!/[A-Z]/.test(password)) {
            agregarError(lista, erroresMsg.password.mayuscula);
            valido = false;
        }
        if (!/[0-9]/.test(password)) {
            agregarError(lista, erroresMsg.password.numero);
            valido = false;
        }
        if (!/[!"#$%&'()*+,\-./:;<=>?@\[\]\\^_`{|}~]/.test(password)) {
            agregarError(lista, erroresMsg.password.caracterEspecial);
            valido = false;
        }

        if (!valido) {
            const titulo = document.createElement("span");
            titulo.textContent = erroresMsg.password.conplejidadPassword;
            errorDiv.appendChild(titulo);
            errorDiv.appendChild(lista);
            errorDiv.classList.remove("hidden");
        }

        return valido;
    }

    function passwordConfirm() {
        const password = form.password.value.trim();
        const confirm = form.password_confirmation.value.trim();
        const errorDiv = form.password_confirmation
            .closest(".mb-3")
            .querySelector(".error");

        if (password !== confirm) {
            errorDiv.textContent = erroresMsg.password.confirm;
            errorDiv.classList.remove("hidden");
            return false;
        }

        return true;
    }

    function validarSaldo() {
        const saldo = form.saldo.value.trim();
        const errorDiv = form.saldo.closest(".mb-3").querySelector(".error");

        if (saldo !== "" && (isNaN(saldo) || parseFloat(saldo) < 0)) {
            errorDiv.textContent = erroresMsg.saldoInvalido;
            errorDiv.classList.remove("hidden");
            return false;
        }

        return true;
    }

    function validarRol() {
        const rol = form.rol.value.trim();
        const errorDiv = form.rol
            .closest(".mb-3, .mb-4")
            .querySelector(".error");

        const rolesValidos = ["user", "admin"];

        errorDiv.textContent = "";
        errorDiv.classList.add("hidden");

        if (!rolesValidos.includes(rol)) {
            errorDiv.textContent = erroresMsg.rolInvalido;
            errorDiv.classList.remove("hidden");
            return false;
        }

        return true;
    }

    function agregarError(lista, texto) {
        const li = document.createElement("li");
        li.textContent = texto;
        lista.appendChild(li);
    }

    function mostrarErroresServidor(errors) {
        for (const campo in errors) {
            const input = document.getElementById(campo);
            if (!input) continue;

            const errorDiv = input.closest(".mb-3")?.querySelector(".error");
            if (!errorDiv) continue;

            errorDiv.textContent = errors[campo].join(", ");
            errorDiv.classList.remove("hidden");
        }
    }

    /* Toggle contraseña */
    document.querySelectorAll(".toggle-password").forEach((btn) => {
        const input = document.getElementById(btn.dataset.target);
        const icon = btn.querySelector(".icon-eye");

        btn.addEventListener("click", () => {
            const hidden = input.type === "password";
            input.type = hidden ? "text" : "password";
            icon.innerHTML = hidden ? svgVisible : svgOculto;
        });
    });

    // Volver a la lista de usuarios
    btnVolver.addEventListener("click", () => {
        window.location.href = "/admin/panel/usuarios";
    });
});
