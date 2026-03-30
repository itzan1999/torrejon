import { showConfirmModal } from "../../../../components/confirmModal.js";

const tabla = document.getElementById("tablaPedidos");
const buscador = document.getElementById("buscador");
const cachePedidos = {};

// Rutas de la API
const API_URL_NORMAL = "/api/admin/pedidos";
const API_URL_FILTRO = "/api/admin/pedidos/filtro";

// Renderizar tabla
function renderTablaPedidos(pedidos) {
    tabla.innerHTML = "";

    // Si no hay usuarios, mostrar mensaje
    if (!pedidos || pedidos.length === 0) {
        const fila = document.createElement("tr");
        fila.innerHTML = ` <td colspan="7" class="text-center text-muted py-3"> 
        No se han encontrado pedidos con ese filtro.
        </td> `;
        tabla.appendChild(fila);
        return;
    }

    pedidos.forEach((pedido) => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td ${pedido.suscripcion ? 'class="table-success"' : ""}>${pedido.id}</td>
            <td ${pedido.suscripcion ? 'class="table-success"' : ""}>${pedido.codigo}</td>
            <td ${pedido.suscripcion ? 'class="table-success"' : ""}>${pedido.nombre} ${pedido.apellidos}</td>
            <td ${pedido.suscripcion ? 'class="table-success"' : ""}>${pedido.email}</td>
            <td ${pedido.suscripcion ? 'class="table-success"' : ""}>${pedido.estado}</td>
            <td ${pedido.suscripcion ? 'class="table-success"' : ""}>${pedido.suscripcion ? "Si" : "No"}</td>
            <td ${pedido.suscripcion ? 'class="table-success"' : ""}>
                <button class="btn btn-info btn-sm ver" data-id="${pedido.id}">Detalle</button>
                <button class="btn btn-warning btn-sm editar" data-id="${pedido.id}">Editar</button>
                <button class="btn btn-danger btn-sm eliminar" data-id="${pedido.id}">Eliminar</button>
            </td>
        `;
        tabla.appendChild(fila);
    });
}

// Función para cargar pedidos
async function cargarPedidos(filtro = "", forceReload = false) {
    // Si ya está en cache y no forzamos recarga, usarlo
    if (!forceReload && cachePedidos[filtro]) {
        renderTablaPedidos(cachePedidos[filtro]);
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
            cachePedidos[filtro] = data.pedidos; //Guardamos en caché el dato obtenido
            renderTablaPedidos(data.pedidos);
        } else if (response.status === 401) {
            mostrarAlert(
                "No autorizado. Verifica tu token o si eres administrador.",
                "danger",
            );
        } else {
            mostrarAlert(data.message || "Error al cargar pedidos", "danger");
        }
    } catch (error) {
        console.error("Error al cargar pedidos:", error);
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
        cargarPedidos(texto);
    }, 500);
});

// Delegación de eventos para acciones
tabla.addEventListener("click", (evento) => {
    const id = evento.target.dataset.id;
    if (!id) return;

    if (evento.target.classList.contains("ver")) {
        window.location.href = `/admin/panel/pedidos/${id}/detalle`;
    } else if (evento.target.classList.contains("editar")) {
        window.location.href = `/admin/panel/pedidos/${id}/editar`;
    } else if (evento.target.classList.contains("eliminar")) {
        // Usamos el modal en vez de confirm()
        showConfirmModal({
            title: "Eliminar Pedido",
            message: "¿Seguro que quieres eliminar este pedido?",
            confirmText: "Sí, eliminar",
            cancelText: "Cancelar",
            onConfirm: async () => {
                try {
                    const res = await fetch(`/api/admin/pedidos/${id}`, {
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
                        cargarPedidos("", true); // recarga tabla
                        mostrarAlert(
                            data.message || "Pedido eliminado correctamente",
                            "success",
                        );
                    } else {
                        mostrarAlert(
                            data.message || "Error al eliminar pedido",
                            "danger",
                        );
                    }
                } catch (error) {
                    console.error("Error al eliminar pedido:", error);
                    mostrarAlert(
                        "Error de conexión con el servidor al eliminar pedido",
                        "danger",
                    );
                }
            },
        });
    }
});

// Carga inicial: muestra todos los usuarios
cargarPedidos();
