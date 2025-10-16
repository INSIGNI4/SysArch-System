<?php
// ==========================
// get_sales_forecast.php
// ==========================

// Database connection settings
$host="localhost";
$user="root";
$pass="12345QWERT";

$db="login"; // Change this to your actual database name

header('Content-Type: application/json');

try {
    // Connect to MySQL
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Query your new view
    // $sql = "SELECT SalesDate, DailyTotalQuantity, MovingAverage3 FROM v_store_sales_forecast ORDER BY SalesDate";
    $sql = "
    SELECT SalesDate, DailyTotalQuantity, MovingAverage3
    FROM v_store_sales_forecast
    WHERE SalesDate >= DATE_SUB(CURDATE(), INTERVAL 5 DAY)
    ORDER BY SalesDate
    ";
    $result = $conn->query($sql);

    $data = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'SalesDate'         => $row['SalesDate'],
                'DailyTotalQuantity'=> (float)$row['DailyTotalQuantity'],
                'MovingAverage3'    => (float)$row['MovingAverage3']
            ];
        }
    }

    echo json_encode($data);
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>