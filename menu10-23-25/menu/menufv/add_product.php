<?php
	session_start();
	include("connection.php");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!isset($_SESSION['user_id'])) {
			echo json_encode(["status" => "error", "message" => "User not logged in"]);
			exit();
		}

		$name = $_POST["product_name"];
		$price = $_POST["product_price"];
		$description = $_POST["product_description"];
		$amount = $_POST["product_amount"];
		$sId = $_SESSION['user_id']; // Get seller ID

		// Fetch the SellerID from sellers table
		$sqlSeller = "SELECT SellerID FROM sellers WHERE UserID = $sId";
		$result = $con->query($sqlSeller);
		
		if ($result->num_rows > 0) {
			$seller = $result->fetch_assoc();
			$sId = $seller['SellerID'];

			$sql = "INSERT INTO products (ProductName, Quantity, Price, ProductDesc, Status, SellerID) 
					VALUES (?, ?, ?, ?, 'ACTIVE', ?)";

			$stmt = $con->prepare($sql);
			$stmt->bind_param("sdsi", $name, $amount, $price, $description, $sId);

			if ($stmt->execute()) {
				echo json_encode(["status" => "success", "message" => "Product added successfully"]);
			} else {
				echo json_encode(["status" => "error", "message" => "Error adding product: " . $stmt->error]);
			}
			$stmt->close();
		} else {
			echo json_encode(["status" => "error", "message" => "Seller not found"]);
		}
	}
?>