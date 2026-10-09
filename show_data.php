<?php
include('connect.php');


$table = isset($_GET['table']) ? $_GET['table'] : 'product';


/**
 * @param mysqli $conn
 * @return array
 */

function getAllTables($conn) {
    $tables = [];
    $query = $conn->query("SHOW TABLES");

    if ($query) {
        while ($row = $query->fetch_array()) {
            $tables[] = $row[0];
        } 
    }  else {
        die("Failed to show data" . $conn->error);
    }
    
    return $tables;
}
/**
 * @param mysqli $conn
 * @param string $table
 * @return array
 */


// function fetchTableData($conn, $table) {
//     $valid_tables = getAllTables($conn);

//     if (!in_array($table, $valid_tables)){
//         return [];
//     }

//     $sql = "SELECT * FROM `$table`";
//     $result = $conn->query($sql);
    
//     if (!$result) {
//         return [];
//     }

//     return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
// }


function fetchTableData($conn, $table) {

    $valid_tables = getAllTables($conn);

    if (!in_array($table, $valid_tables)) {
        return [];
    }

    // Special query for Restock
    // Adds Product Name and Supplier Name
    if ($table === 'restock') {

        // $sql = "
        //     SELECT
        //         r.*,

        //         p.ProductName,
        //         s.Email,
        //         s.Location,

        //         CONCAT_WS(
        //             ' ',
        //             s.SupplierFName,
        //             s.SupplierLName
        //         ) AS SupplierName

        //     FROM restock r

        //     LEFT JOIN product p
        //         ON p.Product_ID = r.Product_ID

        //     LEFT JOIN supplier s
        //         ON s.Supplier_ID = r.Supplier_ID

        //     ORDER BY r.Orestock_ID DESC
        // ";




        $sql = "
            SELECT
                r.*,
                p.ProductName,
                s.Email,
                s.PhoneNumber,
                s.Address,
                s.City,
                s.CompanyName AS SupplierName,
                s.ContactPerson
            FROM restock r
            LEFT JOIN product p
                ON p.Product_ID = r.Product_ID
            LEFT JOIN supplier s
                ON s.Supplier_ID = r.Supplier_ID
            ORDER BY r.Orestock_ID DESC
        ";


        




    } elseif ($table === 'sales_item_test') {

        $sql = "
            SELECT *
            FROM sales_item_test
            ORDER BY SalesItem_ID DESC
            LIMIT 50
        ";

    } elseif ($table === 'sales_test') {

        $sql = "
            SELECT *
            FROM sales_test
            ORDER BY Sales_ID DESC
            LIMIT 50
        ";

    } elseif ($table === 'inventory') {

        $sql = "
            SELECT
                r.*,
                
                s.Email,
                s.PhoneNumber,
                s.Address,
                s.City,
                s.CompanyName AS SupplierName,
                s.ContactPerson
            FROM inventory r
            LEFT JOIN supplier s
                ON s.Supplier_ID = r.Supplier_ID
            
        ";


    } elseif ($table === 'product') {

        $sql = "
            SELECT
                r.*,
                
                s.Email,
                s.PhoneNumber,
                s.Address,
                s.City,
                s.CompanyName AS SupplierName,
                s.ContactPerson
            FROM product r
            LEFT JOIN supplier s
                ON s.Supplier_ID = r.Supplier_ID
            
        ";




    } else {

        // Keep the original behavior for all other tables

        $sql = "SELECT * FROM `$table`";
    }

    $result = $conn->query($sql);

    if (!$result) {
        return [];
    }

    return $result->num_rows > 0
        ? $result->fetch_all(MYSQLI_ASSOC)
        : [];
}



return fetchTableData($conn, $table);

// $sql = "SELECT * FROM users";
// $result = $conn->query($sql);

// if (!$result) {
//     die("Query failed: " . $conn->error);
// }

// return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

?>
    