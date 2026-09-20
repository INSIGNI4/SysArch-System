<?php

$python = "python";
$script = __DIR__ . "/ml/demand_prediction.py";

$command = $python . " " . escapeshellarg($script) . " 2>&1";

$output = shell_exec($command);

header('Content-Type: application/json');

echo json_encode([
    "success" => true,
    "output" => $output
]);
?>
