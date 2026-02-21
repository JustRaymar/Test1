<?php
	include("connection.php");

	$sql = "
	  UPDATE products
	  SET product_name = ?, description = ?, unit_price = ?, available_stock = ?
	  WHERE product_id = ?
	";
	$stmt = $con->prepare($sql);
	$stmt->bind_param(
	  "ssdii",
	  $_POST['product_name'],
	  $_POST['description'],
	  $_POST['unit_price'],
	  $_POST['available_stock'],
	  $_POST['product_id']
	);

	$stmt->execute();

	echo json_encode([
	  "status" => "success",
	  "message" => "Product updated"
	]);
?>
