<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>MENU Admin - Edit Customer</title>
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
		<a href="MENUAdminMenu.php" class="header-button"><p class="header-logout">MANAGE MENU</p></a>
		<a href="MENUAdminTransactions.php" class="header-button"><p class="header-logout">TRANSACTION LOGS</p></a>
		<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
    </header>
	<center>
			<?php
				include("connection.php");
				
				if(isset($_POST['btn_edit'])) {
					$id = $_POST['txt_uid'];
					
					$getsql = "SELECT * FROM customers c, users u WHERE c.UserID = ".$id." AND u.UserID =".$id;
					$getres = $con->query($getsql);
					if ($getres->num_rows>0) {
						if ($row=$getres->fetch_assoc()) {
							if ($row['Priority'] == false) {
								$priority = 'False';
							} else {
								$priority = 'True';
							}
							echo "
								<table>
									<form method='POST'>
										<tr>
											<th colspan=2>Edit Customer Information</th>
										</tr>
										<tr>
											<td>UserID</td>
											<td><input type='text' name='txt_uid' value='".$row['UserID']."' readonly></td>
										</tr>
										<tr>
											<td>CustomerID</td>
											<td><input type='text' name='txt_cid' value='".$row['CustomerID']."' readonly></td>
										</tr>
										<tr>
											<td>Username</td>
											<td><input type='text' name='txt_un' value='".$row['Username']."'></td>
										</tr>
										<tr>
											<td>Email</td>
											<td><input type='text' name='txt_em' value='".$row['Email']."'></td>
										</tr>
										<tr>
											<td>Password</td>
											<td><input type='text' name='txt_pw' value='".$row['Password']."'></td>
										</tr>
										<tr>
											<td>Last Name</td>
											<td><input type='text' name='txt_ln' value='".$row['LName']."'></td>
										</tr>
										<tr>
											<td>First Name</td>
											<td><input type='text' name='txt_fn' value='".$row['FName']."'></td>
										</tr>
										<tr>
											<td>Middle Name</td>
											<td><input type='text' name='txt_mn' value='".$row['MName']."'></td>
										</tr>
										<tr>
											<td>Priority</td>
											<td>
												<select type='text' name='txt_pri' value='".$priority."'>
													<option value='False'>False</option>
													<option value='True'>True</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Finalize</td>
											<td>
											<button name='btn_save' type='submit' class='checkout-button'>Save Changes</button>
											</td>
										</tr>
									</form>
								</table>
							";
						}
					}
				}
			?>
			<br/>
			<a href='MENUAdmin.php'><button class="clear-button">Cancel</button></a>
	</center>
	</main>
	</body>
</html>
<?php
	if(isset($_POST['btn_save'])) {
		$uid = $_POST['txt_uid'];
		$un = $_POST['txt_un'];
		$em = $_POST['txt_em'];
		$pw = $_POST['txt_pw'];
		$ln = $_POST['txt_ln'];
		$fn = $_POST['txt_fn'];
		$mn = $_POST['txt_mn'];
		if ($_POST['txt_pri'] == 'True') {
			$pri = 1;
		} else {
			$pri = 0;
		}
		
		$savesql = "UPDATE users u, customers c SET u.Username = '".$un."', u.Email = '".$em."', u.Password = '".$pw."', c.LName = '".$ln."', c.FName = '".$fn."',
		c.MName = '".$mn."', c.Priority = '".$pri."' WHERE u.UserID = ".$uid." AND c.UserID = ".$uid;
		
		if ($con->query($savesql) == TRUE) {
			Print '<script>alert("Data updated successfully!")</script>';
        	Print '<script>window.location.assign("MENUAdmin.php?page=1")</script>';
		}
	}
?>