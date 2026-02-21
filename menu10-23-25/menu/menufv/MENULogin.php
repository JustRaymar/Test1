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

    // Get user from user table
    $stmt = $con->prepare("SELECT user_id, email, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $em);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Basic login check (replace with password_hash later)
    if ($user && $pw == $user['password']) {

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email']   = $user['email'];

        $user_id = $user['user_id'];

        //CHECK CUSTOMER TABLE
        $stmt = $con->prepare("SELECT user_id FROM customer WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $customer = $stmt->get_result()->fetch_assoc();

        if ($customer) {
            header("Location: MENUHome.php");
            exit();
        }

        //CHECK PERSONNEL TABLE
        $stmt = $con->prepare("SELECT role_id FROM personnel WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $personnel = $stmt->get_result()->fetch_assoc();

        if ($personnel) {

            $_SESSION['role_id'] = $personnel['role_id'];

            if ($personnel['role_id'] == 1) {
                header("Location: MENUSeller.php");
            } elseif ($personnel['role_id'] == 3) {
                header("Location: MENUAdmin.php");
            } elseif ($personnel['role_id'] == 2) {
                header("Location: MENUOwner.php");
            } else {
                echo '<script>alert("Unknown personnel role.");</script>';
            }
            exit();
        }

        // If user exists but is in neither table
        echo '<script>alert("Account has no assigned role.");</script>';

    } else {
        echo '<script>alert("Email or password is incorrect.");</script>';
    }
}
?>

