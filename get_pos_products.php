<?php
header('Content-Type: application/json');
include('connect.php');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "12345QWERT");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Matches your exact schema: StorePrice, UnitsOrdered, UnitSold
    $query = "SELECT Product_ID, ProductName, StorePrice, 
                     (COALESCE(UnitsOrdered, 0) - COALESCE(UnitSold, 0)) AS AvailableStock 
              FROM product";

    $stmt = $pdo->query($query);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $products = [];
    foreach ($rows as $row) {
        $pid = $row['Product_ID'];
        $stock = (int)$row['AvailableStock'];

        $products[$pid] = [
            'name'  => $row['ProductName'],
            'price' => (float)($row['StorePrice'] ?? 0.00),
            'stock' => $stock < 0 ? 0 : $stock
        ];
    }

    echo json_encode([
        'status'   => 'success',
        'products' => $products
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
}
?>