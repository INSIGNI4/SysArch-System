<?php
// Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION)) session_start();
include('connect.php');

$email = $_SESSION['email'] ?? null;
if (!$email) {
    echo "<tr><td colspan='4'>You must be logged in to view sales.</td></tr>";
    exit;
}

// Get logged-in user's ID
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'] ?? null;

if (!$user_id) {
    echo "<tr><td colspan='4'>User not found.</td></tr>";
    exit;
}

// Fetch recent sales for this user
$stmt = $conn->prepare("
    SELECT s.Order_ID, s.SalesDate, p.ProductName, s.Quantity 
    FROM sales s
    LEFT JOIN product p ON s.Product_ID = p.Product_ID
    WHERE s.user_id = ? 
    ORDER BY s.SalesDate DESC 
    LIMIT 10
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$sales_result = $stmt->get_result();

if ($sales_result->num_rows === 0) {
    echo "<tr><td colspan='4'>No recent sales</td></tr>";
} else {
    while ($sale = $sales_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($sale['Order_ID']) . "</td>";
        echo "<td>" . htmlspecialchars($sale['SalesDate']) . "</td>";
        echo "<td>" . htmlspecialchars($sale['ProductName']) . "</td>";
        echo "<td>" . htmlspecialchars($sale['Quantity']) . "</td>";
        echo "</tr>";
    }
}

$stmt->close();
$conn->close();
?>
