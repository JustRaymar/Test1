<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>MENU Admin - Transactions History</title>
		<link rel="stylesheet" href="styles6.css" />
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
		<a href="MENUAdmin.php" class="header-button"><p class="header-logout">MANAGE USERS</p></a>
		<a href="MENUAdminTransactions.php" class="active-button"><p class="header-logout">TRANSACTION LOGS</p></a>
		<a href="MENUAdminCredit.php" class="header-button"><p class="header-logout">LOAD CREDIT</p></a>
		<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
    </header>
	<center>
		<table border="1px">
			<tr>
				<th colspan=3>TRANSACTION LOG</th>
				<th><button class="clear-button">Close orders</button></th>
			</tr>
			<?php
				include("connection.php");
				
				$limit = 7;  // Number of entries to show in a page.
				// Look for a GET variable page if not found default is 1.     
				if (isset($_GET["page"])) { 
				  $pagen  = $_GET["page"]; 
				} 
				else { 
				  $pagen=1; 
				};  

				$start = ($pagen-1) * $limit;  
				
				$sql = "SELECT * FROM orders ORDER BY OrderID DESC LIMIT ".$start.",".$limit." ";
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
							<td>
								<p>".$customer." (UID ".$row['UserID'].") has ordered</p>
								<p>at ".$row['TimeOrdered']."</p>
							</td>
							<td><p class='orderdisp'>".$product." x".$row['Quantity']."</p></td>
							<td><p class='orderdisp'>₱".$row['OrderPrice']."</p></td>";
							if ($row['Status'] == 'Completed') {
								echo"<td>
										<p>Order is ".$row['Status']."!</p>
										<p>Done at ".$row['OrderCompleted']."</p>
									</td>";
							} else {
								if ($row['Status'] == 'Cancelled') {
									echo"<td>
											<p>Order is ".$row['Status']."!</p>
											<p>Cancelled at ".$row['OrderCompleted']."</p>
										</td>";
								} else {
									echo"<td>Order is ".$row['Status']."...</td>";
								}
							}
						echo"
						</tr>";
					}
				}
			?>
		</table>
		<br/>
		<div class="pagination">
			<?php  
				$sqlN = "SELECT COUNT(*) AS 'Total' FROM orders";  
				$resN = $con->query($sqlN); 
				$rowN = $resN->fetch_assoc();  
				$total_records = $rowN['Total'];  
				
				// Number of pages required.
				$total_pages = ceil($total_records / $limit);  
				$pagLink = "";                        
				for ($i=1; $i<=$total_pages; $i++) {
				  if ($i==$pagen) {
					  $pagLink .= "<a class='active' href='MENUAdminTransactions.php?page=".$i."'>".$i."</a>";
				  }            
				  else  {
					  $pagLink .= "<a href='MENUAdminTransactions.php?page=".$i."'>".$i."</a>";  
				  }
				};  
				echo $pagLink;  
			?>
		</div>
	</center>
	</main>
	</body>
</html>