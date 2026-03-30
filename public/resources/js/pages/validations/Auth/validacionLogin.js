const form = document.getElementById("loginForm");
// const errorDiv = document.getElementById('credenciales-error')
const loginBtn = document.getElementById("loginBtn");

const erroresMsg = {
    campoVacio: "* Este campo es obligatorio.",
};

const svgOculto = ` <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"> 
<path fill="#000000" fill-rule="evenodd" d="M12 17.8c4.034 0 7.686-2.25 9.648-5.8C19.686 8.45 16.034 6.2 12 6.2S4.314 8.45 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8M12 5c4.808 0 8.972 2.848 11 7c-2.028 4.152-6.192 7-11 7s-8.972-2.848-11-7c2.028-4.152 6.192-7 11-7m0 9.8a2.8 2.8 0 1 0 0-5.6a2.8 2.8 0 0 0 0 5.6m0 1.2a4 4 0 1 1 0-8a4 4 0 0 1 0 8"/> 
</svg>`;
const svgVisible = ` <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"> 
<path fill="#000000" fill-rule="evenodd" d="m18.67 16.973l2.755 2.755l-.849.848L3.85 3.85L4.697 3l2.855 2.855C8.932 5.303 10.432 5 12 5c4.808 0 8.972 2.848 11 7a12.65 12.65 0 0 1-4.33 4.973M8.486 6.79l1.664 1.664a4 4 0 0 1 5.398 5.398l2.255 2.255c1.574-1 2.904-2.403 3.845-4.106C19.686 8.45 16.034 6.2 12 6.2a10.8 10.8 0 0 0-3.514.59m6.152 6.152a2.8 2.8 0 0 0-3.579-3.579zm1.81 5.204c-1.38.552-2.88.855-4.448.855c-4.808 0-8.972-2.848-11-7a12.65 12.65 0 0 1 4.33-4.973l.867.867A11.36 11.36 0 0 0 2.352 12c1.962 3.55 5.614 5.8 9.648 5.8a10.8 10.8 0 0 0 3.514-.59l.934.935zM8.453 10.15l.909.91a2.8 2.8 0 0 0 3.579 3.579l.91.908a4 4 0 0 1-5.398-5.398z"/> 
</svg>`;

// Evento para enviar el formulario de login
loginBtn.addEventListener("click", (event) => {
    event.preventDefault();
    const formdata = new FormData();

    formdata.append("username", form.username.value.trim());
    formdata.append("password", form.password.value.trim());

    console.log(form);

    if (validarCamposVacios({ formdata })) {
        form.submit();
    }
});

// Función para validar que los campos no estén vacíos
function validarCamposVacios({ formdata }) {
    const datos = Array.from(formdata.entries());
    let valido = !datos.some((campo) => campo[1] === "");

    datos.forEach((campo) => {
        const nombre = campo[0];
        const dato = campo[1];

        const input = document.querySelector(`#${nombre}`);
        if (!input) return;

        // Buscamos el .error dentro del contenedor del campo
        const errorDiv = document.querySelector(`#${nombre} ~ div.error`);

        if (!errorDiv) return;

        if (!dato) {
            errorDiv.textContent = erroresMsg.campoVacio;
            errorDiv.classList.remove("hidden");
            valido = false;
        } else {
            errorDiv.classList.add("hidden");
        }
    });

    return valido;
}

// Para cambiar la visibilidad de la contraseña
document.querySelectorAll(".toggle-password").forEach((btn) => {
    const input = document.getElementById(btn.dataset.target);
    const icon = btn.querySelector(".icon-eye");

    btn.addEventListener("click", () => {
        const isHidden = input.type === "password";

        input.type = isHidden ? "text" : "password";
        icon.innerHTML = isHidden ? svgVisible : svgOculto;

        btn.classList.toggle("visible", isHidden);
    });
});
