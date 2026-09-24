<?php
require_once 'connect.php';

header('Content-Type: application/json');

$sql = "
    SELECT
        ListToOrder_ID,
        Supplier_ID,
        Order_Status
    FROM list_to_order
    WHERE Order_Status NOT IN ('Cancelled', 'Received')
    ORDER BY ListToOrder_ID DESC
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => $conn->error
    ]);
    exit;
}

$lists = [];

while ($row = $result->fetch_assoc()) {
    $lists[] = $row;
}

echo json_encode([
    'success' => true,
    'data' => $lists
]);
?>  