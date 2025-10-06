<?php
include('../connect.php');

header('Content-Type: application/json');

$query = "
    SELECT ConfidenceLevel, ProjectedSales
    FROM weekly_forecast
    ORDER BY WeeklyForecast_ID DESC
    LIMIT 1
";

$result = $conn->query($query);
$data = $result->fetch_assoc();

$confidenceLevel = $data['ConfidenceLevel'] ?? 0;
$projectedSales = $data['ProjectedSales'] ?? 0;

$interval = round(($projectedSales * 0.0765), 2);
$weekSalesUpper = round($projectedSales + $interval, 2);
$weekSalesLower = round($projectedSales - $interval, 2);

echo json_encode([
    'ConfidenceLevel' => $confidenceLevel,
    'Interval' => $interval,
    'WeekSalesUpper' => $weekSalesUpper,
    'WeekSalesLower' => $weekSalesLower
]);

$conn->close();
?>
