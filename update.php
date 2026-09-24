<?php
session_start();
include('table_colums.php');
include('connect.php');

// check table name
if (!isset($_POST['table']) || empty($_POST['table'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Error: No Table'
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

$table_name = $_POST['table'];

// check if mapping of column exist
if (!isset($table_colums_mapping[$table_name])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "Error: No column in table '$table_name'."
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

$columns = $table_colums_mapping[$table_name];
$primaryKey = $columns[0];

// check id if provided
if (!isset($_POST['id']) || empty($_POST['id'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "Error: Missing ID"
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

$id = $_POST['id'];

// collect data
$update_arr = [];
foreach ($columns as $col) {
    if ($col !== $primaryKey) {
        if ($col === "ExpirationDate") {
            if (isset($_POST[$col]) && $col !== $primaryKey) {
                $update_arr[$col] = $_POST[$col];
            } else {
                $update_arr[$col] = null;
            }
        } else {
            if (isset($_POST[$col])) {
                $update_arr[$col] = $_POST[$col];
            }
        }
    }
}

if (in_array("Image", $columns) && isset($_FILES['Image']) && !empty($_FILES['Image']['name'])) {
    $stmtOld = $conn->prepare("SELECT `Image` FROM `$table_name` WHERE `$primaryKey` = ?");
    $paramType = ctype_digit((string)$id) ? "i" : "s";
    $stmtOld->bind_param($paramType, $id);
    $stmtOld->execute();
    $stmtOld->bind_result($oldImage);
    $stmtOld->fetch();
    $stmtOld->close();



    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = uniqid() . "_" . basename($_FILES['Image']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['Image']['tmp_name'], $targetFile)) {
        $update_arr['Image'] = $fileName;

        if (!empty($oldImage)) {
            $oldFilePath = $uploadDir . $oldImage;
            if (file_exists($oldFilePath)) {
                if (unlink($oldFilePath)) {
                $_SESSION['response'] = [
                    'success' => true,
                    'message' => "Deleted old image: $oldFilePath"
                ];
            } else {
                $_SESSION['response'] = [
                    'success' => false,
                    'message' => "Failed to delete: $oldFilePath"
                ];
            }
        } else {
            $_SESSION['response'] = [
                'success' => false,
                'message' => "Old image not found: $oldFilePath"
            ];
        }
        }
        

        // if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
        //     if (!empty($oldImage)) {
        //         $oldFilePath = $uploadDir . $oldImage;
        //         if(unlink($oldFilePath)) {
        //             error_log("FILE is deleted: $oldFilePath");
        //         } else {
        //             error_log("Failed to delete: $oldFilePath");
        //         }
        //     } else {
        //         error_log("File not found: $oldFilePath");
        //     }
        // }
    } else {
        $_SESSION['response'] = [
            'success' => false,
            'message' => "Failed to upload image"
        ];
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// FOR PROOF OF TRANSACTIONS

if (in_array("ProofOfTransaction", $columns) && isset($_FILES['ProofOfTransaction']) && !empty($_FILES['ProofOfTransaction']['name'])) {
    $stmtOld = $conn->prepare("SELECT `ProofOfTransaction` FROM `$table_name` WHERE `$primaryKey` = ?");
    $paramType = ctype_digit((string)$id) ? "i" : "s";
    $stmtOld->bind_param($paramType, $id);
    $stmtOld->execute();
    $stmtOld->bind_result($oldImage);
    $stmtOld->fetch();
    $stmtOld->close();



    $uploadDir = "proofs/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = uniqid() . "_" . basename($_FILES['ProofOfTransaction']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['ProofOfTransaction']['tmp_name'], $targetFile)) {
        $update_arr['ProofOfTransaction'] = $fileName;

        if (!empty($oldImage)) {
            $oldFilePath = $uploadDir . $oldImage;
            if (file_exists($oldFilePath)) {
                if (unlink($oldFilePath)) {
                $_SESSION['response'] = [
                    'success' => true,
                    'message' => "Deleted old image: $oldFilePath"
                ];
            } else {
                $_SESSION['response'] = [
                    'success' => false,
                    'message' => "Failed to delete: $oldFilePath"
                ];
            }
        } else {
            $_SESSION['response'] = [
                'success' => false,
                'message' => "Old image not found: $oldFilePath"
            ];
        }
        }
        

        // if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
        //     if (!empty($oldImage)) {
        //         $oldFilePath = $uploadDir . $oldImage;
        //         if(unlink($oldFilePath)) {
        //             error_log("FILE is deleted: $oldFilePath");
        //         } else {
        //             error_log("Failed to delete: $oldFilePath");
        //         }
        //     } else {
        //         error_log("File not found: $oldFilePath");
        //     }
        // }
    } else {
        $_SESSION['response'] = [
            'success' => false,
            'message' => "Failed to upload image"
        ];
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// PROOF OF TRANSACTIONS ENDS HERE!!!


/* =========================================================
 * DETECT PARTIAL DELIVERY BEFORE UPDATE
 * ========================================================= */

$isPartialRestock = false;
$restockOrderedQuantity = 0;
$restockReceivedQuantity = 0;
$restockIssueQuantity = 0;

if ($table_name === 'restock') {

    $checkRestockStmt = $conn->prepare("
        SELECT Quantity
        FROM restock
        WHERE Orestock_ID = ?
        LIMIT 1
    ");

    $checkRestockStmt->bind_param("i", $id);
    $checkRestockStmt->execute();

    $checkRestockResult =
        $checkRestockStmt->get_result();

    if ($checkRestockRow = $checkRestockResult->fetch_assoc()) {

        $restockOrderedQuantity =
            (int) $checkRestockRow['Quantity'];

    }

    $checkRestockStmt->close();


    $restockReceivedQuantity =
        (int) ($_POST['TotalReceived'] ?? 0);

    $restockIssueQuantity =
        (int) ($_POST['withIssue'] ?? 0);


    /*
     * Total quantity accounted for in this delivery.
     */
    $totalThisDelivery =
        $restockReceivedQuantity
        + $restockIssueQuantity;


    /*
     * If manager selected Received but the quantity
     * is still less than the ordered quantity,
     * automatically change it to Partially-Received.
     */
    if (
        ($_POST['Status'] ?? '') === 'Received'
        &&
        $totalThisDelivery < $restockOrderedQuantity
    ) {

        $isPartialRestock = true;

        $update_arr['Status'] =
            'Partially-Received';
    }


    /*
     * Manager may also directly select
     * Partially-Received.
     */
    elseif (
        ($_POST['Status'] ?? '') === 'Partially-Received'
    ) {

        $isPartialRestock = true;
    }

}











if (empty($update_arr)) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'No data to update'
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

$set_clause = implode(", ", array_map(fn($col) => "`$col` = ?", array_keys($update_arr)));
$sql = "UPDATE `$table_name` SET $set_clause WHERE `$primaryKey` = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'SQL Prepare failed: ' . $conn->error
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Bind parameters dynamically
// $paramType = ctype_digit((string)$id) ? "i" : "s";
// $types = str_repeat("s", count($update_arr)) . $paramType;
// $stmt->bind_param($types, ...array_values($update_arr), $id);


// Bind parameters dynamically
$paramType = ctype_digit((string)$id) ? "i" : "s";
$types = str_repeat("s", count($update_arr)) . $paramType;

$params = array_merge(array_values($update_arr), [$id]);
// $stmt->bind_param($types, ...array_merge(array_values($update_arr), [$id]));

foreach ($params as $key => $val) {
    if ($val === null) {
        $params[$key] = null;
    }
}

$stmt->bind_param($types, ...$params);

/*
 * =========================================================
 * REMEMBER OLD RESTOCK STATUS
 * =========================================================
 */

$oldRestockStatus = null;
$oldItemToOrderId = null;

if ($table_name === 'restock') {

    $oldRestockStmt = $conn->prepare("
        SELECT
            Status,
            ItemToOrder_ID
        FROM restock
        WHERE Orestock_ID = ?
        LIMIT 1
    ");

    $oldRestockStmt->bind_param("i", $id);
    $oldRestockStmt->execute();

    $oldRestockResult = $oldRestockStmt->get_result();

    if ($oldRestockRow = $oldRestockResult->fetch_assoc()) {

        $oldRestockStatus = $oldRestockRow['Status'];
        $oldItemToOrderId = $oldRestockRow['ItemToOrder_ID'];

    }

    $oldRestockStmt->close();
}



// Execute and set response
// if ($stmt->execute()) {
//     $_SESSION['response'] = [
//         'success' => true,
//         'message' => ucfirst($table_name) . " updated successfully!"
//     ];
// } else {
//     $_SESSION['response'] = [
//         'success' => false,
//         'message' => 'Update failed: ' . $stmt->error
//     ];
// }



// if ($stmt->execute()) {

//     /*
//      * =========================================================
//      * CREATE CHILD RESTOCK FOR PARTIAL DELIVERY
//      * =========================================================
//      */

//     if (
//         $table_name === 'restock'
//         && ($oldRestockStatus !== 'Received')
//         && (($_POST['Status'] ?? '') === 'Received')
//         && $oldItemToOrderId !== null
//     ) {

//         $itemToOrderId = (int)$oldItemToOrderId;


//         /*
//          * Get the original ordered quantity
//          * and current received quantity.
//          */
//         $remainingStmt = $conn->prepare("
//             SELECT
//                 ItemToOrder_ID,
//                 Ordered_Quantity,
//                 Received_Quantity,
//                 Product_ID
//             FROM item_to_order
//             WHERE ItemToOrder_ID = ?
//             LIMIT 1
//         ");

//         $remainingStmt->bind_param(
//             "i",
//             $itemToOrderId
//         );

//         $remainingStmt->execute();

//         $remainingResult = $remainingStmt->get_result();


//         if ($remainingRow = $remainingResult->fetch_assoc()) {

//             $orderedQuantity =
//                 (int)$remainingRow['Ordered_Quantity'];

//             $receivedQuantity =
//                 (int)$remainingRow['Received_Quantity'];

//             $productId =
//                 (int)$remainingRow['Product_ID'];


//             /*
//              * Get all quantities marked "With Issue"
//              * for completed deliveries of this item.
//              */
//             $issueStmt = $conn->prepare("
//                 SELECT
//                     COALESCE(SUM(withIssue), 0) AS TotalIssues
//                 FROM restock
//                 WHERE ItemToOrder_ID = ?
//                   AND Status = 'Received'
//             ");

//             $issueStmt->bind_param(
//                 "i",
//                 $itemToOrderId
//             );

//             $issueStmt->execute();

//             $issueResult = $issueStmt->get_result();

//             $issueRow = $issueResult->fetch_assoc();

//             $totalIssues =
//                 (int)($issueRow['TotalIssues'] ?? 0);

//             $issueStmt->close();


//             /*
//              * Calculate everything accounted for.
//              *
//              * Good quantity
//              * +
//              * Quantity with issue
//              */
//             $totalAccounted =
//                 $receivedQuantity + $totalIssues;


//             /*
//              * Calculate remaining quantity.
//              */
//             $remainingQuantity =
//                 max(
//                     $orderedQuantity - $totalAccounted,
//                     0
//                 );


//             /*
//              * Only create a child if something
//              * is still missing.
//              */
//             if ($remainingQuantity > 0) {


//                 /*
//                  * Get supplier and type from
//                  * the completed restock row.
//                  */
//                 $restockInfoStmt = $conn->prepare("
//                     SELECT
//                         Supplier_ID,
//                         Type
//                     FROM restock
//                     WHERE Orestock_ID = ?
//                     LIMIT 1
//                 ");

//                 $restockInfoStmt->bind_param(
//                     "i",
//                     $id
//                 );

//                 $restockInfoStmt->execute();

//                 $restockInfoResult =
//                     $restockInfoStmt->get_result();

//                 $restockInfo =
//                     $restockInfoResult->fetch_assoc();

//                 $restockInfoStmt->close();


//                 $supplierId =
//                     (int)($restockInfo['Supplier_ID'] ?? 0);

//                 $type =
//                     $restockInfo['Type'] ?? 'Re-Order';


//                 /*
//                  * Prevent duplicate open child rows.
//                  */
//                 $checkChildStmt = $conn->prepare("
//                     SELECT Orestock_ID
//                     FROM restock
//                     WHERE ItemToOrder_ID = ?
//                       AND Status IN (
//                           'Requested',
//                           'Out for Delivery'
//                       )
//                     LIMIT 1
//                 ");

//                 $checkChildStmt->bind_param(
//                     "i",
//                     $itemToOrderId
//                 );

//                 $checkChildStmt->execute();

//                 $checkChildResult =
//                     $checkChildStmt->get_result();


//                 if ($checkChildResult->num_rows === 0) {

//                     /*
//                      * Create the child restock.
//                      */
//                     $childStmt = $conn->prepare("
//                         INSERT INTO restock (
//                             ItemToOrder_ID,
//                             Type,
//                             Quantity,
//                             Product_ID,
//                             Supplier_ID,
//                             Status,
//                             TotalReceived,
//                             withIssue
//                         )
//                         VALUES (
//                             ?,
//                             ?,
//                             ?,
//                             ?,
//                             ?,
//                             'Requested',
//                             0,
//                             0
//                         )
//                     ");

//                     $childStmt->bind_param(
//                         "isiii",
//                         $itemToOrderId,
//                         $type,
//                         $remainingQuantity,
//                         $productId,
//                         $supplierId
//                     );

//                     $childStmt->execute();

//                     $childStmt->close();
//                 }

//                 $checkChildStmt->close();
//             }
//         }

//         $remainingStmt->close();
//     }


//     /*
//      * =========================================================
//      * NORMAL SUCCESS RESPONSE
//      * =========================================================
//      */

//     $_SESSION['response'] = [
//         'success' => true,
//         'message' => ucfirst($table_name) . " updated successfully!"
//     ];

// } else {

//     $_SESSION['response'] = [
//         'success' => false,
//         'message' => 'Update failed: ' . $stmt->error
//     ];
// }

if ($stmt->execute()) {

    /* =========================================================
     * CREATE BACKORDER-FULFILLMENT FOR PARTIAL DELIVERY
     * ========================================================= */

    if (
        $table_name === 'restock'
        && $isPartialRestock
        && $oldItemToOrderId !== null
    ) {

        $itemToOrderId =
            (int) $oldItemToOrderId;


        /*
         * Get the original order information.
         */
        $remainingStmt = $conn->prepare("
            SELECT
                Ordered_Quantity,
                Received_Quantity,
                Product_ID
            FROM item_to_order
            WHERE ItemToOrder_ID = ?
            LIMIT 1
        ");

        $remainingStmt->bind_param(
            "i",
            $itemToOrderId
        );

        $remainingStmt->execute();

        $remainingResult =
            $remainingStmt->get_result();


        if ($remainingRow =
            $remainingResult->fetch_assoc()) {

            $orderedQuantity =
                (int)
                $remainingRow['Ordered_Quantity'];

            $receivedQuantity =
                (int)
                $remainingRow['Received_Quantity'];

            $productId =
                (int)
                $remainingRow['Product_ID'];


            /*
             * Calculate all quantities already accounted for.
             */
            $issueStmt = $conn->prepare("
                SELECT
                    COALESCE(
                        SUM(withIssue),
                        0
                    ) AS TotalIssues
                FROM restock
                WHERE ItemToOrder_ID = ?
                  AND Status IN (
                      'Partially-Received',
                      'Received'
                  )
            ");

            $issueStmt->bind_param(
                "i",
                $itemToOrderId
            );

            $issueStmt->execute();

            $issueResult =
                $issueStmt->get_result();

            $issueRow =
                $issueResult->fetch_assoc();

            $totalIssues =
                (int)
                ($issueRow['TotalIssues'] ?? 0);

            $issueStmt->close();


            /*
             * Good quantity + issue quantity.
             */
            $totalAccounted =
                $receivedQuantity
                + $totalIssues;


            /*
             * Remaining quantity owed by supplier.
             */
            $remainingQuantity =
                max(
                    $orderedQuantity
                    - $totalAccounted,
                    0
                );


            /*
             * Only create backorder when
             * something is still missing.
             */
            if ($remainingQuantity > 0) {

                /*
                 * Get supplier/type from original restock.
                 */
                $restockInfoStmt =
                    $conn->prepare("
                        SELECT
                            Supplier_ID,
                            Type
                        FROM restock
                        WHERE Orestock_ID = ?
                        LIMIT 1
                    ");

                $restockInfoStmt->bind_param(
                    "i",
                    $id
                );

                $restockInfoStmt->execute();

                $restockInfoResult =
                    $restockInfoStmt->get_result();

                $restockInfo =
                    $restockInfoResult->fetch_assoc();

                $restockInfoStmt->close();


                $supplierId =
                    (int)
                    ($restockInfo['Supplier_ID'] ?? 0);

                $type =
                    $restockInfo['Type']
                    ?? 'Re-Order';


                /*
                 * Check if a backorder row already exists.
                 */
                $checkChildStmt =
                    $conn->prepare("
                        SELECT
                            Orestock_ID
                        FROM restock
                        WHERE
                            ItemToOrder_ID = ?

                            AND Status =
                                'Backorder-Fulfillment'

                        LIMIT 1
                    ");

                $checkChildStmt->bind_param(
                    "i",
                    $itemToOrderId
                );

                $checkChildStmt->execute();

                $checkChildResult =
                    $checkChildStmt->get_result();


                if ($checkChildResult->num_rows === 0) {

                    /*
                     * Create the remaining
                     * supplier obligation.
                     */
                    $childStmt =
                        $conn->prepare("
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
                            VALUES (
                                ?,
                                ?,
                                ?,
                                ?,
                                ?,
                                'Backorder-Fulfillment',
                                0,
                                0
                            )
                        ");

                    $childStmt->bind_param(
                        "isiii",
                        $itemToOrderId,
                        $type,
                        $remainingQuantity,
                        $productId,
                        $supplierId
                    );

                    $childStmt->execute();

                    $childStmt->close();

                }

                $checkChildStmt->close();

            }

        }

        $remainingStmt->close();

    }


    $_SESSION['response'] = [
        'success' => true,
        'message' =>
            ucfirst($table_name)
            . " updated successfully!"
    ];

} else {

    $_SESSION['response'] = [
        'success' => false,
        'message' =>
            'Update failed: '
            . $stmt->error
    ];
}



$stmt->close();
$conn->close();

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
