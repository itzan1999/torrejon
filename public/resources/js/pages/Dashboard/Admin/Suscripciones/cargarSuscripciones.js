import { showConfirmModal } from "../../../../components/confirmModal.js";

const tabla = document.getElementById("tablaSuscripciones");
const buscador = document.getElementById("buscador");
// const btnNuevo = document.getElementById("btnNuevoUsuario");
const cacheSuscripciones = {};
const cacheUsuarios = {};

// Rutas de la API
const API_URL_NORMAL = "/api/admin/suscripcion";
const API_URL_FILTRO = "/api/admin/suscripcion/filtro";

// Renderizar tabla
async function renderTablaSuscripciones(suscripciones) {
    tabla.innerHTML = "";

    // Si no hay suscripciones, mostrar mensaje
    if (!suscripciones || suscripciones.length === 0) {
        const fila = document.createElement("tr");
        fila.innerHTML = ` <td colspan="5" class="text-center text-muted py-3"> 
        No se han encontrado suscripciones con ese filtro.
        </td> `;
        tabla.appendChild(fila);
        return;
    }

    for (const suscripcion of suscripciones) {
        const nombreUsuario = await obtenerNombreUsuario(
            suscripcion.id_usuario,
        );

        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${suscripcion.id}</td>
            <td>${suscripcion.tipo}</td>
            <td>${formatDateDDMMYYYY(suscripcion.fecha_inicio)}</td>
            <td>${nombreUsuario}</td>
            <td>
                <button class="btn btn-info btn-sm ver" data-id="${suscripcion.id}">Detalle</button>
                <button class="btn btn-warning btn-sm editar" data-id="${suscripcion.id}">Editar</button>
                <button class="btn btn-danger btn-sm eliminar" data-id="${suscripcion.id}">Eliminar</button>
            </td>
        `;
        tabla.appendChild(fila);
    }
}

// Función para formatear fecha a DD-MM-YYYY
function formatDateDDMMYYYY(fecha) {
    const d = new Date(fecha);
    const dia = String(d.getDate()).padStart(2, "0");
    const mes = String(d.getMonth() + 1).padStart(2, "0"); // Enero = 0
    const anio = d.getFullYear();
    return `${dia}-${mes}-${anio}`;
}

// Función obtener usuarios (para mostrar nombre en vez de id_usuario)
async function obtenerNombreUsuario(id_usuario) {
    if (cacheUsuarios[id_usuario]) return cacheUsuarios[id_usuario];

    try {
        const res = await fetch(`/api/admin/usuarios/${id_usuario}`, {
            headers: { Accept: "application/json" },
        });
        const data = await res.json();

        if (res.ok) {
            const nombreUsuario =
                data.usuario.nombre + " " + data.usuario.apellidos;
            cacheUsuarios[id_usuario] = nombreUsuario; // Guardamos en caché el nombre completo
            return cacheUsuarios[id_usuario];
        } else {
            return "Usuario desconocido";
        }
    } catch (error) {
        console.error("Error al obtener nombre de usuario:", error);
        return "Usuario desconocido";
    }
}

// Función para cargar suscripciones
async function cargarSuscripciones(filtro = "", forceReload = false) {
    // Si ya está en cache y no forzamos recarga, usarlo
    if (!forceReload && cacheSuscripciones[filtro]) {
        renderTablaSuscripciones(cacheSuscripciones[filtro]);
        return;
    }
    try {
        let url = filtro
            ? `${API_URL_FILTRO}?buscar=${encodeURIComponent(filtro)}`
            : API_URL_NORMAL;
        const response = await fetch(url, {
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });

        const data = await response.json();

        if (response.status === 200) {
            cacheSuscripciones[filtro] = data.suscripciones; //Guardamos en caché el dato obtenido
            renderTablaSuscripciones(data.suscripciones);
        } else if (response.status === 401) {
            mostrarAlert(
                "No autorizado. Verifica tu token o si eres administrador.",
                "danger",
            );
        } else {
            mostrarAlert(
                data.message || "Error al cargar suscripciones",
                "danger",
            );
        }
    } catch (error) {
        console.error("Error al cargar suscripciones:", error);
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
        cargarSuscripciones(texto);
    }, 500);
});

// Botón crear usuario
// btnNuevo.addEventListener("click", () => {
//     window.location.href = "suscripciones/nuevo";
// });

// Delegación de eventos para acciones
tabla.addEventListener("click", (evento) => {
    const id = evento.target.dataset.id;
    if (!id) return;

    if (evento.target.classList.contains("ver")) {
        window.location.href = `/admin/panel/suscripciones/${id}/detalle`;
    } else if (evento.target.classList.contains("editar")) {
        window.location.href = `/admin/panel/suscripciones/${id}/editar`;
    } else if (evento.target.classList.contains("eliminar")) {
        // Usamos el modal en vez de confirm()
        showConfirmModal({
            title: "Eliminar Suscripción",
            message: "¿Seguro que quieres eliminar esta suscripción?",
            confirmText: "Sí, eliminar",
            cancelText: "Cancelar",
            onConfirm: async () => {
                try {
                    const res = await fetch(`/api/admin/suscripciones/${id}`, {
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
                        cargarSuscripciones("", true); // recarga tabla
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
cargarSuscripciones();
