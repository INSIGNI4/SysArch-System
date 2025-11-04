

<?php
include('connect.php');


$pdo = new PDO("mysql:host=localhost;dbname=login", "root", "12345QWERT");
$stmt = $pdo->query("SELECT Customer_ID,CustomerName,Location FROM customers");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($customers);
?>

