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
        st.Sales_ID,
        st.ReferenceNo,
        si.Product_ID,
        si.Quantity,
        si.`Unit_Price`,
        si.TotalPrice,
        si.BatchNum,
        st.date_created
    FROM sales_test st
    INNER JOIN sales_item_test si
        ON st.Sales_ID = si.Sales_ID
    WHERE DATE(st.date_created) BETWEEN '$startDate' AND '$endDate'
    ORDER BY st.date_created DESC
";

$result = mysqli_query($conn_pos, $query);

$totalTransactions = 0;
$totalItems = 0;
$totalSales = 0;

$transactions = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
        $totalItems += (int)$row['Quantity'];
        $totalSales += (float)$row['TotalPrice'];
    }
}

$countQuery = "
    SELECT COUNT(DISTINCT Sales_ID) AS total_transactions
    FROM sales_test
    WHERE DATE(date_created) BETWEEN '$startDate' AND '$endDate'
";

$countResult = mysqli_query($conn_pos, $countQuery);

if ($countResult) {
    $countRow = mysqli_fetch_assoc($countResult);
    $totalTransactions = (int)$countRow['total_transactions'];
}

$pdf = new TCPDF();
$pdf->SetCreator('Inventory and Sales Management System');
$pdf->SetAuthor('Inventory and Sales Management System');
$pdf->SetTitle('Sales Summary Report');

$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'SALES SUMMARY REPORT', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Period: ' . $period, 0, 1, 'C');

$pdf->Ln(5);

$summary = '
<table border="1" cellpadding="6">
    <tr>
        <td width="33%"><b>Total Transactions</b><br>' . $totalTransactions . '</td>
        <td width="33%"><b>Total Items Sold</b><br>' . $totalItems . '</td>
        <td width="34%"><b>Total Sales</b><br>PHP ' . number_format($totalSales, 2) . '</td>
    </tr>
</table>
';

$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTML($summary, true, false, true, false, '');

$pdf->Ln(5);

$html = '
<table border="1" cellpadding="4">
    <tr style="background-color:#f2f2f2;">
        <th width="14%">Reference No.</th>
        <th width="11%">Product ID</th>
        <th width="10%">Quantity</th>
        <th width="14%">Unit Price</th>
        <th width="15%">Total Price</th>
        <th width="13%">Batch No.</th>
        <th width="23%">Date</th>
    </tr>
';

if (count($transactions) > 0) {

    foreach ($transactions as $row) {

        $date = !empty($row['date_created'])
            ? date('M d, Y h:i A', strtotime($row['date_created']))
            : 'N/A';

        $html .= '
        <tr>
            <td>' . htmlspecialchars($row['ReferenceNo']) . '</td>
            <td>' . htmlspecialchars($row['Product_ID']) . '</td>
            <td>' . htmlspecialchars($row['Quantity']) . '</td>
            <td>PHP ' . number_format((float)$row['Unit_Price'], 2) . '</td>
            <td>PHP ' . number_format((float)$row['TotalPrice'], 2) . '</td>
            <td>' . htmlspecialchars($row['BatchNum'] ?? 'N/A') . '</td>
            <td>' . $date . '</td>
        </tr>';
    }

} else {

    $html .= '
    <tr>
        <td colspan="7" align="center">
            No sales found for this period.
        </td>
    </tr>';
}

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();

$pdf->Output('sales_summary_report.pdf', 'I');
?>