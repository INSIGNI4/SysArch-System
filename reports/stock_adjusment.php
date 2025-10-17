<?php
include('connect.php');
date_default_timezone_set('Asia/Manila');

$today = date('Y-m-d');
$newProductsQuery = "
    SELECT 
        r.Orestock_ID,
        r.Product_ID,
        p.ProductName,
        p.Type,
        r.Quantity,
        p.StorePrice,
        r.ExpirationDate,
        r.Date_Received
    FROM restock r
    INNER JOIN product p ON r.Product_ID = p.Product_ID
    WHERE r.Status = 'Received' AND DATE(r.Date_Received) = '$today'
";
$newProducts = $conn->query($newProductsQuery);

$salesQuery = "
    SELECT 
        s.Transaction_ID,
        s.Product_ID,
        p.ProductName,
        s.Quantity,
        s.TotalPrice,
        s.SalesDate
    FROM sales s
    INNER JOIN product p ON s.Product_ID = p.Product_ID
    WHERE DATE(s.SalesDate) = '$today'
";
$sales = $conn->query($salesQuery);

$returnsQuery = "
    SELECT 
        cr.CReturn_ID,
        cr.ReferenceNo,
        p.ProductName,
        p.Type,
        cr.Quantity,
        cr.ReasonForReturn,
        cr.ReturnedDate
    FROM customersreturns cr
    INNER JOIN product p ON cr.Product_ID = p.Product_ID
    WHERE DATE(cr.ReturnedDate) = '$today'
";
$returns = $conn->query($returnsQuery);

$pendingRestockQuery = "
    SELECT 
        r.Orestock_ID,
        r.Product_ID,
        p.ProductName,
        r.Quantity,
        r.Status,
        r.Supplier_ID,
        r.OrderDate
    FROM restock r
    INNER JOIN product p ON r.Product_ID = p.Product_ID
    WHERE r.Status != 'Received'
";
$pendingRestocks = $conn->query($pendingRestockQuery);

// --- PULLED OUT ITEMS ---
$pulledOutQuery = "
    SELECT Pulled_ID, Product_ID, Supplier_ID, Quantity, Reason, PulledDate, Account_ID
    FROM pulledout
";
$pulledoutitems = $conn->query($pulledOutQuery);
?>