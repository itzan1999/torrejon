import { showConfirmModal } from "../../../../components/confirmModal.js";

const tabla = document.getElementById("tablaProductos");
const btnNuevo = document.getElementById("btnNuevoProducto");
const buscador = document.getElementById("buscador");
const cacheProductos = {};

// Rutas de la API
const API_URL_NORMAL = "/api/productos";
const API_URL_FILTRO = "/api/productos/filtro";

// Renderizar tabla
function renderTablaProductos(productos) {
    tabla.innerHTML = "";

    // Si no hay usuarios, mostrar mensaje
    if (!productos || productos.length === 0) {
        const fila = document.createElement("tr");
        fila.innerHTML = ` <td colspan="6" class="text-center text-muted py-3"> 
        No se han encontrado productos con ese filtro.
        </td> `;
        tabla.appendChild(fila);
        return;
    }

    productos.forEach((producto) => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${producto.id}</td>
            <td>${producto.nombre}</td>
            <td>${producto.descripcion}</td>
            <td>${producto.precio}€</td>
            <td>${producto.stock} productos</td>
            <td>${producto.tamanyo} ${producto.unidad_medida}</td>
            <td>
                <button class="btn btn-info btn-sm ver" data-id="${producto.id}">Detalle</button>
                <button class="btn btn-warning btn-sm editar" data-id="${producto.id}">Editar</button>
                <button class="btn btn-danger btn-sm eliminar" data-id="${producto.id}">Eliminar</button>
            </td>
        `;
        tabla.appendChild(fila);
    });
}

// Función para cargar pedidos
async function cargarProductos(filtro = "", forceReload = false) {
    // Si ya está en cache y no forzamos recarga, usarlo
    if (!forceReload && cacheProductos[filtro]) {
        renderTablaProductos(cacheProductos[filtro]);
        return;
    }
    try {
        let url = filtro
            ? `${API_URL_FILTRO}?buscar=${encodeURIComponent(filtro)}`
            : API_URL_NORMAL;
        const response = await fetch(url, {
            method: "GET",
            credentials: "include",
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });

        const data = await response.json();

        if (response.status === 200) {
            cacheProductos[filtro] = data.productos; //Guardamos en caché el dato obtenido
            renderTablaProductos(data.productos);
        } else if (response.status === 401) {
            mostrarAlert(
                "No autorizado. Verifica tu token o si eres administrador.",
                "danger",
            );
        } else {
            mostrarAlert(data.message || "Error al cargar productos", "danger");
        }
    } catch (error) {
        console.error("Error al cargar productos:", error);
        mostrarAlert("Error de conexión con el servidor", "danger");
    }
}

/*BÚSCADOR DINÁMICO */
let timeoutBusqueda = null;
buscador.addEventListener("input", (e) => {
    const texto = e.target.value.trim();
    clearTimeout(timeoutBusqueda);

    // Timeout para esperar y enviar la petición cuando hayan pasado 400 ms
    timeoutBusqueda = setTimeout(() => {
        cargarProductos(texto);
    }, 500);
});

// Botón crear producto
btnNuevo.addEventListener("click", () => {
    window.location.href = "productos/nuevo";
});

// Delegación de eventos para acciones
tabla.addEventListener("click", (evento) => {
    const id = evento.target.dataset.id;
    if (!id) return;

    if (evento.target.classList.contains("ver")) {
        window.location.href = `/admin/panel/productos/${id}/detalle`;
    } else if (evento.target.classList.contains("editar")) {
        window.location.href = `/admin/panel/productos/${id}/editar`;
    } else if (evento.target.classList.contains("eliminar")) {
        // Usamos el modal en vez de confirm()
        showConfirmModal({
            title: "Eliminar Producto",
            message: "¿Seguro que quieres eliminar este producto?",
            confirmText: "Sí, eliminar",
            cancelText: "Cancelar",
            onConfirm: async () => {
                try {
                    const res = await fetch(`/api/admin/productos/${id}`, {
                        method: "DELETE",
                        credentials: "include",
                        headers: {
                            "X-CSRF-TOKEN": obtenerTokenCSRF(),
                            Accept: "application/json",
                            "Content-Type": "application/json",
                        },
                    });

                    const data = await res.json();

                    if (res.ok) {
                        cargarProductos("", true); // recarga tabla
                        mostrarAlert(
                            data.message || "Producto eliminado correctamente",
                            "success",
                        );
                    } else {
                        mostrarAlert(
                            data.message || "Error al eliminar producto",
                            "danger",
                        );
                    }
                } catch (error) {
                    console.error("Error al eliminar producto:", error);
                    mostrarAlert(
                        "Error de conexión con el servidor al eliminar producto",
                        "danger",
                    );
                }
            },
        });
    }
});

// Carga inicial: muestra todos los usuarios
cargarProductos();
