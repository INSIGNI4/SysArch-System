<?php
session_start();
header('Content-Type: application/json');

$email = $_SESSION['email'] ?? null;
if (!$email) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

include 'connect.php';
$lowStockThreshold = 10;

$query = "SELECT Product_ID FROM inventory WHERE Inventory <= $lowStockThreshold";
$result = $conn->query($query);

$readNotifs = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $readNotifs[] = (string)$row['Product_ID'];
    }
}

$conn->close();

// ✅ Match same folder and filename as other scripts
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);

$userFile = $dataDir . '/' . md5($email) . '_read.json';
file_put_contents($userFile, json_encode($readNotifs));

echo json_encode(['success' => true]);
?>
