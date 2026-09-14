
<?php
include('connect.php');
// $action =$_GET['action'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

if($action === 'checkout') saveProducts();

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
    $customer = $input['customer'] ?? $_POST['customer'] ?? null;

    if (!$data || !$customer) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Missing customer or cart data."]);
        return;
    }

    try {
        $date_created = date('Y-m-d H:i:s');
        $date_updated = date('Y-m-d H:i:s');

        // 1. Insert Customer Details
        $sql_customer = "INSERT INTO customers (first_name, last_name, address, contact, date_created, date_updated)
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn_pos->prepare($sql_customer);

        $firstName = $customer['firstName'] ?? '';
        $lastName  = $customer['lastName'] ?? '';
        $address   = $customer['address'] ?? '';
        $contact   = $customer['contact'] ?? '';

        $stmt->bind_param("ssssss", $firstName, $lastName, $address, $contact, $date_created, $date_updated);
        $stmt->execute();
        $customer_id = $conn_pos->insert_id; 



        // 2. Insert Sales Summary
        $sql_sales = "INSERT INTO sales (Customer_ID, User_ID, total_amount, amount_tendered, change_amt, date_created, date_updated)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Checks all possible key variations sent by frontend JavaScript
        $total_amount    = (float)($input['totalAmt'] ?? $input['total_amount'] ?? $input['total'] ?? $_POST['totalAmt'] ?? 0);
        $change_amt      = (float)($input['change'] ?? $input['change_amt'] ?? $_POST['change'] ?? 0); 
        
        // Tendered Amount fallback chain
        $amount_tendered = (float)(
            $input['tenderAmt'] ?? 
            $input['tenderedAmt'] ?? 
            $input['amountTendered'] ?? 
            $input['tendered'] ?? 
            $input['amount_tendered'] ?? 
            $_POST['tenderAmt'] ?? 0
        );
        
        $user_id = 17; // Default cashier/user ID

        $stmt_sales = $conn_pos->prepare($sql_sales);
        $stmt_sales->bind_param("iidddss", $customer_id, $user_id, $total_amount, $amount_tendered, $change_amt, $date_created, $date_updated);
        $stmt_sales->execute();
        $sales_id = $conn_pos->insert_id;

        
        // 3. Prepare Sales Items Insert, Product Update, & Expiration Batch Deductions
        $sql_item = "INSERT INTO sales_item (Sales_ID, Product_ID, Quantity, Unit_Price, TotalPrice, date_created, date_updated, BatchNum)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_item = $conn_pos->prepare($sql_item);

        // Inventory DB updates
        $sql_update_stock = "UPDATE product SET UnitSold = UnitSold + ? WHERE Product_ID = ?";
        $stmt_stock = $conn_inventory->prepare($sql_update_stock);

        // Deduct purchased quantity from specific batch in expiration table
        $sql_update_expiration = "UPDATE expiration SET Quantity = Quantity - ? WHERE Expiration_ID = ?";
        $stmt_expiration = $conn_inventory->prepare($sql_update_expiration);

        foreach($data as $product_id => $sales_item){
            $pid           = (int)$product_id;
            $quantity      = (int)($sales_item['orderQty'] ?? 0);
            $unit_price    = (float)($sales_item['price'] ?? 0);
            $total_price   = (float)($sales_item['amount'] ?? 0);
            $expiration_id = (int)($sales_item['BatchNum'] ?? $sales_item['Expiration_ID'] ?? 0); // Expiration_ID integer key

            // Insert into sales_item (Types: iiiddssi - BatchNum stored as INT ID)
            $stmt_item->bind_param("iiiddssi", $sales_id, $pid, $quantity, $unit_price, $total_price, $date_created, $date_updated, $expiration_id);
            $stmt_item->execute();

            // Increment UnitSold in product table
            if ($conn_inventory instanceof mysqli) {
                $stmt_stock->bind_param("ii", $quantity, $pid);
                $stmt_stock->execute();

                // Decrement batch Quantity in expiration table
                $stmt_expiration->bind_param("ii", $quantity, $expiration_id);
                $stmt_expiration->execute();
            } else {
                // If $conn_inventory uses PDO
                $stmt_stock->execute([$quantity, $pid]);
                $stmt_expiration->execute([$quantity, $expiration_id]);
            }
        }
        

        echo json_encode([
            // "status" => "success",
            "success" => true,
            "message" => "Order completed and stock updated successfully!",
            "sales_id" => $sales_id,
            "products" => getProducts()
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            // "status" => "error",
            "success" => false,
            "message" => "Transaction Failed: " . $e->getMessage()
        ]);
    }
}




        // // 2. Insert Sales Summary
        // $sql_sales = "INSERT INTO sales (Customer_ID, User_ID, total_amount, amount_tendered, change_amt, date_created, date_updated)
        //               VALUES (?, ?, ?, ?, ?, ?, ?)";

        // // $total_amount    = (float)($_POST['totalAmt'] ?? 0);
        // // $change_amt      = (float)($_POST['change'] ?? 0); 
        // // $amount_tendered = (float)($_POST['tenderAmt'] ?? 0);
        // // $user_id         = 17; // Default cashier/user ID

        // // Read raw JSON input values first, fallback to $_POST
        // $total_amount    = (float)($input['totalAmt'] ?? $_POST['totalAmt'] ?? 0);
        // $change_amt      = (float)($input['change'] ?? $_POST['change'] ?? 0); 
        // $amount_tendered = (float)($input['tenderAmt'] ?? $_POST['tenderAmt'] ?? 0);
        // $user_id         = 17; // Default cashier/user ID

        // $stmt_sales = $conn_pos->prepare($sql_sales);
        // $stmt_sales->bind_param("iidddss", $customer_id, $user_id, $total_amount, $amount_tendered, $change_amt, $date_created, $date_updated);
        // $stmt_sales->execute();
        // $sales_id = $conn_pos->insert_id; 



// 3. Prepare Sales Items Insert & Inventory Update Statements
        // $sql_item = "INSERT INTO sales_item (Sales_ID, Product_ID, Quantity, Unit_Price, TotalPrice, date_created, date_updated, BatchNum)
        //              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        // $stmt_item = $conn_pos->prepare($sql_item);

        // // SQL to directly increment UnitsSold in Inventory DB
        // $sql_update_stock = "UPDATE product SET UnitSold = UnitSold + ? WHERE Product_ID = ?";
        // $stmt_stock = $conn_inventory->prepare($sql_update_stock);

        // foreach($data as $product_id => $sales_item){
        //     $pid        = (int)$product_id;
        //     $quantity   = (int)($sales_item['orderQty'] ?? 0);
        //     $unit_price = (float)($sales_item['price'] ?? 0);
        //     $total_price= (float)($sales_item['amount'] ?? 0);
        //     $batch_num  = $sales_item['BatchNum'] ?? 'BATCH-001';

        //     // Insert into POS DB (sales_item)
        //     $stmt_item->bind_param("iiiddsss", $sales_id, $pid, $quantity, $unit_price, $total_price, $date_created, $date_updated, $batch_num);
        //     $stmt_item->execute();

        //     // Update Stock in Inventory DB (product)
        //     $stmt_stock->bind_param("ii", $quantity, $pid);
        //     $stmt_stock->execute();
        // }







//////////////////////////////////////////////////
// function saveProducts(){
//     $conn = $GLOBALS['conn_pos'];

//     // Read raw JSON input in case the frontend sends JSON, fallback to $_POST
//     $input = json_decode(file_get_contents('php://input'), true);
//     $data = $input['data'] ?? $_POST['data'] ?? null;
//     $customer = $input['customer'] ?? $_POST['customer'] ?? null;

//     // Stop execution if data is missing to prevent database errors
//     if (!$data || !$customer) {
//         http_response_code(400);
//         echo json_encode(["status" => "error", "message" => "Missing customer or cart data."]);
//         return;
//     }

//     // 1. Insert customer details using MySQLi positional placeholders (?)
//     $sql = "INSERT INTO customers (first_name, last_name, address, contact, date_created, date_updated)
//             VALUES (?, ?, ?, ?, ?, ?)";

//     $stmt = $conn->prepare($sql);

//     $date_created = date('Y-m-d H:i:s');
//     $date_updated = date('Y-m-d H:i:s');

//     $firstName = $customer['firstName'] ?? '';
//     $lastName = $customer['lastName'] ?? '';
//     $address = $customer['address'] ?? '';
//     $contact = $customer['contact'] ?? '';

//     $stmt->bind_param(
//         "ssssss",
//         $firstName,
//         $lastName,
//         $address,
//         $contact,
//         $date_created,
//         $date_updated
//     );

//     $stmt->execute();
//     $customer_id = $conn->lastInsertId(); 

//     $sql = "INSERT INTO sales (Customer_ID, User_ID, total_amount, amount_tendered, change_amt, date_created, date_updated)
//             VALUES (?, ?, ?, ?, ?, ?, ?)";

//     $total_amount = $_POST['totalAmt'],
//     $change_amt = $_POST['change'], 
//     $tenderedAmt = $_POST['tenderAmt'],
//     $user_id = 17;

//     $stmt = $conn->prepare($sql);
    
//     $Customer_ID = $sales['Customer_ID'] ?? '';
//     $User_ID = $sales['User_ID'] ?? '';
//     $total_amount = $sales['total_amount'] ?? '';
//     $amount_tendered = $sales['amount_tendered'] ?? '';
//     $change_amt = $sales['change_amt'] ?? '';
//     $date_created = date('Y-m-d H:i:s');
//     $date_updated = date('Y-m-d H:i:s');

//     $stmt->bind_param(
//         "sssssss",
//         $Customer_ID,
//         $User_ID,
//         $total_amount,
//         $amount_tendered,
//         $change_amt,
//         $date_created,
//         $date_updated
//     );

//     $stmt->execute();
//     $sales_id = $conn->lastInsertId(); 

//     foreach($data as $product_id => $sales_item){

//         $sql = "INSERT INTO sales_item (Sales_ID, Product_ID , Quantity, Unit_Price, TotalPrice, date_created, date_updated, BatchNum)
//             VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

//         $total_amount = $_POST['totalAmt'],
//         $change_amt = $_POST['change'], 
//         $tenderedAmt = $_POST['tenderAmt'],
//         $user_id = 17;

//         $stmt = $conn->prepare($sql);
        
//         $Sales_ID = $sales['Sales_ID'] ?? '';
//         $Product_ID = $sales['Product_ID'] ?? '';
//         $Quantity = $sales['Quantity'] ?? '';
//         $Unit_Price = $sales['Unit_Price'] ?? '';
//         $TotalPrice = $sales['TotalPrice'] ?? '';
//         $date_created = date('Y-m-d H:i:s');
//         $date_updated = date('Y-m-d H:i:s');
//         $BatchNum = $sales['BatchNum'] ?? '';

//         $stmt->bind_param(
//             "ssssssss",
//             $Sales_ID,
//             $Product_ID,
//             $Quantity,
//             $Unit_Price,
//             $TotalPrice,
//             $date_created,
//             $date_updated,
//             $BatchNum
//         );

//         $stmt->execute();
//         // $salesItem_id = $conn->lastInsertId(); 

//         //
//         $inv_conn = $GLOBALS['conn'];

//         $stmt = $conn->prepare("SELECT `UnitsOrdered` - `UnitSold` AS stock FROM product WHERE Product_ID=?")
//         $stmt->execute();

//         $result = $stmt->get_result();
//         $product = $result->fetch(MYSQLI_ASSOC);
//         $cur_stock = $product['stock'];

//         $sql = "UPDATE product SET UnitsSold=? WHERE Product_ID=?,
//         "

//     }

    

    
   

 

    

//     var_dump($data);
    
//     // foreach($data as $order_item){
//     //     $sql ="INSERT INTO $table_name($table_properties) VALUES
//     //                 ($table_placeholder)";

//     //     $db_arr = [

//     //     ];        
//     //     $stmt = $conn->prepare($sql);
//     //     $stmt->execute($db_arr);   
        
//     // }


// }

///////////////////////////////////////////////////////////


// include('connect.php');

// $action = isset($_GET['action']) ? $_GET['action'] : '';

// if($action === 'checkout') saveProducts();

// function getProducts(){
//     $conn = $GLOBALS['conn'];

//     $stmt = $conn->prepare("SELECT * FROM product");
//     $stmt->execute();
    
//     // Fetch all rows using PDO syntax
//     $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     return $rows;
// }

// function saveProducts(){
//     $conn = $GLOBALS['conn_pos'];
//     $data = $_POST['data'];
//     $customer = $_POST['customer'];

//     $sql = "INSERT INTO 
//                 customers(first_name, last_name, address, contact, date_created, date_updated)
//             VALUES
//                 (:first_name, :last_name, :address, :contact, :date_created, :date_updated)";
//     $db_arr = [
//         'first_name' => $customer['firstName'],
//         'last_name' => $customer['lastName'],
//         'contact' => $customer['contact'],
//         'address' => $customer['address'],
//         'date_created' => date('Y-m-d H:i:s'),
//         'date_updated' => date('Y-m-d H:i:s')
//     ];
//     $stmt = $conn->prepare($sql);
//     $stmt->execute($db_arr);
//     $customer_id = $conn->lastInsertId();

//     var_dump($data);
    
//     // foreach($data as $order_item){
//     //     $sql ="INSERT INTO $table_name($table_properties) VALUES
//     //                 ($table_placeholder)";

//     //     $db_arr = [

//     //     ];        
//     //     $stmt = $conn->prepare($sql);
//     //     $stmt->execute($db_arr);   
//     // }
// }




// include('connect.php');

// $action = isset($_GET['action']) ? $_GET['action'] : '';

// if($action === 'checkout') saveProducts();

// function getProducts(){
//     $conn = $GLOBALS['conn'];

//     $stmt = $conn->prepare("SELECT * FROM product");
//     $stmt->execute();
    
//     // Fetch result set using MySQLi
//     $result = $stmt->get_result();
//     $rows = $result->fetch_all(MYSQLI_ASSOC);

//     return $rows;
// }

// function saveProducts(){
//     $conn = $GLOBALS['conn_pos'];

//     // Read raw JSON input in case the frontend sends JSON, fallback to $_POST
//     $input = json_decode(file_get_contents('php://input'), true);
//     $data = $input['data'] ?? $_POST['data'] ?? null;
//     $customer = $input['customer'] ?? $_POST['customer'] ?? null;

//     // Stop execution if data is missing to prevent database errors
//     // if (!$data || !$customer) {
//     //     http_response_code(400);
//     //     echo json_encode(["status" => "error", "message" => "Missing customer or cart data."]);
//     //     return;
//     // }

//     // 1. Insert customer details using MySQLi positional placeholders (?)
//     $sql = "INSERT INTO customers (first_name, last_name, address, contact, date_created, date_updated)
//             VALUES (?, ?, ?, ?, ?, ?)";

//     $stmt = $conn->prepare($sql);

//     $date_created = date('Y-m-d H:i:s');
//     $date_updated = date('Y-m-d H:i:s');

//     $firstName = $customer['firstName'] ?? '';
//     $lastName = $customer['lastName'] ?? '';
//     $address = $customer['address'] ?? '';
//     $contact = $customer['contact'] ?? '';

//     $stmt->bind_param(
//         "ssssss",
//         $firstName,
//         $lastName,
//         $address,
//         $contact,
//         $date_created,
//         $date_updated
//     );

//     $stmt->execute();
//     $customer_id = $conn->insert_id; 

//     // // 2. Insert order items
//     // // Change 'order_items' to your actual table name if it differs
//     // $sql_item = "INSERT INTO order_items (customer_id, product_id, price, quantity, total_amount, date_created) 
//     //              VALUES (?, ?, ?, ?, ?, ?)";

//     // $stmt_item = $conn->prepare($sql_item);

//     // foreach($data as $product_id => $order_item){
//     //     $price = $order_item['price'] ?? 0;
//     //     $quantity = $order_item['orderQty'] ?? 0;
//     //     $total_amount = $order_item['amount'] ?? 0;

//     //     $stmt_item->bind_param(
//     //         "iiddss",
//     //         $customer_id,
//     //         $product_id,
//     //         $price,
//     //         $quantity,
//     //         $total_amount,
//     //         $date_created
//     //     );

//     //     $stmt_item->execute();
//     // }
// }









?>


