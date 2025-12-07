<?php
	session_start();
	session_destroy();
	header("Location: MENULogin.php"); // Redirect to login page
	exit();
?>