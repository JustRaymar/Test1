<?php
	session_start();
	include("connection.php"); // Ensure this file connects to your database

	header("Content-Type: application/json");

	// Check if request is POST
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$product_id = $_POST["product_id"];

		// Validate input
		if (empty($product_id)) {
			echo json_encode(["status" => "error", "message" => "Product ID is required"]);
			exit();
		}

		// Delete product query
		$sql = "DELETE FROM products WHERE ProductID=?";
		$stmt = $con->prepare($sql);
		$stmt->bind_param("i", $product_id);

		if ($stmt->execute()) {
			echo json_encode(["status" => "success", "message" => "Product deleted successfully"]);
		} else {
			echo json_encode(["status" => "error", "message" => "Failed to delete product"]);
		}

		$stmt->close();
		$con->close();
	} else {
		echo json_encode(["status" => "error", "message" => "Invalid request"]);
	}
?>