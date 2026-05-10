function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
}

// function deleteModal() {
//     return {
//         show: false,
//         url: "",
//         nama: "",
//         open(id, nama) {
//             this.url = `/barang/${id}`;
//             this.nama = nama;
//             this.show = true;
//         },
//         close() {
//             this.show = false;
//         },
//     };
// }
function deleteModal() {
    return {
        show: false,
        url: "",
        nama: "",
        open(id, nama) {
            this.url = `/barang/${id}`;
            this.nama = nama;
            this.show = true;
        },
        close() {
            this.show = false;
        },

        // ── search state ──
        search: "",

        open(id, nama, baseRoute) {
            // baseRoute defaults to the url pattern already set per-page.
            // Each page overrides open() inline if needed.
            this.url = baseRoute ? `/${baseRoute}/${id}` : this.url;
            this.nama = nama;
            this.show = true;
        },

        close() {
            this.show = false;
        },
    };
}
