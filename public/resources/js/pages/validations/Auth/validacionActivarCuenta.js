// Función para obtener el token del usuario de la url
function obtenerTokenURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get("token").trim();
}

// Funcuión para actaulizar la UI dependiendo del resultado de la activación
function actualizarUI({
    tituloMsg,
    textoMsg,
    iconoClases,
    iconoHTML,
    botonClases,
    botonTexto,
    botonHref,
}) {
    // Constantes para seleccionar los selectores
    const titulo = document.querySelector("h1");
    const texto = document.querySelector("p");
    const icono = document.querySelector(".icon-circle");
    const boton = document.querySelector(".action-btn");

    // Título y texto
    titulo.textContent = tituloMsg;
    texto.textContent = textoMsg;

    // Círculo del icono
    icono.className = `rounded-circle ${iconoClases} d-flex justify-content-center align-items-center mx-auto`;
    icono.innerHTML = iconoHTML;

    // Botón
    boton.className = `action-btn ${botonClases}`;
    boton.textContent = botonTexto;
    boton.href = botonHref;
}

// Evento que se ejecuta al cargar el DOM
document.addEventListener("DOMContentLoaded", () => {
    const token = obtenerTokenURL();

    if (!token) {
        actualizarUI({
            tituloMsg: "Enlace inválido",
            textoMsg: "No se ha proporcionado ningún token.",
            iconoClases: "bg-danger",
            iconoHTML: '<i class="bi bi-x-lg text-white fs-4"></i>',
            botonClases: "btn btn-danger px-4 py-2",
            botonTexto: "Volver al inicio",
            botonHref: "/",
        });
    } else {
        fetch(`/api/auth/activar-cuenta/${token}`, {
            method: "POST",
            credentials: "include", // para enviar cookies de sesión
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === 200) {
                    actualizarUI({
                        tituloMsg: "Cuenta activada correctamente",
                        textoMsg:
                            "Tu cuenta ya está activa. Ahora puedes iniciar sesión y acceder a todos nuestros servicios.",
                        iconoClases: "bg-success",
                        iconoHTML:
                            '<i class="bi bi-check-lg text-white fs-3"></i>',
                        botonClases: "btn btn-dark px-4 py-2",
                        botonTexto: "Iniciar Sesión",
                        botonHref: "/login",
                    });
                } else {
                    actualizarUI({
                        tituloMsg: `Error ${data.status || 500}`,
                        textoMsg:
                            data.message || "Ha ocurrido un error inesperado",
                        iconoClases: "bg-danger",
                        iconoHTML: '<i class="bi bi-x-lg text-white fs-4"></i>',
                        botonClases: "btn btn-danger px-4 py-2",
                        botonTexto: "Volver al inicio",
                        botonHref: "/",
                    });
                }
            })
            .catch(() => {
                actualizarUI({
                    tituloMsg: "Error inesperado",
                    textoMsg:
                        "Ha ocurrido un problema al procesar la activación.",
                    iconoClases: "bg-danger",
                    iconoHTML: '<i class="bi bi-x-lg text-white fs-4"></i>',
                    botonClases: "btn btn-danger px-4 py-2",
                    botonTexto: "Volver al inicio",
                    botonHref: "/",
                });
            });
    }

    // Mostrar modal
    document.getElementById("modal").classList.remove("hidden");
});
