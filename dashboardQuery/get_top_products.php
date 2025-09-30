<?php
header('Content-Type: application/json');

include('../connect.php');
$query = "
    SELECT p.ProductName, SUM(s.Quantity) AS TotalSold
    FROM sales s
    INNER JOIN product p ON s.Product_ID = p.Product_ID
    GROUP BY p.ProductName
    ORDER BY TotalSold DESC
    LIMIT 5
";

$result = $conn->query($query);

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
