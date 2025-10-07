<?php
include('../connect.php');

header('Content-Type: application/json');

$query = "
    SELECT 
        p.ProductName, 
        SUBSTRING_INDEX(GROUP_CONCAT(r.ReasonForReturn ORDER BY r.ReturnedDate DESC), ',', 1) AS ReasonForReturn, 
        COUNT(*) AS TotalReturned
    FROM customersreturns r
    JOIN product p ON r.Product_ID = p.Product_ID
    WHERE YEAR(r.ReturnedDate) = YEAR(CURDATE()) 
      AND MONTH(r.ReturnedDate) = MONTH(CURDATE())
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
