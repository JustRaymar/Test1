<?php
	include("connection.php");

	header("Content-Type: application/json");

	$sql = "SELECT * FROM products WHERE Status = 'ACTIVE'";
	$result = $con->query($sql);

	$products = [];

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$products[] = $row;
		}
	}

	echo json_encode($products);
?>