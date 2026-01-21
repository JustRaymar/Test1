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
								<td>".$row['UserID']."</td>
								<td>".$username."</td>
								<td>".$email."</td>
								<td>".$row['LName'].", ".$row['FName']." ".$row['MName']."</td>
								<input type='hidden' name='txt_uid' value='".$row['UserID']."'>
								<td>
									<button 
										class='home-button'
										onclick='openEditModal(
											\"".$row['UserID']."\",
											\"".$username."\",
											\"".$email."\",
											\"".$rowU['Password']."\",
											\"".$row['LName']."\",
											\"".$row['FName']."\",
											\"".$row['MName']."\",
											\"".$row['Priority']."\"
										)'
									>
										Edit Information
									</button>
								</td>
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
		
		<div id="editModal" class="modal">
			<div class="modal-content">
				<span class="close" onclick="closeEditModal()">&times;</span>

				<h3>Edit Customer</h3>

				<form method="POST">
					<input type="hidden" name="txt_uid" id="m_uid">

					<label>Username</label>
					<input type="text" name="txt_un" id="m_un">

					<label>Email</label>
					<input type="text" name="txt_em" id="m_em">

					<label>Password</label>
					<input type="text" name="txt_pw" id="m_pw">

					<label>Last Name</label>
					<input type="text" name="txt_ln" id="m_ln">

					<label>First Name</label>
					<input type="text" name="txt_fn" id="m_fn">

					<label>Middle Name</label>
					<input type="text" name="txt_mn" id="m_mn">

					<label>Priority</label>
					<select name="txt_pri" id="m_pri">
						<option value="0">False</option>
						<option value="1">True</option>
					</select>

					<br><br>
					<button type="submit" name="btn_save" class="checkout-button">
						Save Changes
					</button>
				</form>
			</div>
		</div>
	
	<script>
		function openEditModal(uid, un, em, pw, ln, fn, mn, pri) {
			document.getElementById("m_uid").value = uid;
			document.getElementById("m_un").value = un;
			document.getElementById("m_em").value = em;
			document.getElementById("m_pw").value = pw;
			document.getElementById("m_ln").value = ln;
			document.getElementById("m_fn").value = fn;
			document.getElementById("m_mn").value = mn;
			document.getElementById("m_pri").value = pri;

			document.getElementById("editModal").style.display = "block";
		}

		function closeEditModal() {
			document.getElementById("editModal").style.display = "none";
		}
	</script>

	
	</center>
	</main>
	</body>
</html>
<?php
if (isset($_POST['btn_save'])) {
    include("connection.php");

    $uid = $_POST['txt_uid'];
    $un  = $_POST['txt_un'];
    $em  = $_POST['txt_em'];
    $pw  = $_POST['txt_pw'];
    $ln  = $_POST['txt_ln'];
    $fn  = $_POST['txt_fn'];
    $mn  = $_POST['txt_mn'];
    $pri = $_POST['txt_pri'];

    $sql = "
        UPDATE users u
        JOIN customers c ON u.UserID = c.UserID
        SET
            u.Username = '$un',
            u.Email = '$em',
            u.Password = '$pw',
            c.LName = '$ln',
            c.FName = '$fn',
            c.MName = '$mn',
            c.Priority = '$pri'
        WHERE u.UserID = $uid
    ";

    if ($con->query($sql)) {
        echo "<script>
            //alert('Customer updated successfully!');
            window.location.href='MENUAdmin.php?page=1';
        </script>";
    }
}
?>
