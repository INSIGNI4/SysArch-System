<?php

ob_start();

include('../connect.php');

require_once __DIR__ . '/../tcpdf/tcpdf.php';

date_default_timezone_set('Asia/Manila');

$days = 14;

$reportTitle = 'NEXT 2 WEEKS DEMAND FORECAST & REORDERING RECOMMENDATION';

$firstForecastDate = date('Y-m-d', strtotime('tomorrow'));

$lastDate = date(
    'Y-m-d',
    strtotime($firstForecastDate . ' +13 days')
);

$predictionQuery = "
    SELECT
        Product_ID,
        ProductName,
        Category,
        SUM(Predicted_Demand) AS Predicted_Demand,
        MIN(ForecastDate) AS ForecastStart,
        MAX(ForecastDate) AS ForecastEnd
    FROM ml_predictions
    WHERE ForecastDate BETWEEN '$firstForecastDate' AND '$lastDate'
    GROUP BY
        Product_ID,
        ProductName,
        Category
    ORDER BY ProductName ASC
";

$predictionResult = mysqli_query($conn_pos, $predictionQuery);

$forecastData = [];

if ($predictionResult) {

    while ($row = mysqli_fetch_assoc($predictionResult)) {

        $productID = mysqli_real_escape_string(
            $conn,
            $row['Product_ID']
        );

        $inventoryQuery = "
            SELECT
                COALESCE(SUM(Inventory), 0) AS Current_Stock
            FROM inventory
            WHERE Product_ID = '$productID'
        ";

        $inventoryResult = mysqli_query($conn, $inventoryQuery);

        $currentStock = 0;

        if ($inventoryResult) {

            $inventoryRow = mysqli_fetch_assoc($inventoryResult);

            $currentStock = (float)$inventoryRow['Current_Stock'];
        }

        $packSize = 1;

        $packQuery = "
            SELECT Pack_Size
            FROM item_to_order
            WHERE Product_ID = '$productID'
            ORDER BY ItemToOrder_ID DESC
            LIMIT 1
        ";

        $packResult = mysqli_query($conn, $packQuery);

        if ($packResult && mysqli_num_rows($packResult) > 0) {

            $packRow = mysqli_fetch_assoc($packResult);

            if (
                !empty($packRow['Pack_Size']) &&
                $packRow['Pack_Size'] > 0
            ) {

                $packSize = (float)$packRow['Pack_Size'];
            }
        }

        $predictedDemand = (float)$row['Predicted_Demand'];

        $netDemand = max(
            0,
            $predictedDemand - $currentStock
        );

        if ($packSize > 1) {

            $recommendedOrder =
                ceil($netDemand / $packSize) * $packSize;

        } else {

            $recommendedOrder = ceil($netDemand);
        }

        if ($recommendedOrder > 0) {

            $status = 'Recommended';

        } else {

            $status = 'Stock Sufficient';
        }

        $row['Current_Stock'] = $currentStock;

        $row['Predicted_Demand'] = $predictedDemand;

        $row['Net_Demand'] = $netDemand;

        $row['Pack_Size'] = $packSize;

        $row['Recommended_Order_Quantity'] = $recommendedOrder;

        $row['Status'] = $status;

        $forecastData[] = $row;
    }
}

$totalProducts = count($forecastData);

$totalPredictedDemand = 0;

$totalRecommended = 0;

foreach ($forecastData as $row) {

    $totalPredictedDemand += $row['Predicted_Demand'];

    $totalRecommended += $row['Recommended_Order_Quantity'];
}

$pdf = new TCPDF();

$pdf->SetCreator(
    'Inventory and Sales Management System'
);

$pdf->SetAuthor(
    'Inventory and Sales Management System'
);

$pdf->SetTitle($reportTitle);

$pdf->AddPage('L');

$pdf->SetFont(
    'helvetica',
    'B',
    16
);

$pdf->Cell(
    0,
    10,
    $reportTitle,
    0,
    1,
    'C'
);

$pdf->SetFont(
    'helvetica',
    '',
    10
);

$startFormatted = date(
    'M d, Y',
    strtotime($firstForecastDate)
);

$endFormatted = date(
    'M d, Y',
    strtotime($lastDate)
);

$pdf->Cell(
    0,
    7,
    'Forecast Period: ' . $startFormatted . ' - ' . $endFormatted,
    0,
    1,
    'C'
);

$pdf->Ln(5);

$summary = '
<table border="1" cellpadding="6">

    <tr>

        <td width="33%">

            <b>Products Forecasted</b><br>

            ' . $totalProducts . '

        </td>

        <td width="33%">

            <b>Total Predicted Demand</b><br>

            ' . number_format(
                $totalPredictedDemand,
                2
            ) . '

        </td>

        <td width="34%">

            <b>Total Recommended Order</b><br>

            ' . number_format(
                $totalRecommended,
                2
            ) . '

        </td>

    </tr>

</table>
';

$pdf->writeHTML(
    $summary,
    true,
    false,
    true,
    false,
    ''
);

$pdf->Ln(5);

$html = '
<table border="1" cellpadding="4">

    <tr style="background-color:#f2f2f2;">

        <th width="8%">Product ID</th>

        <th width="20%">Product Name</th>

        <th width="11%">Category</th>

        <th width="13%">Predicted Demand</th>

        <th width="11%">Current Stock</th>

        <th width="10%">Net Demand</th>

        <th width="13%">Recommended Order</th>

        <th width="7%">Pack Size</th>

        <th width="7%">Status</th>

    </tr>
';

if (count($forecastData) > 0) {

    foreach ($forecastData as $row) {

        $html .= '

        <tr>

            <td>
                ' . htmlspecialchars(
                    $row['Product_ID']
                ) . '
            </td>

            <td>
                ' . htmlspecialchars(
                    $row['ProductName']
                ) . '
            </td>

            <td>
                ' . htmlspecialchars(
                    $row['Category']
                ) . '
            </td>

            <td>
                ' . number_format(
                    $row['Predicted_Demand'],
                    2
                ) . '
            </td>

            <td>
                ' . number_format(
                    $row['Current_Stock'],
                    0
                ) . '
            </td>

            <td>
                ' . number_format(
                    $row['Net_Demand'],
                    2
                ) . '
            </td>

            <td>
                ' . number_format(
                    $row['Recommended_Order_Quantity'],
                    0
                ) . '
            </td>

            <td>
                ' . number_format(
                    $row['Pack_Size'],
                    0
                ) . '
            </td>

            <td>
                ' . htmlspecialchars(
                    $row['Status']
                ) . '
            </td>

        </tr>';

    }

} else {

    $html .= '

    <tr>

        <td colspan="9" align="center">

            No demand predictions were found for the next 2 weeks.

        </td>

    </tr>';

}

$html .= '
</table>
';

$pdf->SetFont(
    'helvetica',
    '',
    8
);

$pdf->writeHTML(
    $html,
    true,
    false,
    true,
    false,
    ''
);

$pdf->Ln(5);

$pdf->SetFont(
    'helvetica',
    'I',
    8
);

$pdf->MultiCell(
    0,
    5,
    'The predicted demand is based on the machine learning forecast from tomorrow through the next 14 days. The recommended order quantity is calculated using predicted demand, current inventory, and available pack size.',
    0,
    'L'
);

ob_end_clean();

$fileName = 'demand_forecast_reorder_next_2_weeks.pdf';

$pdf->Output(
    $fileName,
    'I'
);

?>