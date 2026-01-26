<?php
	header('Content-Type: application/json');
	include("connection.php");

	if (!isset($_GET['seller_id'])) {
		echo json_encode([]);
		exit;
	}

	$sellerId = intval($_GET['seller_id']);

	$sql = "SELECT ProductID, ProductName, ProductDesc, Price, Quantity
			FROM products
			WHERE Status = 'ACTIVE' AND SellerID = ?";

	$stmt = $con->prepare($sql);
	$stmt->bind_param("i", $sellerId);
	$stmt->execute();
	$result = $stmt->get_result();

	$products = [];
	while ($row = $result->fetch_assoc()) {
		$products[] = $row;
	}

	echo json_encode($products);
?>