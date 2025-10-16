<?php
ob_start();
include('../connect.php');
include('report_functions.php');
require_once __DIR__ . '/../tcpdf/tcpdf.php';

$type = $_GET['type'] ?? 'daily';

$condition = getDateCondition($type, 'ReturnedDate');

$query = "
    SELECT 
        cr.CReturn_ID,
        cr.ReferenceNo,
        cr.Quantity,
        cr.ReasonForReturn,
        cr.ReturnedDate,
        p.ProductName,
        p.Type,
        p.StorePrice
    FROM customersreturns AS cr
    INNER JOIN product AS p ON cr.Product_ID = p.Product_ID
    WHERE $condition
    ORDER BY cr.ReturnedDate DESC
";

$result = $conn->query($query);

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, strtoupper($type) . " RETURNED ITEMS REPORT", 0, 1, 'C');
$pdf->Ln(6);

$pdf->SetFont('helvetica', '', 11);
$html = '<table border="1" cellpadding="6">
<tr style="background-color:#f2f2f2;">
<th>Return ID</th>
<th>Reference No</th>
<th>Product Name</th>
<th>Type</th>
<th>Quantity</th>
<th>Store Price</th>
<th>Reason</th>
<th>Date Returned</th>
</tr>';

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>'.$row['CReturn_ID'].'</td>
            <td>'.$row['ReferenceNo'].'</td>
            <td>'.$row['ProductName'].'</td>
            <td>'.$row['Type'].'</td>
            <td>'.$row['Quantity'].'</td>
            <td>'.number_format($row['StorePrice'], 2).'</td>
            <td>'.$row['ReasonForReturn'].'</td>
            <td>'.$row['ReturnedDate'].'</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="8" align="center">No returned items found for this period.</td></tr>';
}

$html .= '</table>';

$pdf->writeHTML($html);
ob_end_clean();
$pdf->Output("returns_{$type}.pdf", 'I');
?>
