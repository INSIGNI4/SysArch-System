<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

require_once __DIR__ . '/connect.php';


// ============================================================
// READ JSON FROM JAVASCRIPT
// ============================================================

$input = json_decode(
    file_get_contents('php://input'),
    true
);


if (!$input || !isset($input['items'])) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'No order items were received.'
    ]);

    exit;
}


$items = $input['items'];


if (!is_array($items) || count($items) === 0) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => 'There are no items to order.'
    ]);

    exit;
}


// ============================================================
// START DATABASE TRANSACTION
// ============================================================

mysqli_begin_transaction($conn);


try {

    // ========================================================
    // GROUP ITEMS BY SUPPLIER
    // ========================================================

    $supplierGroups = [];

    foreach ($items as $item) {

        $supplierId =
            isset($item['supplierId'])
                ? (int) $item['supplierId']
                : 0;


        $orderedQuantity =
            isset($item['orderedQuantity'])
                ? (int) $item['orderedQuantity']
                : 0;


        // Ignore invalid supplier
        if ($supplierId <= 0) {
            continue;
        }


        // Ignore zero/negative orders
        if ($orderedQuantity <= 0) {
            continue;
        }


        if (!isset($supplierGroups[$supplierId])) {

            $supplierGroups[$supplierId] = [];

        }


        $supplierGroups[$supplierId][] =
            $item;

    }


    if (count($supplierGroups) === 0) {

        throw new Exception(
            'No valid order items were found.'
        );

    }


    // ========================================================
    // PREPARE LIST_TO_ORDER INSERT
    // ========================================================

    $listSQL = "
        INSERT INTO list_to_order (
            Supplier_ID,
            Forecast_Start_Date,
            Forecast_End_Date,
            Order_Status,
            Created_By,
            Notes
        )
        VALUES (
            ?,
            ?,
            ?,
            'Confirmed',
            ?,
            ?
        )
    ";


    $listStmt =
        mysqli_prepare(
            $conn,
            $listSQL
        );


    if (!$listStmt) {

        throw new Exception(
            mysqli_error($conn)
        );

    }


    // ========================================================
    // PREPARE ITEM_TO_ORDER INSERT
    // ========================================================

    $itemSQL = "
        INSERT INTO item_to_order (
            ListToOrder_ID,
            Product_ID,
            Supplier_ID,
            Current_Stock,
            Predicted_Demand,
            Pack_Size,
            Recommended_Order_Quantity,
            Ordered_Quantity,
            Unit_Cost,
            Received_Quantity,
            Item_Status
        )
        VALUES (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            0,
            'Pending'
        )
    ";


    $itemStmt =
        mysqli_prepare(
            $conn,
            $itemSQL
        );


    if (!$itemStmt) {

        throw new Exception(
            mysqli_error($conn)
        );

    }


    // ========================================================
    // FORECAST DATES
    // ========================================================

    $forecastStart =
        date('Y-m-d');

    $forecastEnd =
        date(
            'Y-m-d',
            strtotime('+14 days')
        );


    // ========================================================
    // CREATED BY
    // ========================================================

    /*
     * We are temporarily using this value until we connect
     * this to your actual logged-in user/session.
     */

    $createdBy =
        'AI Recommendation';


    $notes =
        'Created from AI demand recommendation.';


    $createdListIds = [];


    // ========================================================
    // CREATE ONE LIST_TO_ORDER PER SUPPLIER
    // ========================================================

    foreach (
        $supplierGroups
        as $supplierId => $supplierItems
    ) {


        // ----------------------------------------------------
        // CREATE LIST_TO_ORDER
        // ----------------------------------------------------

        mysqli_stmt_bind_param(
            $listStmt,
            'issss',
            $supplierId,
            $forecastStart,
            $forecastEnd,
            $createdBy,
            $notes
        );


        if (!mysqli_stmt_execute($listStmt)) {

            throw new Exception(
                mysqli_stmt_error($listStmt)
            );

        }


        $listToOrderId =
            mysqli_insert_id($conn);


        $createdListIds[] =
            $listToOrderId;


        // ----------------------------------------------------
        // INSERT EACH ITEM
        // ----------------------------------------------------

        foreach (
            $supplierItems
            as $item
        ) {

            $productId =
                (int) ($item['productId'] ?? 0);

            $currentStock =
                (int) ($item['currentStock'] ?? 0);

            $predictedDemand =
                (int) round(
                    $item['predictedDemand'] ?? 0
                );

            $packSize =
                (int) ($item['packSize'] ?? 1);

            $recommendedQuantity =
                (int) (
                    $item['recommendedQuantity'] ?? 0
                );

            $orderedQuantity =
                (int) (
                    $item['orderedQuantity'] ?? 0
                );

            $unitCost =
                (float) (
                    $item['unitCost'] ?? 0
                );


            if ($productId <= 0) {

                throw new Exception(
                    'Invalid Product ID.'
                );

            }


            if ($orderedQuantity <= 0) {
                continue;
            }


            // ------------------------------------------------
            // INSERT ITEM
            //
            // Net_Demand and Line_Total are intentionally
            // NOT included because your database triggers
            // calculate them automatically.
            // ------------------------------------------------

            mysqli_stmt_bind_param(
                $itemStmt,
                // 'iiiiiiid',
                'iiiiiiiid',
                $listToOrderId,
                $productId,
                $supplierId,
                $currentStock,
                $predictedDemand,
                $packSize,
                $recommendedQuantity,
                $orderedQuantity,
                $unitCost
            );


            if (!mysqli_stmt_execute($itemStmt)) {

                throw new Exception(
                    mysqli_stmt_error($itemStmt)
                );

            }

        }

    }


    // ========================================================
    // EVERYTHING WORKED
    // ========================================================

    mysqli_commit($conn);


    echo json_encode([
        'success' => true,
        'message' => 'AI order successfully confirmed.',
        'listToOrderIds' => $createdListIds
    ]);


} catch (Exception $e) {


    // ========================================================
    // SOMETHING FAILED
    // ========================================================

    mysqli_rollback($conn);


    http_response_code(500);


    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);

}
?>