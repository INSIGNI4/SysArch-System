<?php

include('../connect.php');

header('Content-Type: application/json');

// TOTAL PRODUCTS COUNT
$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM product")->fetch_assoc()['total'];

//GROWTH RATE (THIS WEEK - LAST WEEK sales / LAST WEEK sales * 100)

$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek   = date('Y-m-d', strtotime('sunday this week'));


$startOfLastWeek = date('Y-m-d', strtotime('monday last week'));
$endOfLastWeek   = date('Y-m-d', strtotime('sunday last week'));


$weekSales = $conn->query("
    SELECT COALESCE(SUM(Quantity),0) AS total
    FROM sales
    WHERE SalesDate BETWEEN '$startOfWeek' AND '$endOfWeek'
")->fetch_assoc()['total'];

// Total quantity sold last week
$lastWeekSales = $conn->query("
    SELECT COALESCE(SUM(Quantity),0) AS total
    FROM sales
    WHERE SalesDate BETWEEN '$startOfLastWeek' AND '$endOfLastWeek'
")->fetch_assoc()['total'];

$growthRate = 0;
if ($lastWeekSales > 0) {
    $growthRate = round((($weekSales - $lastWeekSales) / $lastWeekSales) * 100, 2);
}

// TOTAL SOLD
$totalSold = $conn->query("SELECT COALESCE(SUM(Quantity), 0) AS total FROM sales")->fetch_assoc()['total'];

// TOTAL CUSTOMER
$totalCustomer = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];

// RESPONSE ARRAY
$response = [
    "totalProducts" => $totalProducts,
    "growthRate"    => $growthRate,
    "totalSold"     => $totalSold,
    "totalCustomers"=> $totalCustomer,
    "updateDate"    => date("j M Y")
];

echo json_encode($response);
$conn->close();

?>
