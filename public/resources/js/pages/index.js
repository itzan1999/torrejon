    /* --- Configuración de Datos --- */
    const listaHero = {
        0: { 
            titulo: "Leche fresca de origen 100% natural", 
            texto: "El 98% de la leche que elaboramos en El Torrejón procede de granjas de la Región de Murcia, donde cuidamos y controlamos la materia prima desde su origen hasta su distribuición, garantizando así su máxima frescura y naturalidad consiguiendo alcanzar una vida ótil de 18 días en la nevera sin utilizar aditivos ni conservantes.", 
            rutaImagen: "/resources/images/cabecera1.jpg", 
            enlace: "/productos" 
        },
        1: { 
            titulo: "Sabor a tradición familiar", 
            texto: "Gracias al incansable trabajo de los pioneros de El Torrejón, actualmente contamos con la confianza de muchas familias que nos han hecho un hueco en su cocina. Calidad, tradición y familia siempre serán nuestros valores más preciados y seguiremos luchando para que se reflejen en nuestra leche.", 
            rutaImagen: "/resources/images/cabecera2.jpg", 
            enlace: "/historia" 
        },
        2: { 
            titulo: "Excelencia y reconocimiento", 
            texto: "El paso del tiempo, acompañado de un trabajo minucioso y adecuadamente desarro llado, nos otorgan varios reconocimientos por nuestro ímpetu en obtener el mejor producto lácteo, el respecto al medio ambiente y nuestra apuesta por la calidad.", 
            rutaImagen: "/resources/images/cabecera3.jpg", 
            enlace: "/calidad" 
        }
    };

    let actualHero = 0;
    const heroTrack = $("#carrusel-hero-track");

    /* --- Métodos del Carrusel --- */

    function inicializarHero() {
        Object.keys(listaHero).forEach((key, i) => {
            heroTrack.append(`
                <div class="slide-item ${i === 0 ? 'active' : ''}" data-index="${i}">
                    <div class="info-lado">
                        <div class="limite-ancho-hero">
                            <h1 class="titulo-hero-serif">${listaHero[key].titulo}</h1>
                            <p class="texto-hero-muted">${listaHero[key].texto}</p>
                            <a href="${listaHero[key].enlace}" class="flecha-enlace">
                                <img src="/resources/icons/icon_arrow_a.svg" class="icono-flecha-guia"/>
                            </a>
                        </div>
                    </div>
                    <div class="imagen-lado" style="background-image: url('${listaHero[key].rutaImagen}')"></div>
                </div>
            `);
        });
    }

    function actualizarHeroUI(indice) {
        $(".slide-item").removeClass("active").eq(indice).addClass("active");
        $(".punto-wrapper").removeClass("active").eq(indice).addClass("active");
    }

    function iniciarTemporizadorHero() {
        setInterval(() => {
            actualHero = (actualHero + 1) % Object.keys(listaHero).length;
            actualizarHeroUI(actualHero);
        }, 6000);
    }

    function configurarEventosPuntos() {
        $(".punto-wrapper").on("click", function() {
            actualHero = $(this).data("slide");
            actualizarHeroUI(actualHero);
        });
    }

    /* --- Inicialización --- */

    $(document).ready(function () {
        inicializarHero();
        configurarEventosPuntos();
        iniciarTemporizadorHero();
    });