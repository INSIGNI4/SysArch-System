<?php
include('../connect.php');

header('Content-Type: application/json');

$query = "
    SELECT COUNT(*) AS TotalReturned,
           p.ProductName,
           r.ReasonForReturn
    FROM customersreturns r
    JOIN product p ON r.Product_ID = p.Product_ID
    WHERE MONTH(ReturnedDate) = MONTH(CURDATE())
    GROUP BY r.Product_ID
    ORDER BY TotalReturned DESC
    LIMIT 1
";

$result = $conn->query($query);
$data = $result->fetch_assoc();

echo json_encode([
    'TotalReturned' => $data['TotalReturned'] ?? 0,
    'MostReturned' => $data['ProductName'] ?? 'N/A',
    'Reason' => $data['ReasonForReturn'] ?? 'N/A'
]);

$conn->close();
?>
