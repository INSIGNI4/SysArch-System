document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("addCusReturnsModal");
    const openBtn = document.getElementById("cusreturns-add-btn");
    const cusreturncancelbtn = document.getElementById("cus-return-cancel-btn");

    // Open modal when ADD button is clicked
    openBtn.addEventListener("click", () => {
        modal.style.display = "block";
    }); 

    // Close modal when cancel button is clicked
    cusreturncancelbtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside modal content
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

});