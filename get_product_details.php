


<?php


include 'connect.php';

header('Content-Type: application/json'); // Ensure proper response

if (isset($_GET['Product_ID'])) {
    $productId = $_GET['Product_ID'];

    // Assuming $conn is your mysqli connection variable from connect.php
    $stmt = $conn->prepare("SELECT StorePrice, ProductName, Barcode, UnitsOrdered, UnitSold FROM product WHERE Product_ID = ?");

    if ($stmt) {
        $stmt->bind_param("s", $productId); // Use "i" if Product_ID is an integer
        $stmt->execute();

        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            echo json_encode([
                'store_price'  => $product['StorePrice'],
                'product_name' => $product['ProductName'],
                'units_ordered'=> $product['UnitsOrdered'],
                'units_sold'   => $product['UnitSold'],
                'barcode'      => $product['Barcode']
            ]);
        } else {
            echo json_encode(['error' => 'Product not found']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare statement']);
    }
} else {
    echo json_encode(['error' => 'Product_ID not received']);
}
// include 'connect.php';


// header('Content-Type: application/json'); // Ensure proper response

// if (isset($_GET['Product_ID'])) {
//     $productId = $_GET['Product_ID'];

//     $stmt = $pdo->prepare("SELECT StorePrice,ProductName, Barcode, UnitsOrdered, UnitSold FROM product WHERE Product_ID = ?");
//     $stmt->execute([$productId]);
//     $product = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($product) {
//         echo json_encode([
//             'store_price' => $product['StorePrice'],
//             'product_name' => $product['ProductName'],
//             'units_ordered' => $product['UnitsOrdered'],
//             'units_sold' => $product['UnitSold'],
//             'barcode' => $product['Barcode']]);

//     } else {
//         echo json_encode(['error' => 'Product not found']);
//     }
// } else {
//     echo json_encode(['error' => 'Product_ID not received']);
// }
?>