

<?php
$host="localhost";
$user="root";
$pass="12345QWERT";

$db="login";
$conn=new mysqli($host,$user,$pass,$db);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "12345QWERT");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

return $conn;
?>

