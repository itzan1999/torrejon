// Variable global para almacenar los productos y no tener que llamar a la API en cada filtro
let productos = [];

/**
 * CARGA INICIAL: Obtiene los productos del servidor
 */
    async function cargarDatos() {
        try {
            let response = await fetch("/api/productos", {
                method: "GET",
                credentials: "include",
                headers: {
                    "X-CSRF-TOKEN": obtenerTokenCSRF(),
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            });
            let data = await response.json();
            productos = data.productos; // Guardamos en la variable global
            mostrarTarjetas(productos); // Pintamos los productos por primera vez
        } catch (error) {
            console.error("Error al cargar los productos:", error);
        }
    }

/**
 * RENDERIZADO: Genera el HTML de las tarjetas de producto
 */
function mostrarTarjetas(productos) {
    let html = "";
    
    // Si el array llega vacío (por filtros o error), mostramos mensaje
    if (productos.length === 0) {
        $(".tienda").html('<div class="col-12 text-center mt-5">No hay productos disponibles.</div>');
        return;
    }

    productos.forEach((p) => {
        let precioFinal = parseFloat(p.precio);
        let descuento = parseFloat(p.oferta) || 0;
        
        // Lógica para mostrar el precio tachado si hay oferta
        let htmlPrecio = (descuento > 0) 
            ? `<div class="precio-producto">
                <span class="precio-oferta">${precioFinal.toFixed(2)}€/l</span>
                <span class="text-muted text-decoration-line-through ms-1 precio-original">${(precioFinal + descuento).toFixed(2)}€/l</span>
               </div>`
            : `<div class="precio-producto">${precioFinal.toFixed(2)}€/l</div>`;

        // Construcción del grid de Bootstrap (12 columnas en móvil, 6 en tablet, 3 en desktop)
        html += `
        <div class="col-12 col-md-6 col-lg-3 mb-4 item-producto">
            <div class="card tarjeta redondeo-0">
                <div class="contenedor-imagen-card">
                    <img src="${p.path_imagen}" class="img-producto-card" alt="${p.nombre}">
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="titulo-producto">${p.nombre}</h6>
                        <button class="btn boton-anadir" onclick="anadirCarrito(${p.id})">Añadir</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        ${htmlPrecio}
                        <input type="number" min="1" value="1" class="form-control cantidadProducto redondeo-0" id="cant-${p.id}">
                    </div>
                    <p class="descripcion-corta mb-0">${p.descripcion || ""}</p>
                </div>
            </div>
        </div>`;
    });
    
    // Inyectamos todo el HTML generado de golpe (más eficiente)
    $(".tienda").html(html);
}

/**
 * ACCIÓN: Añade un producto al carrito mediante POST
 */
async function anadirCarrito(idProducto) {
    // Obtenemos el valor del input específico usando el ID del producto
    let cantidad = parseInt($(`#cant-${idProducto}`).val());

    // Validación básica de cantidad
    if (isNaN(cantidad) || cantidad < 1) {
        mostrarAlert("Cantidad no válida", "danger");
        return;
    }

    try {
        let response = await fetch("/api/carrito/productos", {
            method: "POST",
            credentials: "include",
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ idProducto, cantidad }),
        });

        let result = await response.json();

        if (response.ok) {
            // Disparamos un evento global para que otros componentes (como el contador del header) se actualicen
            document.dispatchEvent(new CustomEvent("productoAñadidoAlCarrito"));
            mostrarAlert("¡Producto añadido correctamente!", "success");
        } else {
            mostrarAlert(result.message || "El producto ya está en el carrito", "warning");
        }
    } catch (error) {
        console.error("Error:", error);
        mostrarAlert("Error al conectar", "danger");
    }
}

/**
 * FILTROS: Filtra el array local de productos por categoría y precio
 */
function aplicarFiltros() {
    let cat = $("#categoria").val().toLowerCase().trim();
    let precioMax = parseFloat($("#precio").val());

    let filtrados = productos.filter((p) => {
        // Comprueba si coincide la categoría (o si está en "todas" / vacía)
        let cumpleCat = cat === "" || (p.categoria || "").toLowerCase().trim() === cat;
        // Comprueba si el precio es menor o igual al máximo indicado
        let cumplePre = isNaN(precioMax) || parseFloat(p.precio) <= precioMax;
        
        return cumpleCat && cumplePre;
    });
    
    // Volvemos a pintar solo los que pasan el filtro
    mostrarTarjetas(filtrados);
}

/**
 * BÚSQUEDA: Buscador predictivo en tiempo real sobre el array local
 */
function buscarProductosLocal() {
    let prod = $(".buscarProductos").val().toLowerCase().trim();
    let contenedor = $("#resultadosBusqueda");

    // Si el buscador está vacío, ocultamos la caja de resultados
    if (prod === "") {
        contenedor.addClass("resBusqueda").html("");
        return;
    }

    // Filtramos por nombre o descripción
    let sugerencias = productos.filter(p => 
        p.nombre.toLowerCase().includes(prod) || 
        (p.descripcion && p.descripcion.toLowerCase().includes(prod))
    );

    if (sugerencias.length > 0) {
        // Generamos el HTML de la lista desplegable de sugerencias
        let html = sugerencias.map(s => `
            <a href="/producto/${s.id}" class="item-busqueda border-bottom">
                <img src="/resources${s.path_imagen}" class="img-sugerencia">
                <div>
                    <div class="fw-bold small text-dark">${s.nombre}</div>
                    <div class="small text-muted">${parseFloat(s.precio).toFixed(2)}€</div>
                </div>
            </a>`).join("");
        
        // .show() asegura que el contenedor sea visible
        contenedor.removeClass("resBusqueda").html(html).show();
    } else {
        contenedor.removeClass("resBusqueda").html('<div class="p-3 small text-muted">No hay resultados</div>').show();
    }
}

/**
 * EVENTOS: Inicialización cuando el DOM está listo
 */
$(document).ready(function () {
    cargarDatos();

    // Listeners para filtros y búsqueda
    $(document).on("change", "#categoria", aplicarFiltros);
    $(document).on("input", "#precio", aplicarFiltros);
    $(document).on("input", ".buscarProductos", buscarProductosLocal);

    // Cierre del menú de búsqueda al hacer clic fuera
    $(document).on("click", function (e) {
        // Si el clic no es dentro del contenedor de búsqueda, lo ocultamos
        if (!$(e.target).closest(".posicion-relativa").length) {
            $("#resultadosBusqueda").addClass("resBusqueda");
        }
    });
});