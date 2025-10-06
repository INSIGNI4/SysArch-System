async function loadStock() {
    const res = await fetch('dashboardQuery/stock_availability.php')
    const data = await res.json();

    document.getElementById("total-stock").innerHTML = `<h3>Total Stock</h3><div>${data.totalStock}</div>`;

    new Chart(document.getElementById('stockDonut'), {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [data.chart.available, data.chart.lowStock, data.chart.outOfStock],
                backgroundColor: ['#45df45ff', '#ecc61cff', '#ff0000ff']
            }]
        },
        options: { cutout: '70%', plugins: { legend: { display: true } } }
    });

    let list = "<h3>Low Stock</h3><ul>";
    data.lowStockList.forEach((item, idx) => {
        list += `<li>${idx+1}. ${item.ProductName} | Units: ${item.Units} | Type: ${item.Type}</li>`;
    });
    list += "</ul>";
    document.getElementById("low-stock-list").innerHTML = list;
}

loadStock();