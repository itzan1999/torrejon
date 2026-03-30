document.addEventListener("DOMContentLoaded", () => {
    const btnActualizarProducto = document.getElementById(
        "btnActualizarProducto",
    );
    const form = document.getElementById("formActualizarProducto");
    const btnVolver = document.getElementById("btnVolver");
    const erroresMsg = {
        servidor: "Error al actualizar el producto en el servidor.",
        actualizar: "Error al actualizar el producto",
    };

    const camposNutricionales = [
        "calorias",
        "grasas",
        "grasas_saturadas",
        "hidratos",
        "azucares",
        "proteinas",
        "sal",
    ];

    // Obtener ID del producto desde la URL
    const urlParts = window.location.pathname.split("/");
    const id = urlParts[urlParts.length - 2];

    cargarProducto();

    async function cargarProducto() {
        try {
            const response = await fetch(`/api/productos/${id}`, {
                method: "GET",
                credentials: "include",
                headers: {
                    "X-CSRF-TOKEN": obtenerTokenCSRF(),
                    Accept: "application/json",
                },
            });

            const data = await response.json();

            if (response.status === 200) {
                const producto = data.producto;

                form.nombre.value = producto.nombre ?? "";
                form.precio.value = producto.precio ?? "";
                form.stock.value = producto.stock ?? "";
                form.descripcion.value = producto.descripcion ?? "";
                form.oferta.value = producto.oferta ?? "";
                form.tamanyo.value = producto.tamanyo ?? "";
                form.unidad_medida.value = producto.unidad_medida ?? "";

                const info = producto.informacion_nutricional ?? {};

                camposNutricionales.forEach((campo) => {
                    if (form[campo]) {
                        form[campo].value = info[campo] ?? "";
                    }
                });
            } else {
                mostrarAlert(
                    data.message || "Error al cargar el producto",
                    "danger",
                );
            }
        } catch (error) {
            console.error(error);
            mostrarAlert("Error de conexión con el servidor", "danger");
        }
    }

    btnActualizarProducto.addEventListener("click", (event) => {
        event.preventDefault();

        const formdata = new FormData();

        // Campos simples
        const campos = [
            "nombre",
            "precio",
            "stock",
            "descripcion",
            "oferta",
            "tamanyo",
            "unidad_medida",
        ];

        campos.forEach((campo) => {
            const valor = form[campo].value.trim();

            if (valor !== "") {
                formdata.append(campo, valor);
            }
        });

        // Imagen
        if (form.path_imagen.files.length > 0) {
            formdata.append("path_imagen", form.path_imagen.files[0]);
        }

        // Información nutricional (solo si se cambia algo)
        let infoNutricional = false;

        camposNutricionales.forEach((campo) => {
            const valor = form[campo].value.trim();

            if (valor !== "") {
                formdata.append(`informacion_nutricional[${campo}]`, valor);
                infoNutricional = true;
            }
        });

        enviarFormulario(formdata);
    });

    function enviarFormulario(formdata) {
        fetch(`/api/admin/productos/${id}`, {
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
                    mostrarAlert(
                        data.message || "Producto actualizado correctamente",
                        "success",
                    );
                } else {
                    mostrarAlert(erroresMsg.actualizar, "danger");
                }
            })
            .catch(() => {
                mostrarAlert(erroresMsg.servidor, "danger");
            });
    }

    // Botón para volver al listado de productos
    btnVolver.addEventListener("click", () => {
        window.location.href = "/admin/panel/productos";
    });
});
