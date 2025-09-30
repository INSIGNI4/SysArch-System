<?php
include('../connect.php');

$query = "
    SELECT 
        COALESCE(c.CustomerName, 'Customer') AS CustomerName,
        COUNT(t.Transaction_ID) AS TotalOrders
    FROM transactions t
    LEFT JOIN customers c ON t.Customer_ID = c.Customer_ID
    WHERE YEARWEEK(t.Transaction_Date, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY CustomerName
    ORDER BY TotalOrders DESC
    LIMIT 3
";

$result = $conn->query($query);

$customers = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($customers);

$conn->close();
?>
