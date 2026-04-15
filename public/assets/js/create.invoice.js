function setTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
}

function showStockPopup(type, message) {
    const popup = document.getElementById("stock-popup");
    const icon = document.getElementById("stock-popup-icon");
    const title = document.getElementById("stock-popup-title");
    const msg = document.getElementById("stock-popup-msg");

    if (type === "habis") {
        icon.textContent = "🚫";
        title.textContent = "Stok Habis";
        title.style.color = "var(--mz-red, #f26c6c)";
    } else {
        icon.textContent = "⚠️";
        title.textContent = "Peringatan Stok";
        title.style.color = "var(--mz-yellow, #f5c542)";
    }

    msg.textContent = message;
    popup.style.display = "flex";
}

function closeStockPopup() {
    document.getElementById("stock-popup").style.display = "none";
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
                const selects = document.querySelectorAll(".select2-jasa");
                const last = selects[selects.length - 1];
                const index = this.jasa.length - 1;

                last.setAttribute("data-index", index);

                if ($(last).data("select2")) $(last).select2("destroy");

                $(last)
                    .select2({
                        placeholder: "Cari jasa...",
                        allowClear: true,
                        width: "100%",
                    })
                    .on("change", (e) => {
                        const i = parseInt(e.target.getAttribute("data-index"));
                        this.setJasa(e, i);
                    });
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
                document
                    .querySelectorAll(".select2-barang")
                    .forEach((el, i) => {
                        if (!$(el).data("select2")) {
                            el.setAttribute("data-index", i);
                            $(el)
                                .select2({
                                    placeholder: "Cari barang...",
                                    allowClear: true,
                                    width: "100%",
                                })
                                .on("change", (e) => {
                                    const idx = parseInt(
                                        e.target.getAttribute("data-index"),
                                    );
                                    this.setBarang(e, idx);
                                });
                        }
                    });
            });
        },

        setBarang(e, i) {
            let select = e.target;
            let val = $(select).val();
            let o = select.querySelector(`option[value="${val}"]`);

            if (!o) return;

            let stock = +o.dataset.stock;

            if (stock <= 0) {
                showStockPopup(
                    "habis",
                    `${o.dataset.nama} sudah tidak tersedia.`,
                );
                return;
            }

            if (stock <= 5) {
                showStockPopup(
                    "warning",
                    `Sisa stok ${o.dataset.nama} tinggal ${stock} unit.`,
                );
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
                showStockPopup(
                    "warning",
                    `Qty melebihi stok. Maksimal ${b.stock} unit.`,
                );
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
                alpine.tipePelanggan = tipe;
                alpine.updateAllPrices();
            }
        });
}

$(document).ready(function () {
    $('[name="pelanggan_id"]').select2({
        placeholder: "Cari pelanggan / plat nomor",
        allowClear: true,
        width: "100%",
    });
    bindSelect2Pelanggan();

    $(".select2-jasa").each(function (i, el) {
        $(el).select2({ width: "100%", placeholder: "Cari jasa..." });
    });
    $(".select2-barang").each(function (i, el) {
        $(el).select2({ width: "100%", placeholder: "Cari barang..." });
    });
});
