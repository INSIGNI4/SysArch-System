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
        i.Product_ID,
        p.ProductName,
        p.Type,
        i.LocationS,
        i.LocationR,
        i.Inventory,
        i.Status,
        i.ExpirationDate,
        i.Supplier_ID
    FROM inventory i
    LEFT JOIN product p
        ON i.Product_ID = p.Product_ID
    ORDER BY i.Inventory ASC, p.ProductName ASC
";

$result = mysqli_query($conn, $query);

$totalProducts = 0;
$totalStock = 0;
$lowStock = 0;
$outOfStock = 0;

$inventoryData = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {

        $inventoryData[] = $row;

        $totalProducts++;
        $stock = (int)$row['Inventory'];

        $totalStock += $stock;

        if ($stock <= 0) {
            $outOfStock++;
        } elseif (strtolower($row['Status']) === 'low stock') {
            $lowStock++;
        }
    }
}

$pdf = new TCPDF();
$pdf->SetCreator('Inventory and Sales Management System');
$pdf->SetAuthor('Inventory and Sales Management System');
$pdf->SetTitle('Inventory Summary Report');

$pdf->AddPage('L');

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'INVENTORY SUMMARY REPORT', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Report Date: ' . date('F d, Y'), 0, 1, 'C');

$pdf->Ln(5);

$summary = '
<table border="1" cellpadding="6">
    <tr>
        <td width="25%"><b>Total Products</b><br>' . $totalProducts . '</td>
        <td width="25%"><b>Total Stock</b><br>' . $totalStock . '</td>
        <td width="25%"><b>Low Stock</b><br>' . $lowStock . '</td>
        <td width="25%"><b>Out of Stock</b><br>' . $outOfStock . '</td>
    </tr>
</table>
';

$pdf->writeHTML($summary, true, false, true, false, '');

$pdf->Ln(5);

$html = '
<table border="1" cellpadding="4">
    <tr style="background-color:#f2f2f2;">
        <th width="9%">Product ID</th>
        <th width="22%">Product Name</th>
        <th width="11%">Category</th>
        <th width="9%">Shelf</th>
        <th width="9%">Row</th>
        <th width="10%">Stock</th>
        <th width="12%">Status</th>
        <th width="12%">Expiration</th>
        <th width="6%">Supplier</th>
    </tr>
';

if (count($inventoryData) > 0) {

    foreach ($inventoryData as $row) {

        $expiration = !empty($row['ExpirationDate'])
            ? date('M d, Y', strtotime($row['ExpirationDate']))
            : 'N/A';

        $html .= '
        <tr>
            <td>' . htmlspecialchars($row['Product_ID']) . '</td>
            <td>' . htmlspecialchars($row['ProductName'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['Type'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['LocationS'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['LocationR'] ?? 'N/A') . '</td>
            <td>' . max(0, (int)$row['Inventory']) . '</td>
            <td>' . htmlspecialchars($row['Status'] ?? 'N/A') . '</td>
            <td>' . $expiration . '</td>
            <td>' . htmlspecialchars($row['Supplier_ID'] ?? 'N/A') . '</td>
        </tr>';
    }

} else {

    $html .= '
    <tr>
        <td colspan="9" align="center">
            No inventory records found.
        </td>
    </tr>';
}

$html .= '</table>';

$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();

$pdf->Output('inventory_summary_report.pdf', 'I');
?>