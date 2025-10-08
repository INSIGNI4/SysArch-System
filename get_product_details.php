


<?php
include 'connect.php';


header('Content-Type: application/json'); // Ensure proper response

if (isset($_GET['Product_ID'])) {
    $productId = $_GET['Product_ID'];

    $stmt = $pdo->prepare("SELECT StorePrice,ProductName, Barcode, UnitsOrdered, UnitSold FROM product WHERE Product_ID = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo json_encode([
            'store_price' => $product['StorePrice'],
            'product_name' => $product['ProductName'],
            'units_ordered' => $product['UnitsOrdered'],
            'units_sold' => $product['UnitSold'],
            'barcode' => $product['Barcode']]);

    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    echo json_encode(['error' => 'Product_ID not received']);
}
