document.addEventListener("DOMContentLoaded", () => {
    const forecastModals = [
        { openId: "daily-forecast-add-btn", modalId: "daily-addForecastModal", cancelId: "daily-forecast-cancel-btn" },
        { openId: "weekly-forecast-add-btn", modalId: "weekly-addForecastModal", cancelId: "weekly-forecast-cancel-btn" },
        { openId: "monthly-forecast-add-btn", modalId: "monthly-addForecastModal", cancelId: "monthly-forecast-cancel-btn" }
    ];

    forecastModals.forEach(({ openId, modalId, cancelId }) => {
        const openBtn = document.getElementById(openId);
        const modal = document.getElementById(modalId);
        const cancelBtn = document.getElementById(cancelId);

        if (!openBtn || !modal) return;

        openBtn.addEventListener("click", () => {
            modal.style.display = "block";
        });

        if (cancelBtn) {
            cancelBtn.addEventListener("click", () => {
                modal.style.display = "none";
            });
        }
    });

    window.addEventListener("click", (event) => {
        forecastModals.forEach(({ modalId }) => {
            const modal = document.getElementById(modalId);
            if (modal && event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
});
