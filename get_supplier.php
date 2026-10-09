

<?php
// include('connect.php');





header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=login;charset=utf8mb4",
        "root",
        "12345QWERT",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $stmt = $pdo->query("
        SELECT
            Supplier_ID,
            CompanyName,
            CompanyName AS SupplierName,
            ContactPerson,
            Address,
            City,
            ProductCategory,
            PhoneNumber,
            Email
        FROM supplier
        ORDER BY CompanyName ASC
    ");

    echo json_encode($stmt->fetchAll());

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to retrieve supplier records.'
    ]);
}









// $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "12345QWERT");

// $stmt = $pdo->query("
//     SELECT
//         Supplier_ID,
//         SupplierFName,
//         SupplierLName,
//         CONCAT_WS(
//             ' ',
//             SupplierFName,
//             SupplierLName
//         ) AS SupplierName,
//         OfferedProductsType,
//         Location,
//         PhoneNumber,
//         Email
//     FROM supplier
// ");
// $supplier = $stmt->fetchAll(PDO::FETCH_ASSOC);
// echo json_encode($supplier);
?>

