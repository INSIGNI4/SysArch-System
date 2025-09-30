<?php
include('connect.php');

$table = isset($_GET['table']) ? $_GET['table'] : 'users';

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];


?>