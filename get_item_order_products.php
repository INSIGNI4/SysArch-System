<?php
require_once 'connect.php';

header('Content-Type: application/json');

$sql = "
    SELECT
        p.Product_ID,
        p.ProductName,
        p.Pack_Size,
        p.SupplierPrice,
        p.Supplier_ID,
        p.CurrentStock
    FROM product p
    ORDER BY p.Product_ID ASC
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => $conn->error
    ]);
    exit;
}

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    'success' => true,
    'data' => $products
]);
?>