<?php

include('../connect.php');

header('Content-Type: application/json');


// ==========================================
// TOTAL PRODUCTS
// ==========================================
$productQuery = $conn->query("
    SELECT COUNT(*) AS total
    FROM product
");

if (!$productQuery) {
    echo json_encode([
        "error" => true,
        "message" => $conn->error
    ]);
    exit;
}

$totalProducts = $productQuery->fetch_assoc()['total'] ?? 0;


// ==========================================
// CURRENT WEEK
// ==========================================
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));


// ==========================================
// LAST WEEK
// ==========================================
$startOfLastWeek = date('Y-m-d', strtotime('monday last week'));
$endOfLastWeek = date('Y-m-d', strtotime('sunday last week'));


// ==========================================
// CURRENT WEEK SALES
// ==========================================
$weekSalesQuery = $conn_pos->query("
    SELECT COALESCE(SUM(si.Quantity), 0) AS total
    FROM point_of_sale.sales_item_test si
    INNER JOIN point_of_sale.sales_test s
        ON si.Sales_ID = s.Sales_ID
    WHERE DATE(s.date_created)
    BETWEEN '$startOfWeek' AND '$endOfWeek'
");

if (!$weekSalesQuery) {
    echo json_encode([
        "error" => true,
        "message" => $conn_pos->error
    ]);
    exit;
}

$weekSales = $weekSalesQuery->fetch_assoc()['total'] ?? 0;


// ==========================================
// LAST WEEK SALES
// ==========================================
$lastWeekSalesQuery = $conn_pos->query("
    SELECT COALESCE(SUM(si.Quantity), 0) AS total
    FROM point_of_sale.sales_item_test si
    INNER JOIN point_of_sale.sales_test s
        ON si.Sales_ID = s.Sales_ID
    WHERE DATE(s.date_created)
    BETWEEN '$startOfLastWeek' AND '$endOfLastWeek'
");

if (!$lastWeekSalesQuery) {
    echo json_encode([
        "error" => true,
        "message" => $conn_pos->error
    ]);
    exit;
}

$lastWeekSales = $lastWeekSalesQuery->fetch_assoc()['total'] ?? 0;


// ==========================================
// GROWTH RATE
// ==========================================
$growthRate = 0;

if ($lastWeekSales > 0) {
    $growthRate = round(
        (($weekSales - $lastWeekSales) / $lastWeekSales) * 100,
        2
    );
}


// ==========================================
// TOTAL SOLD
// ==========================================
$totalSoldQuery = $conn_pos->query("
    SELECT COALESCE(SUM(Quantity), 0) AS total
    FROM point_of_sale.sales_item_test
");

if (!$totalSoldQuery) {
    echo json_encode([
        "error" => true,
        "message" => $conn_pos->error
    ]);
    exit;
}

$totalSold = $totalSoldQuery->fetch_assoc()['total'] ?? 0;


// ==========================================
// TOTAL CUSTOMER
// ==========================================
$customerQuery = $conn->query("
    SELECT COUNT(*) AS total
    FROM customers
");

if (!$customerQuery) {
    echo json_encode([
        "error" => true,
        "message" => $conn->error
    ]);
    exit;
}

$totalCustomer = $customerQuery->fetch_assoc()['total'] ?? 0;


// ==========================================
// RESPONSE
// ==========================================
echo json_encode([
    "totalProducts"  => (int)$totalProducts,
    "growthRate"     => (float)$growthRate,
    "totalSold"      => (int)$totalSold,
    "totalCustomers" => (int)$totalCustomer,
    "updateDate"     => date("j M Y")
]);

$conn->close();
$conn_pos->close();

?>