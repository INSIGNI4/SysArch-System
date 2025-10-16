<?php
ob_start();

include('../connect.php');
require_once __DIR__ . '/../tcpdf/tcpdf.php';

$type = $_GET['type'] ?? 'daily';

$today = date('Y-m-d');

$query = "
    SELECT DISTINCT
        r.Orestock_ID,
        r.Product_ID,
        p.ProductName,
        p.Type,
        p.StorePrice,
        r.Quantity,
        r.ExpirationDate,
        r.Date_Received
    FROM restock r
    INNER JOIN product p ON r.Product_ID = p.Product_ID
    WHERE r.Status = 'Received'
      AND DATE(r.Date_Received) = '$today'
    ORDER BY r.Date_Received DESC
";

$result = $conn->query($query);

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, strtoupper($type) . " NEWLY ADDED PRODUCTS REPORT", 0, 1, 'C');
$pdf->Ln(6);

$pdf->SetFont('helvetica', '', 11);
$html = '<table border="1" cellpadding="6">
<tr style="background-color:#f2f2f2;">
<th>ID</th>
<th>Product Name</th>
<th>Type</th>
<th>Quantity</th>
<th>Store Price</th>
<th>Expiration Date</th>
<th>Date Received</th>
</tr>';

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
            <td>'.$row['Product_ID'].'</td>
            <td>'.$row['ProductName'].'</td>
            <td>'.$row['Type'].'</td>
            <td>'.$row['Quantity'].'</td>
            <td>'.number_format($row['StorePrice'], 2).'</td>
            <td>'.($row['ExpirationDate'] ?? '—').'</td>
            <td>'.$row['Date_Received'].'</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="7" align="center">No products received today.</td></tr>';
}

$html .= '</table>';

$pdf->writeHTML($html);

ob_end_clean();
$pdf->Output("newproducts_{$type}.pdf", 'I');
?>
