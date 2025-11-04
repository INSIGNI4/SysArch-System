

<?php
include('connect.php');

$pdo = new PDO("mysql:host=localhost;dbname=login", "root", "12345QWERT");
$stmt = $pdo->query("SELECT Supplier_ID,SupplierName,Location FROM supplier");
$supplier = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($supplier);
?>

