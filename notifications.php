<?php
include 'connect.php';
header('Content-Type: application/json');

$lowStockThreshold = 10;

$query = "
    SELECT 
        i.Product_ID,
        p.ProductName,
        i.Inventory,
        i.Status
    FROM inventory i
    INNER JOIN product p ON i.Product_ID = p.Product_ID
    WHERE i.Inventory <= $lowStockThreshold
";

$result = $conn->query($query);
$notifications = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['Inventory'] == 0) {
            $notifications[] = [
                'type' => 'out',
                'message' => "⚠️ Product ID {$row['Product_ID']} Name {$row['ProductName']} is OUT OF STOCK!"
            ];
        } else {
            $notifications[] = [
                'type' => 'low',
                'message' => "‼️ Product ID {$row['Product_ID']} Name {$row['ProductName']} is LOW on stock ({$row['Inventory']} left)."
            ];
        }
    }
}

echo json_encode($notifications);
$conn->close();
?>
