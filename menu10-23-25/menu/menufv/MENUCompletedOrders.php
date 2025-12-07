<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>MENU Seller Recent Orders</title>
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
		<a href="MENUCompletedOrders.php" class="active-button"><p class="header-logout">RECENT ORDERS</p></a>
		<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
    </header>
	<center>
		<table border="1px">
			<tr>
				<th colspan=3>RECENTLY COMPLETED ORDERS</th>
			</tr>
			<?php
				include("connection.php");
				
				$sql = "SELECT * FROM orders WHERE Status = 'Completed' OR Status = 'Cancelled' ORDER BY OrderID DESC LIMIT 10";
				$res = $con->query($sql);
				if ($res->num_rows>0) {
					while ($row=$res->fetch_assoc()) {
						$sqlC = "SELECT FName FROM customers WHERE UserID = ".$row['UserID'];
						$resC = $con->query($sqlC);
						$rowC = $resC->fetch_assoc();
						$customer = $rowC['FName'];
						
						$sqlP = "SELECT ProductName FROM products WHERE ProductID = ".$row['ProductID'];
						$resP = $con->query($sqlP);
						$rowP = $resP->fetch_assoc();
						$product = $rowP['ProductName'];
						
						if ($row['Status'] == 'Completed') {
								echo"<tr class='complete'>";
							} else {
								if ($row['Status'] == 'Cancelled') {
									echo"<tr class='cancelled'>";
								} else {
									echo"<tr class='pending'>";
								}
							}
						echo "
							<td><img style='height: 200px; width: 100%; object-fit: contain;' src='modals/".$product."_r.png'><p class='prodname'>".strtoupper($product)."</p></td>
							<td><p class='orderdisp'>x".$row['Quantity']." | ₱".$row['OrderPrice']."</p>";
							if ($row['Status'] == 'Completed') {
								echo"Order is ".$row['Status']."!</td>";
							} else {
								if ($row['Status'] == 'Cancelled') {
									echo"Order is ".$row['Status']."!</td>";
								} else {
									echo"Order is ".$row['Status']."...</td>";
								}
							}
						echo"
							<td><button onclick=\"window.location.href='MENUSellerViewOrder.php';\" class='total-button'>View Order</button></td>
						</tr>";
						
					}
				}
			?>
		</table>
	</center>
	</main>
	</body>
</html>