<?php
include('../connect.php');

header('Content-Type: application/json');

$salesQuery = "
    SELECT
        COALESCE(SUM(s.total_amount), 0) AS TotalSales,
        COUNT(s.Sales_ID) AS TotalTransactions
    FROM point_of_sale.sales_test s
    WHERE YEAR(s.date_created) = YEAR(CURDATE())
      AND MONTH(s.date_created) = MONTH(CURDATE())
";

$salesResult = $conn->query($salesQuery);

if (!$salesResult) {
    echo json_encode([
        'error' => 'Sales query failed',
        'message' => $conn->error
    ]);
    exit;
}

$salesData = $salesResult->fetch_assoc();

$totalSales = (float)($salesData['TotalSales'] ?? 0);
$totalTransactions = (int)($salesData['TotalTransactions'] ?? 0);


$unitsQuery = "
    SELECT
        COALESCE(SUM(si.Quantity), 0) AS UnitsSold
    FROM point_of_sale.sales_item_test si
    INNER JOIN point_of_sale.sales_test s
        ON si.Sales_ID = s.Sales_ID
    WHERE YEAR(s.date_created) = YEAR(CURDATE())
      AND MONTH(s.date_created) = MONTH(CURDATE())
";

$unitsResult = $conn->query($unitsQuery);

if (!$unitsResult) {
    echo json_encode([
        'error' => 'Units sold query failed',
        'message' => $conn->error
    ]);
    exit;
}

$unitsData = $unitsResult->fetch_assoc();

$unitsSold = (int)($unitsData['UnitsSold'] ?? 0);


$averageSale = $totalTransactions > 0
    ? $totalSales / $totalTransactions
    : 0;


echo json_encode([
    'TotalSales' => $totalSales,
    'TotalTransactions' => $totalTransactions,
    'UnitsSold' => $unitsSold,
    'AverageSale' => $averageSale
]);

$conn->close();
?>