
<?php

include('../connect.php');

header('Content-Type: application/json');

$typeFilter = isset($_GET['type']) && $_GET['type'] !== 'All'
    ? $_GET['type']
    : '';

$orderFilter = isset($_GET['order'])
    ? $_GET['order']
    : 'Bestseller';

$timeFilter = isset($_GET['time'])
    ? $_GET['time']
    : 'daily';

$dateCondition = "";

switch ($timeFilter) {

    case 'daily':
        $dateCondition = "
            AND DATE(s.date_created) >= CURDATE()
        ";
        break;

    case 'weekly':
        $dateCondition = "
            AND s.date_created >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        ";
        break;

    case 'monthly':
        $dateCondition = "
            AND s.date_created >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ";
        break;
    default:
        $dateCondition = "";
        break;
}


$query = "
    SELECT
        p.Product_ID,
        p.ProductName,
        p.Type,
        p.StorePrice,
        p.Image,
        IFNULL(SUM(si.Quantity), 0) AS UnitSold

    FROM login.product p

    LEFT JOIN point_of_sale.sales_item_test si
        ON p.Product_ID = si.Product_ID

    LEFT JOIN point_of_sale.sales_test s
        ON si.Sales_ID = s.Sales_ID
        $dateCondition
";


if ($typeFilter !== '') {

    $safeType = $conn->real_escape_string($typeFilter);

    $query .= "
        WHERE p.Type = '$safeType'
    ";
}


$query .= "
    GROUP BY
        p.Product_ID,
        p.ProductName,
        p.Type,
        p.StorePrice,
        p.Image
";


if ($orderFilter === 'Bestseller') {

    $query .= "
        ORDER BY UnitSold DESC
    ";

} else {

    $query .= "
        ORDER BY p.ProductName ASC
    ";
}


$result = $conn->query($query);


if (!$result) {

    echo json_encode([
        "error" => "Products summary query failed",
        "message" => $conn->error,
        "query" => $query
    ]);

    exit;
}


$products = [];

while ($row = $result->fetch_assoc()) {

    if (
        empty($row['Image']) ||
        $row['Image'] === 'null'
    ) {
        $row['Image'] = 'uploads/no_image.png';
    }

    $products[] = $row;
}


echo json_encode($products);


$conn->close();
$conn_pos->close();

?>