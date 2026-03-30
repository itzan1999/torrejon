/**
 * GESTIÓN DE CIERRES EXTERNOS
 * Escucha clics en todo el documento para cerrar menús y cestas si el usuario pulsa fuera.
 */
document.addEventListener("click", function (event) {
    // --- Lógica Menú Dropdown (Bootstrap) ---
    const menu = document.getElementById("menuPrincipal");
    const button = document.querySelector(".navbar-toggler");

    // Si el menú móvil está desplegado
    if (menu && menu.classList.contains("show")) {
        const isClickInsideMenu = menu.contains(event.target);
        const isClickOnButton = button && button.contains(event.target);

        // Si el clic no es ni en el menú ni en el botón de hamburguesa, lo cerramos
        if (!isClickInsideMenu && !isClickOnButton) {
            const bsCollapse = bootstrap.Collapse.getInstance(menu);
            if (bsCollapse) bsCollapse.hide();
        }
    }

    // --- Lógica Cerrar Cesta (Sidebar) ---
    const sidebar = document.getElementById("sidebarCesta");
    if (sidebar && sidebar.classList.contains("active")) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnCartButton = event.target.closest(".cart-btn");
        const isClickOnCloseBtn = event.target.closest(".btn-close");
        const isClickOnOverlay = event.target.id === "overlayCesta";

        // Cerramos si: se pulsa la X, se pulsa el fondo oscuro (overlay)
        // o si se pulsa fuera del sidebar (siempre que no sea el botón de abrir)
        if (
            isClickOnCloseBtn ||
            isClickOnOverlay ||
            (!isClickInsideSidebar && !isClickOnCartButton)
        ) {
            toggleCesta();
        }
    }
});

/**
 * ACCESIBILIDAD: Cerrar con la tecla ESC
 */
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        const sidebar = document.getElementById("sidebarCesta");
        if (sidebar && sidebar.classList.contains("active")) {
            toggleCesta();
        }
    }
});

/**
 * INICIALIZACIÓN Y EVENTOS PERSONALIZADOS
 */
document.addEventListener("DOMContentLoaded", () => {
    // Carga inicial del número de productos en el icono del carrito
    cargarContadorCarrito();

    // Escuchamos el evento 'productoAñadidoAlCarrito' para refrescar el contador
    // sin necesidad de recargar la página completa.
    document.addEventListener(
        "productoAñadidoAlCarrito",
        cargarContadorCarrito,
    );
});

/**
 * CONTADOR: Obtiene la cantidad total de productos para el badge del header
 */
async function cargarContadorCarrito() {
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
        if (!response.ok) return;

        const data = await response.json();
        const contador = document.getElementById("contadorCarrito");
        let totalProductos = 0;

        // Sumamos las cantidades de todos los productos en el array
        if (data.productosCarrito) {
            totalProductos = data.productosCarrito.reduce(
                (acc, p) => acc + (parseInt(p.cantidad) || 0),
                0,
            );
        }

        if (contador) {
            contador.textContent = totalProductos;
        }
    } catch (error) {
        console.error("Error cargando carrito:", error);
    }
}

/**
 * SIDEBAR: Alterna la visibilidad de la cesta y bloquea el scroll de la web
 */
function toggleCesta() {
    $("#sidebarCesta").toggleClass("active");
    $("#overlayCesta").toggleClass("active");

    // Si la cesta se abre, añadimos clase al body para evitar scroll infinito
    if ($("#sidebarCesta").hasClass("active")) {
        $("body").addClass("no-scroll");
        mostrarContenidoCesta(); // Cargamos los datos actuales de la API
    } else {
        $("body").removeClass("no-scroll");
    }
}

/**
 * RENDER: Carga y dibuja los productos dentro del sidebar
 */
async function mostrarContenidoCesta() {
    try {
        let response = await fetch("/api/carrito", {
            method: "GET",
            credentials: "include",
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });
        let data = await response.json();
        let htmlEjemplo = "";

        if (data.productosCarrito && data.productosCarrito.length > 0) {
            data.productosCarrito.forEach((producto) => {
                let descuento = parseFloat(producto.oferta) || 0;
                let precioFinal = parseFloat(producto.precio);
                let htmlPrecio = "";

                // Formateo de precios (normal o con descuento)
                if (descuento > 0) {
                    let precioOriginal = precioFinal + descuento;
                    htmlPrecio = `
                        <span class="precio-actual-cesta fw-bold">${precioFinal.toFixed(2)}€</span> 
                        <span class="precio-viejo-cesta text-muted text-decoration-line-through ms-1 small">${precioOriginal.toFixed(2)}€</span>
                    `;
                } else {
                    htmlPrecio = `<span class="fw-bold">${precioFinal.toFixed(2)}€</span>`;
                }

                // Generación de cada fila de producto
                htmlEjemplo += `
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                       <img src="${producto.path_imagen}" class="img-sidebar me-3">
                        <div class="flex-grow-1">
                            <div class="small fw-bold">${producto.nombre}</div>
                            <div class="small">${htmlPrecio}</div>
                            <div class="small text-muted">Cant: ${producto.cantidad}</div>
                        </div>
                        <button class="btn btn-sm text-danger eliminarproducto" data-id="${producto.idProducto}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
            });
        }

        // Cálculo de impuestos (IVA 21%)
        const IVA = 1.21;
        let precioBase = parseFloat(data.precio_total || 0);
        let precioConIVA = precioBase * IVA;

        // Inyección de HTML y actualización del total
        $("#contenidoCesta").html(
            htmlEjemplo ||
                '<div class="text-center mt-4">Tu cesta está vacía</div>',
        );
        $("#totalCesta").html(precioConIVA.toFixed(2) + "€");
    } catch (error) {
        console.error("Error al cargar los productos de la cesta:", error);
    }
}

/**
 * ACCIÓN: Borra un producto específico y refresca la vista y el contador
 */
async function eliminarProductoCarrito(id) {
    try {
        const response = await fetch(`/api/carrito/productos/${id}`, {
            method: "DELETE",
            credentials: "include",
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });

        if (response.ok) {    
            mostrarContenidoCesta(); // Refrescamos el listado del sidebar
            // Notificamos al sistema para que actualice el badge del header
            document.dispatchEvent(new CustomEvent("productoAñadidoAlCarrito"));
        }
    } catch (error) {
        console.error("Error al eliminar el producto:", error);
    }
}

/**
 * LISTENERS DE JQUERY
 */
$(document).ready(function () {
    // Apertura manual de la cesta desde el botón del header
    $(document).on("click", ".cart-btn", function (a) {
        a.preventDefault();
        if (!$("#sidebarCesta").hasClass("active")) {
            toggleCesta();
        }
    });

    // Delegación de evento para el botón de eliminar producto
    $(document).on("click", ".eliminarproducto", function (a) {
        a.preventDefault();
        let id = $(this).data("id");
        if (id) eliminarProductoCarrito(id);
    });
});
