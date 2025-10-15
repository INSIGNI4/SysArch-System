<?php
include 'connect.php';

$agg = $_GET['agg'] ?? 'daily';
$viewName = match($agg) {
    'weekly' => 'v_sales_total_weekly', // change view
    'monthly' => 'v_sales_total_monthly', // change view
    default => 'v_sales_total_daily' // change view
};

$sql = "SELECT * FROM $viewName ORDER BY 1";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data); // JS will fetch this
?>
