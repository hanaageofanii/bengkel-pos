/* ═══════════════════════════════════════════════════════
   selfbilling.js — Modal manager + table filter
   Tidak ada konflik: satu sumber kebenaran untuk modal
════════════════════════════════════════════════════════ */

// ── Modal open/close ─────────────────────────────────────────────────────────

function openModal(id) {
    const overlay = document.getElementById(id);
    if (!overlay) return;
    const modal = overlay.querySelector(".sb-modal");

    // Reset transform sebelum tampil
    modal.style.transform = "translateY(16px) scale(0.97)";
    overlay.style.display = "flex";

    // Micro-delay agar browser sempat paint display:flex sebelum transition
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            overlay.style.opacity = "1";
            overlay.style.pointerEvents = "all";
            modal.style.transform = "translateY(0) scale(1)";
        });
    });

    document.body.style.overflow = "hidden";
}

function closeModal(id) {
    const overlay = document.getElementById(id);
    if (!overlay) return;
    const modal = overlay.querySelector(".sb-modal");

    overlay.style.opacity = "0";
    overlay.style.pointerEvents = "none";
    modal.style.transform = "translateY(16px) scale(0.97)";

    setTimeout(() => {
        overlay.style.display = "none";
    }, 260);
    document.body.style.overflow = "";
}

// Klik di luar modal (overlay) → tutup
function handleOverlayClick(e, id) {
    if (e.target === e.currentTarget) closeModal(id);
}

// Escape key → tutup semua
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        ["modalEdit", "modalDelete"].forEach((id) => closeModal(id));
    }
});

// ── Open Edit (ambil data dari data-* attribute tombol) ───────────────────────

function openEdit(btn) {
    const id = btn.dataset.id;
    const vendor = btn.dataset.vendor;
    const tanggal = btn.dataset.tanggal;
    const barang = btn.dataset.barang;
    const jumlah = btn.dataset.jumlah;
    const total = btn.dataset.total;
    const notes = btn.dataset.notes;

    document.getElementById("e-vendor").value = vendor;
    document.getElementById("e-tanggal").value = tanggal;
    document.getElementById("e-barang").value = barang;
    document.getElementById("e-jumlah").value = jumlah;
    document.getElementById("e-total").value = total
        ? "Rp. " + Number(total).toLocaleString("id-ID")
        : "";
    document.getElementById("e-notes").value = notes || "";

    // Update hint total dalam format rupiah
    updateTotalHint(total);

    // Set action form ke route update yang benar
    document.getElementById("editForm").action = "/selfbilling/" + id;

    openModal("modalEdit");
}

// Preview rupiah saat ketik di field total
document.addEventListener("DOMContentLoaded", () => {
    const totalInput = document.getElementById("e-total");
    if (totalInput) {
        totalInput.addEventListener("input", () =>
            updateTotalHint(totalInput.value),
        );
    }
});

function updateTotalHint(val) {
    const hint = document.getElementById("e-total-hint");
    if (!hint) return;
    const raw = String(val).replace(/[^0-9]/g, "");
    hint.textContent = raw
        ? "= " +
          new Intl.NumberFormat("id-ID", {
              style: "currency",
              currency: "IDR",
              minimumFractionDigits: 0,
          }).format(raw)
        : "";
}

function openDelete(btn) {
    const id = btn.dataset.id;
    const vendor = btn.dataset.vendor;
    const total = btn.dataset.total;
    const sisa = btn.dataset.sisa;

    document.getElementById("d-vendor").textContent = vendor;
    document.getElementById("d-total").textContent = "Rp " + total;

    // Info sisa hutang untuk konteks penghapusan
    const sisaInfo = document.getElementById("d-sisa-info");
    if (sisaInfo) {
        const sisaNum = parseInt(sisa.replace(/\D/g, ""));
        sisaInfo.textContent =
            sisaNum > 0
                ? "seluruh riwayat cicilan (sisa hutang Rp " + sisa + ")"
                : "seluruh riwayat cicilan (sudah lunas)";
    }

    document.getElementById("deleteForm").action = "/selfbilling/" + id;

    openModal("modalDelete");
}

// ── Filter tabel berdasarkan input search ─────────────────────────────────────

function filterTable() {
    const q = document.getElementById("tableSearch").value.toLowerCase().trim();
    const rows = document.querySelectorAll("#invoiceTable tbody tr");

    rows.forEach((row) => {
        // Jangan sembunyikan empty-state row
        if (row.querySelector("td[colspan]")) return;

        const text = row.textContent.toLowerCase();
        row.style.display = !q || text.includes(q) ? "" : "none";
    });
}
