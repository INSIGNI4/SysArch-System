<?php
ob_start();
include('../connect.php');
include('report_functions.php');
require_once __DIR__ . '/../tcpdf/tcpdf.php';

$type = $_GET['type'] ?? 'daily';
$condition = getDateCondition($type, 'SalesDate');

$query = "SELECT DATE(SalesDate) as DateSold, SUM(TotalPrice) AS TotalSales, SUM(Quantity) AS TotalQty
          FROM sales WHERE $condition GROUP BY DATE(SalesDate)";
$result = $conn->query($query);

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, strtoupper($type) . " SALES REPORT", 0, 1, 'C');
$pdf->Ln(6);

$pdf->SetFont('helvetica', '', 11);
$html = '<table border="1" cellpadding="6">
<tr style="background-color:#f2f2f2;">
<th>Date</th><th>Total Quantity Sold</th><th>Total Sales (₱)</th>
</tr>';

$totalAll = 0;
while ($row = $result->fetch_assoc()) {
    $totalAll += $row['TotalSales'];
    $html .= '<tr>
        <td>'.$row['DateSold'].'</td>
        <td>'.$row['TotalQty'].'</td>
        <td>'.number_format($row['TotalSales'], 2).'</td>
    </tr>';
}
$html .= '<tr style="font-weight:bold;background-color:#f9f9f9;">
<td colspan="2" align="right">TOTAL:</td>
<td>₱'.number_format($totalAll,2).'</td>
</tr>';
$html .= '</table>';

$pdf->writeHTML($html);
ob_end_clean();
$pdf->Output("sales_{$type}.pdf", 'I');
?>
