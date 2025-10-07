document.addEventListener("DOMContentLoaded", () => {
    fetch("dashboardQuery/return_summary.php")
        .then(res => res.json())
        .then(data => {
            document.getElementById("return-count").textContent = data.TotalReturned || 0;
            document.getElementById("return-product").textContent = data.MostReturned || "N/A";
            document.getElementById("return-reason").textContent = data.Reason || "N/A";
        })
        .catch(err => console.error("Return summary error:", err));

    fetch("dashboardQuery/cofidence_data.php")
        .then(res => res.json())
        .then(data => {
            document.getElementById("confidence-level").textContent = data.confidenceLevel || 0;
            document.getElementById("confidence-interval").textContent = data.interval || "±0";
            document.getElementById("week-upper").textContent = data.weekUpper || "-";
            document.getElementById("week-lower").textContent = data.weekLower || "-";
        })
        .catch(err => console.error("Confidence fetch error:", err));

    fetch("dashboardQuery/projected_sales.php")
        .then(res => res.json())
        .then(data => {
            const projected = data.projected || 0;
            const current = data.current || 0;
            const percent = projected ? ((current / projected) * 100).toFixed(1) : 0;

            document.getElementById("projected-value").textContent = projected.toLocaleString();
            document.getElementById("current-value").textContent = current.toLocaleString();
            document.getElementById("progress-percent").textContent = percent + "%";

            const progressFill = document.getElementById("progress-fill");
            progressFill.style.width = Math.min(percent, 100) + "%";
        })
        .catch(err => console.error("Projected fetch error:", err));
});
