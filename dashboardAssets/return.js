document.addEventListener("DOMContentLoaded", () => {
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    const currentMonth = new Date().getMonth();

    const monthEl = document.getElementById("return-month");
    if (monthEl) monthEl.textContent = `(${monthNames[currentMonth]})`;

    // Fetch return summary
    fetch("dashboardQuery/return_summary.php")
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            return res.json();
        })
        .then(data => {
            const countEl = document.getElementById("return-count");
            const productEl = document.getElementById("return-product");
            const reasonEl = document.getElementById("return-reason");

            if (countEl) countEl.textContent = data.TotalReturned ?? 0;
            if (productEl) productEl.textContent = data.MostReturned ?? "N/A";
            if (reasonEl) reasonEl.textContent = data.Reason ?? "N/A";
        })
        .catch(err => console.error("Return summary error:", err));
});
