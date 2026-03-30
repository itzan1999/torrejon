// Scripts que se cargan al inicio de la web, para funcionalidades que compartirian muchas páginas.

// Función para mostrar alertas que compartirían todas las páginas, con mensaje, tipo (success, danger, etc) y tiempo de duración
function mostrarAlert(mensaje, tipo = "success", tiempo = 3000) {
    const alertDiv = document.getElementById("alert");
    const alertMsg = document.getElementById("alertMsg");

    alertMsg.textContent = mensaje;
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.style.display = "block"; // aparece el alert

    // Ocultar automáticamente después del tiempo indicado
    setTimeout(() => {
        alertDiv.classList.remove("show");
        // Dar tiempo a la animación de fade antes de ocultar completamente
        setTimeout(() => {
            alertDiv.style.display = "none";
        }, 150); // coincide con la duración de la animación fade
    }, tiempo);
}

// Función que comparten todas las vistas para poder obtener el token de sesion
function obtenerTokenCSRF() {
    const tokenCSRF = document.querySelector('meta[name="csrf-token"]').content;
    return tokenCSRF;
}