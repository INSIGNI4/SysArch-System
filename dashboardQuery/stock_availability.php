<?php
include('../connect.php');
header('Content-Type: application/json')

$totalStockQuery = "
    SELECT SUM(i.Inventory) AS totalStock
    FROM inventory i
";

$totalStock = $conn->query($totalStockQuery)->fetch_assoc()['totalStock'] ?? 0;

//Chart
$chartQuery = "
    SELECT
        SUM(CASE WHEN i.Inventory = 0 THEN 1 ELSE 0 END) AS outOfStock,
        SUM(CASE WHEN i.Inventory <= p.ReordingPoints AND i.Inventory > 0 THEN 1 ELSE 0 END) AS lowStock,
        SUM(CASE WHEN i.Inventory > p.ReordingPoints Then 1 ELSE 0 END) AS available
    FROM product p
    INNER JOIN inventory i on p.Product_ID = i.Product_ID
";

$charData = $conn->query($chartQuery)->fetch_assoc();

//Low stock list

$lowStockQuery = "
    SELECT p.ProductName, p.Type, i.Inventory
    FROM product p
    INNER JOIN inventory i on p.Product_ID = i.Product_ID
    WHERE i.Inventory <= p.ReordingPoints AND i.INVENTORY > 0
    ORDER BY i.Inventory ASC
    LIMIT by 5
";

$lowStockResult = $conn->query($lowStockQuery);

$lowStock = [];
while ($row = $lowStockResult->fetch_assoc()) {
    $lowStock[] = [
        'ProductName' => $row['ProductName'],
        'Type' => $row['Type'],
        'Units' => $row['Inventory']
    ];
}


echo json_encode([
    'totalStock' => (int)$totalStock,
    'chart' => [
        'available' => (int)$chartData['available'],
        'lowStock' => (int)$chartData['lowStock'],
        'outOfStock' => (int)$chartData['outOfStock']
    ],
    'lowStockList' => $lowStock
]);


?>