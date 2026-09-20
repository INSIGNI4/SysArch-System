<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../db_connection.php';

$mode = $_GET['mode'] ?? 'product';


// --  WHERE ForecastDate >= CURDATE()
//         --    AND ForecastDate < DATE_ADD(CURDATE(), INTERVAL 7 DAY)

if ($mode === 'category') {
    $sql = "
        SELECT
            Category,
            ROUND(SUM(Predicted_Demand), 2) AS Predicted_Demand
        FROM ml_predictions
        
        WHERE ForecastDate > CURDATE()
          AND ForecastDate <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)
        GROUP BY Category
        ORDER BY Predicted_Demand DESC
    ";
} elseif ($mode === 'trend') {
    $sql = "
        SELECT
            ForecastDate,
            ROUND(SUM(Predicted_Demand), 2) AS Predicted_Demand
        FROM ml_predictions
        WHERE ForecastDate > CURDATE()
          AND ForecastDate <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)
        GROUP BY ForecastDate
        ORDER BY ForecastDate
    ";
} else {
    $sql = "
        SELECT
            Product_ID,
            ProductName,
            Category,
            ROUND(SUM(Predicted_Demand), 2) AS Predicted_Demand
        FROM ml_predictions
        WHERE ForecastDate > CURDATE()
          AND ForecastDate <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)
        GROUP BY Product_ID, ProductName, Category
        ORDER BY Predicted_Demand DESC
    ";
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'error' => mysqli_error($conn)
    ]);
    exit;
}

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
