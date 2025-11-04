<?php
session_start();
header('Content-Type: application/json');

$email = $_SESSION['email'] ?? null;
if (!$email) {
    echo json_encode(['success' => false, 'msg' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['id'] ?? null;

if (!$productId) {
    echo json_encode(['success' => false, 'msg' => 'Missing product ID']);
    exit;
}

// ✅ Unified directory and filename
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);

$userFile = $dataDir . '/' . md5($email) . '_read.json';
$readList = file_exists($userFile)
    ? json_decode(file_get_contents($userFile), true)
    : [];

if (!in_array($productId, $readList)) $readList[] = $productId;

file_put_contents($userFile, json_encode($readList));
echo json_encode(['success' => true]);
?>
