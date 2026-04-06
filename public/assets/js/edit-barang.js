function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
}

function previewHarga(inputId, previewId) {
    const val = parseInt(document.getElementById(inputId).value);
    const el = document.getElementById(previewId);
    if (!isNaN(val) && val > 0) {
        el.textContent = "→ Rp " + val.toLocaleString("id-ID");
    } else {
        el.textContent = "";
    }
}

function markChanged(input) {
    const original = input.dataset.original ?? "";
    if (input.value !== original) {
        input.classList.add("is-changed");
    } else {
        input.classList.remove("is-changed");
    }
}
