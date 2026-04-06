function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
}

function markChanged(input) {
    const original = input.dataset.original ?? "";
    input.classList.toggle("is-changed", input.value !== original);
}

function updatePreview() {
    const nama = document.getElementById("namaInput").value.trim();
    const jabatan = document.getElementById("jabatanInput").value.trim();
    const circle = document.getElementById("avatarCircle");
    const avName = document.getElementById("avName");
    const avJab = document.getElementById("avJabatan");

    if (nama) {
        const initials = nama
            .split(" ")
            .slice(0, 2)
            .map((w) => w[0])
            .join("")
            .toUpperCase();
        circle.textContent = initials;
        avName.textContent = nama;
        avJab.textContent = jabatan || "—";
    }
}

const statusConfig = {
    aktif: { cls: "chip-aktif", label: "Aktif" },
    cuti: { cls: "chip-cuti", label: "Cuti" },
    resign: { cls: "chip-resign", label: "Resign" },
    nonaktif: { cls: "chip-nonaktif", label: "Nonaktif" },
};

function updateStatusChip() {
    const val = document.getElementById("statusSelect").value;
    const chip = document.getElementById("statusChip");
    const text = document.getElementById("statusChipText");
    const cfg = statusConfig[val] || statusConfig.aktif;

    chip.className = "ek-status-chip " + cfg.cls;
    text.textContent = cfg.label;
}

document.addEventListener("DOMContentLoaded", function () {
    updateStatusChip();
    updatePreview();
});
