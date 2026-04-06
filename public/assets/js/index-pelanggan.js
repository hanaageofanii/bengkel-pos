function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
}

function deleteModal() {
    return {
        show: false,
        url: "",
        nama: "",
        open(id, nama) {
            this.url = `/pelanggan/${id}`;
            this.nama = nama;
            this.show = true;
        },
        close() {
            this.show = false;
        },
    };
}
