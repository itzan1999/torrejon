document.addEventListener("DOMContentLoaded", () => {
    const btnActualizarUsuario = document.getElementById(
        "btnActualizarUsuario",
    );
    const form = document.getElementById("formEditarUsuario");

    const erroresMsg = {
        servidor: "Error al actualizar el usuario en el servidor.",
        campoVacio: "* Este campo es obligatorio.",
        usernameUsado: "* Este nombre de usuario no está disponible.",
        emailInvalido: "* El email no tiene un formato válido.",
        saldoInvalido:
            "* El saldo debe ser un número válido mayor o igual a 0.",
        rolInvalido: "* Debe seleccionar un rol.",
        actualizar: "Error al actualizar el usuario",
    };

    const svgOculto = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="#000000" fill-rule="evenodd" d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8"/></svg>`;
    const svgVisible = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path fill="#000000" fill-rule="evenodd" d="m18.67 16.973l2.755 2.755l-.849.848L3.85 3.85L4.697 3l2.855 2.855C8.932 5.303 10.432 5 12 5c4.808 0 8.972 2.848 11 7a12.65 12.65 0 0 1-4.33 4.973"/></svg>`;

    // Obtener ID del usuario desde la URL
    const urlParts = window.location.pathname.split("/");
    const id = urlParts[urlParts.length - 2];

    // Cargar datos del usuario al iniciar
    cargarUsuario();

    // Función asincrónica para cargar los datos del usuario
    async function cargarUsuario() {
        try {
            const res = await fetch(`/api/admin/usuarios/${id}`, {
                credentials: "include", // para enviar cookies de sesión
                headers: {
                    "X-CSRF-TOKEN": obtenerTokenCSRF(),
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            const data = await res.json();

            if (!res.ok) {
                mostrarAlert(data.message || erroresMsg.servidor, "danger");
                return;
            }

            const usuario = data.usuario;

            form.username.value = usuario.username;
            form.nombre.value = usuario.nombre;
            form.apellidos.value = usuario.apellidos;
            form.email.value = usuario.email;
            form.direccion.value = usuario.direccion;

            // if (document.getElementById("adminFields")) {
            //     const adminFields = document.getElementById("adminFields");
            //     adminFields.classList.remove("d-none");
            //     form.rol.value = usuario.rol;
            //     form.saldo.value = usuario.saldo ?? 0;
            // }
        } catch (error) {
            console.error("Error al cargar el usuario:", error);
            mostrarAlert(erroresMsg.servidor, "danger");
        }
    }

    // Evento para actualizar usuario
    btnActualizarUsuario.addEventListener("click", (event) => {
        event.preventDefault();

        const formdata = new FormData();
        formdata.append("username", form.username.value.trim().toLowerCase());
        formdata.append("nombre", form.nombre.value.trim());
        formdata.append("apellidos", form.apellidos.value.trim());
        formdata.append("email", form.email.value.trim());
        formdata.append("direccion", form.direccion.value.trim());

        // if (
        //     document.getElementById("adminFields") &&
        //     !document.getElementById("adminFields").classList.contains("d-none")
        // ) {
        //     formdata.append("rol", form.rol.value);
        //     formdata.append("saldo", form.saldo.value.trim());
        // }

        validarFormulario(formdata);
    });

    // Funciones de validación del formulario
    function validarFormulario(formdata) {
        limpiarErrores();

        const validaciones = [
            validarCamposVacios(formdata),
            validarEmail(),
            validarSaldo(),
            validarRol(),
        ];

        if (validaciones.every((v) => v === true)) {
            enviarFormulario(formdata);
        }
    }

    // Función para enviar el formulario al servidor
    function enviarFormulario(formdata) {
        const payload = {};
        formdata.forEach((value, key) => {
            payload[key] = value;
        });
        fetch(`/api/admin/usuarios/${id}`, {
            method: "PUT",
            credentials: "include", // para enviar cookies de sesión
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        })
            .then(async (res) => {
                const data = await res.json();
                limpiarErrores();

                if (res.ok && data.status === 200) {
                    mostrarAlert(
                        data.message || "Usuario actualizado correctamente",
                        "success",
                    );
                    cargarUsuario();
                } else if (
                    (data.status === 422 || data.status === 400) &&
                    data.errors
                ) {
                    mostrarErroresServidor(data.errors);
                    mostrarAlert(
                        data.message || erroresMsg.actualizar,
                        "danger",
                    );
                } else {
                    mostrarAlert(
                        data.message || erroresMsg.actualizar,
                        "danger",
                    );
                }
            })
            .catch((err) => {
                console.error("Error al actualizar el usuario", error);
                mostrarAlert(erroresMsg.servidor, "danger");
            });
    }

    // Funciones para limpiar errores y mostrar mensajes de error
    function limpiarErrores() {
        document.querySelectorAll("div.error").forEach((div) => {
            div.innerHTML = "";
            div.classList.add("hidden");
        });
    }

    // Función para validar campos vacíos
    function validarCamposVacios(formdata) {
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

    // Función para validar formato de email
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

    // Función para validar saldo (si el campo existe)
    function validarSaldo() {
        const saldo = form.saldo?.value.trim();
        const errorDiv = form.saldo?.closest(".mb-3")?.querySelector(".error");
        if (!errorDiv) return true;

        if (saldo !== "" && (isNaN(saldo) || parseFloat(saldo) < 0)) {
            errorDiv.textContent = erroresMsg.saldoInvalido;
            errorDiv.classList.remove("hidden");
            return false;
        }
        return true;
    }

    // Función para validar rol (si el campo existe)
    function validarRol() {
        if (
            !document.getElementById("adminFields") ||
            document.getElementById("adminFields").classList.contains("d-none")
        )
            return true;

        const rol = form.rol.value.trim();
        const errorDiv = form.rol.closest(".mb-3")?.querySelector(".error");
        const rolesValidos = ["user", "admin"];

        if (!rolesValidos.includes(rol)) {
            errorDiv.textContent = erroresMsg.rolInvalido;
            errorDiv.classList.remove("hidden");
            return false;
        }
        return true;
    }

    // Función para mostrar errores enviados desde el servidor
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

    // Evento para volver al listado de usuarios
    const btnVolver = document.getElementById("btnVolver");
    if (btnVolver) {
        btnVolver.addEventListener("click", () => {
            window.location.href = "/admin/panel/usuarios";
        });
    }
});
