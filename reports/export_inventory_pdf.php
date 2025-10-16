<?php
ob_start();
include('../connect.php');
include('report_functions.php');
require_once __DIR__ . '/../tcpdf/tcpdf.php';



$query = "SELECT Product_ID, ProductName, Type, UnitSold, UnitsOrdered, ExpirationDate 
          FROM product ORDER BY Product_ID ASC";
$result = $conn->query($query);

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, "INVENTORY SUMMARY REPORT", 0, 1, 'C');
$pdf->Ln(6);

$pdf->SetFont('helvetica', '', 11);
$html = '<table border="1" cellpadding="6">
<tr style="background-color:#f2f2f2;">
<th>ID</th><th>Product Name</th><th>Type</th><th>Sold</th><th>In Stock</th><th>Expiration Date</th>
</tr>';

while ($row = $result->fetch_assoc()) {
    $stock = $row['UnitsOrdered'] - $row['UnitSold'];
    $expiration = !empty($row['ExpirationDate']) ? date('Y-m-d', strtotime($row['ExpirationDate'])) : '—';

    $html .= '<tr>
        <td>'.$row['Product_ID'].'</td>
        <td>'.$row['ProductName'].'</td>
        <td>'.$row['Type'].'</td>
        <td>'.$row['UnitSold'].'</td>
        <td>'.$stock.'</td>
        <td>'.$expiration.'</td>
    </tr>';
}
$html .= '</table>';

$pdf->writeHTML($html);

ob_end_clean();
$pdf->Output("inventory_summary.pdf", 'I');
?>
