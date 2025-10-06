<?php
include('../connect.php');

header('Content-Type: application/json');

$forecastQuery = $conn->query("
    SELECT ProjectedSales
    FROM weekly_forecast
    ORDER BY WeeklyForecast_ID DESC
    LIMIT 1
");
$forecast = $forecastQuery->fetch_assoc();
$projectedSales = $forecast['ProjectedSales'] ?? 0;

$currentSalesQuery = $conn->query("
    SELECT SUM(TotalSales) AS TotalSales
    FROM weekly_total_sales
    ORDER BY EntireWeeklySales_ID DESC
    LIMIT 1
");
$currentSales = $currentSalesQuery->fetch_assoc()['TotalSales'] ?? 0;

// Calculate progress
$progress = $projectedSales > 0 ? round(($currentSales / $projectedSales) * 100, 0) : 0;
$progress = min(100, max(0, $progress));

echo json_encode([
    'ProjectedSales' => round($projectedSales, 2),
    'CurrentSales' => round($currentSales, 2),
    'Progress' => $progress
]);

$conn->close();
?>
