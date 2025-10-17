<?php
include 'connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

function inverseNormalCDF($p) {
    if ($p < 0 || $p > 1) {
        throw new Exception("Probability out of range.");
    }
    if ($p == 0.5) return 0;

    $a1 = -39.6968302866538; $a2 = 220.946098424521; $a3 = -275.928510446969;
    $a4 = 138.357751867269; $a5 = -30.6647980661472; $a6 = 2.50662827745924;
    $b1 = -54.4760987982241; $b2 = 161.585836858041; $b3 = -155.698979859887;
    $b4 = 66.8013118877197; $b5 = -13.2806815528857;
    $c1 = -7.78489400243029E-03; $c2 = -0.322396458041136;
    $c3 = -2.40075827716184; $c4 = -2.54973253934373;
    $c5 = 4.37466414146497; $c6 = 2.93816398269878;
    $d1 = 7.78469570904146E-03; $d2 = 0.32246712907004;
    $d3 = 2.445134137143; $d4 = 3.75440866190742;

    $p_low = 0.02425;
    $p_high = 1 - $p_low;

    if ($p < $p_low) {
        $q = sqrt(-2 * log($p));
        return ((((( $c1 * $q + $c2 ) * $q + $c3 ) * $q + $c4 ) * $q + $c5 ) * $q + $c6 ) /
               (((( $d1 * $q + $d2 ) * $q + $d3 ) * $q + $d4 ) * $q + 1 );
    } elseif ($p <= $p_high) {
        $q = $p - 0.5;
        $r = $q * $q;
        return ((((( $a1 * $r + $a2 ) * $r + $a3 ) * $r + $a4 ) * $r + $a5 ) * $r + $a6 ) * $q /
               ((((( $b1 * $r + $b2 ) * $r + $b3 ) * $r + $b4 ) * $r + $b5 ) * $r + 1 );
    } else {
        $q = sqrt(-2 * log(1 - $p));
        return -((((( $c1 * $q + $c2 ) * $q + $c3 ) * $q + $c4 ) * $q + $c5 ) * $q + $c6 ) /
               (((( $d1 * $q + $d2 ) * $q + $d3 ) * $q + $d4 ) * $q + 1 );
    }
}

$type = $_GET['type'] ?? 'daily';
switch ($type) {
    case 'weekly':
        $view = 'v_store_sales_forecast_weekly';
        break;
    case 'monthly':
        $view = 'v_store_sales_forecast_monthly';
        break;
    default:
        $view = 'v_store_sales_forecast_daily';
        break;
}

$query = "
    SELECT 
        AVG(TotalQuantity) AS mean, 
        STDDEV(TotalQuantity) AS stddev, 
        COUNT(*) AS n 
    FROM $view
";

$result = $conn->query($query);

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit;
}

$row = $result->fetch_assoc();

if (!$row || !$row['n'] || $row['n'] == 0) {
    echo json_encode(['error' => 'No data found in view']);
    exit;
}

$mean = (float)$row['mean'];
$stddev = (float)$row['stddev'];
$n = (int)$row['n'];

if ($n <= 1 || $stddev == 0) {
    echo json_encode(['error' => 'Not enough data to compute CI']);
    exit;
}

if ($n <= 10) {
    $confidenceLevel = 99;
} elseif ($n <= 30) {
    $confidenceLevel = 95;
} else {
    $confidenceLevel = 90;
}

$p = 0.5 + ($confidenceLevel / 100) / 2;
$z = round(inverseNormalCDF($p), 3);

$se = $stddev / sqrt($n);
$margin = $z * $se;
$lower = $mean - $margin;
$upper = $mean + $margin;

$response = [
    'type' => $type,
    'mean' => round($mean, 2),
    'stdDev' => round($stddev, 2),
    'n' => $n,
    'confidenceLevel' => $confidenceLevel,
    'zValue' => $z,
    'interval' => round($margin, 2),
    'lower' => round($lower, 2),
    'upper' => round($upper, 2)
];

echo json_encode($response);
?>
