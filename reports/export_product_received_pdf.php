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
        n.Inventory_ID,
        n.Product_ID,
        p.ProductName,
        p.Type,
        n.Quantity,
        n.Date_Added,
        n.Expiration_Date,
        n.Supplier_ID,
        n.LocationS,
        n.LocationR
    FROM newaddition n
    LEFT JOIN product p
        ON n.Product_ID = p.Product_ID
    WHERE DATE(n.Date_Added) BETWEEN '$startDate' AND '$endDate'
    ORDER BY n.Date_Added DESC
";

$result = mysqli_query($conn, $query);

$totalReceived = 0;
$totalRecords = 0;

$receivedData = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $receivedData[] = $row;
        $totalRecords++;
        $totalReceived += (int)$row['Quantity'];
    }
}

$pdf = new TCPDF();
$pdf->SetCreator('Inventory and Sales Management System');
$pdf->SetAuthor('Inventory and Sales Management System');
$pdf->SetTitle('Product Received Report');

$pdf->AddPage('L');

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'PRODUCT RECEIVED REPORT', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Period: ' . $period, 0, 1, 'C');

$pdf->Ln(5);

$summary = '
<table border="1" cellpadding="6">
    <tr>
        <td width="50%"><b>Received Records</b><br>' . $totalRecords . '</td>
        <td width="50%"><b>Total Quantity Received</b><br>' . $totalReceived . '</td>
    </tr>
</table>
';

$pdf->writeHTML($summary, true, false, true, false, '');

$pdf->Ln(5);

$html = '
<table border="1" cellpadding="4">
    <tr style="background-color:#f2f2f2;">
        <th width="9%">Inventory ID</th>
        <th width="9%">Product ID</th>
        <th width="21%">Product Name</th>
        <th width="10%">Category</th>
        <th width="9%">Quantity</th>
        <th width="13%">Date Added</th>
        <th width="13%">Expiration</th>
        <th width="8%">Supplier</th>
        <th width="4%">Shelf</th>
        <th width="4%">Row</th>
    </tr>
';

if (count($receivedData) > 0) {

    foreach ($receivedData as $row) {

        $dateAdded = !empty($row['Date_Added'])
            ? date('M d, Y', strtotime($row['Date_Added']))
            : 'N/A';

        $expiration = !empty($row['Expiration_Date'])
            ? date('M d, Y', strtotime($row['Expiration_Date']))
            : 'N/A';

        $html .= '
        <tr>
            <td>' . htmlspecialchars($row['Inventory_ID']) . '</td>
            <td>' . htmlspecialchars($row['Product_ID']) . '</td>
            <td>' . htmlspecialchars($row['ProductName'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['Type'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['Quantity']) . '</td>
            <td>' . $dateAdded . '</td>
            <td>' . $expiration . '</td>
            <td>' . htmlspecialchars($row['Supplier_ID'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['LocationS'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['LocationR'] ?? 'N/A') . '</td>
        </tr>';
    }

} else {

    $html .= '
    <tr>
        <td colspan="10" align="center">
            No received products found for this period.
        </td>
    </tr>';
}

$html .= '</table>';

$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();

$pdf->Output('product_received_report.pdf', 'I');
?>