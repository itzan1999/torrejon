document.addEventListener("DOMContentLoaded", function () {

    if (typeof bootstrap === "undefined") return;

    const modalElement = document.getElementById("cookieModal");
    if (!modalElement) return;

    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    const analytics = document.getElementById("analyticsCookies");
    const functional = document.getElementById("functionalCookies");
    const advertising = document.getElementById("advertisingCookies");
    const behavioral = document.getElementById("behavioralCookies");

    const rejectAllBtn = document.getElementById("rejectAll");
    const acceptAllBtn = document.getElementById("acceptAll");
    const saveBtn = document.getElementById("savePreferences");

    function setConsent(data) {
        localStorage.setItem("cookie_consent", JSON.stringify(data));
    }

    function getConsent() {
        return JSON.parse(localStorage.getItem("cookie_consent"));
    }

    function hasConsent() {
        return localStorage.getItem("cookie_consent") !== null;
    }

    function applyConsent(consent) {

        // ANALYTICS
        if (consent.analytics) {
            loadAnalytics();
        }

        // Aquí se podría activar:
        // loadAdvertising()
        // loadBehavioral()
    }

    function loadAnalytics() {
        if (document.getElementById("ga-script")) return;

        let script = document.createElement("script");
        script.id = "ga-script";
        script.src = "https://www.googletagmanager.com/gtag/js?id=TU_ID_AQUI";
        script.async = true;
        document.head.appendChild(script);
    }

    if (!hasConsent()) {
        modal.show();
    } else {
        applyConsent(getConsent());
    }

    rejectAllBtn.addEventListener("click", function () {

        const consent = {
            necessary: true,
            analytics: false,
            functional: false,
            advertising: false,
            behavioral: false
        };

        setConsent(consent);
        modal.hide();
    });

    acceptAllBtn.addEventListener("click", function () {

        analytics.checked = true;
        functional.checked = true;
        advertising.checked = true;
        behavioral.checked = true;

        const consent = {
            necessary: true,
            analytics: true,
            functional: true,
            advertising: true,
            behavioral: true
        };

        setConsent(consent);
        applyConsent(consent);
        modal.hide();
    });

    saveBtn.addEventListener("click", function () {

        const consent = {
            necessary: true,
            analytics: analytics.checked,
            functional: functional.checked,
            advertising: advertising.checked,
            behavioral: behavioral.checked
        };

        setConsent(consent);
        applyConsent(consent);
        modal.hide();
    });

});