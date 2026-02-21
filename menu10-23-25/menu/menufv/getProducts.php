<?php
	session_start();
	include("connection.php");

	header("Content-Type: application/json");

	// Debug mode ON for now (turn off later)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// Validate session
	if (!isset($_SESSION['store_id'])) {
		echo json_encode([
			"status" => "error",
			"message" => "store_id missing from session",
			"session" => $_SESSION
		]);
		exit();
	}

	$store_id = $_SESSION['store_id'];

	$sql = "
		SELECT
			product_id,
			product_name,
			description,
			unit_price,
			available_stock
		FROM product
		WHERE store_id = ?
	";

	$stmt = $con->prepare($sql);

	if (!$stmt) {
		echo json_encode([
			"status" => "error",
			"message" => "SQL prepare failed",
			"mysql_error" => $con->error
		]);
		exit();
	}

	$stmt->bind_param("i", $store_id);
	$stmt->execute();

	$result = $stmt->get_result();
	$products = [];

	while ($row = $result->fetch_assoc()) {
		$products[] = $row;
	}

	echo json_encode($products);
?>