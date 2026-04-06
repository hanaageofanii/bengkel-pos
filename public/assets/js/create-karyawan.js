function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
}

function updatePreview() {
    const nama = document.getElementById("namaInput").value.trim();
    const jabatan = document.getElementById("jabatanInput").value.trim();

    const circle = document.getElementById("avatarCircle");
    const avName = document.getElementById("avName");
    const avJab = document.getElementById("avJabatan");
    const avEmpty = document.getElementById("avEmpty");

    if (nama) {
        const initials = nama
            .split(" ")
            .slice(0, 2)
            .map((w) => w[0])
            .join("")
            .toUpperCase();
        circle.textContent = initials;
        avName.textContent = nama;
        avName.style.display = "block";
        avEmpty.style.display = "none";

        if (jabatan) {
            avJab.textContent = jabatan;
            avJab.style.display = "block";
        } else {
            avJab.style.display = "none";
        }
    } else {
        circle.textContent = "?";
        avName.style.display = "none";
        avJab.style.display = "none";
        avEmpty.style.display = "block";
    }
}
