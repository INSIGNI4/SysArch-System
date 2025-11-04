

<?php
include('connect.php');


$pdo = new PDO("mysql:host=localhost;dbname=login", "root", "12345QWERT");
$stmt = $pdo->query("SELECT Product_ID,ProductName FROM product");
// $stmt = $pdo->query("SELECT Product_ID, ProductName FROM product ORDER BY ProductName ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
?>

