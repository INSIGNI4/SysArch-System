
<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../connect.php';

if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed.'
    ]);
    exit;
}

$sql = "
    SELECT Product_ID, ProductName
    FROM product
    ORDER BY ProductName
";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch products.'
    ]);
    exit;
}

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);