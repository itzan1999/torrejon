document.addEventListener("DOMContentLoaded", async () => {
    const tabla = document.getElementById("tablaUsuarios");

    // Obtener ID del usuario desde la URL
    const urlParts = window.location.pathname.split("/");
    const id = urlParts[urlParts.length - 2];

    try {
        const res = await fetch(`/api/admin/usuarios/${id}`, {
            method: "GET",
            credentials: "include", // para enviar cookies de sesión
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });

        const data = await res.json();

        if (!res.ok) {
            alert(data.message || "Error al cargar el usuario");
            return;
        }

        const user = data.usuario;

        // Crear fila con los datos
        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${user.id}</td>
            <td>${user.username}</td>
            <td>${user.nombre}</td>
            <td>${user.apellidos}</td>
            <td>${user.rol.join(", ")}</td>
            <td>${user.nPedidos ?? 0}</td>
        `;

        tabla.appendChild(fila);
    } catch (err) {
        console.error("Error al obtener el usuario", error);
        mostrarAlert("Error al cargar el usuario", "danger");
    }

    // Botón volver al listado de usuarios
    const btnVolver = document.getElementById("btnVolver");
    btnVolver.addEventListener("click", () => {
        window.location.href = "/admin/panel/usuarios";
    });
});
