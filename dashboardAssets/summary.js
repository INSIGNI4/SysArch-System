document.addEventListener("DOMContentLoaded", () => {
    fetch("dashboardQuery/get_summary.php")
        .then(res => res.json())
        .then(data => {
            document.getElementById("total-products").textContent = data.totalProducts
            document.getElementById("growth-rate").textContent = data.growthRate + "%";
            document.getElementById("total-sold").textContent = Number(data.totalSold || 0).toLocaleString();
            document.getElementById("total-customers").textContent = data.totalCustomers;

            document.getElementById("update-products").textContent = "Update " + data.updateDate;
            document.getElementById("update-growth").textContent   = "Update " + data.updateDate;
            document.getElementById("update-sold").textContent     = "Update " + data.updateDate;
            document.getElementById("update-customers").textContent= "Update " + data.updateDate;
        })
        .catch(err => console.error("Summary fetch error:", err))
})