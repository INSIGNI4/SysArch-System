<?php
ob_start();

include('../connect.php');
require_once __DIR__ . '/../tcpdf/tcpdf.php';

date_default_timezone_set('Asia/Manila');

$type = isset($_GET['type']) ? $_GET['type'] : 'daily';

$today = date('Y-m-d');

if ($type === 'weekly') {
    $startDate = date('Y-m-d', strtotime('monday this week'));
    $endDate = date('Y-m-d', strtotime('sunday this week'));
    $period = date('F d, Y', strtotime($startDate)) . ' - ' . date('F d, Y', strtotime($endDate));
} elseif ($type === 'monthly') {
    $startDate = date('Y-m-01');
    $endDate = date('Y-m-t');
    $period = date('F Y');
} else {
    $startDate = $today;
    $endDate = $today;
    $period = date('F d, Y');
}

$query = "
    SELECT
        l.ListToOrder_ID,
        l.Supplier_ID,
        l.Order_Date,
        l.Expected_Receive_Date,
        l.Order_Status,
        l.Total_Amount,
        i.ItemToOrder_ID,
        i.Product_ID,
        p.ProductName,
        i.Recommended_Order_Quantity,
        i.Ordered_Quantity,
        i.Received_Quantity,
        i.Unit_Cost,
        i.Line_Total,
        i.Item_Status
    FROM list_to_order l
    LEFT JOIN item_to_order i
        ON l.ListToOrder_ID = i.ListToOrder_ID
    LEFT JOIN product p
        ON i.Product_ID = p.Product_ID
    WHERE DATE(l.Order_Date) BETWEEN '$startDate' AND '$endDate'
    ORDER BY l.Order_Date DESC
";

$result = mysqli_query($conn, $query);

$totalOrders = 0;
$totalQuantity = 0;
$totalAmount = 0;

$orderData = [];

if ($result) {

    $orderIDs = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $orderData[] = $row;

        if (!in_array($row['ListToOrder_ID'], $orderIDs)) {
            $orderIDs[] = $row['ListToOrder_ID'];
            $totalOrders++;
        }

        $totalQuantity += (int)$row['Ordered_Quantity'];
        $totalAmount += (float)$row['Line_Total'];
    }
}

$pdf = new TCPDF();
$pdf->SetCreator('Inventory and Sales Management System');
$pdf->SetAuthor('Inventory and Sales Management System');
$pdf->SetTitle('Product Orders Report');

$pdf->AddPage('L');

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'PRODUCT ORDERS REPORT', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Period: ' . $period, 0, 1, 'C');

$pdf->Ln(5);

$summary = '
<table border="1" cellpadding="6">
    <tr>
        <td width="33%"><b>Total Orders</b><br>' . $totalOrders . '</td>
        <td width="33%"><b>Total Ordered Quantity</b><br>' . $totalQuantity . '</td>
        <td width="34%"><b>Total Order Amount</b><br>PHP ' . number_format($totalAmount, 2) . '</td>
    </tr>
</table>
';

$pdf->writeHTML($summary, true, false, true, false, '');

$pdf->Ln(5);

$html = '
<table border="1" cellpadding="4">
    <tr style="background-color:#f2f2f2;">
        <th width="9%">Order ID</th>
        <th width="8%">Supplier</th>
        <th width="9%">Product ID</th>
        <th width="20%">Product Name</th>
        <th width="9%">Recommended</th>
        <th width="9%">Ordered</th>
        <th width="9%">Received</th>
        <th width="9%">Unit Cost</th>
        <th width="9%">Line Total</th>
        <th width="9%">Status</th>
    </tr>
';

if (count($orderData) > 0) {

    foreach ($orderData as $row) {

        $html .= '
        <tr>
            <td>' . htmlspecialchars($row['ListToOrder_ID']) . '</td>
            <td>' . htmlspecialchars($row['Supplier_ID'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['Product_ID'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['ProductName'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['Recommended_Order_Quantity'] ?? '0') . '</td>
            <td>' . htmlspecialchars($row['Ordered_Quantity'] ?? '0') . '</td>
            <td>' . htmlspecialchars($row['Received_Quantity'] ?? '0') . '</td>
            <td>PHP ' . number_format((float)($row['Unit_Cost'] ?? 0), 2) . '</td>
            <td>PHP ' . number_format((float)($row['Line_Total'] ?? 0), 2) . '</td>
            <td>' . htmlspecialchars($row['Item_Status'] ?? $row['Order_Status'] ?? 'N/A') . '</td>
        </tr>';
    }

} else {

    $html .= '
    <tr>
        <td colspan="10" align="center">
            No product orders found for this period.
        </td>
    </tr>';
}

$html .= '</table>';

$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();

$pdf->Output('product_orders_report.pdf', 'I');
?>