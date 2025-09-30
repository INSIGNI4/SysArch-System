document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addCustomerModal");
    const openBtn = document.getElementById("customer-add-btn");
    const cuscancelbtn = document.getElementById("cus-cancel-btn");

    // Open modal when ADD button is clicked
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    }); 

    // Close modal when cancel button is clicked
    cuscancelbtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside modal content
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

});