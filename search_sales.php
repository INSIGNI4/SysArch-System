<?php

include('connect.php');

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$query = trim($_GET['q'] ?? '');

if ($query === '') {
    echo json_encode([
        'success' => false,
        'data' => []
    ]);
    exit;
}

$search = "%{$query}%";

try {

    /* =====================================================
       TRANSACTION SEARCH
       ===================================================== */

    if ($type === 'transaction') {

        $sql = "
            SELECT
                Sales_ID,
                ReferenceNo,
                User_ID,
                total_amount,
                amount_tendered,
                change_amt,
                date_created
            FROM sales_test
            WHERE
                ReferenceNo LIKE ?
                OR CAST(Sales_ID AS CHAR) LIKE ?
                OR CAST(date_created AS CHAR) LIKE ?
            ORDER BY Sales_ID DESC
            LIMIT 50
        ";

        $stmt = $conn_pos->prepare($sql);

        if (!$stmt) {
            throw new Exception($conn_pos->error);
        }

        $stmt->bind_param(
            "sss",
            $search,
            $search,
            $search
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);

        exit;
    }


    /* =====================================================
       SALES ITEM SEARCH
       ===================================================== */

    if ($type === 'sales') {

        $sql = "
            SELECT
                si.SalesItem_ID,
                si.Sales_ID,
                si.Product_ID,
                p.ProductName,
                si.BatchNum,
                si.Unit_Price,
                si.Quantity,
                si.TotalPrice,
                si.date_created
            FROM sales_item_test si
            LEFT JOIN login.product p
                ON p.Product_ID = si.Product_ID
            WHERE
                CAST(si.SalesItem_ID AS CHAR) LIKE ?
                OR CAST(si.Sales_ID AS CHAR) LIKE ?
                OR CAST(si.Product_ID AS CHAR) LIKE ?
                OR CAST(si.BatchNum AS CHAR) LIKE ?
                OR CAST(si.date_created AS CHAR) LIKE ?
            ORDER BY si.SalesItem_ID DESC
            LIMIT 50
        ";

        $stmt = $conn_pos->prepare($sql);

        if (!$stmt) {
            throw new Exception($conn_pos->error);
        }

        $stmt->bind_param(
            "sssss",
            $search,
            $search,
            $search,
            $search,
            $search
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);

        exit;
    }


    echo json_encode([
        'success' => false,
        'data' => []
    ]);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'data' => [],
        'error' => $e->getMessage()
    ]);
}
?>