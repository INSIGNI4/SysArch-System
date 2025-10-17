<?php

$email = $_SESSION['email'] ?? null;
if (!$email) {
    echo "<tr><td colspan='4'>You must be logged in to view sales.</td></tr>";
    exit;
}

// Fetch logged-in user's ID
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$id = $user['id'] ?? null;

if (!$id) {
    echo "<tr><td colspan='4'>User not found.</td></tr>";
    exit;
}


$stmt = $conn->prepare("SELECT Order_ID, SalesDate, Product_ID, Quantity FROM sales WHERE user_id = ? ORDER BY SalesDate DESC LIMIT 10");
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
        echo "<td>" . htmlspecialchars($sale['Product_ID']) . "</td>";
        echo "<td>" . htmlspecialchars($sale['Quantity']) . "</td>";
        echo "</tr>";
    }
}

$stmt->close();
$conn->close();


?>