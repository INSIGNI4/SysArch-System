

<?php
include('connect.php');

$pdo = new PDO("mysql:host=localhost;dbname=login", "root", "12345QWERT");
$stmt = $pdo->query("SELECT Transaction_ID FROM transactions");
$transactions = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo json_encode($transactions);
?>

