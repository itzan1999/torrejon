let aside;
let detalleConsulta;
let idConsultaActual = null;

const TAG_COLORS = {
    "pendiente": "#f39c12",
    "en proceso": "#2980b9",
    "resuelta": "#27ae60"
};

const cargarConsultas = async () => {
    if (!aside) return; 
    
    try {
        const response = await fetch("/api/admin/consultas", {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": typeof obtenerTokenCSRF === 'function' ? obtenerTokenCSRF() : '',
                "Accept": "application/json",
                "Content-Type": "application/json",
            },
            credentials: "include",
        });

        const data = await response.json();
        const consultas = data.consultas;
        
        aside.innerHTML = "";

        if (consultas.length === 0) {
            aside.innerHTML = "<p style='padding: 15px;'>No hay consultas disponibles.</p>";
            return;
        }

        consultas.forEach((consulta) => {
            const consultaElement = document.createElement("div");
            consultaElement.classList.add("consulta-item");
            
            if(idConsultaActual == consulta.id) consultaElement.classList.add("selected");

            consultaElement.innerHTML = `
                <div>
                    <span class="consulta-nombre">${consulta.nombre}</span>
                    <span class="consulta-estado" style="background-color: ${TAG_COLORS[consulta.estado] || '#ccc'}">${consulta.estado}</span>
                </div>
                <span class="consulta-email">${consulta.email}</span>`;
            
            consultaElement.addEventListener("click", () => {
                cargarDetalleConsulta(consulta.id);
                aside.querySelectorAll(".consulta-item").forEach((el) => el.classList.remove("selected"));
                consultaElement.classList.add("selected");
            });
            aside.appendChild(consultaElement);
        });
    } catch (error) {
        console.error("Error al cargar las consultas:", error);
    }
};

const cargarDetalleConsulta = async (id) => {
    idConsultaActual = id;
    
    const contenedorTexto = document.getElementById("contenido-dinamico-consulta");
    const fabContainer = document.getElementById("fab-container");

    try {
        document.getElementById("fab-menu")?.classList.remove("show");
        document.getElementById("fab-main")?.classList.remove("active");
        document.getElementById("fab-overlay")?.classList.remove("active");

        const response = await fetch(`/api/admin/consultas/${id}`);
        const data = await response.json();
        const consulta = data.consulta;

        contenedorTexto.innerHTML = `
            <div class="detalle-header">
                <div>
                    <h2>${consulta.nombre}</h2>
                    <span class="consulta-estado" style="background-color: ${TAG_COLORS[consulta.estado] || '#ccc'}">${consulta.estado}</span>
                </div>
                <div>
                    <p>${consulta.email}</p>
                    <p>${consulta.telefono || ''}</p>
                </div>
            </div>
            <div class="detalle-main">${consulta.consulta}</div>`;

        if (fabContainer) fabContainer.style.display = "flex";

    } catch (error) {
        console.error("Error:", error);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    aside = document.getElementById("lista-consultas");
    detalleConsulta = document.getElementById("detalle-consulta");
    const fabMain = document.getElementById("fab-main");
    const fabMenu = document.getElementById("fab-menu");
    const fabOverlay = document.getElementById("fab-overlay");

    if (!aside) return;

    const toggleMenu = () => {
      fabMenu.classList.toggle("show");
      fabMain.classList.toggle("active");
      fabOverlay.classList.toggle("active");
    };

    fabMain.addEventListener("click", toggleMenu);
    fabOverlay.addEventListener("click", toggleMenu);

    document.querySelectorAll(".fab-item").forEach(btn => {
        btn.addEventListener("click", async () => {
            const nuevoEstado = btn.getAttribute("data-estado");
            if (!idConsultaActual) return;

            try {
              toggleMenu();

                const response = await fetch(`/api/admin/consultas/${idConsultaActual}`, {
                    method: "PATCH",
                    headers: {
                        "X-CSRF-TOKEN": typeof obtenerTokenCSRF === 'function' ? obtenerTokenCSRF() : '',
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ estado: nuevoEstado })
                });

                if (response.ok) {
                    await cargarDetalleConsulta(idConsultaActual);
                    await cargarConsultas(); 
                }
            } catch (error) {
                console.error("Error al actualizar estado:", error);
            }
        });
    });

    cargarConsultas();
});