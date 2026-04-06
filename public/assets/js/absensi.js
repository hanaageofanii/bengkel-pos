function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
}

function absensiModal() {
    const wrap = document.querySelector(".absensi-wrap");
    const storeUrl = wrap.dataset.storeUrl;
    const csrfToken = wrap.dataset.csrf;

    return {
        show: false,
        infoShow: false,
        infoStatus: "",
        karyawan_id: null,
        tanggal: null,
        absensi_id: null,

        open(data) {
            this.karyawan_id = data.karyawan_id;
            this.tanggal = data.tanggal;
            this.absensi_id = data.absensi_id;
            this.show = true;
        },
        openInfo(status) {
            this.infoStatus = status;
            this.infoShow = true;
        },
        close() {
            this.show = false;
        },
        save(status) {
            fetch(storeUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    karyawan_id: this.karyawan_id,
                    tanggal: this.tanggal,
                    status: status,
                }),
            }).then(() => location.reload());
        },
        remove() {
            if (!this.absensi_id) return;
            fetch(`/absensi/${this.absensi_id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
            }).then(() => location.reload());
        },
    };
}
