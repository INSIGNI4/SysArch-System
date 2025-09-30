document.addEventListener("DOMContentLoaded", () => {
    fetch('dashboardQuery/top_customers.php')
        .then(response => response.json())
        .then(customers => {
            console.log("Fetched customers:", customers);

            let tableBody = document.querySelector("#topcustomer-body");
            if (!tableBody) {
                console.error("Could not find #topcustomer-body");
                return;
            }

            tableBody.innerHTML = "";

            if (!customers || customers.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="2">No data found</td></tr>`;
                return;
            }

            customers.forEach((cust, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}. ${cust.CustomerName}</td>
                        <td>${cust.TotalOrders}</td>
                    </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(err => console.error("Loading Top Customers Error:", err));
});



