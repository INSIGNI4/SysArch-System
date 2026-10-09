
<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../connect.php';

if (!isset($conn) || !$conn) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed.'
    ]);
    exit;
}

$sql = "
    SELECT Supplier_ID, CompanyName, ContactPerson
    FROM supplier
    ORDER BY CompanyName
";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch suppliers.'
    ]);
    exit;
}

$suppliers = [];

while ($row = $result->fetch_assoc()) {
    $suppliers[] = $row;
}

echo json_encode($suppliers);