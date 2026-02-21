<?php
	session_start();
	include("connection.php");

	$store_id = $_SESSION['store_id'];

	$sql = "
	  INSERT INTO products
	  (store_id, product_name, description, unit_price, available_stock, status)
	  VALUES (?, ?, ?, ?, ?, 'active')
	";
	$stmt = $con->prepare($sql);
	$stmt->bind_param(
	  "issdi",
	  $store_id,
	  $_POST['product_name'],
	  $_POST['description'],
	  $_POST['unit_price'],
	  $_POST['available_stock']
	);

	$stmt->execute();

	echo json_encode([
	  "status" => "success",
	  "message" => "Product added"
	]);
?>