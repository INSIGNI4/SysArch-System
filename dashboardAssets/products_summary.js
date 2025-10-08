document.addEventListener("DOMContentLoaded", () => {
    const categories = document.querySelectorAll(".product-categories button");
    const sortSelect = document.getElementById("sort-select");
    const timeSelect = document.getElementById("time-select");
    const productsBody = document.getElementById("products-body");

    let currentType = "All";
    let currentOrder = "Bestseller";
    let currentTime = "daily";

    const fetchProducts = () => {
        fetch(`dashboardQuery/products_summary.php?type=${currentType}&order=${currentOrder}&time=${currentTime}`)
            .then(res => res.json())
            .then(data => renderProducts(data))
            .catch(err => console.error("Fetching ERROR:", err));
    };

    const renderProducts = (products) => {
        productsBody.innerHTML = "";
        if (products.length === 0) {
            productsBody.innerHTML = `<tr><td colspan = "2">No Products Found</td></tr>`;
            return;
        }
        products.forEach((p, i) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>
                    <div class="product-item">
                        <span class="product-rank">${i + 1}.</span>
                        <img src="${p.Image}" alt="${p.ProductName}">
                        <div class="product-info">
                            <strong>${p.ProductName}</strong> <small>${p.Type}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="product-order">
                        Price: <span class="price">PHP${p.StorePrice}</span><br>
                        Sold: <span class="sold">${p.UnitSold}</span>
                    </div>
                </td>
            `;
            productsBody.appendChild(row);
        });
    };

    categories.forEach(btn => {
        btn.addEventListener("click", () => {
            categories.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            currentType = btn.dataset.type;
            fetchProducts();
        });
    });

    sortSelect.addEventListener("change", () => {
        currentOrder = sortSelect.value;
        fetchProducts();
    });

    timeSelect.addEventListener("change", () => {
        currentTime = timeSelect.value;
        fetchProducts();
    });

    fetchProducts();

});