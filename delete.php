<?php
session_start();
include('table_colums.php');
include('connect.php'); // MySQLi connection

// Get logged-in user email
$email = $_SESSION['email'] ?? null;
if (!$email) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to delete data.'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Fetch user ID from database
$query = $conn->prepare("SELECT id FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
$userId = $user['id'] ?? null;

if (!$userId) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'User not found.'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Check table
if (!isset($_POST['table']) || empty($_POST['table'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Error: Table name is missing!'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
$table_name = $_POST['table'];

// Check column mapping
if (!isset($table_colums_mapping[$table_name])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "Error: No column mapping found for table '$table_name'."
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$columns = $table_colums_mapping[$table_name];
$primaryKey = $columns[0];

// Check record ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Error: Missing record ID to delete!'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
$id = $_POST['id'];

// Get Image file before deleting
$imageFile = null;
if (in_array("Image", $columns)) {
    $imgQuery = $conn->prepare("SELECT `Image` FROM `$table_name` WHERE `$primaryKey` = ?");
    $paramType = ctype_digit((string)$id) ? "i" : "s";
    $imgQuery->bind_param($paramType, $id);
    $imgQuery->execute();
    $imgResult = $imgQuery->get_result();
    if ($imgResult && $row = $imgResult->fetch_assoc()) {
        $imageFile = $row['Image'];
    }
    $imgQuery->close();
}

// Optional: get a record name for logging
$name = null;
if (in_array("ProductName", $columns)) {
    $nameQuery = $conn->prepare("SELECT `ProductName` FROM `$table_name` WHERE `$primaryKey` = ?");
    $nameQuery->bind_param($paramType, $id);
    $nameQuery->execute();
    $nameResult = $nameQuery->get_result();
    if ($nameResult && $row = $nameResult->fetch_assoc()) {
        $name = $row['ProductName'];
    }
    $nameQuery->close();
}

// Delete record
$sql = "DELETE FROM `$table_name` WHERE `$primaryKey` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($paramType, $id);

if ($stmt->execute()) {
    if ($imageFile && file_exists("uploads/" . $imageFile)) {
        unlink("uploads/" . $imageFile);
    }

    // Track deleted items per user
    $logFile = 'deleted_log.json';
    $deletedItems = [];

    if (file_exists($logFile)) {
        $deletedItems = json_decode(file_get_contents($logFile), true) ?: [];
    }

    // Add current deletion
    $deletedItems[] = [
        'table' => $table_name,
        'id' => $id,
        'name' => $name,
        'deleted_by' => $userId,
        'deleted_at' => date("Y-m-d H:i:s")
    ];

    // Keep only last 10 deletions per user
    $userItems = array_filter($deletedItems, fn($item) => $item['deleted_by'] == $userId);
    $userItems = array_slice($userItems, -10, 10, true);
    $otherItems = array_filter($deletedItems, fn($item) => $item['deleted_by'] != $userId);

    $finalItems = array_merge($otherItems, $userItems);
    file_put_contents($logFile, json_encode(array_values($finalItems), JSON_PRETTY_PRINT));

    $_SESSION['response'] = [
        'success' => true,
        'message' => ucfirst($table_name) . " deleted successfully!"
    ];
} else {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Delete failed: ' . $stmt->error
    ];
}

$stmt->close();
$conn->close();

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>







<!-- <?php
session_start();
include('table_colums.php');
include('connect.php'); // MySQLi connection

// Check table
if (!isset($_POST['table']) || empty($_POST['table'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Error: Table name is missing!'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$table_name = $_POST['table'];

// Check column mapping
if (!isset($table_colums_mapping[$table_name])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "Error: No column mapping found for table '$table_name'."
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$columns = $table_colums_mapping[$table_name];
$primaryKey = $columns[0];

// Check if record ID is provided
if (!isset($_POST['id']) || empty($_POST['id'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Error: Missing record ID to delete!'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$id = $_POST['id'];


// getting the file before delete
$imageFile = null;
if (in_array("Image", $columns)) {
    $imgQuery = $conn->prepare("SELECT `Image` FROM `$table_name` WHERE `$primaryKey` = ?");
    $paramType = ctype_digit((string)$id) ? "i" : "s";
    $imgQuery->bind_param($paramType, $id);
    $imgQuery->execute();
    $imgResult = $imgQuery->get_result();
    if ($imgResult && $row = $imgResult->fetch_assoc()) {
        $imageFile = $row['Image'];
    }
    $imgQuery->close();
}

// Delete values
$sql = "DELETE FROM `$table_name` WHERE `$primaryKey` = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "SQL Prepare failed: " . $conn->error
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$stmt->bind_param($paramType, $id);

if ($stmt->execute()) {
    if ($imageFile && file_exists("uploads/" . $imageFile)) {
        unlink("uploads/" . $imageFile);
    }

    $_SESSION['response'] = [
        'success' => true,
        'message' => ucfirst($table_name) . " deleted successfully!"
    ];
} else {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Delete failed: ' . $stmt->error
    ];
}

$stmt->close();
$conn->close();

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?> -->
