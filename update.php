<?php
session_start();
include('table_colums.php');
include('connect.php');

// check table name
if (!isset($_POST['table']) || empty($_POST['table'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Error: No Table'
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

$table_name = $_POST['table'];

// check if mapping of column exist
if (!isset($table_colums_mapping[$table_name])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "Error: No column in table '$table_name'."
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

$columns = $table_colums_mapping[$table_name];
$primaryKey = $columns[0];

// check id if provided
if (!isset($_POST['id']) || empty($_POST['id'])) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => "Error: Missing ID"
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

$id = $_POST['id'];

// collect data
$update_arr = [];
foreach ($columns as $col) {
    if ($col !== $primaryKey) {
        if ($col === "ExpirationDate") {
            if (isset($_POST[$col]) && $col !== $primaryKey) {
                $update_arr[$col] = $_POST[$col];
            } else {
                $update_arr[$col] = null;
            }
        } else {
            if (isset($_POST[$col])) {
                $update_arr[$col] = $_POST[$col];
            }
        }
    }
}

if (in_array("Image", $columns) && isset($_FILES['Image']) && !empty($_FILES['Image']['name'])) {
    $stmtOld = $conn->prepare("SELECT `Image` FROM `$table_name` WHERE `$primaryKey` = ?");
    $paramType = ctype_digit((string)$id) ? "i" : "s";
    $stmtOld->bind_param($paramType, $id);
    $stmtOld->execute();
    $stmtOld->bind_result($oldImage);
    $stmtOld->fetch();
    $stmtOld->close();



    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = uniqid() . "_" . basename($_FILES['Image']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['Image']['tmp_name'], $targetFile)) {
        $update_arr['Image'] = $fileName;

        if (!empty($oldImage)) {
            $oldFilePath = $uploadDir . $oldImage;
            if (file_exists($oldFilePath)) {
                if (unlink($oldFilePath)) {
                $_SESSION['response'] = [
                    'success' => true,
                    'message' => "Deleted old image: $oldFilePath"
                ];
            } else {
                $_SESSION['response'] = [
                    'success' => false,
                    'message' => "Failed to delete: $oldFilePath"
                ];
            }
        } else {
            $_SESSION['response'] = [
                'success' => false,
                'message' => "Old image not found: $oldFilePath"
            ];
        }
        }
        

        // if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
        //     if (!empty($oldImage)) {
        //         $oldFilePath = $uploadDir . $oldImage;
        //         if(unlink($oldFilePath)) {
        //             error_log("FILE is deleted: $oldFilePath");
        //         } else {
        //             error_log("Failed to delete: $oldFilePath");
        //         }
        //     } else {
        //         error_log("File not found: $oldFilePath");
        //     }
        // }
    } else {
        $_SESSION['response'] = [
            'success' => false,
            'message' => "Failed to upload image"
        ];
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// FOR PROOF OF TRANSACTIONS

if (in_array("ProofOfTransaction", $columns) && isset($_FILES['ProofOfTransaction']) && !empty($_FILES['ProofOfTransaction']['name'])) {
    $stmtOld = $conn->prepare("SELECT `ProofOfTransaction` FROM `$table_name` WHERE `$primaryKey` = ?");
    $paramType = ctype_digit((string)$id) ? "i" : "s";
    $stmtOld->bind_param($paramType, $id);
    $stmtOld->execute();
    $stmtOld->bind_result($oldImage);
    $stmtOld->fetch();
    $stmtOld->close();



    $uploadDir = "proofs/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = uniqid() . "_" . basename($_FILES['ProofOfTransaction']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['ProofOfTransaction']['tmp_name'], $targetFile)) {
        $update_arr['ProofOfTransaction'] = $fileName;

        if (!empty($oldImage)) {
            $oldFilePath = $uploadDir . $oldImage;
            if (file_exists($oldFilePath)) {
                if (unlink($oldFilePath)) {
                $_SESSION['response'] = [
                    'success' => true,
                    'message' => "Deleted old image: $oldFilePath"
                ];
            } else {
                $_SESSION['response'] = [
                    'success' => false,
                    'message' => "Failed to delete: $oldFilePath"
                ];
            }
        } else {
            $_SESSION['response'] = [
                'success' => false,
                'message' => "Old image not found: $oldFilePath"
            ];
        }
        }
        

        // if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
        //     if (!empty($oldImage)) {
        //         $oldFilePath = $uploadDir . $oldImage;
        //         if(unlink($oldFilePath)) {
        //             error_log("FILE is deleted: $oldFilePath");
        //         } else {
        //             error_log("Failed to delete: $oldFilePath");
        //         }
        //     } else {
        //         error_log("File not found: $oldFilePath");
        //     }
        // }
    } else {
        $_SESSION['response'] = [
            'success' => false,
            'message' => "Failed to upload image"
        ];
        header('Location:' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// PROOF OF TRANSACTIONS ENDS HERE!!!














if (empty($update_arr)) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'No data to update'
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

$set_clause = implode(", ", array_map(fn($col) => "`$col` = ?", array_keys($update_arr)));
$sql = "UPDATE `$table_name` SET $set_clause WHERE `$primaryKey` = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'SQL Prepare failed: ' . $conn->error
    ];
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Bind parameters dynamically
// $paramType = ctype_digit((string)$id) ? "i" : "s";
// $types = str_repeat("s", count($update_arr)) . $paramType;
// $stmt->bind_param($types, ...array_values($update_arr), $id);


// Bind parameters dynamically
$paramType = ctype_digit((string)$id) ? "i" : "s";
$types = str_repeat("s", count($update_arr)) . $paramType;

$params = array_merge(array_values($update_arr), [$id]);
// $stmt->bind_param($types, ...array_merge(array_values($update_arr), [$id]));

foreach ($params as $key => $val) {
    if ($val === null) {
        $params[$key] = null;
    }
}

$stmt->bind_param($types, ...$params);
// Execute and set response
if ($stmt->execute()) {
    $_SESSION['response'] = [
        'success' => true,
        'message' => ucfirst($table_name) . " updated successfully!"
    ];
} else {
    $_SESSION['response'] = [
        'success' => false,
        'message' => 'Update failed: ' . $stmt->error
    ];
}

$stmt->close();
$conn->close();

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
