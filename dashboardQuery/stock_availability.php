<?php
include('../connect.php');
header('Content-Type: application/json');

$totalStockQuery = "
    SELECT SUM(i.Inventory) AS totalStock
    FROM inventory i
";
$totalStock = $conn->query($totalStockQuery)->fetch_assoc()['totalStock'] ?? 0;

$chartQuery = "
    SELECT
        SUM(CASE WHEN i.Inventory = 0 THEN 1 ELSE 0 END) AS outOfStock,
        SUM(CASE WHEN i.Inventory <= IFNULL(p.ReordingPoints, 5) AND i.Inventory > 0 THEN 1 ELSE 0 END) AS lowStock,
        SUM(CASE WHEN i.Inventory > IFNULL(p.ReordingPoints, 5) THEN 1 ELSE 0 END) AS available
    FROM product p
    INNER JOIN inventory i ON p.Product_ID = i.Product_ID
";
$chartData = $conn->query($chartQuery)->fetch_assoc();

$lowStockQuery = "
    SELECT 
        p.ProductName, 
        p.Type, 
        i.Inventory,
        CASE 
            WHEN i.Inventory = 0 THEN 'Out of Stock'
            WHEN i.Inventory <= IFNULL(p.ReordingPoints, 5) THEN 'Low Stock'
            ELSE 'Available'
        END AS StockStatus
    FROM product p
    INNER JOIN inventory i ON p.Product_ID = i.Product_ID
    WHERE i.Inventory <= IFNULL(p.ReordingPoints, 5)
    ORDER BY i.Inventory ASC
    LIMIT 10
";
$lowStockResult = $conn->query($lowStockQuery);

$lowStock = [];
while ($row = $lowStockResult->fetch_assoc()) {
    $lowStock[] = [
        'ProductName' => $row['ProductName'],
        'Type' => $row['Type'],
        'Units' => (int)$row['Inventory'],
        'Status' => $row['StockStatus']
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

$conn->close();
?>
