<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>MENU Seller Incoming Orders</title>
		<link rel="stylesheet" href="styles4.css" />
	</head>
	<body>
	<?php
		session_start();
		include("connection.php");
		if (!isset($_SESSION['user_id'])) {
			header("Location: MENULogin.php");
			exit();
		}
		//echo '<script>alert("Successfully logged in as '.$_SESSION['user_id'].'!");</script>';
		$sqlId = "SELECT * FROM sellers WHERE UserID = ".$_SESSION['user_id'];
		$resId = $con->query($sqlId);
		$rowId = $resId->fetch_assoc();
	?>
    <main>
	<header>
		<img src="MenuLOGO.png" alt="Header Image" class="header-image" />
		<a href="MENUSeller.php" class="header-button"><p class="header-logout">EDIT PRODUCTS</p></a>
		<a href="MENUOrders.php" class="header-button"><p class="header-logout">INCOMING ORDERS</p></a>
		<a href="MENUCompletedOrders.php" class="header-button"><p class="header-logout">RECENT ORDERS</p></a>
		<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
    </header>
	<center>
		<div class="order-content">
			<h2>Processing Order...</h2>
			<form id="edit-product-form">
				<img style='height: 400px; width: 100%; object-fit: contain;' src='modals/Pancit Canton_r.png'>
				<table class="receipt">
					<tr>
						<td>Order ID:<td>
						<td>64</td>
					</tr>
					<tr>
						<td>Username:<td>
						<td>asdf</td>
					</tr>
					<tr>
						<td>Product Name:<td>
						<td>Pancit Canton</td>
					</tr>
					<tr>
						<td>Quantity:<td>
						<td>x2</td>
					</tr>
					<tr>
						<td>Total Price:<td>
						<td>₱60</td>
					</tr>
					<tr>
						<td>Non-cash payment<td>
						<td>
							<input type="checkbox" id="myCheckbox">
							<div class="content-to-toggle">
							<input type="text" placeholder="Reference number">
							</div>
						</td>
					</tr>
					<tr>
						<td><button class="clear-button">Cancel the order</button><td>
						<td><button class="checkout-button">Submit the order</button></td>
					</tr>
				</table>
			</form>
		</div>
	</center>
	</main>
	</body>
</html>
<?php
	include("connection.php");
	
	if(isset($_POST['btn_updateOrd'])) {
		$oid = $_POST['txt_oid'];
		$sql = "UPDATE orders SET Status = 'Completed', OrderCompleted = '".date('Y-m-d h:i:sa')."' WHERE OrderID =".$oid;
		$res = $con->query($sql);
		if ($con->query($sql) == TRUE) {
			echo "<script>
			alert('Order ".$oid." was completed successfully!');
			window.location.href='MENUOrders.php';
			</script>";
		} else {
			echo "<script>
			alert('Order completion error!');
			window.location.href='MENUOrders.php';
			</script>";
		}
	}
	
	if(isset($_POST['btn_cancelOrd'])) {
		$oid = $_POST['txt_oid'];
		$sqlX = "UPDATE orders SET Status = 'Cancelled', OrderCompleted = '".date('Y-m-d h:i:sa')."' WHERE OrderID =".$oid;
		$resX = $con->query($sqlX);
		if ($con->query($sqlX) == TRUE) {
			$sqlA = "SELECT Quantity, ProductID FROM orders WHERE OrderID =".$oid;
			$resA = $con->query($sqlA);
			$rowA = $resA->fetch_assoc();
			$quantityA = $rowA['Quantity'];
			$prodId = $rowA['ProductID'];
			
			$sqlB = "SELECT Quantity FROM products WHERE ProductID =".$prodId;
			$resB = $con->query($sqlB);
			$rowB = $resB->fetch_assoc();
			$quantityB = $rowB['Quantity'];
			
			$finalAmount = $quantityA + $quantityB;
			
			$sqlU = "UPDATE products SET Quantity = ".$finalAmount." WHERE ProductID =".$prodId;
			$resU = $con->query($sqlU);
			if ($con->query($sqlU) == TRUE) {
				echo "<script>
				alert('Order ".$oid." was cancelled successfully');
				window.location.href='MENUOrders.php';
				</script>";
			} else {
				echo "<script>
				alert('Order cancellation error!');
				window.location.href='MENUOrders.php';
				</script>";
			}
		} else {
			echo "<script>
			alert('Order cancellation error!');
			window.location.href='MENUOrders.php';
			</script>";
		}
	}
?>