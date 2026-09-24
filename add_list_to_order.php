<?php
session_start();
require_once 'connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

try {
    $supplierId = isset($_POST['Supplier_ID']) ? (int)$_POST['Supplier_ID'] : 0;
    $expectedReceiveDate = !empty($_POST['Expected_Receive_Date']) ? $_POST['Expected_Receive_Date'] : null;
    $forecastStartDate = !empty($_POST['Forecast_Start_Date']) ? $_POST['Forecast_Start_Date'] : null;
    $forecastEndDate = !empty($_POST['Forecast_End_Date']) ? $_POST['Forecast_End_Date'] : null;
    $notes = isset($_POST['Notes']) ? trim($_POST['Notes']) : null;

    if ($supplierId <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Please select a supplier.'
        ]);
        exit;
    }

    $createdBy = $_SESSION['email'] ?? 'Manual';

    $sql = "INSERT INTO list_to_order (
                Supplier_ID,
                Expected_Receive_Date,
                Forecast_Start_Date,
                Forecast_End_Date,
                Order_Status,
                Total_Amount,
                Created_By,
                Notes
            ) VALUES (?, ?, ?, ?, 'Pending', 0.00, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Failed to prepare query: ' . $conn->error);
    }

    $stmt->bind_param(
        'isssss',
        $supplierId,
        $expectedReceiveDate,
        $forecastStartDate,
        $forecastEndDate,
        $createdBy,
        $notes
    );

    if (!$stmt->execute()) {
        throw new Exception('Failed to save List to Order: ' . $stmt->error);
    }

    $newListToOrderId = $stmt->insert_id;
    $stmt->close();

    echo json_encode([
        'success' => true,
        'message' => 'List to Order added successfully.',
        'ListToOrder_ID' => $newListToOrderId
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}