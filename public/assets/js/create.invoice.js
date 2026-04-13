function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
}

function invoiceForm() {
    return {
        tipePelanggan: "pribadi",
        keluhan: [""],
        jasa: [],
        barang: [],
        statusBayar: "belum",
        paymentAwal: 0,

        init() {
            this.$nextTick(() => {
                let select = document.querySelector('[name="pelanggan_id"]');
                if (select && select.selectedOptions.length > 0) {
                    this.tipePelanggan =
                        select.selectedOptions[0].dataset.tipe || "pribadi";
                }
            });
        },

        setPelanggan(e) {
            this.tipePelanggan =
                e.target.selectedOptions[0].dataset.tipe || "pribadi";
            this.updateAllPrices();
        },

        updateAllPrices() {
            this.jasa.forEach((j) => {
                j.harga =
                    this.tipePelanggan === "perusahaan"
                        ? j.harga_perusahaan
                        : j.harga_pribadi;
            });
            this.barang.forEach((b) => {
                b.harga =
                    this.tipePelanggan === "perusahaan"
                        ? b.harga_perusahaan
                        : b.harga_pribadi;
            });
        },

        addJasa() {
            this.jasa.push({
                id: "",
                nama: "",
                harga: 0,
                harga_pribadi: 0,
                harga_perusahaan: 0,
            });
            this.$nextTick(() => {
                $(".select2-jasa").select2({
                    placeholder: "Cari jasa...",
                    allowClear: true,
                });
                // Re-bind Select2 change setiap kali baris baru ditambah
                bindSelect2Jasa();
            });
        },

        setJasa(e, i) {
            let select = e.target;
            let val = $(select).val();
            let o = select.querySelector(`option[value="${val}"]`);

            if (!o) return;

            let hargaDipakai =
                this.tipePelanggan === "perusahaan"
                    ? +o.dataset.perusahaan
                    : +o.dataset.pribadi;

            this.jasa[i].id = o.value;
            this.jasa[i].nama = o.dataset.nama;
            this.jasa[i].harga_pribadi = +o.dataset.pribadi;
            this.jasa[i].harga_perusahaan = +o.dataset.perusahaan;
            this.jasa[i].harga = hargaDipakai;
        },

        addBarang() {
            this.barang.push({
                id: "",
                nama: "",
                qty: 1,
                stock: 0,
                harga: 0,
                harga_pribadi: 0,
                harga_perusahaan: 0,
            });
            this.$nextTick(() => {
                $(".select2-barang").select2({
                    placeholder: "Cari barang...",
                    allowClear: true,
                });
                // Re-bind Select2 change setiap kali baris baru ditambah
                bindSelect2Barang();
            });
        },

        setBarang(e, i) {
            let select = e.target;
            let val = $(select).val();
            let o = select.querySelector(`option[value="${val}"]`);

            if (!o) return;

            let stock = +o.dataset.stock;

            if (stock <= 0) {
                alert("STOCK HABIS");
                return;
            }

            if (stock <= 5) {
                alert(`PERINGATAN: Stock ${o.dataset.nama} tinggal ${stock}`);
            }

            let hargaDipakai =
                this.tipePelanggan === "perusahaan"
                    ? +o.dataset.perusahaan
                    : +o.dataset.pribadi;

            this.barang[i].id = o.value;
            this.barang[i].nama = o.dataset.nama;
            this.barang[i].qty = 1;
            this.barang[i].stock = stock;
            this.barang[i].harga_pribadi = +o.dataset.pribadi;
            this.barang[i].harga_perusahaan = +o.dataset.perusahaan;
            this.barang[i].harga = hargaDipakai;
        },

        updateQty(i) {
            let b = this.barang[i];
            if (b.qty > b.stock) {
                alert("Qty melebihi stock");
                b.qty = b.stock;
            }
        },

        get grandTotal() {
            let totalJasa = this.jasa.reduce(
                (t, j) => t + Number(j.harga || 0),
                0,
            );
            let totalPart = this.barang.reduce(
                (t, b) => t + Number(b.harga || 0) * Number(b.qty || 0),
                0,
            );
            return totalJasa + totalPart;
        },

        get sisa() {
            return this.grandTotal - Number(this.paymentAwal || 0);
        },

        formatRupiah(num) {
            return "Rp " + Number(num).toLocaleString("id-ID");
        },
    };
}

function getAlpine() {
    let wrap = document.querySelector("[x-data]");
    if (!wrap) return null;
    if (wrap._x_dataStack) return wrap._x_dataStack[0];
    if (wrap.__x) return wrap.__x.$data;
    return null;
}

function bindSelect2Pelanggan() {
    $('[name="pelanggan_id"]')
        .off("change.s2")
        .on("change.s2", function () {
            let tipe = $(this).find(":selected").data("tipe") || "pribadi";
            let alpine = getAlpine();
            if (alpine) {
                // Update langsung ke Alpine state — ini yang fix masalah utama
                alpine.tipePelanggan = tipe;
                alpine.updateAllPrices();
            }
        });
}

function bindSelect2Jasa() {
    $(".select2-jasa")
        .off("change.s2")
        .on("change.s2", function () {
            // Dispatch native event supaya Alpine @change="setJasa" berjalan
            this.dispatchEvent(new Event("change"));
        });
}

function bindSelect2Barang() {
    $(".select2-barang")
        .off("change.s2")
        .on("change.s2", function () {
            this.dispatchEvent(new Event("change"));
        });
}

$(document).ready(function () {
    // Init Select2
    $('[name="pelanggan_id"]').select2({
        placeholder: "Cari pelanggan / plat nomor",
        allowClear: true,
    });

    $(".select2-jasa").select2({
        placeholder: "Cari jasa...",
        allowClear: true,
    });

    $(".select2-barang").select2({
        placeholder: "Cari barang...",
        allowClear: true,
    });

    // Bind event handler yang benar
    bindSelect2Pelanggan();
    bindSelect2Jasa();
    bindSelect2Barang();
});
