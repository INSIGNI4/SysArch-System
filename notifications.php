<?php
session_start();
include 'connect.php';
header('Content-Type: application/json');

$email = $_SESSION['email'] ?? null;
if (!$email) {
    echo json_encode([]);
    exit;
}

$lowStockThreshold = 10;

// --- Ensure data directory exists ---
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);

// --- User read list file ---
$userFile = $dataDir . '/' . md5($email) . '_read.json';
$readList = file_exists($userFile)
    ? json_decode(file_get_contents($userFile), true)
    : [];

// --- Global inventory snapshot file (for detecting changes) ---
$snapshotFile = $dataDir . '/inventory_snapshot.json';
$lastSnapshot = file_exists($snapshotFile)
    ? json_decode(file_get_contents($snapshotFile), true)
    : [];

// --- Fetch all current inventory ---
$query = "
    SELECT i.Product_ID, p.ProductName, i.Inventory
    FROM inventory i
    INNER JOIN product p ON i.Product_ID = p.Product_ID
";
$result = $conn->query($query);

$notifications = [];
$currentSnapshot = [];
$changedProducts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pid = (string)$row['Product_ID'];
        $inv = (int)$row['Inventory'];
        $currentSnapshot[$pid] = $inv;

        // Detect movement since last snapshot
        if (isset($lastSnapshot[$pid]) && $lastSnapshot[$pid] != $inv) {
            $changedProducts[] = $pid;
        }

        // Build notification message with bold parts
        if ($inv <= $lowStockThreshold) {
            $type = $inv == 0 ? 'out' : 'low';
            $msg = $type === 'out'
                ? "⚠️ <b>OUT OF STOCK!</b> <b>Product ID</b> <span class='product-id'>{$pid}</span> ({$row['ProductName']}) "
                : "‼️ <b>LOW OF STOCK! ({$inv} left).</b> <b>Product ID</b> <span class='product-id'>{$pid}</span> ({$row['ProductName']}) ";

            // Show only if not marked as read
            if (!in_array($pid, $readList)) {
                $notifications[] = [
                    'id' => $pid,
                    'type' => $type,
                    'message' => $msg
                ];
            }
        }
    }
}
$conn->close();

// --- Auto-clean user read list if stock changed ---
if (!empty($changedProducts)) {
    $readList = array_values(array_diff($readList, $changedProducts));
}

// --- Save updated snapshot + user read list ---
file_put_contents($snapshotFile, json_encode($currentSnapshot));
file_put_contents($userFile, json_encode($readList));

// --- Send back current unread notifications ---
echo json_encode($notifications);
?>
