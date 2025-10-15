<?php
include('connect.php');

//  FETCH ALL PRODUCTS
$products = [];
$productQuery = "SELECT Product_ID FROM product";
$productResult = $conn->query($productQuery);
if ($productResult && $productResult->num_rows > 0) {
    while ($row = $productResult->fetch_assoc()) {
        $products[] = $row['Product_ID'];
    }
}

// Confidence Interval Function
function calculateConfidenceInterval($conn, $productId, $groupType, $limit = 3, $confidenceLevel = 0.95) {
    $where = $productId ? "WHERE Product_ID = '$productId'" : "";

    $query = "
        SELECT SUM(Quantity) AS groupQty
        FROM sales
        $where
        GROUP BY $groupType(SalesDate)
        ORDER BY $groupType(SalesDate) DESC
        LIMIT $limit
    ";
    $result = $conn->query($query);

    $values = [];
    while ($row = $result->fetch_assoc()) {
        $values[] = $row['groupQty'];
    }

    $n = count($values);
    if ($n < 2) {
        return [
            'mean' => 0,
            'margin' => 0,
            'lower' => 0,
            'upper' => 0,
            'confidenceLevel' => round($confidenceLevel * 100, 1) // 95.0
        ];
    }

    $mean = array_sum($values) / $n;

    $variance = 0;
    foreach ($values as $v) {
        $variance += pow($v - $mean, 2);
    }
    $stdDev = sqrt($variance / ($n - 1));

    $zValues = [0.90 => 1.645, 0.95 => 1.96, 0.99 => 2.576];
    $z = $zValues[$confidenceLevel] ?? 1.96;

    $margin = $z * ($stdDev / sqrt($n));

    // Calculate lower and upper bounds
    $lower = $mean - $margin;
    $upper = $mean + $margin;

    return [
        'mean' => round($mean, 2),
        'margin' => round($margin, 2),
        'lower' => round($lower, 2),
        'upper' => round($upper, 2),
        'confidenceLevel' => round($confidenceLevel * 100, 1) // decimal
    ];
}

//  Insert or Update Forecast
function insertForecast($conn, $type, $productId, $period, $start, $end, $projected, $confidence) {
    $dbProductId = ($productId === 0 || $productId === null) ? NULL : $productId;

    $query = "
        INSERT INTO forecast (ForecastType, Product_ID, ForecastPeriod, ForecastStart, ForecastEnd, ProjectedSales, ConfidenceLevel)
        VALUES ('$type', " . ($dbProductId === NULL ? "NULL" : "'$dbProductId'") . ", '$period', '$start', '$end', '$projected', '$confidence')
        ON DUPLICATE KEY UPDATE 
            ProjectedSales = VALUES(ProjectedSales),
            ConfidenceLevel = VALUES(ConfidenceLevel),
            ForecastStart = VALUES(ForecastStart),
            ForecastEnd = VALUES(ForecastEnd);
    ";
    $conn->query($query);
}


//  DAILY FORECAST (Last 3 Days)
foreach ($products as $productId) {
    $avgQuery = "
        SELECT AVG(DailySales) AS MovingAvg
        FROM (
            SELECT SUM(Quantity) AS DailySales
            FROM sales
            WHERE Product_ID = '$productId'
            GROUP BY DATE(SalesDate)
            ORDER BY DATE(SalesDate) DESC
            LIMIT 3
        ) AS sub
    ";
    $avgResult = $conn->query($avgQuery);
    $avgRow = $avgResult->fetch_assoc();
    $projectedSales = $avgRow['MovingAvg'] ?? 0;

    $interval = calculateConfidenceInterval($conn, $productId, 'DATE', 3);
    $confidence = $interval['confidenceLevel'];

    insertForecast($conn, 'SMA (3 DAYS)', $productId, 'Daily', date('Y-m-d'), date('Y-m-d', strtotime('+1 day')), $projectedSales, $confidence);
}

// DAILY FORECAST FOR ALL PRODUCTS
$avgQuery = "
    SELECT AVG(DailySales) AS MovingAvg
    FROM (
        SELECT SUM(Quantity) AS DailySales
        FROM sales
        GROUP BY DATE(SalesDate)
        ORDER BY DATE(SalesDate) DESC
        LIMIT 3
    ) AS sub
";
$avgResult = $conn->query($avgQuery);
$avgRow = $avgResult->fetch_assoc();
$projectedSales = $avgRow['MovingAvg'] ?? 0;

$interval = calculateConfidenceInterval($conn, null, 'DATE', 3);
$confidence = $interval['confidenceLevel'];

insertForecast($conn, 'SMA (3 DAYS)', 0, 'Daily', date('Y-m-d'), date('Y-m-d', strtotime('+1 day')), $projectedSales, $confidence);

//  WEEKLY FORECAST (Last 3 Weeks)
foreach ($products as $productId) {
    $avgQuery = "
        SELECT AVG(WeeklySales) AS MovingAvg
        FROM (
            SELECT SUM(Quantity) AS WeeklySales
            FROM sales
            WHERE Product_ID = '$productId'
            GROUP BY YEARWEEK(SalesDate)
            ORDER BY YEARWEEK(SalesDate) DESC
            LIMIT 3
        ) AS sub
    ";
    $avgResult = $conn->query($avgQuery);
    $avgRow = $avgResult->fetch_assoc();
    $projectedSales = $avgRow['MovingAvg'] ?? 0;

    $interval = calculateConfidenceInterval($conn, $productId, 'YEARWEEK', 3);
    $confidence = $interval['confidenceLevel'];

    insertForecast($conn, 'SMA (3 WEEKS)', $productId, 'Weekly', date('Y-m-d'), date('Y-m-d', strtotime('+7 days')), $projectedSales, $confidence);
}

// WEEKLY FORECAST FOR ALL PRODUCTS
$avgQuery = "
    SELECT AVG(WeeklySales) AS MovingAvg
    FROM (
        SELECT SUM(Quantity) AS WeeklySales
        FROM sales
        GROUP BY YEARWEEK(SalesDate)
        ORDER BY YEARWEEK(SalesDate) DESC
        LIMIT 3
    ) AS sub
";
$avgResult = $conn->query($avgQuery);
$avgRow = $avgResult->fetch_assoc();
$projectedSales = $avgRow['MovingAvg'] ?? 0;

$interval = calculateConfidenceInterval($conn, null, 'YEARWEEK', 3);
$confidence = $interval['confidenceLevel'];

insertForecast($conn, 'SMA (3 WEEKS)', 0, 'Weekly', date('Y-m-d'), date('Y-m-d', strtotime('+7 days')), $projectedSales, $confidence);

// MONTHLY FORECAST (Last 3 Months)
foreach ($products as $productId) {
    $avgQuery = "
        SELECT AVG(MonthlySales) AS MovingAvg
        FROM (
            SELECT SUM(Quantity) AS MonthlySales
            FROM sales
            WHERE Product_ID = '$productId'
            GROUP BY YEAR(SalesDate), MONTH(SalesDate)
            ORDER BY YEAR(SalesDate) DESC, MONTH(SalesDate) DESC
            LIMIT 3
        ) AS sub
    ";
    $avgResult = $conn->query($avgQuery);
    $avgRow = $avgResult->fetch_assoc();
    $projectedSales = $avgRow['MovingAvg'] ?? 0;

    $interval = calculateConfidenceInterval($conn, $productId, 'MONTH', 3);
    $confidence = $interval['confidenceLevel'];

    insertForecast($conn, 'SMA (3MONTHS)', $productId, 'Monthly', date('Y-m-d'), date('Y-m-d', strtotime('+1 month')), $projectedSales, $confidence);
}

// MONTHLY FORECAST FOR ALL PRODUCTS
$avgQuery = "
    SELECT AVG(MonthlySales) AS MovingAvg
    FROM (
        SELECT SUM(Quantity) AS MonthlySales
        FROM sales
        GROUP BY YEAR(SalesDate), MONTH(SalesDate)
        ORDER BY YEAR(SalesDate) DESC, MONTH(SalesDate) DESC
        LIMIT 3
    ) AS sub
";
$avgResult = $conn->query($avgQuery);
$avgRow = $avgResult->fetch_assoc();
$projectedSales = $avgRow['MovingAvg'] ?? 0;

$interval = calculateConfidenceInterval($conn, null, 'MONTH', 3);
$confidence = $interval['confidenceLevel'];

insertForecast($conn, 'SMA (MONTHS)', 0, 'Monthly', date('Y-m-d'), date('Y-m-d', strtotime('+1 month')), $projectedSales, $confidence);
?>
