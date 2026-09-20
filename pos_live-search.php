<?php
	// Add connection file.
	include('connect.php');

	// Get the search term using $_GET
	$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';
	// Transform to lowercase and remove spaces
	$search_term = trim(strtolower($search_term));

	// Search database.
	$conn = $GLOBALS['conn'];
	$stmt = $conn->prepare("
				SELECT * FROM product 
					WHERE ProductName LIKE '%$search_term%' OR Barcode LIKE '%$search_term%'
					ORDER BY Product_ID DESC"
			);	
            
    $stmt->execute();

	$result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);

	

	echo json_encode([
		'length' => count($rows),
		'data' => $rows
	]);