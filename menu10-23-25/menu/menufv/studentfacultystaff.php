<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Creating Account</title>
	<link rel="stylesheet" href="styles2.css">
</head>
<body class="body">
	<div class="container">
        <img src="menu.png" alt="Menu" width="250">
    </div>
    <div class="box">
    <form method="POST">
    	<p id="text1">Create an account for student, faculty and staff</p>
    	<input type="name" name="txt_fName" placeholder="FIRST NAME" class="input-field">
		<input type="name" name="txt_mName" placeholder="MIDDLE NAME" class="input-field">
		<input type="name" name="txt_lName" placeholder="LAST NAME" class="input-field">
    	<input type="email" name="txt_email" placeholder="EMAIL" class="input-field" required>
    	<input type="password" name="txt_pass" placeholder="SET PASSWORD" required>
    	<input type="password" name="txt_cpass" placeholder="CONFIRM PASSWORD" required>
    	<button type="submit" name="btn_reg">CREATE ACCOUNT</button>	
    </form>
		<button class='clear-button' onclick="window.location.href='MENULogin.php';">CANCEL</button>
    </div>
</body>
</html>

<?php
	include("connection.php");
	
	if (isset($_POST['btn_reg'])) {
	$fn = $_POST['txt_fName'];
		if ($_POST['txt_mName'] != "") {
			$mn = $_POST['txt_mName'];
		} else {
			$mn = "";
		}
	$ln = $_POST['txt_lName'];
	$em = $_POST['txt_email'];
	$pw = $_POST['txt_pass'];
	$cpw = $_POST['txt_cpass'];
	
		if ($pw != $cpw) {
			Print '<script>alert("Passwords do not match!")</script>';
		} else {
			$sql = "SELECT * FROM customer c, user u
					WHERE c.fname = '".$fn."'
					AND c.mname = '".$mn."'
					AND c.lname = '".$ln."'
					AND u.email = '".$em."'";
						
			$res = $con->query($sql);
			if ($res->num_rows>0) {
				Print '<script>alert("That name or email has already been used! Please log in instead.")</script>';
			} else {
				$sqlUserReg = "INSERT INTO user (email, password) VALUES ('".$em."', '".$pw."')";
				if ($con->query($sqlUserReg) == TRUE) {
					$sqlGet = "SELECT user_id FROM user WHERE email = '".$em."'";
					$resGet = $con->query($sqlGet);
					if ($resGet->num_rows>0) {
						if ($row = $resGet->fetch_assoc()) {
							$uid = $row['user_id'];
							$sqlCustReg = "INSERT INTO customer (user_id, fname, mname, lname) VALUES (".$uid.", '".$fn."', '".$mn."', '".$ln."')";
							if ($con->query($sqlCustReg) == TRUE) {
								Print '<script>alert("Account created successfully!")</script>';
								Print '<script>window.location.assign("MENULogin.php")</script>';
							} else {
								Print '<script>alert("Error adding customer data!")</script>';	
							}
						}
					} else {
						Print '<script>alert("Error retrieving necessary data!")</script>';	
					}
				} else {
					Print '<script>alert("Error adding user!")</script>';	
				}
			}
		}
	}
	
	
?>
