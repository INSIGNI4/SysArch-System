<?php

header('Content-Type: application/json');

include('../connect.php');

$query = "
    SELECT
        p.ProductName,
        SUM(si.Quantity) AS TotalSold
    FROM point_of_sale.sales_item_test si
    INNER JOIN point_of_sale.sales_test s
        ON si.Sales_ID = s.Sales_ID
    INNER JOIN login.product p
        ON si.Product_ID = p.Product_ID
    GROUP BY
        si.Product_ID,
        p.ProductName
    ORDER BY TotalSold DESC
    LIMIT 5
";

$result = $conn_pos->query($query);

if (!$result) {
    echo json_encode([
        "error" => true,
        "message" => $conn_pos->error
    ]);
    exit;
}

$categories = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row['ProductName'];
    $data[] = (int)$row['TotalSold'];
}

echo json_encode([
    "categories" => $categories,
    "data" => $data
]);

$conn_pos->close();

?>