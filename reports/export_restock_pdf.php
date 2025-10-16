<?php
ob_start();
include('../connect.php');
include('report_functions.php');
require_once __DIR__ . '/../tcpdf/tcpdf.php';

$type = $_GET['type'] ?? 'daily';
$condition = getDateCondition($type, 'OrderDate');

$query = "SELECT 
            r.Orestock_ID, 
            p.ProductName, 
            s.SupplierName, 
            r.Quantity, 
            r.Date_Received, 
            r.TotalReceived,
            r.Status,
            r.ExpirationDate
          FROM restock r
          LEFT JOIN product p ON r.Product_ID = p.Product_ID
          LEFT JOIN supplier s ON r.Supplier_ID = s.Supplier_ID
          WHERE $condition 
            AND LOWER(r.Status) != 'received'
          ORDER BY r.OrderDate DESC";

$result = $conn->query($query);

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, strtoupper($type) . " RESTOCK REPORT (INCOMING PRODUCT)", 0, 1, 'C');
$pdf->Ln(6);

$pdf->SetFont('helvetica', '', 11);
$html = '<table border="1" cellpadding="6">
<tr style="background-color:#f2f2f2;">
<th>ID</th><th>Product</th><th>Supplier</th><th>Ordered Qty</th>
<th>Received Qty</th><th>Status</th><th>Expiration</th>
</tr>';

while ($row = $result->fetch_assoc()) {
    $expiration = !empty($row['ExpirationDate']) ? date('Y-m-d', strtotime($row['ExpirationDate'])) : '—';
    $received = $row['TotalReceived'] ?? 0;
    $status = htmlspecialchars($row['Status']);

    $color = '';
    if (strtolower($status) === 'out-for-delivery') $color = 'color:orange;';
    elseif (strtolower($status) === 'cancelled') $color = 'color:red;';
    elseif (strtolower($status) === 'requested') $color = 'color:blue;';

    $html .= "<tr>
        <td>{$row['Orestock_ID']}</td>
        <td>{$row['ProductName']}</td>
        <td>{$row['SupplierName']}</td>
        <td>{$row['Quantity']}</td>
        <td>{$received}</td>
        <td style='{$color} font-weight:bold;'>{$status}</td>
        <td>{$expiration}</td>
    </tr>";
}
$html .= '</table>';

$pdf->writeHTML($html);
ob_end_clean();
$pdf->Output("restock_except_received_{$type}.pdf", 'I');
?>
