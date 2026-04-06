function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
}

function updateTipePreview() {
    const val = document.getElementById("tipeSelect").value;
    const el = document.getElementById("tipePreview");
    if (val === "perusahaan") {
        el.innerHTML = `<svg viewBox="0 0 24 24" style="width:11px;height:11px;fill:#f5c542"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>`;
        el.innerHTML += " Pelanggan Perusahaan";
        el.style.color = "#f5c542";
    } else {
        el.innerHTML = `<svg viewBox="0 0 24 24" style="width:11px;height:11px;fill:#2dd4bf"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>`;
        el.innerHTML += " Pelanggan Pribadi";
        el.style.color = "#2dd4bf";
    }
}

function updateCarVisual() {
    const plat = document
        .getElementById("platInput")
        .value.toUpperCase()
        .trim();
    const merk = document.getElementById("merkInput").value.trim();
    const model = document.getElementById("modelInput").value.trim();
    const tahun = document.getElementById("tahunInput").value.trim();

    const cvPlat = document.getElementById("cvPlat");
    const cvModel = document.getElementById("cvModel");
    const cvEmpty = document.getElementById("cvEmpty");

    if (plat || merk || model) {
        cvEmpty.style.display = "none";
        cvPlat.style.display = plat ? "block" : "none";
        cvModel.style.display = merk || model ? "block" : "none";
        cvPlat.textContent = plat || "";
        cvModel.textContent = [merk, model, tahun ? `(${tahun})` : ""]
            .filter(Boolean)
            .join(" ");
    } else {
        cvPlat.style.display = "none";
        cvModel.style.display = "none";
        cvEmpty.style.display = "block";
    }
}

// init on load
updateTipePreview();
