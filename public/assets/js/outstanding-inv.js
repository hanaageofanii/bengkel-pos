function filterTable() {
    const input = document.getElementById("tableSearch").value.toLowerCase();
    const rows = document.querySelectorAll("#invoiceTable tbody tr");
    rows.forEach((row) => {
        const cell = row.querySelector("td:nth-child(2)");
        if (!cell) return;
        row.style.display = cell.textContent.toLowerCase().includes(input)
            ? ""
            : "none";
    });
}
