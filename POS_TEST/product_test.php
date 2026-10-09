
<?php

// Use Philippine local time (UTC+8)
date_default_timezone_set('Asia/Manila');

include('../connect.php');

// $action = $_GET['action'];

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'checkout') {
    saveProducts();
}


function getProducts(){

    $conn = $GLOBALS['conn'];

    $stmt = $conn->prepare("SELECT * FROM product");

    $stmt->execute();

    // Fetch result set using MySQLi
    $result = $stmt->get_result();

    $rows = $result->fetch_all(MYSQLI_ASSOC);

    // Fetch all rows using PDO syntax
    // $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows;
}




function saveProducts(){

    // Set headers for JSON response
    header('Content-Type: application/json');

    $conn_pos = $GLOBALS['conn_pos'];
    $conn_inventory = $GLOBALS['conn']; // Connection to inventory DB

    // Read raw JSON input, fallback to $_POST
    $input = json_decode(file_get_contents('php://input'), true);
    $data = $input['data'] ?? $_POST['data'] ?? null;

    if (!$data) {
        http_response_code(400);

        echo json_encode([
            "status" => "error",
            "message" => "Missing cart data."
        ]);

        return;
    }

    // try {

    //     $date_created = date('Y-m-d H:i:s');
        

    try {

    // Start checkout transaction
        $conn_pos->begin_transaction();


        $date_created = date('Y-m-d H:i:s');
        $date_updated = date('Y-m-d H:i:s');

        // TEMPORARY DEBUG: write timestamps to PHP's error log
        error_log("POS TEST DEBUG - File: " . __FILE__);
        error_log("POS TEST DEBUG - PHP timezone: " . date_default_timezone_get());
        error_log("POS TEST DEBUG - PHP date: " . $date_created);
        error_log("POS TEST DEBUG - PHP UTC: " . gmdate('Y-m-d H:i:s'));

        // =========================================================
        // 1. Generate Reference Number
        // =========================================================

        $reference_no = 'POS-' . date('Ymd-His');


        // =========================================================
        // 2. Insert Sales Summary (PARENT)
        // =========================================================

        $sql_sales = "INSERT INTO sales_test
                    (
                        ReferenceNo,
                        User_ID,
                        total_amount,
                        amount_tendered,
                        change_amt,
                        date_created,
                        date_updated
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?)";


        // Total amount
        $total_amount = (float)(
            $input['totalAmt'] ??
            $input['total_amount'] ??
            $input['total'] ??
            $_POST['totalAmt'] ??
            0
        );


        // Change amount
        $change_amt = (float)(
            $input['change'] ??
            $input['change_amt'] ??
            $_POST['change'] ??
            0
        );


        // Tendered amount
        $amount_tendered = (float)(
            $input['tenderAmt'] ??
            $input['tenderedAmt'] ??
            $input['amountTendered'] ??
            $input['tendered'] ??
            $input['amount_tendered'] ??
            $_POST['tenderAmt'] ??
            0
        );


        // =========================================================
        // Get Logged-in User
        // =========================================================

        session_start();

        if (!isset($_SESSION['user_id'])) {
            throw new Exception("User is not logged in.");
        }

        $user_id = (int) $_SESSION['user_id'];


        // =========================================================
        // Prepare Sales INSERT
        // =========================================================

        $stmt_sales = $conn_pos->prepare($sql_sales);

        $stmt_sales->bind_param(
            "sidddss",
            $reference_no,
            $user_id,
            $total_amount,
            $amount_tendered,
            $change_amt,
            $date_created,
            $date_updated
        );

        $stmt_sales->execute();


        // Get newly created parent Sales_ID
        $sales_id = $conn_pos->insert_id;


        // =========================================================
        // 3. Prepare Sales Item INSERT
        // =========================================================

        $sql_item = "INSERT INTO sales_item_test
                    (
                        Sales_ID,
                        Product_ID,
                        Quantity,
                        Unit_Price,
                        TotalPrice,
                        Expiration_ID,
                        date_created,
                        date_updated
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_item = $conn_pos->prepare($sql_item);


        // =========================================================
        // 4. Prepare FIFO Expiration Query
        // =========================================================

        // $sql_fifo = "SELECT
        //                 Expiration_ID,
        //                 Quantity,
        //                 ExpirationDate
        //              FROM expiration
        //              WHERE Product_ID = ?
        //                AND Quantity > 0
        //                AND ExpirationDate >= CURDATE()
        //              ORDER BY ExpirationDate ASC, Expiration_ID ASC";

        // $stmt_fifo = $conn_inventory->prepare($sql_fifo);


        // 12
        $sql_fifo = "SELECT
                Expiration_ID,
                Quantity,
                ExpirationDate
             FROM login.expiration
             WHERE Product_ID = ?
               AND Quantity > 0
               AND ExpirationDate >= CURDATE()
             ORDER BY ExpirationDate ASC, Expiration_ID ASC";

        $stmt_fifo = $conn_pos->prepare($sql_fifo);


        // =========================================================
        // 5. Prepare Expiration Batch Update
        // =========================================================

        // $sql_update_expiration = "UPDATE expiration
        //                           SET Quantity = Quantity - ?
        //                           WHERE Expiration_ID = ?
        //                             AND Quantity >= ?";

        // $stmt_expiration = $conn_inventory->prepare(
        //     $sql_update_expiration
        // );


        $sql_update_expiration = "UPDATE login.expiration
                                SET Quantity = Quantity - ?
                                WHERE Expiration_ID = ?
                                    AND Quantity >= ?";

        $stmt_expiration = $conn_pos->prepare(
            $sql_update_expiration
        );



        // =========================================================
        // 6. Process Each Cart Item
        // =========================================================

        foreach ($data as $product_id => $sales_item) {

            $pid = (int)$product_id;

            $quantity = (int)(
                $sales_item['orderQty'] ?? 0
            );

            $unit_price = (float)(
                $sales_item['price'] ?? 0
            );

            $total_price = (float)(
                $sales_item['amount'] ?? 0
            );


            // Nothing to process if quantity is zero
            if ($quantity <= 0) {
                continue;
            }


            // =====================================================
            // 6A. Check Whether Product Uses Expiration Tracking
            // =====================================================

            // $sql_check_expiration = "SELECT COUNT(*) AS total_batches
            //                          FROM expiration
            //                          WHERE Product_ID = ?";

            // $stmt_check_expiration = $conn_inventory->prepare(
            //     $sql_check_expiration
            // );

            $sql_check_expiration = "SELECT COUNT(*) AS total_batches
                         FROM login.expiration
                         WHERE Product_ID = ?";

            $stmt_check_expiration = $conn_pos->prepare(    
                $sql_check_expiration
            );

            $stmt_check_expiration->bind_param(
                "i",
                $pid
            );

            $stmt_check_expiration->execute();

            $result_check_expiration =
                $stmt_check_expiration->get_result();

            $expiration_info =
                $result_check_expiration->fetch_assoc();

            $total_batches =
                (int)$expiration_info['total_batches'];


            // =====================================================
            // 6B. Product HAS NO Expiration Records
            // =====================================================

            if ($total_batches === 0) {

                // Product does not use expiration tracking.
                // Allow normal checkout.

                $batch_total_price =
                    $unit_price * $quantity;


                // Insert item with Expiration_ID = NULL
                $sql_item_no_expiration = "INSERT INTO sales_item_test
                    (
                        Sales_ID,
                        Product_ID,
                        Quantity,
                        Unit_Price,
                        TotalPrice,
                        Expiration_ID,
                        date_created,
                        date_updated
                    )
                    VALUES (?, ?, ?, ?, ?, NULL, ?, ?)";

                $stmt_item_no_expiration =
                    $conn_pos->prepare($sql_item_no_expiration);


                $stmt_item_no_expiration->bind_param(
                    "iiiddss",
                    $sales_id,
                    $pid,
                    $quantity,
                    $unit_price,
                    $batch_total_price,
                    $date_created,
                    $date_updated
                );


                $stmt_item_no_expiration->execute();


            // =====================================================
            // 6C. Product HAS Expiration Records
            // =====================================================

            } else {

                // Find valid expiration batches using FIFO.

                $stmt_fifo->bind_param(
                    "i",
                    $pid
                );

                $stmt_fifo->execute();

                $result_fifo =
                    $stmt_fifo->get_result();


                // How many units still need to be allocated
                $remaining_quantity =
                    $quantity;


                // Track allocated quantity
                $allocated_quantity =
                    0;


                // =================================================
                // 6D. Go Through Batches in FIFO Order
                // =================================================

                while (
                    $batch =
                    $result_fifo->fetch_assoc()
                ) {

                    if ($remaining_quantity <= 0) {
                        break;
                    }


                    $expiration_id =
                        (int)$batch['Expiration_ID'];

                    $batch_quantity =
                        (int)$batch['Quantity'];


                    // Determine quantity to take
                    $quantity_from_batch =
                        min(
                            $remaining_quantity,
                            $batch_quantity
                        );


                    if ($quantity_from_batch <= 0) {
                        continue;
                    }


                    // =================================================
                    // 6E. Deduct From Expiration Batch
                    // =================================================

                    $stmt_expiration->bind_param(
                        "iii",
                        $quantity_from_batch,
                        $expiration_id,
                        $quantity_from_batch
                    );

                    $stmt_expiration->execute();


                    // Make sure update actually happened
                    if ($stmt_expiration->affected_rows !== 1) {

                        throw new Exception(
                            "Failed to update expiration batch ID "
                            . $expiration_id
                        );
                    }


                    // =================================================
                    // Calculate Batch Sales Total
                    // =================================================

                    $batch_total_price =
                        $unit_price * $quantity_from_batch;


                    // =================================================
                    // Insert Sales Item
                    // =================================================

                    $stmt_item->bind_param(
                        "iiiddiss",
                        $sales_id,
                        $pid,
                        $quantity_from_batch,
                        $unit_price,
                        $batch_total_price,
                        $expiration_id,
                        $date_created,
                        $date_updated
                    );

                    $stmt_item->execute();


                    // =================================================
                    // Update Remaining Quantity
                    // =================================================

                    $remaining_quantity -=
                        $quantity_from_batch;

                    $allocated_quantity +=
                        $quantity_from_batch;
                }


                // =================================================
                // 6F. Check If Enough Expiration Stock Exists
                // =================================================

                if ($remaining_quantity > 0) {

                    throw new Exception(
                        "Not enough valid expiration stock for Product ID "
                        . $pid
                        . ". Requested: "
                        . $quantity
                        . ", Allocated: "
                        . $allocated_quantity
                    );
                }
            }
        }


        // =========================================================
        // 7. Return Successful Checkout
        // =========================================================

        // Everything succeeded
        $conn_pos->commit();


        echo json_encode([
            "success" => true,
            "message" => "TEST order completed successfully!",
            "sales_id" => $sales_id,
            "reference_no" => $reference_no,
            "products" => getProducts()
        ]);


    } catch (Exception $e) {

    // Undo all changes made during this checkout
        $conn_pos->rollback();


        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "TEST Transaction Failed: "
                . $e->getMessage()
        ]);
    }
}
