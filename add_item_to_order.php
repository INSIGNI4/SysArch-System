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
    $listToOrderId = (int)($_POST['ListToOrder_ID'] ?? 0);
    $productId = (int)($_POST['Product_ID'] ?? 0);
    $currentStock = (int)($_POST['Current_Stock'] ?? 0);
    $predictedDemand = (int)($_POST['Predicted_Demand'] ?? 0);
    $packSize = (int)($_POST['Pack_Size'] ?? 1);
    $recommendedQuantity = (int)($_POST['Recommended_Order_Quantity'] ?? 0);
    $orderedQuantity = (int)($_POST['Ordered_Quantity'] ?? 0);
    $unitCost = (float)($_POST['Unit_Cost'] ?? 0);
    $notes = trim($_POST['Notes'] ?? '');

    if ($listToOrderId <= 0) {
        throw new Exception('Please select a List to Order.');
    }

    if ($productId <= 0) {
        throw new Exception('Please select a product.');
    }

    if ($orderedQuantity <= 0) {
        throw new Exception('Ordered quantity must be greater than 0.');
    }

    if ($unitCost < 0) {
        throw new Exception('Unit cost cannot be negative.');
    }

    $supplierStmt = $conn->prepare("
        SELECT Supplier_ID
        FROM list_to_order
        WHERE ListToOrder_ID = ?
    ");

    if (!$supplierStmt) {
        throw new Exception('Failed to prepare List to Order query: ' . $conn->error);
    }

    $supplierStmt->bind_param('i', $listToOrderId);

    if (!$supplierStmt->execute()) {
        throw new Exception('Failed to find List to Order: ' . $supplierStmt->error);
    }

    $supplierResult = $supplierStmt->get_result();
    $list = $supplierResult->fetch_assoc();
    $supplierStmt->close();

    if (!$list) {
        throw new Exception('Selected List to Order was not found.');
    }

    $supplierId = (int)$list['Supplier_ID'];

    $sql = "INSERT INTO item_to_order (
                ListToOrder_ID,
                Product_ID,
                Supplier_ID,
                Current_Stock,
                Predicted_Demand,
                Pack_Size,
                Recommended_Order_Quantity,
                Ordered_Quantity,
                Unit_Cost,
                Notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception('Failed to prepare query: ' . $conn->error);
    }

    $stmt->bind_param(
        'iiiiiiiids',
        $listToOrderId,
        $productId,
        $supplierId,
        $currentStock,
        $predictedDemand,
        $packSize,
        $recommendedQuantity,
        $orderedQuantity,
        $unitCost,
        $notes
    );

    if (!$stmt->execute()) {
        throw new Exception('Failed to save Item to Order: ' . $stmt->error);
    }

    $newItemToOrderId = $stmt->insert_id;
    $stmt->close();

    echo json_encode([
        'success' => true,
        'message' => 'Item to Order added successfully.',
        'ItemToOrder_ID' => $newItemToOrderId
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}