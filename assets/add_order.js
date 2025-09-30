document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addOrderRestockModal");
    const openBtn = document.getElementById("order-restock-add-btn");
    const ordcancelbtn = document.getElementById("ord-cancel-btn");

    // Open modal when ADD button is clicked
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    }); 

    // Close modal when cancel button is clicked
    ordcancelbtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside modal content
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

});