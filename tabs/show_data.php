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


function fetchTableData($conn, $table) {
    $valid_tables = getAllTables($conn);

    if (!in_array($table, $valid_tables)){
        return [];
    }

    $sql = "SELECT * FROM `$table`";
    $result = $conn->query($sql);
    
    if (!$result) {
        return [];
    }

    return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

return fetchTableData($conn, $table);

// $sql = "SELECT * FROM users";
// $result = $conn->query($sql);

// if (!$result) {
//     die("Query failed: " . $conn->error);
// }

// return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

?>
    