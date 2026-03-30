
$(document).ready(function() {
    // 1. Guardar estado del switch
    $("#checkSuscripcion").on("change", function() {
        sessionStorage.setItem("suscripcion_activa", $(this).is(":checked"));
    });

    // 2. Evento principal
    $(".btn-realizar-pedido").on("click", function(e) {
        e.preventDefault();
        ejecutarFlujoCompra();
    });

    // 3. Botón final del modal
    $("#btnVolverInicio").on("click", function() {
        sessionStorage.removeItem("suscripcion_activa");
        window.location.href = "/"; 
    });
});

/**
 * Orquestación: Si falla la suscripción, NO se hace el pedido.
 * Si falla el pedido, se considera que el proceso no se ha completado.
 */
async function ejecutarFlujoCompra() {
    const $btn = $(".btn-realizar-pedido");
    const textoOriginal = $btn.text();
    const quiereSuscripcion = sessionStorage.getItem("suscripcion_activa") === "true";

    $btn.prop("disabled", true).text("PROCESANDO...");

    try {
        // --- PASO 1: SUSCRIPCIÓN (Si el usuario la activó) ---
        if (quiereSuscripcion) {
            const suscripcionOk = await enviarSuscripcion();
            if (!suscripcionOk) {
                alert("No se pudo procesar la suscripción. El pedido no se ha realizado.");
                $btn.prop("disabled", false).text(textoOriginal);
                return; // DETENEMOS TODO: No llegamos a enviar el pedido
            }
        }

        // --- PASO 2: PEDIDO ---
        const pedidoOk = await enviarPedido();

        if (pedidoOk) {
            // Solo si ambos (o el pedido solo, si no hay suscripción) funcionan:
            abrirVentanaConfirmacion();
        } else {
            alert("Error al procesar el pedido. Por favor, inténtelo de nuevo.");
            $btn.prop("disabled", false).text(textoOriginal);
        }

    } catch (error) {
        console.error("Error crítico en el proceso:", error);
        $btn.prop("disabled", false).text(textoOriginal);
    }
}

async function enviarPedido() {
    try {
        const res = await fetch("/api/pedidos", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ fecha: new Date().toISOString() })
        });
        return res.ok;
    } catch (e) { 
        return false; 
    }
}

async function enviarSuscripcion() {
    try {
        const res = await fetch("/api/suscripcion", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                "Accept": "application/json",
                "Content-Type": "application/json"
            }
        });
        return res.ok;
    } catch (e) { 
        return false; 
    }
}

function abrirVentanaConfirmacion() {
    const resumenHtml = $("#desgloseArticulos").html();
    const totalPagar = $("#totalFinal").text();

    const htmlModal = `
        <div class="border-bottom mb-2 pb-2">
            ${resumenHtml}
        </div>
        <div class="d-flex justify-content-between fw-bold">
            <span>TOTAL FINAL:</span>
            <span class="text-success">${totalPagar}</span>
        </div>
    `;

    $("#resumenPedidoModal").html(htmlModal);
    $("#modalConfirmacion").css("display", "flex").hide().fadeIn(400);
}