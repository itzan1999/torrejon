/**
 * LÓGICA DEL CARRUSEL - EL TORREJÓN (Versión Unificada)
 */
const CarruselManager = {
    init: function(config) {
        const track = $(config.trackId);
        if (track.length === 0) return;

        track.empty();

        const datos = config.datos;
        const isFade = config.mode === 'fade';
        let posicion = 0;

        // 1. Renderizar
        datos.forEach((item, i) => {
            track.append(config.renderFunc(item, i));
        });

        // AÑADIDO: .slide-final para que reconozca la nueva sección
        const slides = track.find('.slide-item, .slide-final, .producto-item, .noticia-item, .ayuda-item');

        // 2. Configuración según modo
        if (isFade) {
            // Estilo HERO / SECCIÓN FINAL: Capas apiladas
            slides.css({
                'position': 'absolute',
                'inset': '0',
                'opacity': '0',
                'transition': 'opacity 0.8s ease',
                'display': 'flex',
                'z-index': '1'
            });
            slides.first().css('opacity', '1').addClass('active').css('z-index', '2');
            track.css('position', 'relative').css('transform', 'none');
        } else {
            // Estilo PRODUCTOS / NOTICIAS: Desplazamiento lateral
            if (config.loop && datos.length > (config.visibles || 1)) {
                for (let i = 0; i < (config.visibles || 1); i++) {
                    track.append(config.renderFunc(datos[i], i));
                }
            }
        }

        const mover = (n) => {
            posicion = (n + datos.length) % datos.length;
            
            if (isFade) {
                // Lógica de desvanecimiento
                slides.removeClass('active').css({'opacity': '0', 'z-index': '1'});
                $(slides[posicion]).addClass('active').css({'opacity': '1', 'z-index': '2'});
            } else {
                // Lógica de desplazamiento
                track.addClass("con-animacion");
                const porcentaje = posicion * (100 / (config.visibles || 1));
                track.css("transform", `translateX(-${porcentaje}%)`);
            }

            // Sincronizar puntos
            if (config.puntosSelector) {
                $(config.puntosSelector).removeClass('active');
                $(config.puntosSelector + `[data-slide="${posicion}"]`).addClass('active');
            }
        };

        // Eventos
        if (config.puntosSelector) {
            $(document).off('click', config.puntosSelector).on('click', config.puntosSelector, function() {
                mover($(this).data('slide'));
            });
        }

        if (config.btnNextId) {
            $(config.btnNextId).on('click', () => mover(posicion + 1));
        }
        if (config.btnPrevId) {
            $(config.btnPrevId).on('click', () => mover(posicion - 1));
        }

        if (config.autoplay) {
            setInterval(() => mover(posicion + 1), config.interval || 5000);
        }
    }
};

/**
 * CONFIGURACIÓN DE SECCIONES
 */

function carruselDeProductos() {
    CarruselManager.init({
        trackId: "#carrusel-track",
        btnPrevId: "#btn-prev",
        btnNextId: "#btn-next",
        visibles: 3,
        loop: true,
        datos: [
            { nombre: "Leche Fresca Semidesnatada SL", img: "/resources/images/pateurizada_semidesnatada_1l_sl.jpg" },
            { nombre: "Leche Fresca Entera", img: "/resources/images/pateurizada_entera_1l-500x500.jpg" },
            { nombre: "Leche UHT Desnatada", img: "/resources/images/uht_desnatada_1l.jpg" },
            { nombre: "Leche Fresca Desnatada", img: "/resources/images/pateurizada_desnatada_1l.jpg" }
        ],
        renderFunc: (p) => `
            <div class="producto-item">
                <img src="${p.img}" class="img-producto">
                <p class="texto-producto">${p.nombre}</p>
            </div>`
    });
}

function carruselDeNoticias() {
    const datosArray = [
        { titulo: "El mercado de la leche experimenta la demanda de productos naturales por parte de los consumidores, que apuestan por la leche fresca.", desc: "El mercado de la leche experimenta la demanda de productos naturales.", img: "/resources/images/article-500x500.jpg", link: "#" },
        { titulo: "Receta Chocolate a la taza", desc: "Cómo hacer chocolate a la taza. En invierno, cuando las temperaturas bajan, una taza de chocolate caliente nos ayuda a calmar el cuerpo y entrar en calor. Hoy os explicamos cómo prepararlo de forma fácil y rápida.", img: "/resources/images/torrejon-cabecera-recetas-500x500.jpg", link: "#" }
    ];

    CarruselManager.init({
        trackId: "#news-track",
        btnPrevId: "#btn-prev-news",
        btnNextId: "#btn-next-news",
        visibles: 3,
        autoplay: true,
        loop: true,
        datos: [...datosArray, ...datosArray, ...datosArray],
        renderFunc: (n) => `
            <div class="noticia-item">
                <a href="${n.link}" class="card-noticia-completa">
                    <div class="img-container-news"><img src="${n.img}" class="img-news"></div>
                    <div class="body-news">
                        <h3 class="titulo-news text-uppercase">${n.titulo}</h3>
                        <p class="extracto-news">${n.desc}</p>
                        <div class="footer-card"><img src="/resources/icons/icon_arrow_a.svg" class="flecha-footer"></div>
                    </div>
                </a>
            </div>`
    });
}

function carruselDeAyudas() {
    CarruselManager.init({
        trackId: "#ayudas-track",
        visibles: 1,
        autoplay: true,
        loop: true,
        datos: [{ img: "/resources/images/image_baea7d.jpg" }, { img: "/resources/images/image_baed81.jpg" }],
        renderFunc: (a) => `<div class="ayuda-item w-100 text-center"><img src="${a.img}" class="img-fluid" style="max-height: 250px; padding: 20px;"></div>`
    });
}

function initCarruselFinal() {
    CarruselManager.init({
        trackId: "#carrusel-final-track",
        mode: 'fade', 
        puntosSelector: ".seccion-final-mixta .punto-wrapper",
        autoplay: true,
        interval: 6000,
        datos: [
            { t: "SEGUIMOS FORTALECIENDO NUESTRA CIBERSEGURIDAD", d: "LECHE EL TORREJÓN, S. L., con el fin de poder adoptar las medidas necesarias para una buena ciberseguridad aplicamos las medidas necesarias y recomendadas. Esta inversión se realiza con ayudas de la Región de Murcia.", i: "/resources/images/proyecto-nuevo-2.png" },
            { t: "PONEMOS RUMBO A NUEVOS MERCADOS", d: "LECHE EL TORREJÓN, S. L., con el fin de poder adaptarnos y llegar a nuevos mercados estamos en cosntante expansión y cambio. Esta inversión se realiza con ayudas de la Región de Murcia.", i: "/resources/images/proyecto-nuevo.png" },
            { t: "AVANZANDO HACIA UNA EMPRESA MÁS SOSTENIBLE", d: "LECHE EL TORREJÓN, S. L., con el fin de poder realizar nuestra actividad cada vez de forma más sostenible cumplimos con las recomendaciones y adaptamos todas las medidas posibles para lograr un fin lo más sostenible que esté en nuestra mano. Esta inversión se realiza con ayudas de la Región de Murcia.", i: "/resources/images/proyecto-nuevo-1.png" }
        ],
        // AÑADIDO: Estructura exacta que espera el CSS (.slide-final, .col-texto, .col-imagen)
        renderFunc: (p, i) => `
            <div class="slide-final ${i === 0 ? 'active' : ''}">
                <div class="col-texto border-end">
                    <div class="limite-ancho-texto">
                        <h3 class="titulo-hero-serif" style="font-size: 2.2rem;">${p.t}</h3>
                        <p class="texto-hero-muted">${p.d}</p>
                        <img src="/resources/icons/icon_arrow_a.svg" style="width: 45px; opacity: 0.3;">
                    </div>
                </div>
                <div class="col-imagen" style="background-image: url('${p.i}');"></div>
            </div>`
    });
}

$(document).ready(function() {
    carruselDeProductos();
    carruselDeNoticias();
    carruselDeAyudas();
    initCarruselFinal();
});