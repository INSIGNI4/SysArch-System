<?php

header('Content-Type: application/json');

include('connect.php');

try {

    /* =========================================================
       1. READ JSON REQUEST
       ========================================================= */

    $rawInput = file_get_contents("php://input");

    $data = json_decode($rawInput, true);

    if (
        !is_array($data) ||
        !isset($data['items']) ||
        !is_array($data['items']) ||
        empty($data['items'])
    ) {
        throw new Exception(
            "No AI recommendation items were provided."
        );
    }


    /* =========================================================
       2. START TRANSACTION
       ========================================================= */

    $conn->begin_transaction();


    /* =========================================================
       3. PREVENT DUPLICATE PRODUCTS
       ========================================================= */

    $usedProducts = [];


    /* =========================================================
       4. PROCESS EACH AI RECOMMENDATION
       ========================================================= */

    foreach ($data['items'] as $item) {

        $productID = isset($item['productId'])
            ? (int)$item['productId']
            : 0;

        $supplierID = isset($item['supplierId'])
            ? (int)$item['supplierId']
            : 0;

        $quantity = isset($item['orderedQuantity'])
            ? (int)$item['orderedQuantity']
            : 0;

        $expectedReceiveDate = isset($item['expectedReceiveDate'])
            ? trim($item['expectedReceiveDate'])
            : '';            


        /* =====================================================
           PRODUCT VALIDATION
           ===================================================== */

        if ($productID <= 0) {
            throw new Exception(
                "Invalid Product ID."
            );
        }


        if ($supplierID <= 0) {
            throw new Exception(
                "Please select a supplier for every product."
            );
        }


        if ($quantity <= 0) {
            throw new Exception(
                "Order quantity must be greater than zero."
            );
        }



        if ($expectedReceiveDate === '') {
            throw new Exception(
                "Expected Receive Date is required."
            );
        }

        $dateCheck = DateTime::createFromFormat(
            'Y-m-d',
            $expectedReceiveDate
        );

        if (
            !$dateCheck ||
            $dateCheck->format('Y-m-d') !== $expectedReceiveDate
        ) {
            throw new Exception(
                "Invalid Expected Receive Date."
            );
        }        


        /* =====================================================
           DUPLICATE PRODUCT CHECK
           ===================================================== */

        if (in_array($productID, $usedProducts, true)) {

            throw new Exception(
                "The same product cannot be added more than once."
            );
        }

        $usedProducts[] = $productID;


        /* =====================================================
           GET PRODUCT + PACK SIZE
           ===================================================== */

        $productStmt = $conn->prepare("
            SELECT
                Product_ID,
                Pack_Size
            FROM product
            WHERE Product_ID = ?
            LIMIT 1
        ");

        $productStmt->bind_param(
            "i",
            $productID
        );

        $productStmt->execute();

        $productResult = $productStmt->get_result();

        $product = $productResult->fetch_assoc();

        $productStmt->close();


        if (!$product) {

            throw new Exception(
                "Product ID $productID was not found."
            );
        }


        $packSize = (int)$product['Pack_Size'];


        if ($packSize <= 0) {

            throw new Exception(
                "Invalid pack size for Product ID $productID."
            );
        }


        /* =====================================================
           PACK SIZE VALIDATION
           ===================================================== */

        if ($quantity < $packSize) {

            throw new Exception(
                "Order quantity for Product ID $productID " .
                "must be at least the pack size ($packSize)."
            );
        }


        if ($quantity % $packSize !== 0) {

            throw new Exception(
                "Order quantity for Product ID $productID " .
                "must be a multiple of the pack size ($packSize)."
            );
        }


        /* =====================================================
           VERIFY SUPPLIER EXISTS
           ===================================================== */

        $supplierStmt = $conn->prepare("
            SELECT Supplier_ID
            FROM supplier
            WHERE Supplier_ID = ?
            LIMIT 1
        ");

        $supplierStmt->bind_param(
            "i",
            $supplierID
        );

        $supplierStmt->execute();

        $supplierResult = $supplierStmt->get_result();

        $supplier = $supplierResult->fetch_assoc();

        $supplierStmt->close();


        if (!$supplier) {

            throw new Exception(
                "Supplier ID $supplierID was not found."
            );
        }


        /* =====================================================
           INSERT DIRECTLY INTO RESTOCK
           ===================================================== */

        $status = 'Requested';

        // $restockStmt = $conn->prepare("
        //     INSERT INTO restock
        //     (
        //         Product_ID,
        //         Supplier_ID,
        //         Quantity,
        //         Status
        //     )
        //     VALUES
        //     (?, ?, ?, ?)
        // ");



        $restockStmt = $conn->prepare("
            INSERT INTO restock
            (
                Product_ID,
                Supplier_ID,
                Quantity,
                Expected_Receive_Date,
                Status
            )
            VALUES
            (?, ?, ?, ?, ?)
        ");        

        // $restockStmt->bind_param(
        //     "iiis",
        //     $productID,
        //     $supplierID,
        //     $quantity,
        //     $status
        // );


        $restockStmt->bind_param(
            "iiiss",
            $productID,
            $supplierID,
            $quantity,
            $expectedReceiveDate,
            $status
        );



        if (!$restockStmt->execute()) {

            throw new Exception(
                "Failed to create restock for Product ID $productID: " .
                $restockStmt->error
            );
        }


        $restockStmt->close();
    }


    /* =========================================================
       5. COMMIT
       ========================================================= */

    $conn->commit();


    echo json_encode([
        'success' => true,
        'message' => 'AI recommendation order added to restock successfully.'
    ]);

    exit;


} catch (Exception $e) {

    /* =========================================================
       ROLLBACK IF ANYTHING FAILS
       ========================================================= */

    if ($conn->errno === 0) {
        // Nothing needed here.
    }

    try {
        $conn->rollback();
    } catch (Exception $rollbackError) {
        // Ignore rollback errors.
    }


    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

    exit;
}
?>