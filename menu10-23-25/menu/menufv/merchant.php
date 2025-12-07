<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creating Account</title>
    <link rel="stylesheet" href="merchant.css">
</head>
<body class="body">
    <div class="container">
        <img src="menu.png" alt="Menu" width="250">
    </div>
    <div class="box">
    <form method="POST">    
        <p id="text1">Create an account for merchant</p>
        <input type="name" placeholder="NAME" name="txt_name" class="input-field" required>
        <input type="mName" placeholder="MERCHANT NAME" name="txt_merchant" class="input-field" required>
        <input type="bPermit" placeholder="BUSINESS PERMIT NO." name="txt_permit" class="input-field" required>
        <input type="tNo" placeholder="TIN NO." name="txt_tin" class="input-field" required>
        <input type="email" placeholder="EMAIL" name="txt_email" class="input-field" required>
        <input type="password" placeholder="SET PASSWORD" name="txt_pass" required>
        <input type="password" placeholder="CONFIRM PASSWORD" name="txt_cpass" required>
        <button type="submit" name="btn_reg">CREATE ACCOUNT</button>
    </form>
    </div>
</body>
</html>
<?php
	include("connection.php");
	
	if (isset($_POST['btn_reg'])) {
	$nm = $_POST['txt_name'];
	$mn = $_POST['txt_merchant'];
	$em = $_POST['txt_email'];
	$pr = $_POST['txt_permit'];
	$tn = $_POST['txt_tin'];
	$pw = $_POST['txt_pass'];
	$cpw = $_POST['txt_cpass'];
	
		if ($pw != $cpw) {
			Print '<script>alert("Passwords do not match!")</script>';
		} else {
			$sql = "SELECT * FROM sellers s, users u
					WHERE s.StoreName = '".$mn."'
					AND u.Email = '".$em."'";
						
			$res = $con->query($sql);
			if ($res->num_rows>0) {
				Print '<script>alert("That name or email has already been used! Please log in instead.")</script>';
			} else {
				$sqlUserReg = "INSERT INTO users (Username, Email, Password, Type) VALUES ('".$nm."', '".$em."', '".$pw."', 'Seller')";
				if ($con->query($sqlUserReg) == TRUE) {
					$sqlGet = "SELECT UserID FROM users WHERE Email = '".$em."'";
					$resGet = $con->query($sqlGet);
					if ($resGet->num_rows>0) {
						if ($row = $resGet->fetch_assoc()) {
							$uid = $row['UserID'];
							$sqlSellReg = "INSERT INTO sellers (UserID, StoreName, Tin, Permit) VALUES (".$uid.", '".$mn."', '".$tn."', '".$pr."')";
							if ($con->query($sqlSellReg) == TRUE) {
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
