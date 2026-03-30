/* --- Configuración y Estado del Carrusel --- */
const catalogoProductos = [
    { nombre: "Leche Fresca Semidesnatada Sin Lactosa", rutaImagen: "/resources/images/productos/pateurizada_semidesnatada_1l_sl.jpg" },
    { nombre: "Leche Fresca Desnatada", rutaImagen: "/resources/images/productos/pateurizada_semidesnatada_1l-500x500.jpg" },
    { nombre: "Leche UHT Semidesnatada", rutaImagen: "/resources/images/productos/uht_semidesnatada_1l.jpg" },
    { nombre: "Leche Fresca Desnatada", rutaImagen: "/resources/images/productos/pateurizada_desnatada_1l.jpg" },
    { nombre: "Leche UHT Desnatada", rutaImagen: "/resources/images/productos/uht_desnatada_1l.jpg" },
    { nombre: "Leche Fresca Entera", rutaImagen: "/resources/images/productos/pateurizada_entera_1l-500x500.jpg" },
    { nombre: "Leche UHT Entera", rutaImagen: "/resources/images/productos/uht_entera_1l-500x500.jpg" },
];

const cintaDeslizable = $("#carrusel-track");
const productosVisibles = 3;
const totalOriginal = catalogoProductos.length;
let posicionActual = 0;
let bloqueado = false; 
let carruselInterval; // Variable para almacenar el temporizador


function renderizarProductos() {
    // Limpiamos antes de añadir por si acaso
    cintaDeslizable.empty(); //IMPORTANTE NO BORRAR
    
    catalogoProductos.forEach((producto) => {
        cintaDeslizable.append(`
            <div class="producto-item">
                <a href="/tienda">
                    <img src="${producto.rutaImagen}" class="img-producto" alt="${producto.nombre}"/>
                </a>
                <p class="texto-producto">${producto.nombre}</p>
            </div>
        `);
    });
}

function prepararClones() {
    const tarjetas = $(".producto-item");
    for (let i = 0; i < productosVisibles; i++) {
        cintaDeslizable.append(tarjetas.eq(i).clone());
    }
}

/* --- Funciones de Movimiento --- */

function moverCarrusel(usarAnimacion = true) {
    if (usarAnimacion) {
        cintaDeslizable.addClass("con-animacion");
    } else {
        cintaDeslizable.removeClass("con-animacion");
    }

    const desplazamiento = posicionActual * (100 / productosVisibles);
    cintaDeslizable.css("transform", `translateX(-${desplazamiento}%)`);
}

function siguienteProducto() {
    if (bloqueado) return;
    posicionActual++;
    moverCarrusel(true);

    if (posicionActual === totalOriginal) {
        bloqueado = true;
        setTimeout(() => {
            posicionActual = 0;
            moverCarrusel(false);
            bloqueado = false;
        }, 500); 
    }
}

function anteriorProducto() {
    if (bloqueado) return;

    if (posicionActual === 0) {
        posicionActual = totalOriginal;
        moverCarrusel(false);

        setTimeout(() => {
            posicionActual--;
            moverCarrusel(true);
        }, 20);
    } else {
        posicionActual--;
        moverCarrusel(true);
    }
}

/* --- Control de Automatización --- */

/* Inicia o reinicia el contador de 5 segundos */
function iniciarAutoplay() {
    pararAutoplay(); // Limpiamos cualquier intervalo previo
    carruselInterval = setInterval(() => {
        siguienteProducto();
    }, 4000); // 5000ms = 5 segundos
}

/* Detiene el movimiento automático */
function pararAutoplay() {
    clearInterval(carruselInterval);
}

/* --- Inicialización y Eventos --- */

$(document).ready(function () {
    renderizarProductos();
    prepararClones();

    // Iniciar movimiento automático al cargar
    iniciarAutoplay();

    // Eventos de botones (reinician el tiempo al hacer clic)
    $("#btn-next").on("click", function() {
        siguienteProducto();
        iniciarAutoplay(); 
    });

    $("#btn-prev").on("click", function() {
        anteriorProducto();
        iniciarAutoplay();
    });

    // Opcional: Pausar el carrusel cuando el ratón está encima
    $(".carrusel-wrapper").on("mouseenter", pararAutoplay).on("mouseleave", iniciarAutoplay);
});