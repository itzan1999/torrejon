import { showConfirmModal } from "../../../../components/confirmModal.js";

const tabla = document.getElementById("tablaUsuarios");
const buscador = document.getElementById("buscador");
const btnNuevo = document.getElementById("btnNuevoUsuario");
const cacheUsuarios = {};

// Rutas de la API
const API_URL_NORMAL = "/api/admin/usuarios";
const API_URL_FILTRO = "/api/admin/usuarios/filtro";

// Renderizar tabla
function renderTablaUsuarios(usuarios) {
    tabla.innerHTML = "";

    // Si no hay usuarios, mostrar mensaje
    if (!usuarios || usuarios.length === 0) {
        const fila = document.createElement("tr");
        fila.innerHTML = ` <td colspan="10" class="text-center text-muted py-3"> 
        No se han encontrado usuarios con ese filtro.
        </td> `;
        tabla.appendChild(fila);
        return;
    }

    usuarios.forEach((usuario) => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${usuario.id}</td>
            <td>${usuario.username}</td>
            <td>${usuario.nombre}</td>
            <td>${usuario.apellidos}</td>
            <td>${usuario.email}</td>
            <td>${usuario.direccion}</td>
            <td>${usuario.roles.join(", ")}</td>
            <td>${usuario.nPedidos}</td>
            <td>${usuario.pedidosActivos ?? 0}</td>
            <td>
                <button class="btn btn-info btn-sm ver" data-id="${usuario.id}">Detalle</button>
                <button class="btn btn-warning btn-sm editar" data-id="${usuario.id}">Editar</button>
                <button class="btn btn-danger btn-sm eliminar" data-id="${usuario.id}">Eliminar</button>
            </td>
        `;
        tabla.appendChild(fila);
    });
}

// Función para cargar usuarios
async function cargarUsuarios(filtro = "", forceReload = false) {
    // Si ya está en cache y no forzamos recarga, usarlo
    if (!forceReload && cacheUsuarios[filtro]) {
        renderTablaUsuarios(cacheUsuarios[filtro]);
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
            cacheUsuarios[filtro] = data.usuarios; //Guardamos en caché el dato obtenido
            renderTablaUsuarios(data.usuarios);
        } else if (response.status === 401) {
            mostrarAlert(
                "No autorizado. Verifica tu token o si eres administrador.",
                "danger",
            );
        } else {
            mostrarAlert(data.message || "Error al cargar usuarios", "danger");
        }
    } catch (error) {
        console.error("Error al cargar usuarios:", error);
        mostrarAlert("Error de conexión con el servidor", "danger");
    }
}

/*BÚSCADOR DINÁMICO */

// Variable de timeout
let timeoutBusqueda = null;
buscador.addEventListener("input", (e) => {
    const texto = e.target.value.trim();
    clearTimeout(timeoutBusqueda);

    // Timeout para esperar y enviar la petición cuando hayan pasado 400 ms
    timeoutBusqueda = setTimeout(() => {
        cargarUsuarios(texto);
    }, 500);
});

// Botón crear usuario
btnNuevo.addEventListener("click", () => {
    window.location.href = "usuarios/nuevo";
});

// Delegación de eventos para acciones
tabla.addEventListener("click", (evento) => {
    const id = evento.target.dataset.id;
    if (!id) return;

    if (evento.target.classList.contains("ver")) {
        window.location.href = `/admin/panel/usuarios/${id}/detalle`;
    } else if (evento.target.classList.contains("editar")) {
        window.location.href = `/admin/panel/usuarios/${id}/editar`;
    } else if (evento.target.classList.contains("eliminar")) {
        // Usamos el modal en vez de confirm()
        showConfirmModal({
            title: "Eliminar Usuario",
            message: "¿Seguro que quieres eliminar este usuario?",
            confirmText: "Sí, eliminar",
            cancelText: "Cancelar",
            onConfirm: async () => {
                try {
                    const res = await fetch(`/api/admin/usuarios/${id}`, {
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
                        cargarUsuarios("", true); // recarga tabla
                        mostrarAlert(
                            data.message || "Usuario eliminado correctamente",
                            "success",
                        );
                    } else {
                        mostrarAlert(
                            data.message || "Error al eliminar usuario",
                            "danger",
                        );
                    }
                } catch (error) {
                    console.error("Error al eliminar usuario:", error);
                    mostrarAlert(
                        "Error de conexión con el servidor al eliminar usuario",
                        "danger",
                    );
                }
            },
        });
    }
});

// Carga inicial: muestra todos los usuarios
cargarUsuarios();
