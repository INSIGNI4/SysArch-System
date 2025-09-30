<?php
session_start();

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     echo "<pre>";
//     print_r($_POST);
//     echo "</pre>";
//     exit;
// }

include('table_colums.php');
include('connect.php'); // ✅ MySQLi connection

// ✅ Get table name directly from POST instead of session
if (!isset($_POST['table']) || empty($_POST['table'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Error: Table name is missing!'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$table_name = $_POST['table'];

// ✅ Check if table mapping exists
if (!isset($table_colums_mapping[$table_name])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "Error: No column mapping found for table '$table_name'."
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$columns = $table_colums_mapping[$table_name];

// ✅ Collect data dynamically based on mapping
$db_arr = [];
foreach ($columns as $col) {
    if (isset($_POST[$col])) {
        $db_arr[$col] = $_POST[$col];
    }
}



if (in_array("Image", $columns) && isset($_FILES['Image']) && !empty($_FILES['Image']['name'])) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

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


// PROFF OF TRANSACTION STARTS HERE
$columns = $table_colums_mapping[$table_name];

// ✅ Collect data dynamically based on mapping
$db_arr = [];
foreach ($columns as $col) {
    if (isset($_POST[$col])) {
        $db_arr[$col] = $_POST[$col];
    }
}




if (in_array("ProofOfTransaction", $columns) && isset($_FILES['ProofOfTransaction']) && !empty($_FILES['ProofOfTransaction']['name'])) {
    $uploadDir = "proofs/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = uniqid() . "_" . basename($_FILES['ProofOfTransaction']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['ProofOfTransaction']['tmp_name'], $targetFile)) {
        $db_arr['ProofOfTransaction'] = $fileName;
    } else {
        $_SESSION['response'] = [
            'success' => false,
            'message' => "Failed to upload image"
        ];
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// PROFF OF TRANSACTION ENDS HERE






// ✅ Stop if no data to insert
if (empty($db_arr)) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'No data provided to insert!'
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// ✅ Build SQL dynamically
$table_properties = implode(", ", array_keys($db_arr));
$placeholders = implode(", ", array_fill(0, count($db_arr), "?"));
$sql = "INSERT INTO `$table_name` ($table_properties) VALUES ($placeholders)";

// ✅ Prepare statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "SQL Prepare failed: " . $conn->error
    ];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// ✅ Bind parameters dynamically
$types = str_repeat("s", count($db_arr));
$stmt->bind_param($types, ...array_values($db_arr));

// ✅ Execute and set response
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

// ✅ Redirect back
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;


?>


