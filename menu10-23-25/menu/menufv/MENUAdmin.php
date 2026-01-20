<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>MENU Admin - View Customers</title>
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
		<a href="MENUAdmin.php" class="active-button"><p class="header-logout">MANAGE USERS</p></a>
		<a href="MENUAdminTransactions.php" class="header-button"><p class="header-logout">TRANSACTION LOGS</p></a>
		<a href="MENUAdminCredit.php" class="header-button"><p class="header-logout">LOAD CREDIT</p></a>
		<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
    </header>Admin
	<center>
		<div class="store">
			<a href="MENUAdmin.php" class='active'>CUSTOMERS</a>
			<a href="MENUAdmin.php">STAFF</a>
			<a href="MENUAdmin.php">OTHER</a>
		</div>
		<br/>
		<table border="1px">
			<tr>
				<th>Customers</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<?php
				include("connection.php");
				
				$limit = 10;  // Number of entries to show in a page.
				// Look for a GET variable page if not found default is 1.     
				if (isset($_GET["page"])) { 
				  $pagen  = $_GET["page"]; 
				} 
				else { 
				  $pagen=1; 
				};  

				$start = ($pagen-1) * $limit;  
				
				$sql = "SELECT * FROM customers LIMIT ".$start.",".$limit;
				$res = $con->query($sql);
				if ($res->num_rows>0) {
					while ($row=$res->fetch_assoc()) {
						$sqlU = "SELECT * FROM users WHERE UserID = ".$row['UserID'];
						$resU = $con->query($sqlU);
						$rowU = $resU->fetch_assoc();
						$email = $rowU['Email'];
						$username = $rowU['Username'];
						$password = substr($rowU['Password'], 0, 3);
						if ($row['Priority'] == 0) {
							$priority = 'False';
						} else {
							$priority = 'True';
						}
						
						echo "
						<tr>
							<form action='MENUEditCustomer.php' method='POST'>
								<td>".$row['UserID']."</td>
								<td>".$username."</td>
								<td>".$email."</td>
								<td>".$row['LName'].", ".$row['FName']." ".$row['MName']."</td>
								<input type='hidden' name='txt_uid' value='".$row['UserID']."'>
								<td><button type='submit' class='home-button' name='btn_edit'>Edit Information</button></td>
							</form>
						</tr>
						";
					}
				}
			?>
		</table>
		<br/>
		<div class="pagination">
			<?php  
				$sqlN = "SELECT COUNT(*) AS 'Total' FROM customers LIMIT ".$start.",".$limit; 
				$resN = $con->query($sqlN); 
				$rowN = $resN->fetch_assoc();  
				$total_records = $rowN['Total'];  
				
				// Number of pages required.
				$total_pages = ceil($total_records / $limit);  
				$pagLink = "";                        
				for ($i=1; $i<=$total_pages; $i++) {
				  if ($i==$pagen) {
					  $pagLink .= "<a class='active' href='MENUAdmin.php?page=".$i."'>".$i."</a>";
				  }            
				  else  {
					  $pagLink .= "<a href='MENUAdmin.php?page=".$i."'>".$i."</a>";  
				  }
				};  
				echo $pagLink;  
			?>
		</div>
	</center>
	</main>
	</body>
</html>