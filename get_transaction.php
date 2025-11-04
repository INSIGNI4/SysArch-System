

<?php
include('connect.php');


$pdo = new PDO("mysql:host=localhost;dbname=login", "root", "12345QWERT");
$stmt = $pdo->query("SELECT Transaction_ID,Transaction_Date FROM transactions ORDER BY Transaction_ID DESC");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($transactions);
?>

