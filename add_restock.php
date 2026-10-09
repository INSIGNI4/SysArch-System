<?php

session_start();

include('connect.php');


// =========================================
// CHECK LOGIN
// =========================================

$email = $_SESSION['email'] ?? null;

if (!$email) {

    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to add an order.'
    ];

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// =========================================
// CHECK ITEMS
// =========================================

if (
    !isset($_POST['items']) ||
    !is_array($_POST['items']) ||
    empty($_POST['items'])
) {

    $_SESSION['response'] = [
        'success' => false,
        'message' => 'No order items were provided.'
    ];

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// =========================================
// DATABASE TRANSACTION
// =========================================

$conn->begin_transaction();


try {


    // =========================================
    // TRACK PRODUCTS IN THIS ORDER
    // =========================================

    $usedProducts = [];




    foreach ($_POST['items'] as $item) {

        // ---------------------------------
        // GET VALUES
        // ---------------------------------

        $productID =
            isset($item['Product_ID'])
            ? (int)$item['Product_ID']
            : 0;



        // =========================================
        // PREVENT DUPLICATE PRODUCT
        // =========================================

        if (in_array($productID, $usedProducts, true)) {

            throw new Exception(
                "The same product cannot be added more than once in the same order."
            );

        }

        $usedProducts[] = $productID;



        $supplierID =
            isset($item['Supplier_ID'])
            ? (int)$item['Supplier_ID']
            : 0;

        $quantity =
            isset($item['Quantity'])
            ? (int)$item['Quantity']
            : 0;


        $expectedReceiveDate =
            isset($item['Expected_Receive_Date'])
            ? trim($item['Expected_Receive_Date'])
            : '';            


        // ---------------------------------
        // VALIDATE PRODUCT
        // ---------------------------------

        if ($productID <= 0) {

            throw new Exception(
                'Please select a product for every order item.'
            );

        }


        // ---------------------------------
        // VALIDATE SUPPLIER
        // ---------------------------------

        if ($supplierID <= 0) {

            throw new Exception(
                'Please select a supplier for every order item.'
            );

        }


        // ---------------------------------
        // VALIDATE QUANTITY
        // ---------------------------------

        if ($quantity <= 0) {

            throw new Exception(
                'Order quantity must be greater than zero.'
            );

        }


                
        // ---------------------------------
        // VALIDATE EXPECTED RECEIVE DATE
        // ---------------------------------

        if (empty($expectedReceiveDate)) {
            throw new Exception(
                'Please select an expected receive date for every supplier.'
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
                'Invalid expected receive date.'
            );
        }        


        // ---------------------------------
        // GET PACK SIZE
        // ---------------------------------

        $packStmt = $conn->prepare("
            SELECT Pack_Size
            FROM product
            WHERE Product_ID = ?
        ");

        $packStmt->bind_param(
            "i",
            $productID
        );

        $packStmt->execute();

        $packResult =
            $packStmt->get_result();

        $product =
            $packResult->fetch_assoc();

        $packStmt->close();


        if (!$product) {

            throw new Exception(
                "Product ID $productID was not found."
            );

        }


        $packSize =
            (int)$product['Pack_Size'];


        // ---------------------------------
        // VALIDATE PACK SIZE
        // ---------------------------------

        if ($packSize <= 0) {

            throw new Exception(
                "Invalid pack size for Product ID $productID."
            );

        }


        if ($quantity % $packSize !== 0) {

            throw new Exception(
                "Quantity for Product ID $productID must be a multiple of the pack size ($packSize)."
            );

        }


        // ---------------------------------
        // INSERT RESTOCK
        // ---------------------------------

        $status = 'Requested';

        $stmt = $conn->prepare("
            INSERT INTO restock
            (
                Product_ID,
                Supplier_ID,
                Quantity,
                Status,
                Expected_Receive_Date
            )
            VALUES
            (?, ?, ?, ?, ?)
        ");


        $stmt->bind_param(
            "iiiss",
            $productID,
            $supplierID,
            $quantity,
            $status,
            $expectedReceiveDate
        );


        if (!$stmt->execute()) {

            throw new Exception(
                'Failed to add restock item: ' .
                $stmt->error
            );

        }


        $stmt->close();

    }


    // =========================================
    // EVERYTHING SUCCESSFUL
    // =========================================

    $conn->commit();


    $_SESSION['response'] = [
        'success' => true,
        'message' => 'Order/Restock items added successfully!'
    ];


} catch (Exception $e) {


    // =========================================
    // SOMETHING FAILED
    // =========================================

    $conn->rollback();


    $_SESSION['response'] = [
        'success' => false,
        'message' => $e->getMessage()
    ];

}


// =========================================
// RETURN TO PREVIOUS PAGE
// =========================================

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;

?>