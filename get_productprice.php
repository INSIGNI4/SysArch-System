<?php
include('connect.php');

if (isset($_GET['Product_ID'])) {
    $product_id = intval($_GET['Product_ID']);

    $stmt = $conn->prepare("SELECT StorePrice FROM product WHERE Product_ID = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => "SQL error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($price);

    if ($stmt->fetch()) {
        echo json_encode(['success' => true, 'price' => $price]);
    } else {
        echo json_encode(['success' => false, 'message' => "Product price not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => "Missing Product_ID"]);
}

$conn->close();
