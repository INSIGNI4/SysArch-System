<?php
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
?>
