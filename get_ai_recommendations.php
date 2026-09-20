<?php

header('Content-Type: application/json');

require_once __DIR__ . '/connect.php';

/*
|--------------------------------------------------------------------------
| GET AI RECOMMENDATIONS
|--------------------------------------------------------------------------
| Uses:
|   ml_predictions  -> 14-day predicted demand
|   expiration      -> current batch inventory
|   product         -> product + current supplier + price + pack size
|   supplier        -> supplier name + location
|
| The result is used by ai_recommendation.js
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        p.Product_ID,
        p.ProductName,

        p.Supplier_ID,
        s.SupplierName,
        s.Location AS SupplierLocation,

        p.SupplierPrice,
        p.Pack_Size,

        COALESCE(inv.CurrentStock, 0) AS CurrentStock,

        ROUND(SUM(mp.Predicted_Demand), 2) AS PredictedDemand

    FROM point_of_sale.ml_predictions mp

    INNER JOIN login.product p
        ON p.Product_ID = mp.Product_ID

    LEFT JOIN login.supplier s
        ON s.Supplier_ID = p.Supplier_ID

    LEFT JOIN (
        SELECT
            Product_ID,
            SUM(Quantity) AS CurrentStock
        FROM login.expiration
        GROUP BY Product_ID
    ) inv
        ON inv.Product_ID = p.Product_ID

    WHERE
        mp.ForecastDate > CURDATE()
        AND mp.ForecastDate <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)

    GROUP BY
        p.Product_ID,
        p.ProductName,
        p.Supplier_ID,
        s.SupplierName,
        s.Location,
        p.SupplierPrice,
        p.Pack_Size,
        inv.CurrentStock

    HAVING
        PredictedDemand > COALESCE(inv.CurrentStock, 0)

    ORDER BY
        (PredictedDemand - COALESCE(inv.CurrentStock, 0)) DESC
";


$result = mysqli_query($conn, $sql);

if (!$result) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => mysqli_error($conn)
    ]);

    exit;
}


$data = [];


while ($row = mysqli_fetch_assoc($result)) {

    $currentStock = (float) $row['CurrentStock'];

    $predictedDemand = (float) $row['PredictedDemand'];

    $netDemand = $predictedDemand - $currentStock;


    /*
    |--------------------------------------------------------------------------
    | PACK SIZE
    |--------------------------------------------------------------------------
    */

    $packSize = (int) $row['Pack_Size'];

    if ($packSize <= 0) {
        $packSize = 1;
    }


    /*
    |--------------------------------------------------------------------------
    | RECOMMENDED QUANTITY
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | Net demand = 196
    | Pack size  = 12
    |
    | 196 / 12 = 16.33
    | ceil      = 17 packs
    | 17 * 12   = 204 units
    |
    */

    $recommendedQuantity =
        ceil($netDemand / $packSize) * $packSize;


    $data[] = [

        'productId' => (int) $row['Product_ID'],

        'productName' => $row['ProductName'],

        'supplierId' => $row['Supplier_ID'] !== null
            ? (int) $row['Supplier_ID']
            : null,

        'supplierName' => $row['SupplierName'] ?? 'No Supplier',

        'supplierLocation' => $row['SupplierLocation'] ?? '',

        'currentStock' => $currentStock,

        'predictedDemand' => $predictedDemand,

        'netDemand' => round($netDemand, 2),

        'packSize' => $packSize,

        'recommendedQuantity' => (int) $recommendedQuantity,

        'orderedQuantity' => (int) $recommendedQuantity,

        'unitCost' => (float) ($row['SupplierPrice'] ?? 0)

    ];
}


echo json_encode([
    'success' => true,
    'data' => $data
]);

?>