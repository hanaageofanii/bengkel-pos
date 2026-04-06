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
        },

        setJasa(e, i) {
            let o = e.target.selectedOptions[0];
            this.jasa[i] = {
                id: o.value,
                nama: o.dataset.nama,
                harga_pribadi: +o.dataset.pribadi,
                harga_perusahaan: +o.dataset.perusahaan,
                harga:
                    this.tipePelanggan === "perusahaan"
                        ? +o.dataset.perusahaan
                        : +o.dataset.pribadi,
            };
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
        },

        setBarang(e, i) {
            let o = e.target.selectedOptions[0];
            let stock = +o.dataset.stock;
            if (stock <= 0) {
                alert("STOCK HABIS");
                return;
            }
            if (stock <= 5) {
                alert(`PERINGATAN: Stock ${o.dataset.nama} tinggal ${stock}`);
            }
            this.barang[i] = {
                id: o.value,
                nama: o.dataset.nama,
                qty: 1,
                stock,
                harga_pribadi: +o.dataset.pribadi,
                harga_perusahaan: +o.dataset.perusahaan,
                harga:
                    this.tipePelanggan === "perusahaan"
                        ? +o.dataset.perusahaan
                        : +o.dataset.pribadi,
            };
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
