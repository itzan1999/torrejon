/*
----------------------------------------------------
                VISTA: Contacto
----------------------------------------------------
*/
$("#send").on("click", validarFormulario);

function validarFormulario() {
    //Almacen de los errores
    let errores = [];

    //Vaciar los errores anteriores
    let error = document.querySelectorAll(".error");
    vaciar_errores_formulario(error);

    //Recoger los datos del formulario
    let nombre = $("#nombre").val().trim();
    let email = $("#email").val().trim();
    let telefono = $("#telefono").val().trim();
    let consulta = $("#consulta").val().trim();
    let aceptar = $("#aceptar").is(":checked");
    let resolucion = $(".resolucion");

    /*
        Comprueba que no hayan errores:
        - Si no hay errores, se devuelve true para que el formulario se envíe.
        - Si hay errores, se muestran debajo de cada campo y no se envía el formulario.
    */
    if (validar_campos(nombre, email, telefono, consulta, aceptar, errores)) {
        fetch("/api/consultas", {
            method: "POST",
            credentials: "include", // para enviar cookies de sesión
            headers: {
                "X-CSRF-TOKEN": obtenerTokenCSRF(),
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                nombre: nombre,
                email: email,
                telefono: telefono,
                consulta: consulta,
            }),
        })
            .then((response) => response.json())
            .then((response) => {
                if (response.status == 200) {
                    +vaciar_formulario();
                    resolucion.html(
                        "¡Consulta enviada con éxito! Nos pondremos en contacto contigo lo antes posible.",
                    );
                    window.setTimeout(() => {
                        resolucion.html("");
                    }, 5000);
                }
            })

            .catch((error) => {
                console.error("Error:", error);
                alert("Error al enviar la consulta. Inténtalo de nuevo.");
            });
    } else {
        for (let elemento of error) {
            elemento.innerHTML =
                errores.find(
                    (error) => error.clase_error === elemento.dataset.error,
                )?.mensaje || "";
        }
    }
}

function vaciar_errores_formulario(error) {
    for (let elemento of error) {
        elemento.innerHTML = "";
    }
}

function validar_campos(nombre, email, telefono, consulta, aceptar, errores) {
    /*-------------------------------------------------------
                        Longitud máxima de los campos
            -------------------------------------------------------*/

    //Comprueba que el nombre no tenga más de 255 caracteres
    if (nombre.length > 255) {
        errores.push({
            clase_error: "nombre",
            mensaje: "El nombre no puede tener más de 255 caracteres.",
        });
    }

    //Comprueba que el email no tenga más de 255 caracteres
    if (email.length > 255) {
        errores.push({
            clase_error: "email",
            mensaje: "El email no puede tener más de 255 caracteres.",
        });
    }

    //Comprueba que el email no tenga más de 255 caracteres
    if (telefono.length > 15) {
        errores.push({
            clase_error: "telefono",
            mensaje: "El telefono no puede tener más de 15 digitos.",
        });
    }

    /*-------------------------------------------------------
                            Verificación de campos
            -------------------------------------------------------*/
    //Comprueba que se haya confirmado la aceptación de la política de privacidad
    if (!aceptar) {
        errores.push({
            clase_error: "check",
            mensaje:
                "Debes aceptar la política de privacidad para enviar tu consulta.",
        });
    }

    //Comprueba que el email sea un email válido
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errores.push({
            clase_error: "email",
            mensaje: "El email no es un email válido.",
        });
    }
    //Comprueba que el telefono sea un numero válido
    if (
        telefono.length > 0 &&
        telefono.length < 9 &&
        /^(\+\d{1,3})?[6789]\d{8}$/.test(telefono)
    ) {
        errores.push({
            clase_error: "telefono",
            mensaje: "El telefono tiene que tener mínimo 9 digitos.",
        });
    }

    //Comprueba que el telefono solo contenga números
    if (telefono.length > 0 && !/^[0-9\+]+$/.test(telefono)) {
        errores.push({
            clase_error: "telefono",
            mensaje: "El telefono solo puede contener números.",
        });
    }

    if (telefono.length > 0 && !/^(\+\d{1,3})?[6789]\d{8}$/.test(telefono)) {
        errores.push({
            clase_error: "telefono",
            mensaje: "El telefono no tiene un formato adecuado.",
        });
    }

    if (nombre.length > 0 && !/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(nombre)) {
        errores.push({
            clase_error: "nombre",
            mensaje: "El nombre solo puede contener letras y espacios.",
        });
    }

    if (consulta.length > 0 && consulta.length < 5) {
        errores.push({
            clase_error: "consulta",
            mensaje: "La consulta debe tener al menos 5 caracteres.",
        });
    }

    /*-------------------------------------------------------
                                Campos vacíos
            -------------------------------------------------------*/
    //Comprueba que el nombre no esté vacío
    if (nombre.length === 0) {
        errores.push({
            clase_error: "nombre",
            mensaje: "El nombre no puede estar vacío.",
        });
    }

    //Comprueba que el email no esté vacío
    if (email.length === 0) {
        errores.push({
            clase_error: "email",
            mensaje: "El email no puede estar vacío.",
        });
    }

    //Comprueba que el telefono no esté vacío
    if (telefono.length === 0) {
        errores.push({
            clase_error: "telefono",
            mensaje: "El telefono no puede estar vacío.",
        });
    }

    //Comprueba que la consulta no esté vacía
    if (consulta.length === 0) {
        errores.push({
            clase_error: "consulta",
            mensaje: "La consulta no puede estar vacía.",
        });
    }

    //Comprueba que no hayan campos vacíos
    if (
        nombre.length === 0 &&
        email.length === 0 &&
        telefono.length === 0 &&
        consulta.length === 0
    ) {
        errores.push({
            clase_error: "check",
            mensaje: "Debes rellenar todos los campos.",
        });
    }
    return errores.length === 0;
}

function vaciar_formulario(nombre, email, telefono, consulta, aceptar) {
    $("#nombre").val("");
    $("#email").val("");
    $("#telefono").val("");
    $("#consulta").val("");
    $("#aceptar").prop("checked", false);
}
/*
PROVISIONAL
----------------------------------------------------
                    VISTA: index
----------------------------------------------------

//FUNCIÓN EN PROCESO
function lista_automatica_index(){
    const lista = {
        1: {
            "titulo":"Leche fresca de origen 100% natural",
            "texto": "El 98% de la leche que elaboramos en El Torrejón procede de granjas de la Región de Murcia, donde cuidamos y controlamos la materia prima desde su origen hasta su distribuición, garantizando así su máxima frescura y naturalidad consiguiendo alcanzar una vida ótil de 18 días en la nevera sin utilizar aditivos ni conservantes.",
            "imagen": "/resource/images/cabecera1.jpg",
            "url": "INDICAR"//NO INDICADO
        },
        2: {
            "titulo":"Sabor a tradición",
            "texto": "Gracias al incansable trabajo de los pioneros de El Torrejón, actualmente contamos con la confianza de muchas familias que nos han hecho un hueco en su cocina. Calidad, tradición y familia siempre serán nuestros valores más preciados y seguiremos luchando para que se reflejen en nuestra leche.",
            "imagen": "/resource/images/cabecera2.jpg",
            "url": "INDICAR"//NO INDICADO
        },
        3:{
            "titulo":"Excelencia y reconocimiento",
            "texto": "El paso del tiempo, acompañado de un trabajo minucioso y adecuadamente desarro llado, nos otorgan varios reconocimientos por nuestro ímpetu en obtener el mejor producto lácteo, el respecto al medio ambiente y nuestra apuesta por la calidad.",
            "imagen": "/resource/images/cabecera3.jpg",
            "url": "INDICAR"//NO INDICADO            
        }
    };

    window.setInterval(()=>{
        for(elemento in lista){
            document.getElementById("titulo").innerHTML = lista[elemento].titulo;
            document.getElementById("texto").innerHTML = lista[elemento].texto;
            document.getElementById("imagen").src = lista[elemento].imagen;    
        }
    },3000);
}

*/
