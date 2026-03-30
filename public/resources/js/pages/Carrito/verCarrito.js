let timeoutCantidad;

/**
 * CARGA INICIAL Y RENDERIZADO
 */
async function cargarDatosCesta() {
    try {
        const response = await fetch("/api/carrito", {
            method: "GET",
            credentials: "include",
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });
        const data = await response.json();
        renderizarCesta(data);
    } catch (error) {
        console.error("Error al obtener la cesta:", error);
    }
}

function renderizarCesta(data) {
    let htmlProductos = "";
    let htmlResumenDetalle = "";
    const tasaIVA = 0.21;
    const tieneProductos =
        data.productosCarrito && data.productosCarrito.length > 0;

    const suscripcionActiva =
        sessionStorage.getItem("suscripcion_activa") === "true";
    actualizarInterfazSuscripcion(suscripcionActiva, tieneProductos);

    if (tieneProductos) {
        data.productosCarrito.forEach((producto) => {
            const precioActual = parseFloat(producto.precio);
            const descuento = parseFloat(producto.oferta) || 0;
            const cantidad = parseInt(producto.cantidad);
            const subtotalItem = precioActual * cantidad;

            let htmlPrecioVisual =
                descuento > 0
                    ? `<span class="fw-bold fs-5 text-danger">${precioActual.toFixed(2)}€</span>
                   <span class="text-muted text-decoration-line-through ms-2 small">${(precioActual + descuento).toFixed(2)}€</span>`
                    : `<span class="fw-bold fs-5">${precioActual.toFixed(2)}€</span>`;

            const pathImagen = producto.path_imagen;

            htmlProductos += `
                <div class="row align-items-center py-4 border-bottom item-cesta">
                    <div class="col-md-2 text-center">
                        <img src="${pathImagen}" class="img-fluid rounded shadow-sm" style="max-height: 80px; object-fit: cover;" alt="${producto.nombre}">
                    </div>
                    <div class="col-md-5">
                        <h6 class="fw-bold mb-1">${producto.nombre}</h6>
                        ${descuento > 0 ? `<span class="badge bg-danger mb-2">¡OFERTA!</span>` : ""}
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="d-flex flex-column">${htmlPrecioVisual}</div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center justify-content-center gap-3">
                        <div class="input-group input-group-sm border rounded-pill overflow-hidden" style="width: 110px;">
                            <button class="btn btn-light border-0" onclick="window.cambiarCantidad(${producto.idProducto}, 'restar')">-</button>
                            <input type="text" id="cant-${producto.idProducto}" class="form-control text-center border-0 bg-white fw-bold" value="${cantidad}" readonly>
                            <button class="btn btn-light border-0" onclick="window.cambiarCantidad(${producto.idProducto}, 'sumar')">+</button>                          
                        </div>
                        <button class="btn btn-sm text-danger eliminarProductoCesta" data-id="${producto.idProducto}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>`;

            htmlResumenDetalle += `
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted fw-bold">${producto.nombre} (x${cantidad})</span>
                    <span class="fw-bold">${subtotalItem.toFixed(2)}€</span>
                </div>`;
        });
    } else {
        htmlProductos = `<div class="text-center py-5"><h5>Tu cesta está vacía</h5><a href="/tienda" class="btn btn-sm btn-outline-dark mt-3">Ir a la tienda</a></div>`;
    }

    const precioBaseTotal = parseFloat(data.precio_total || 0);
    const montoIVA = precioBaseTotal * tasaIVA;
    const precioFinal = precioBaseTotal + montoIVA;

    $("#listaProductosCarrito").html(htmlProductos);
    $("#desgloseArticulos").html(htmlResumenDetalle);
    $("#subtotalBase").text(precioBaseTotal.toFixed(2) + "€");
    $("#totalIVA").text(montoIVA.toFixed(2) + "€");
    $("#totalFinal").text(precioFinal.toFixed(2) + "€");
}

/**
 * GESTIÓN DE SUSCRIPCIÓN (INTERFAZ)
 */
function actualizarInterfazSuscripcion(activa, tieneProductos) {
    const $infoSuscripcion = $("#infoSuscripcion");
    const $statusText = $infoSuscripcion.find("span").last();
    const $checkbox = $("#checkSuscripcion");

    if (!tieneProductos) {
        sessionStorage.removeItem("suscripcion_activa");
        $checkbox.prop("checked", false);
        $infoSuscripcion.addClass("d-none");
        return;
    }

    $infoSuscripcion.removeClass("d-none");
    $checkbox.prop("checked", activa);
    $statusText
        .text(activa ? "Activada" : "No activa")
        .css("color", activa ? "#198754" : "#dc3545")
        .addClass("fw-bold");
}

function manejarCambioSuscripcion() {
    const isChecked = $("#checkSuscripcion").is(":checked");
    sessionStorage.setItem("suscripcion_activa", isChecked);
    actualizarInterfazSuscripcion(isChecked, $(".item-cesta").length > 0);
}

/**
 * ACCIONES DEL CARRITO (PETICIONES)
 */
async function peticionActualizarCantidad(id, nuevaCantidad) {
    try {
        await fetch(`/api/carrito/productos/${id}`, {
            method: "PATCH",
            credentials: "include",
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ cantidad: nuevaCantidad }),
        });
        await cargarDatosCesta();
        document.dispatchEvent(new CustomEvent("productoAñadidoAlCarrito"));
    } catch (error) {
        console.error("Error al sincronizar cantidad:", error);
    }
}

async function peticionVaciarCarrito() {
    try {
        const response = await fetch("/api/carrito/productos", {
            method: "DELETE",
            credentials: "include",
            headers: { "X-CSRF-TOKEN": obtenerTokenCSRF() },
        });
        if (response.ok) {
            sessionStorage.removeItem("suscripcion_activa");
            await cargarDatosCesta();
            document.dispatchEvent(new CustomEvent("productoAñadidoAlCarrito"));
        }
    } catch (error) {
        console.error(error);
    }
}

async function peticionEliminarProducto(id) {
    try {
        const response = await fetch(`/api/carrito/productos/${id}`, {
            method: "DELETE",
            credentials: "include",
            headers: { "X-CSRF-TOKEN": obtenerTokenCSRF() },
        });
        if (response.ok) {
            await cargarDatosCesta();
            document.dispatchEvent(new CustomEvent("productoAñadidoAlCarrito"));
        }
    } catch (error) {
        console.error(error);
    }
}

window.cambiarCantidad = function (id, accion) {
    const input = $(`#cant-${id}`);
    const valorActual = parseInt(input.val());
    const nuevoValor =
        accion === "sumar" ? valorActual + 1 : Math.max(1, valorActual - 1);
    input.val(nuevoValor);
    clearTimeout(timeoutCantidad);
    timeoutCantidad = setTimeout(
        () => peticionActualizarCantidad(id, nuevoValor),
        500,
    );
};

/**
 * EVENTOS READY
 */
$(document).ready(() => {
    cargarDatosCesta();
    $("#checkSuscripcion").on("change", manejarCambioSuscripcion);
    $(".btn-vaciar-carrito").on("click", peticionVaciarCarrito);
    $(document).on("click", ".eliminarProductoCesta", function (e) {
        e.preventDefault();
        const id = $(this).data("id");
        if (id) peticionEliminarProducto(id);
    });
});
