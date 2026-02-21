<?php
	header('Content-Type: application/json');
	include("connection.php");

	if (!isset($_GET['store_id'])) {
		echo json_encode([]);
		exit;
	}

	$storeId = intval($_GET['store_id']);

	$sql = "SELECT product_id, product_name, description, unit_price, available_stock
			FROM product
			WHERE store_id = ?";

	$stmt = $con->prepare($sql);
	$stmt->bind_param("i", $storeId);
	$stmt->execute();
	$result = $stmt->get_result();

	$products = [];
	while ($row = $result->fetch_assoc()) {
		$products[] = $row;
	}

	echo json_encode($products);
?>