<?php
include('../connect.php');

$typeFilter = isset($_GET['type']) && $_GET['type'] !== 'All' ? $_GET['type'] : '';
$orderFilter = isset($_GET['order']) ? $_GET['order'] : 'Bestseller';
$timeFilter = isset($_GET['time']) ? $_GET['time'] : 'daily';

$dateCondition = "";

switch ($timeFilter) {
    case 'daily':
        $dateCondition = "AND s.SalesDate >= CURDATE()";
        break;
    case 'weekly':
        $dateCondition = "AND s.SalesDate >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        break;
    case 'monthly':
        $dateCondition = "AND s.SalesDate >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        break;
}

$query = "
    SELECT
        p.Product_ID,
        p.ProductName,
        p.Type,
        p.StorePrice,
        p.Image,
        IFNULL(SUM(s.Quantity), 0) AS UnitSold
    FROM product p
    LEFT JOIN sales s ON p.Product_ID = s.Product_ID $dateCondition
";

if ($typeFilter != '') {
    $query .= " WHERE p.Type = '" . $conn->real_escape_string($typeFilter) . "'";
}

$query .= " GROUP BY p.Product_ID";

if ($orderFilter === 'Bestseller') {
    $query .= " ORDER BY UnitSold DESC";
} else {
    $query .= " ORDER BY p.ProductName ASC";
}

$result = $conn->query($query);

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
$conn->close();
?>
