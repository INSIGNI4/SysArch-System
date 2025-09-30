document.addEventListener("DOMContentLoaded", () => {
    const analyticsModals = [
        { openId: "daily-analytics-add-btn", modalId: "daily-addAnalyticsModal", cancelId: "daily-analytics-cancel-btn" },
        { openId: "weekly-analytics-add-btn", modalId: "weekly-addAnalyticsModal", cancelId: "weekly-analytics-cancel-btn" },
        { openId: "monthly-analytics-add-btn", modalId: "monthly-addAnalyticsModal", cancelId: "monthly-analytics-cancel-btn" }
    ];

    analyticsModals.forEach(({ openId, modalId, cancelId }) => {
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
        analyticsModals.forEach(({ modalId }) => {
            const modal = document.getElementById(modalId);
            if (modal && event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
});
