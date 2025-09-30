document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addTransactionModal");
    const openBtn = document.getElementById("transaction-add-btn");
    const transactioncancelbtn = document.getElementById("transaction-cancel-btn");

    const productSelect = document.getElementById("Product_IDTRANS");
    const quantityInput = document.querySelector("input[name='Quantity']");
    const totalSalesInput = document.querySelector("input[name='TotalSales']");
    const totalDisplay = document.getElementById("total-display");

    let currentPrice = 0;

    // Open modal
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    });

    // Close modal
    transactioncancelbtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close when clicking outside modal
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    productSelect.addEventListener("change", () => {
        const productId = productSelect.value;
        if (!productId) return;

        fetch(`get_productprice.php?Product_ID=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentPrice = parseFloat(data.price);
                    updateTotal(); 
                } else {
                    alert(data.message || "Product not found");
                    currentPrice = 0;
                    updateTotal();
                }
            })
            .catch(err => console.error("Error getting the price", err));
    });

    quantityInput.addEventListener("input", updateTotal);

    function updateTotal() {
        const quantity = parseInt(quantityInput.value) || 0;
        const total = currentPrice * quantity;
        totalSalesInput.value = total;
        totalDisplay.textContent = "PHP " + total.toFixed(2);
    }
});
