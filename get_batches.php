<?php

include 'connect.php';
header('Content-Type: application/json');

if (isset($_GET['Product_ID'])) {
    $productId = $_GET['Product_ID'];

    // Assuming $conn is your mysqli connection variable from connect.php
    $stmt = $conn->prepare("
        SELECT BatchNum, ExpirationDate, Quantity
        FROM expiration
        WHERE Product_ID = ? AND Quantity > 0
        ORDER BY ExpirationDate ASC
    ");

    if ($stmt) {
        $stmt->bind_param("s", $productId); // Use "i" if Product_ID is an integer
        $stmt->execute();
        
        $result = $stmt->get_result();
        $batches = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode($batches);
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['error' => 'Product_ID not received']);
}


// include 'connect.php';
// header('Content-Type: application/json');

// if (isset($_GET['Product_ID'])) {
//     $productId = $_GET['Product_ID'];

//     // Get only batches that have stock > 0
//     $stmt = $pdo->prepare("
//         SELECT BatchNum, ExpirationDate, Quantity
//         FROM expiration
//         WHERE Product_ID = ? AND Quantity > 0
//         ORDER BY ExpirationDate ASC
//     ");
//     $stmt->execute([$productId]);
//     $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     echo json_encode($batches);
// } else {
//     echo json_encode(['error' => 'Product_ID not received']);
// }
?>