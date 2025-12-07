<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MENU Login Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="body">
    <div class="container">
        <img src="menu.png" alt="Menu" width="350">
        <form method="POST">
            <input type="email" name="txt_email" placeholder="Email" required>
            <input type="password" name="txt_pass" placeholder="Password" required>
            <p>Don’t have an account yet? <a href="studentfacultystaff.php">Sign Up</a></p>       
            <button type="submit" name="btn_login">LOG IN</button>
        </form>
    </div>
</body>
</html>
<?php
session_start();
include("connection.php");

if (isset($_POST['btn_login'])) {
    $em = $_POST['txt_email'];
    $pw = $_POST['txt_pass'];

    $stmt = $con->prepare("SELECT UserID, Email, Password, Type FROM users WHERE Email = ?");
    $stmt->bind_param("s", $em);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $pw == $user['Password']) { 
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['email'] = $user['Email'];
		$_SESSION['type'] = $user['Type'];

		//echo '<script>alert("'.$_SESSION['type'].'");</script>';
		if ($_SESSION['type'] == 'Customer') {
			echo '<script>window.location.assign("MENUHome.php");</script>';
		}
		if ($_SESSION['type'] == 'Seller') {
			echo '<script>window.location.assign("MENUSeller.php");</script>';
		}
		if ($_SESSION['type'] == 'Admin') {
			echo '<script>window.location.assign("MENUAdmin.php");</script>';
		}
		if ($_SESSION['type'] == 'Owner') {
			echo '<script>window.location.assign("MENUOwner.php");</script>';
		}
        exit();
    } else {
        echo '<script>alert("User not found! Email or password may be incorrect.");</script>';
    }
}
?>
