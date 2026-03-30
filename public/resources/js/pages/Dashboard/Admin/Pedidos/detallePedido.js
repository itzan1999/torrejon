document.addEventListener("DOMContentLoaded", async () => {
    const tablaPedido = document.getElementById("tablaPedido");
    const tablaUsuario = document.getElementById("tablaUsuario");
    const btnVolver = document.getElementById("btnVolver");

    // Obtener ID del usuario desde la URL
    const urlParts = window.location.pathname.split("/");
    const id = urlParts[urlParts.length - 2];

    try {
        // OBTENER EL PEDIDO
        const respuestaPedido = await fetch(`/api/admin/pedidos/${id}`, {
            method: "GET",
            credentials: "include", // para enviar cookies de sesión
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });

        // Obtención del json y el pedido
        const dataPedido = await respuestaPedido.json();
        if (!respuestaPedido.ok) {
            mostrarAlert(dataPedido.message || "Error al cargar el pedido");
            return;
        }
        const pedido = dataPedido.pedidos;

        // Crear fila con los datos
        const filaPedido = document.createElement("tr");

        filaPedido.innerHTML = `
            <td>${pedido.id}</td>
            <td>${pedido.codigo}</td>
            <td>${pedido.nombre} ${pedido.apellidos}</td>
            <td>${pedido.email}</td>
            <td>${pedido.estado}</td>
            <td>${pedido.suscripcion ? "Si" : "No"}</td>
        `;
        tablaPedido.appendChild(filaPedido);

        // OBTENER EL USUARIO ASOCIADO AL PEDIDO
        const respuestaUsuario = await fetch(
            `/api/admin/usuarios/${pedido.id_usuario}`,
            {
                method: "GET",
                credentials: "include", // para enviar cookies de sesión
                headers: {
                    "X-CSRF-TOKEN": obtenerTokenCSRF(),
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            },
        );

        // Obtención del json y el usuario
        const datoUsuario = await respuestaUsuario.json();
        if (!datoUsuario) {
            mostrarAlert(datoUsuario.message || "Error al cargar el usuario");
            return;
        }
        const usuario = datoUsuario.usuario;

        // Crear fila con los datos
        const filaUsuario = document.createElement("tr");
        filaUsuario.innerHTML = `
            <td>${usuario.id}</td>
            <td>${usuario.username}</td>
            <td>${usuario.nombre}</td>
            <td>${usuario.apellidos}</td>
            <td>${usuario.rol}</td>
            <td>${usuario.nPedidos ?? 0}</td>
        `;
        tablaUsuario.appendChild(filaUsuario);
    } catch (error) {
        console.error("Error al obtener el pedido o usuario", error);
        mostrarAlert("Error al cargar el pedido o usuario", "danger");
    }

    // Botón volver al listado de pedidos
    btnVolver.addEventListener("click", () => {
        window.location.href = "/admin/panel/pedidos";
    });
});
