(function () {
    const inputCantidad = document.getElementById("cantidad");
    const totalText = document.getElementById("totalText");

    if (!inputCantidad || !totalText) return;

    const precio = parseFloat(totalText.dataset.precio || "0");

    function formatearEuros(valor) {
        return valor.toFixed(2).replace(".", ",") + " €";
    }

    function actualizarTotal() {
        const cantidad = parseInt(inputCantidad.value || "0", 10);

        if (precio <= 0) {
            totalText.textContent = "Gratuït";
            return;
        }

        if (!Number.isFinite(cantidad) || cantidad <= 0) {
            totalText.textContent = formatearEuros(0);
            return;
        }

        const total = precio * cantidad;
        totalText.textContent = formatearEuros(total);
    }

    inputCantidad.addEventListener("input", actualizarTotal);
    inputCantidad.addEventListener("change", actualizarTotal);

    actualizarTotal();
})();
