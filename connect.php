
<?php

    $servername = "localhost";
    $username   = "root";
    $password   = "12345QWERT";

    // 1. Connecting to the first database (login)
    $conn = new mysqli($servername, $username, $password, "login");

    if ($conn->connect_error) {
        die("Database 1 connection failed: " . $conn->connect_error);
    }

    // Make the first connection global
    $GLOBALS['conn'] = $conn;


    // 2. Connecting to the second database (point_of_sale)
    $conn_pos = new mysqli($servername, $username, $password, "point_of_sale");

    if ($conn_pos->connect_error) {
        die("Database 2 connection failed: " . $conn_pos->connect_error);
    }

    // Make the second connection global
    $GLOBALS['conn_pos'] = $conn_pos;


    
    
    
    // return $conn;
    
    
    // $host="localhost";
    // $user="root";
    // $pass="12345QWERT";
    
    // $db="login";
    // $conn=new mysqli($host,$user,$pass,$db);
    
    //     try {
    //         $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "12345QWERT");
    //         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     } catch (PDOException $e) {
    //         die("Database connection failed: " . $e->getMessage());
    //     }
    //     $GLOBALS['conn'] = $conn;
    
        



   
    // $servername = "localhost";
    // $username   = "root";
    // $password   = "12345QWERT";

    // // 1. Connecting to the first database (login) using PDO
    // try {
    //     $conn = new PDO("mysql:host=$servername;dbname=login", $username, $password);
    //     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // } catch (PDOException $e) {
    //     die("Database 1 connection failed: " . $e->getMessage());
    // }

    // // Make the first connection global
    // $GLOBALS['conn'] = $conn;


    // // 2. Connecting to the second database (point_of_sale) using PDO
    // try {
    //     $conn_pos = new PDO("mysql:host=$servername;dbname=point_of_sale", $username, $password);
    //     $conn_pos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // } catch (PDOException $e) {
    //     die("Database 2 connection failed: " . $e->getMessage());
    // }

    // // Make the second connection global
    // $GLOBALS['conn_pos'] = $conn_pos;



?>
