<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>MENU Admin - Credit Management</title>
<link rel="stylesheet" href="styles6.css">
<style>
.tab-btn { padding:10px 20px; cursor:pointer; }
.tab-btn.active { background:#333; color:#fff; }
.tab { display:none; }
.tab.active { display:block; }
</style>
</head>

<body>

<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: MENULogin.php");
  exit();
}

/* =====================
   LOAD CREDIT
===================== */
if (isset($_POST['btn_load'])) {
  $customer_id = $_POST['customer_id'];
  $amount      = $_POST['amount'];

  // get credit account
  $res = $con->query("
    SELECT * FROM credit WHERE customer_id=$customer_id AND active=1
  ");
  $credit = $res->fetch_assoc();

  $old_balance = $credit['credit_amount'];
  $new_balance = $old_balance + $amount;

  // update balance
  $con->query("
    UPDATE credit
    SET credit_amount=$new_balance
    WHERE credit_id={$credit['credit_id']}
  ");

  // history
  $con->query("
    INSERT INTO credit_history
    (datetime, amount, account_amount, credit_id, customer_id)
    VALUES (NOW(), $amount, $new_balance, {$credit['credit_id']}, $customer_id)
  ");

  echo "<script>window.location='MENUAdminCredit.php';</script>";
  exit();
}

/* =====================
   LINK CREDIT ACCOUNT
===================== */
if (isset($_POST['btn_link'])) {
  $customer_id = $_POST['customer_id'];

  $con->query("
    INSERT INTO credit (credit_amount, active, customer_id)
    VALUES (0, 1, $customer_id)
  ");

  echo "<script>window.location='MENUAdminCredit.php';</script>";
  exit();
}
?>

<header>
	<img src="MenuLOGO.png" alt="Header Image" class="header-image" />
	<a href="MENUAdmin.php" class="header-button"><p class="header-logout">MANAGE USERS</p></a>
	<a href="MENUAdminTransactions.php" class="header-button"><p class="header-logout">TRANSACTION LOGS</p></a>
	<a href="MENUAdminCredit.php" class="active-button"><p class="header-logout">LOAD CREDIT</p></a>
	<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
</header>

<center>

<!-- =====================
     TABS
===================== -->
<button class="tab-btn active" onclick="showTab('load', this)">Load Credit</button>
<button class="tab-btn" onclick="showTab('history', this)">History</button>
<button class="tab-btn" onclick="showTab('link', this)">Link Account</button>

<!-- =====================
     LOAD CREDIT TAB
===================== -->
<div id="load" class="tab active">
<form method="POST">
<table border="1">
<tr><th colspan="2">CREDIT LOADING</th></tr>

<tr>
<td>Customer ID</td>
<td><input type="number" name="customer_id" required></td>
</tr>

<tr>
<td>Load Amount (₱)</td>
<td><input type="number" name="amount" required></td>
</tr>

<tr>
<td colspan="2" align="center">
<button class="checkout-button" name="btn_load">CONFIRM LOAD</button>
</td>
</tr>
</table>
</form>
</div>

<!-- =====================
     HISTORY TAB
===================== -->
<div id="history" class="tab">
<table border="1">
<tr>
<th>Customer</th>
<th>Loaded</th>
<th>New Balance</th>
<th>Date</th>
</tr>

<?php
$sql = "
SELECT ch.datetime, ch.amount, ch.account_amount,
       c.fname, c.mname, c.lname
FROM credit_history ch
JOIN customer c ON ch.customer_id = c.customer_id
ORDER BY ch.datetime DESC
";
$res = $con->query($sql);

while ($r = $res->fetch_assoc()) {
  echo "
  <tr>
    <td>{$r['lname']}, {$r['fname']} {$r['mname']}</td>
    <td>₱{$r['amount']}</td>
    <td>₱{$r['account_amount']}</td>
    <td>{$r['datetime']}</td>
  </tr>";
}
?>
</table>
</div>

<!-- =====================
     LINK CREDIT TAB
===================== -->
<div id="link" class="tab">
<table border="1">
<tr>
<th>Customer</th>
<th>Action</th>
</tr>

<?php
$sql = "
SELECT c.customer_id, c.fname, c.mname, c.lname
FROM customer c
LEFT JOIN credit cr ON c.customer_id = cr.customer_id
WHERE cr.credit_id IS NULL
";
$res = $con->query($sql);

while ($r = $res->fetch_assoc()) {
  echo "
  <tr>
    <td>{$r['lname']}, {$r['fname']} {$r['mname']}</td>
    <td>
      <form method='POST'>
        <input type='hidden' name='customer_id' value='{$r['customer_id']}'>
        <button name='btn_link'>Create Credit Account</button>
      </form>
    </td>
  </tr>";
}
?>
</table>
</div>

</center>

<script>
function showTab(tab, btn) {
  document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
  document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));
  document.getElementById(tab).classList.add("active");
  btn.classList.add("active");
}
</script>

</body>
</html>