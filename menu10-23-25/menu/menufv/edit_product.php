<?php
	include("connection.php"); // Ensure this file connects to your database

	header("Content-Type: application/json");

	// Check if request is POST
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$product_id = $_POST["product_id"];
		$amount = $_POST["product_amount"];
		$name = $_POST["product_name"];
		$price = $_POST["product_price"];
		$description = $_POST["product_description"];

		// Validate data
		if (empty($product_id) || empty($name) || empty($price) || empty($description) || empty($amount)) {
			echo json_encode(["status" => "error", "message" => "All fields are required!"]);
			exit();
		}

		// Update query
		$sql = "UPDATE products SET ProductName=?, Quantity=?, Price=?, ProductDesc=? WHERE ProductID=?";
		$stmt = $con->prepare($sql);
		$stmt->bind_param("sidsi", $name, $amount, $price, $description, $product_id);

		if ($stmt->execute()) {
			echo json_encode(["status" => "success", "message" => "Product updated successfully"]);
		} else {
			echo json_encode(["status" => "error", "message" => "Database update failed"]);
		}

		$stmt->close();
		$con->close();
	} else {
		echo json_encode(["status" => "error", "message" => "Invalid request"]);
	}
?>
