<?php
include 'connect.php';
header('Content-Type: application/json');

if (isset($_GET['Product_ID'])) {
    $productId = $_GET['Product_ID'];

    // Get only batches that have stock > 0
    $stmt = $pdo->prepare("
        SELECT BatchNum, ExpirationDate, Quantity
        FROM expiration
        WHERE Product_ID = ? AND Quantity > 0
        ORDER BY ExpirationDate ASC
    ");
    $stmt->execute([$productId]);
    $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($batches);
} else {
    echo json_encode(['error' => 'Product_ID not received']);
}
