

<?php
$host="localhost";
$user="root";
$pass="";

$db="system";
$conn=new mysqli($host,$user,$pass,$db);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=system", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

return $conn;
?>

