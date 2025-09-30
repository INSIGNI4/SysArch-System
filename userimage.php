<?php

session_start();
$userId = $_SESSION['users[id]']; // Make sure this is set during login

$image = $_POST['image']; // Validate this before using!

$mysqli = require __DIR__ . "/connect.php";

$sql = "UPDATE users SET image = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("si", $image, $userId);
$stmt->execute();


?>
