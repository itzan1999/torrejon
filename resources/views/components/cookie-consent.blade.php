<!-- Modal Configuración Cookies -->
<div class="modal fade"
     id="cookieModal"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Configuración de Cookies</h5>
            </div>

            <div class="modal-body">

                <p>
                    LECHE EL TORREJÓN S.L. utiliza cookies propias y de terceros.
                    Puedes aceptar todas, rechazarlas o configurarlas según tus preferencias.
                </p>

                <hr>

                <!-- Técnicas -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" checked disabled>
                    <label class="form-check-label">
                        <strong>Cookies Técnicas (necesarias)</strong><br>
                        Necesarias para el funcionamiento básico de la web.
                    </label>
                </div>

                <!-- Análisis -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="analyticsCookies">
                    <label class="form-check-label">
                        <strong>Cookies de Análisis</strong><br>
                        Permiten mejorar el funcionamiento mediante estadísticas.
                    </label>
                </div>

                <!-- Funcionalidad -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="functionalCookies">
                    <label class="form-check-label">
                        <strong>Cookies de Funcionalidad</strong><br>
                        Permiten recordar preferencias (idioma, navegador, etc.).
                    </label>
                </div>

                <!-- Publicidad -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="advertisingCookies">
                    <label class="form-check-label">
                        <strong>Cookies de Publicidad</strong><br>
                        Gestión de espacios publicitarios.
                    </label>
                </div>

                <!-- Publicidad comportamental -->
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="behavioralCookies">
                    <label class="form-check-label">
                        <strong>Cookies de Publicidad Comportamental</strong><br>
                        Publicidad adaptada a tus intereses.
                    </label>
                </div>

                <hr>

                <small>
                    Puedes obtener más información en nuestra
                    <a href="{{ route('pages.Legal.politicaCookies') }}" target="_blank">
                        Política de Cookies
                    </a>.
                </small>

            </div>

            <div class="modal-footer justify-content-between">
                <button class="btn btn-outline-secondary" id="rejectAll">
                    Rechazar todas
                </button>

                <button class="btn btn-secondary" id="acceptAll">
                    Aceptar todas
                </button>

                <button class="btn btn-dark" id="savePreferences">
                    Guardar configuración
                </button>
            </div>

        </div>
    </div>
</div>