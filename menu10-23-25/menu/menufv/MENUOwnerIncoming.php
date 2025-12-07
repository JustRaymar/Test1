<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>MENU Owner Incoming Order</title>
		<link rel="stylesheet" href="styles5.css" />
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
		<a href="MENUOwner.php" class="header-button"><p class="header-logout">MY STORE PRODUCTS</p></a>
		<a href="MENUOwnerIncoming.php" class="active-button"><p class="header-logout">INCOMING ORDERS</p></a>
		<a href="MENUOwnerHistory.php" class="header-button"><p class="header-logout">ORDER HISTORY</p></a>
		<a href="MENUOwnerStats.php" class="header-button"><p class="header-logout">ORDER STATISTICS</p></a>
		<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
    </header>
	<br/>
	<center>
		<table border="1px">
			<tr>
				<th colspan=3>INCOMING ORDERS</th>
				<th><button class="closebtn">Close orders</button></th>
			</tr>
			<?php
				include("connection.php");
				
				$sql = "SELECT * FROM orders WHERE Status = 'Pending' ORDER BY Priority DESC, OrderID LIMIT 10";
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
						
						echo "
						<tr class='pending'>
							<td><img style='height: 200px; width: 100%; object-fit: contain;' src='modals/".$product."_r.png'><p class='prodname'>".strtoupper($product)."</p></td>
							<td style='font-size: 30px;'>x".$row['Quantity']."</td>
							<td style='font-size: 30px;'>₱".$row['OrderPrice']."</td>
							<td><button class='checkout-button' onclick=\"window.location.href='MENUOwnerStart.php';\">Start Order</button></td>
						</tr>";					
					}
				}
			?>
		</table>
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
			window.location.href='MENUOwnerIncoming.php';
			</script>";
		} else {
			echo "<script>
			alert('Order completion error!');
			window.location.href='MENUOwnerIncoming.php';
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
				window.location.href='MENUOwnerIncoming.php';
				</script>";
			} else {
				echo "<script>
				alert('Order cancellation error!');
				window.location.href='MENUOwnerIncoming.php';
				</script>";
			}
		} else {
			echo "<script>
			alert('Order cancellation error!');
			window.location.href='MENUOwnerIncoming.php';
			</script>";
		}
	}
?>