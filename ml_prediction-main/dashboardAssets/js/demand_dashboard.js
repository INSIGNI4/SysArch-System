const demandCharts = {};

async function loadDemandChart(canvasId, mode = "product") {

    // const response = await fetch(
    //     `../dashboardQuery/get_demand_predictions.php?mode=${mode}`
    // );
    const response = await fetch(
    `ml_prediction-main/dashboardQuery/get_demand_predictions.php?mode=${mode}`
    );

    const data = await response.json();

    if (!Array.isArray(data)) {
        console.error(data);
        return;
    }

    const canvas = document.getElementById(canvasId);

    if (!canvas) {
        return;
    }

    if (demandCharts[canvasId]) {
        demandCharts[canvasId].destroy();
    }

    const labels = data.map(row => {

        if (mode === "category") return row.Category;

        if (mode === "trend") return row.ForecastDate;

        return row.ProductName;
    });

    const values = data.map(row =>
        Number(row.Predicted_Demand)
    );

    if (mode === "product") {

        document.getElementById("productsForecasted").textContent =
            data.length;
    }

    if (mode === "trend" && data.length > 0) {

        const totalDemand = values.reduce(
            (sum, value) => sum + value,
            0
        );

        const peakIndex = values.indexOf(
            Math.max(...values)
        );

        document.getElementById("forecastPeriod").textContent =
            data.length + " Days";

        document.getElementById("totalPredictedDemand").textContent =
            totalDemand.toFixed(2);

        document.getElementById("peakForecastDay").textContent =
            data[peakIndex].ForecastDate;

        document.getElementById("peakForecastValue").textContent =
            values[peakIndex].toFixed(2) + " units";
    }


    demandCharts[canvasId] = new Chart(canvas, {

        type: mode === "trend" ? "line" : "bar",

        data: {
            labels: labels,
            datasets: [{
                label: "Predicted Demand",
                data: values,

                backgroundColor:
                    mode === "product"
                        ? [
                            "#2196F3",
                            "#7E57C2",
                            "#FF9800",
                            "#2ECC71",
                            "#EF5350",
                            "#26B6C4",
                            "#FBC02D"
                        ]
                        : mode === "category"
                            ? [
                                "#9C27B0",
                                "#26B6C4"
                            ]
                            : "rgba(33, 150, 243, 0.15)",

                borderColor:
                    mode === "trend"
                        ? "#2196F3"
                        : undefined,

                borderWidth:
                    mode === "trend"
                        ? 3
                        : 1,

                pointBackgroundColor:
                    mode === "trend"
                        ? "#2196F3"
                        : undefined,

                pointBorderColor:
                    mode === "trend"
                        ? "#ffffff"
                        : undefined,

                pointRadius:
                    mode === "trend"
                        ? 5
                        : undefined
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,

                    title: {
                        display: true,
                        text: "Units"
                    }
                }
            }
        }
    });
}