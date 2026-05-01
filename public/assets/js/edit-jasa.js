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
    input.classList.toggle("is-changed", input.value !== original);
}

function countChars() {
    const len = document.getElementById("keterangan").value.length;
    document.getElementById("charCount").textContent = len + " karakter";
}

// Init char count on load
document.addEventListener("DOMContentLoaded", function () {
    countChars();
});

function formatRupiah(input, hiddenId, previewId) {
    let raw = input.value.replace(/[^0-9]/g, "");

    if (raw === "") {
        input.value = "";
        document.getElementById(hiddenId).value = "";
        document.getElementById(previewId).textContent = "";
        return;
    }

    let formatted = parseInt(raw, 10).toLocaleString("id-ID");

    input.value = "Rp. " + formatted;
    document.getElementById(hiddenId).value = raw;
    document.getElementById(previewId).textContent = "→ Rp. " + formatted;
}
