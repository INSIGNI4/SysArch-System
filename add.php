<?php
session_start();
include('table_colums.php');
include('connect.php'); // MySQLi connection

// Get logged-in user ID
$email = $_SESSION['email'] ?? null;
if (!$email) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'You must be logged in to add data.'
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
$id = $user['id'] ?? null;

if (!$id) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'User not found.'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Check table name
if (!isset($_POST['table']) || empty($_POST['table'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Error: Table name is missing!'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$table_name = $_POST['table'];

// Check if table mapping exists
if (!isset($table_colums_mapping[$table_name])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "Error: No column mapping found for table '$table_name'."
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$columns = $table_colums_mapping[$table_name];

// Collect POST data dynamically
$db_arr = [];
foreach ($columns as $col) {
    if (isset($_POST[$col])) {
        $db_arr[$col] = $_POST[$col];
    }
}

// Assign logged-in user for sales table
if ($table_name === 'sales') {
    $db_arr['id'] = $id;
}

// Handle image upload if column exists
if (in_array("Image", $columns) && isset($_FILES['Image']) && !empty($_FILES['Image']['name'])) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

    $fileName = uniqid() . "_" . basename($_FILES['Image']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['Image']['tmp_name'], $targetFile)) {
        $db_arr['Image'] = $fileName;
    } else {
        $_SESSION['response'] = [
            'success' => false,
            'message' => "Failed to upload image"
        ];
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// Stop if no data to insert
if (empty($db_arr)) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'No data provided to insert!'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Build SQL dynamically
$table_properties = implode(", ", array_keys($db_arr));
$placeholders = implode(", ", array_fill(0, count($db_arr), "?"));
$sql = "INSERT INTO `$table_name` ($table_properties) VALUES ($placeholders)";

// Prepare statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "SQL Prepare failed: " . $conn->error
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Bind parameters dynamically (all strings for simplicity)
$types = str_repeat("s", count($db_arr));
$stmt->bind_param($types, ...array_values($db_arr));

// Execute and set response
if ($stmt->execute()) {
    $tableLabel = ucfirst($table_name);
    $_SESSION['response'] = [
        'success' => true,
        'message' => "$tableLabel added successfully!"
    ];
} else {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Insert failed: ' . $stmt->error
    ];
}

$stmt->close();
$conn->close();

// Redirect back
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
