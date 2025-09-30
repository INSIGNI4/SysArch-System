<?php
include('../connect.php');

// Example: fetching forecast and actual sales per week
$query = "
    SELECT f.ForecastPeriod, f.ProjectedSales AS Forecast, 
           sa.TotalSales AS Actual
    FROM forecast f
    LEFT JOIN salesaggregration sa 
        ON f.ProductScope = sa.Product_ID 
        AND f.ForecastPeriod = sa.PeriodStart
    ORDER BY f.ForecastStart
";

$result = $conn->query($query);

$categories = [];
$forecastData = [];
$actualData = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row['ForecastPeriod'];
    $forecastData[] = (float)$row['Forecast'];
    $actualData[] = (float)$row['Actual'];
}
?>
