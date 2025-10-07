document.addEventListener("DOMContentLoaded", async () => {
    try {
        console.log("Stock Availability JS loaded");

        const res = await fetch('dashboardQuery/stock_availability.php');
        if (!res.ok) throw new Error('❌ Failed to fetch PHP data');

        const data = await res.json();
        console.log("Data from PHP:", data);

        const totalStockEl = document.getElementById("total-stock");
        if (totalStockEl) {
            totalStockEl.textContent = data.totalStock ?? 0;
        } else {
            console.warn("Element #total-stock not found");
        }

        const chartCanvas = document.getElementById("myChart");
        if (!chartCanvas) throw new Error("Canvas with id 'myChart' not found in DOM");

        chartCanvas.style.width = "100%";
        chartCanvas.style.height = "220px";
        chartCanvas.parentElement.style.position = "relative";

        if (window.stockChart) {
            window.stockChart.destroy();
        }

        const ctx = chartCanvas.getContext('2d');
        window.stockChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Low Stock', 'Out of Stock'],
                datasets: [{
                    data: [
                        data.chart?.available || 0,
                        data.chart?.lowStock || 0,
                        data.chart?.outOfStock || 0
                    ],
                    backgroundColor: ['#45df45', '#ecc61c', '#ff0000'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const listContainer = document.querySelector("#low-stock-list ul");
        if (!listContainer) throw new Error("Missing #low-stock-list <ul> element");

        listContainer.innerHTML = "";
        if (Array.isArray(data.lowStockList) && data.lowStockList.length > 0) {
            data.lowStockList.forEach((item, idx) => {
                listContainer.innerHTML += `
                    <li>
                        <strong>${idx + 1}. ${item.ProductName}</strong><br>
                        <span>Units: ${item.Units}</span><br>
                        <span>Type: ${item.Type}</span>
                    </li>`;
            });
        } else {
            listContainer.innerHTML = `<li>No low stock items</li>`;
        }

        console.log("Chart rendered successfully");

    } catch (err) {
        console.error("Error in stock_avail.js:", err);
    }
});
