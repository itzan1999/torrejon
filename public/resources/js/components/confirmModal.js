export function showConfirmModal({
    modalId = "confirmModal",
    title,
    message,
    confirmText = "Sí",
    cancelText = "Cancelar",
    onConfirm,
}) {
    const modalEl = document.getElementById(modalId);
    if (!modalEl) {
        console.error(`No se encontró el modal con id ${modalId}`);
        return;
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

    // Actualizar título y mensaje
    modalEl.querySelector(`#${modalId}Title`).textContent =
        title || "Confirmar";
    modalEl.querySelector(`#${modalId}Message`).textContent = message;

    // Resetear botón confirm
    const confirmBtn = modalEl.querySelector(`#${modalId}ConfirmBtn`);
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.replaceWith(newConfirmBtn);

    newConfirmBtn.textContent = confirmText;
    newConfirmBtn.addEventListener("click", () => {
        if (onConfirm) onConfirm();
        modal.hide();
    });

    // Abrir modal y dar foco al primer botón
    modal.show();
    modalEl.addEventListener("shown.bs.modal", () => {
        newConfirmBtn.focus();
    });

    // Cuando se oculta el modal, aseguramos que aria-hidden sea true
    modalEl.addEventListener("hidden.bs.modal", () => {
        modalEl.setAttribute("aria-hidden", "true");
    });
}
