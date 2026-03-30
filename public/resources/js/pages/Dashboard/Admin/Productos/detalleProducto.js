document.addEventListener("DOMContentLoaded", async () => {
    const tabla = document.getElementById("tablaProducto");
    const imagenProducto = document.getElementById("imagenProducto");
    const infoNutricional = document.getElementById("informacionNutricional");
    const btnVolver = document.getElementById("btnVolver");

    // Obtener ID del usuario desde la URL
    const urlParts = window.location.pathname.split("/");
    const id = urlParts[urlParts.length - 2];

    try {
        const res = await fetch(`/api/productos/${id}`, {
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
            alert(data.message || "Error al cargar el producto");
            return;
        }

        // Obtener el producto
        const producto = data.producto;
        const imagen = data.producto.path_imagen;
        const informacionNutricional = `
            <ul>
                <li>Calorias: ${producto.informacion_nutricional.calorias ?? 0} kcal</li>
                <li>Grasas: ${producto.informacion_nutricional.grasas ?? 0} g</li>
                <li>Grasas saturadas: ${producto.informacion_nutricional.grasas_saturadas ?? 0} g</li>
                <li>Hidratos: ${producto.informacion_nutricional.hidratos ?? 0} g</li>
                <li>Azucares: ${producto.informacion_nutricional.azucares ?? 0} g</li>
                <li>Proteinas: ${producto.informacion_nutricional.proteinas ?? 0} g</li>
                <li>Sal: ${producto.informacion_nutricional.sal ?? 0} g</li>
            </ul>
        `;

        // Crear fila con los datos
        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${producto.id}</td>
            <td>${producto.nombre}</td>
            <td>${producto.descripcion}</td>
            <td>${producto.precio}€</td>
            <td>${producto.oferta}</td>
            <td>${producto.stock} productos</td>
            <td>${producto.tamanyo} ${producto.unidad_medida}</td>
        `;

        // Insertamos los datos en sus lugares correspondientes
        tabla.appendChild(fila);
        imagenProducto.innerHTML = `<img src='/${imagen}' alt='${producto.nombre}'>`;
        infoNutricional.innerHTML = informacionNutricional;
    } catch (error) {
        console.error("Error al obtener el producto", error);
        mostrarAlert("Error al cargar el producto", "danger");
    }

    // Botón volver al listado de productos
    btnVolver.addEventListener("click", () => {
        window.location.href = "/admin/panel/productos";
    });
});
