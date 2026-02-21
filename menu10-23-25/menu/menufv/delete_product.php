<?php
	include("connection.php");

	$sql = "
	  UPDATE products
	  SET status = 'inactive'
	  WHERE product_id = ?
	";
	$stmt = $con->prepare($sql);
	$stmt->bind_param("i", $_POST['product_id']);
	$stmt->execute();

	echo json_encode([
	  "status" => "success",
	  "message" => "Product removed"
	]);
?>