document.addEventListener("DOMContentLoaded", () => {
    const btnCrearProducto = document.getElementById("btnCrearProducto");
    const form = document.getElementById("formNuevoProducto");
    const divErrorNutricional = document.getElementById("errorNutricional");
    const btnVolver = document.getElementById("btnVolver");

    const erroresMsg = {
        servidor: "Error al crear el producto en el servidor.",
        campoVacio: "* Este campo es obligatorio.",
        precioInvalido: "* El precio debe ser un número válido.",
        stockInvalido: "* El stock debe ser un número entero.",
        tamanyoInvalido: "* El tamaño debe ser un número válido.",
        unidadMedida: "* Debe seleccionar una unidad de medida.",
        pathImagen:
            "* Debe seleccionar una imagen válida (jpg, jpeg, png, gif).",
        informacionNutricional:
            "* Todos los campos de información nutricional son obligatorios.",
        crear: "Error al crear el producto",
    };

    const camposObligatorios = [
        "nombre",
        "precio",
        "stock",
        "tamanyo",
        "unidad_medida",
        "path_imagen",
    ];

    const camposNutricionales = [
        "calorias",
        "grasas",
        "grasas_saturadas",
        "hidratos",
        "azucares",
        "proteinas",
        "sal",
    ];

    //Botón para crear el producto
    btnCrearProducto.addEventListener("click", (event) => {
        event.preventDefault();

        const formdata = new FormData();

        // Campos obligatorios generales
        camposObligatorios.forEach((campo) => {
            if (campo === "path_imagen" && form.path_imagen.files.length > 0) {
                formdata.append(campo, form.path_imagen.files[0]);
            } else {
                formdata.append(campo, form[campo].value.trim());
            }
        });

        // Campos opcionales
        formdata.append("descripcion", form.descripcion.value.trim());
        formdata.append(
            "oferta",
            form.oferta.value.trim() === "" ? "0" : form.oferta.value.trim(),
        );

        // Información nutricional
        camposNutricionales.forEach((campo) => {
            formdata.append(
                `informacion_nutricional[${campo}]`,
                form[campo].value.trim(),
            );
        });

        validarFormulario(formdata);
    });

    function validarFormulario(formdata) {
        limpiarErrores();
        let valido = true;

        // Validar campos obligatorios generales
        camposObligatorios.forEach((campo) => {
            const valor =
                campo === "path_imagen"
                    ? form.path_imagen.files[0]
                    : formdata.get(campo);
            if (!valor) {
                mostrarError(campo, erroresMsg.campoVacio);
                valido = false;
            }
        });

        // Validar campos nutricionales y acumular errores en el div general
        const erroresNutricionales = [];
        camposNutricionales.forEach((campo) => {
            if (!formdata.get(`informacion_nutricional[${campo}]`)) {
                erroresNutricionales.push(`* ${campo} es obligatorio`);
            }
        });

        if (erroresNutricionales.length > 0) {
            divErrorNutricional.innerHTML = erroresNutricionales.join("<br>");
            divErrorNutricional.classList.remove("hidden");
            valido = false;
        }

        if (valido) {
            enviarFormulario(formdata);
        }
    }

    // Función para enviar el formulario
    function enviarFormulario(formdata) {
        fetch("/api/admin/productos", {
            method: "POST",
            credentials: "include",
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
                        data.message || "Producto creado correctamente",
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

    // Función para limpiar los errores
    function limpiarErrores() {
        document.querySelectorAll("div.error").forEach((div) => {
            div.innerHTML = "";
            div.classList.add("hidden");
        });
    }

    function mostrarError(campo, mensaje) {
        const input = document.getElementById(campo);
        if (!input) return;
        const errorDiv = input.closest(".mb-3, .mb-2")?.querySelector(".error");
        if (!errorDiv) return;
        errorDiv.textContent = mensaje;
        errorDiv.classList.remove("hidden");
    }

    function mostrarErroresServidor(errors) {
        for (const campo in errors) {
            mostrarError(campo, errors[campo].join(", "));
        }
    }

    // Botoón para volver al listado de productos
    btnVolver.addEventListener("click", () => {
        window.location.href = "/admin/panel/productos";
    });
});
