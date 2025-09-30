<?php

$host="localhost";
$user="root";
$pass="12345QWERT";

$db="login";
$conn=new mysqli($host,$user,$pass,$db);

if($conn->connect_error){
    echo "Failed to connect DB".$conn->connect_error;
}

return $conn;

?>




