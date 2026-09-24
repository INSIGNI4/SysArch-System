<?php

header('Content-Type: application/json');

require_once __DIR__ . '/connect.php';


/*
|--------------------------------------------------------------------------
| CURRENT AI FORECAST CYCLE
|--------------------------------------------------------------------------
|
| We use the current future predictions as the active forecast cycle.
|
*/

$sql = "

    SELECT
        p.Product_ID,
        p.ProductName,
        p.Supplier_ID,
        CONCAT_WS(
            ' ',
            s.SupplierFName,
            s.SupplierLName
        ) AS SupplierName,

        s.Location AS SupplierLocation,
        
        p.SupplierPrice,
        p.Pack_Size,

        COALESCE(inv.CurrentStock, 0) AS CurrentStock,

        ROUND(
            SUM(mp.Predicted_Demand),
            2
        ) AS PredictedDemand


    FROM point_of_sale.ml_predictions mp


    INNER JOIN login.product p
        ON p.Product_ID = mp.Product_ID


    LEFT JOIN login.supplier s
        ON s.Supplier_ID = p.Supplier_ID


    /*
    |--------------------------------------------------------------------------
    | CURRENT PHYSICAL STOCK
    |--------------------------------------------------------------------------
    */

    LEFT JOIN (

        SELECT
            Product_ID,
            SUM(Quantity) AS CurrentStock

        FROM login.expiration

        GROUP BY Product_ID

    ) inv

        ON inv.Product_ID = p.Product_ID


    /*
    |--------------------------------------------------------------------------
    | CURRENT FORECAST CYCLE
    |--------------------------------------------------------------------------
    */

    CROSS JOIN (

        SELECT

            MIN(ForecastDate) AS CycleStart,
            MAX(ForecastDate) AS CycleEnd

        FROM point_of_sale.ml_predictions

        WHERE
            ForecastDate > CURDATE()

            AND ForecastDate
                <= DATE_ADD(
                    CURDATE(),
                    INTERVAL 14 DAY
                )

    ) cycle


    /*
    |--------------------------------------------------------------------------
    | CURRENT 14-DAY FORECAST
    |--------------------------------------------------------------------------
    */

    WHERE

        mp.ForecastDate > CURDATE()

        AND mp.ForecastDate
            <= DATE_ADD(
                CURDATE(),
                INTERVAL 14 DAY
            )


        /*
        |--------------------------------------------------------------------------
        | DO NOT SHOW A PRODUCT THAT WAS ALREADY HANDLED
        | BY AN AI RECOMMENDATION FOR THIS CYCLE
        |--------------------------------------------------------------------------
        */

        AND NOT EXISTS (

            SELECT 1

            FROM login.item_to_order i

            INNER JOIN login.list_to_order l

                ON l.ListToOrder_ID =
                   i.ListToOrder_ID


            WHERE

                i.Product_ID =
                    p.Product_ID


                /*
                | Only AI-generated orders
                */

                AND l.Created_By =
                    'AI Recommendation'


                /*
                | Cancelled orders do NOT count as handled
                */

                AND l.Order_Status <>
                    'Cancelled'


                /*
                | The AI order belongs to
                | this forecast cycle
                */

                AND l.Forecast_Start_Date
                    <= cycle.CycleStart

                AND l.Forecast_End_Date
                    >= cycle.CycleEnd

        )


    GROUP BY
        p.Product_ID,
        p.ProductName,
        p.Supplier_ID,
        s.SupplierFName,
        s.SupplierLName,
        s.Location,
        p.SupplierPrice,
        p.Pack_Size,
        inv.CurrentStock


    /*
    |--------------------------------------------------------------------------
    | ONLY PRODUCTS WITH A SHORTAGE
    |--------------------------------------------------------------------------
    */

    HAVING

        PredictedDemand >
        COALESCE(
            inv.CurrentStock,
            0
        )


    ORDER BY

        (
            PredictedDemand
            - COALESCE(
                inv.CurrentStock,
                0
            )
        ) DESC

";


$result = mysqli_query(
    $conn,
    $sql
);


if (!$result) {

    echo json_encode([

        'success' => false,

        'error' => mysqli_error($conn)

    ]);

    exit;

}


$data = [];


while (
    $row =
        mysqli_fetch_assoc($result)
) {

    $currentStock =
        (float) $row['CurrentStock'];


    $predictedDemand =
        (float) $row['PredictedDemand'];


    /*
    |--------------------------------------------------------------------------
    | NET DEMAND
    |--------------------------------------------------------------------------
    */

    $netDemand =
        $predictedDemand
        - $currentStock;


    /*
    |--------------------------------------------------------------------------
    | PACK SIZE
    |--------------------------------------------------------------------------
    */

    $packSize =
        (int) $row['Pack_Size'];


    if ($packSize <= 0) {

        $packSize = 1;

    }


    /*
    |--------------------------------------------------------------------------
    | AI RECOMMENDED QUANTITY
    |--------------------------------------------------------------------------
    */

    $recommendedQuantity =

        ceil(
            $netDemand /
            $packSize
        )
        * $packSize;


    /*
    |--------------------------------------------------------------------------
    | RETURN DATA
    |--------------------------------------------------------------------------
    */

    $data[] = [

        'productId' =>
            (int)
            $row['Product_ID'],


        'productName' =>
            $row['ProductName'],


        'supplierId' =>

            $row['Supplier_ID'] !== null

                ? (int)
                    $row['Supplier_ID']

                : null,


        'supplierName' =>
            $row['SupplierFName']
            ?? 'No Supplier',


        'supplierLocation' =>
            $row['SupplierLocation']
            ?? '',


        'currentStock' =>
            $currentStock,


        'predictedDemand' =>
            $predictedDemand,


        'netDemand' =>
            round(
                $netDemand,
                2
            ),


        'packSize' =>
            $packSize,


        'recommendedQuantity' =>
            (int)
            $recommendedQuantity,


        /*
        |--------------------------------------------------------------------------
        | MANAGER STARTS WITH AI'S RECOMMENDATION
        |--------------------------------------------------------------------------
        */

        'orderedQuantity' =>
            (int)
            $recommendedQuantity,


        'unitCost' =>
            (float)
            (
                $row['SupplierPrice']
                ?? 0
            )

    ];

}


echo json_encode([

    'success' =>
        true,

    'data' =>
        $data

]);


?>