document.addEventListener("DOMContentLoaded", () => {
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const currentMonth = new Date().getMonth();

    const monthEl = document.getElementById("sales-month");

    if (monthEl) {
        monthEl.textContent = `(${monthNames[currentMonth]})`;
    }

    // Fetch sales performance
    fetch("dashboardQuery/sales_performance.php")
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }

            return res.json();
        })
        .then(data => {

            const totalEl = document.getElementById("sales-total");
            const transactionsEl = document.getElementById("sales-transactions");
            const unitsEl = document.getElementById("sales-units");
            const averageEl = document.getElementById("sales-average");

            if (totalEl) {
                totalEl.textContent = `₱${Number(data.TotalSales || 0).toLocaleString("en-PH", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })}`;
            }

            if (transactionsEl) {
                transactionsEl.textContent = Number(data.TotalTransactions || 0).toLocaleString();
            }

            if (unitsEl) {
                unitsEl.textContent = Number(data.UnitsSold || 0).toLocaleString();
            }

            if (averageEl) {
                averageEl.textContent = `₱${Number(data.AverageSale || 0).toLocaleString("en-PH", {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })}`;
            }
        })
        .catch(err => {
            console.error("Sales performance error:", err);
        });
});