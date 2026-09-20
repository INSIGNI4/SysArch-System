<?php

$servername = "127.0.0.1";
$username = "root";
$password = "12345QWERT";
$database = "point_of_sale";

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");