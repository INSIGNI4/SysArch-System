





<?php
include 'connect.php'; // your DB connect

$type = $_GET['type'] ?? 'daily'; // default to daily

switch ($type) {
    case 'weekly':
        $view = 'v_store_sales_forecast_weekly';
        break;
    case 'monthly':
        $view = 'v_store_sales_forecast_monthly';
        break;
    default:
        $view = 'v_store_sales_forecast_daily';
}

// $sql = "SELECT Period, TotalQuantity, MovingAverage3 FROM $view ORDER BY Period";
$sql = "SELECT * FROM (
            SELECT Period, TotalQuantity, MovingAverage3
            FROM $view
            ORDER BY Period DESC
            LIMIT 5
        ) AS recent
        ORDER BY Period ASC;";




$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>



