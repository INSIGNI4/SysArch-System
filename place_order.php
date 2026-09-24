<?php
header('Content-Type: application/json');

require_once __DIR__ . '/connect.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['listToOrderId'])) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'No List Order ID was received.'
    ]);

    exit;
}

$listToOrderId = (int)$input['listToOrderId'];

if ($listToOrderId <= 0) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'Invalid List Order ID.'
    ]);

    exit;
}

mysqli_begin_transaction($conn);

try {

    /*
     * =========================================================
     * 1. CHECK THE LIST ORDER
     * =========================================================
     */

    $checkSQL = "
        SELECT ListToOrder_ID, Order_Status
        FROM list_to_order
        WHERE ListToOrder_ID = ?
        LIMIT 1
    ";

    $checkStmt = mysqli_prepare($conn, $checkSQL);

    if (!$checkStmt) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $checkStmt,
        'i',
        $listToOrderId
    );

    mysqli_stmt_execute($checkStmt);

    $checkResult = mysqli_stmt_get_result($checkStmt);
    $listOrder = mysqli_fetch_assoc($checkResult);

    if (!$listOrder) {
        throw new Exception('List Order was not found.');
    }

    if (
        $listOrder['Order_Status'] !== 'Confirmed' &&
        $listOrder['Order_Status'] !== 'Pending'
    ) {
        throw new Exception(
            'Only Pending or Confirmed orders can be placed.'
        );
    }


    /*
     * =========================================================
     * 2. GET ALL ITEMS IN THIS LIST ORDER
     * =========================================================
     */

    $itemsSQL = "
        SELECT
            ItemToOrder_ID,
            Product_ID,
            Supplier_ID,
            Ordered_Quantity
        FROM item_to_order
        WHERE ListToOrder_ID = ?
          AND Ordered_Quantity > 0
    ";

    $itemsStmt = mysqli_prepare($conn, $itemsSQL);

    if (!$itemsStmt) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $itemsStmt,
        'i',
        $listToOrderId
    );

    mysqli_stmt_execute($itemsStmt);

    $itemsResult = mysqli_stmt_get_result($itemsStmt);

    if (mysqli_num_rows($itemsResult) === 0) {
        throw new Exception(
            'This List Order has no items with an ordered quantity.'
        );
    }


    /*
     * =========================================================
     * 3. CHANGE LIST ORDER STATUS TO ORDERED
     * =========================================================
     */

    $listSQL = "
        UPDATE list_to_order
        SET Order_Status = 'Ordered'
        WHERE ListToOrder_ID = ?
    ";

    $listStmt = mysqli_prepare($conn, $listSQL);

    if (!$listStmt) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $listStmt,
        'i',
        $listToOrderId
    );

    if (!mysqli_stmt_execute($listStmt)) {
        throw new Exception(mysqli_stmt_error($listStmt));
    }


    /*
     * =========================================================
     * 4. CHANGE PENDING ITEMS TO ORDERED
     * =========================================================
     */

    $itemUpdateSQL = "
        UPDATE item_to_order
        SET Item_Status = 'Ordered'
        WHERE ListToOrder_ID = ?
          AND Item_Status = 'Pending'
    ";

    $itemUpdateStmt = mysqli_prepare($conn, $itemUpdateSQL);

    if (!$itemUpdateStmt) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $itemUpdateStmt,
        'i',
        $listToOrderId
    );

    if (!mysqli_stmt_execute($itemUpdateStmt)) {
        throw new Exception(mysqli_stmt_error($itemUpdateStmt));
    }


    /*
     * =========================================================
     * 5. CREATE RESTOCK RECORDS
     * =========================================================
     */

    $restockSQL = "
        INSERT INTO restock (
            ItemToOrder_ID,
            Type,
            Quantity,
            Product_ID,
            Supplier_ID,
            Status,
            TotalReceived,
            withIssue
        )
        SELECT
            i.ItemToOrder_ID,
            'Re-Order',
            i.Ordered_Quantity,
            i.Product_ID,
            i.Supplier_ID,
            'Requested',
            0,
            0
        FROM item_to_order i
        WHERE i.ListToOrder_ID = ?
          AND i.Ordered_Quantity > 0
          AND NOT EXISTS (
              SELECT 1
              FROM restock r
              WHERE r.ItemToOrder_ID = i.ItemToOrder_ID
          )
    ";

    $restockStmt = mysqli_prepare($conn, $restockSQL);

    if (!$restockStmt) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $restockStmt,
        'i',
        $listToOrderId
    );

    if (!mysqli_stmt_execute($restockStmt)) {
        throw new Exception(mysqli_stmt_error($restockStmt));
    }


    /*
     * =========================================================
     * 6. FINISH TRANSACTION
     * =========================================================
     */

    mysqli_commit($conn);

    echo json_encode([
        'success' => true,
        'message' => 'Order has been placed successfully.',
        'listToOrderId' => $listToOrderId
    ]);

} catch (Exception $e) {

    mysqli_rollback($conn);

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>