<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MENU Seller - Incoming Orders</title>
  <link rel="stylesheet" href="styles4.css">
</head>
<body>

<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: MENULogin.php");
  exit();
}

$seller_id = $_SESSION['user_id'];

/* ============================
   COMPLETE ORDER
============================ */
if (isset($_POST['complete_order'])) {
  $cart_id = (int)$_POST['cart_id'];

  $con->begin_transaction();

  try {
    // Get cart + customer + credit
    $q = "
      SELECT c.cart_id, c.total_price,
             cust.customer_id,
             cr.credit_id, cr.credit_amount, cr.active
      FROM cart c
      JOIN customer cust ON c.customer_id = cust.customer_id
      JOIN credit cr ON cust.customer_id = cr.customer_id
      WHERE c.cart_id = $cart_id
        AND c.status = 'Pending'
        AND cr.active = 1
      FOR UPDATE
    ";
    $res = $con->query($q);
    if ($res->num_rows === 0) {
      throw new Exception("Invalid cart or inactive credit.");
    }

    $row = $res->fetch_assoc();

    if ($row['credit_amount'] < $row['total_price']) {
      throw new Exception("Insufficient credit.");
    }

    // Deduct credit
    $newBalance = $row['credit_amount'] - $row['total_price'];
    $con->query("
      UPDATE credit
      SET credit_amount = $newBalance
      WHERE credit_id = {$row['credit_id']}
    ");

    // Insert order_details
    $con->query("
      INSERT INTO order_details
        (cart_id, ordered_on, price, status)
      VALUES
        ($cart_id, NOW(), {$row['total_price']}, 'Completed')
    ");

    // Update cart status
    $con->query("
      UPDATE cart
      SET status = 'Completed'
      WHERE cart_id = $cart_id
    ");

    $con->commit();

    echo "<script>alert('Order completed successfully');location.href='MENUOrders.php';</script>";
    exit();

  } catch (Exception $e) {
    $con->rollback();
    echo "<script>alert('{$e->getMessage()}');</script>";
  }
}
?>

<header>
	<img src="MenuLOGO.png" alt="Header Image" class="header-image" />
	<a href="MENUSeller.php" class="header-button"><p class="header-logout">EDIT PRODUCTS</p></a>
	<a href="MENUOrders.php" class="active-button"><p class="header-logout">INCOMING ORDERS</p></a>
	<a href="MENUCompletedOrders.php" class="header-button"><p class="header-logout">RECENT ORDERS</p></a>
	<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
</header>

<center>

<table border="1">
<tr><th colspan="4">INCOMING ORDERS</th></tr>

<?php
$sql = "
SELECT
  c.cart_id,
  c.total_price,
  cust.fname,
  cust.lname,
  cust.priority_type,
  CASE
    WHEN cust.priority_type = 'PWD' THEN 3
    WHEN cust.priority_type = 'Faculty' THEN 2
    ELSE 1
  END AS priority
FROM cart c
JOIN customer cust ON c.customer_id = cust.customer_id
WHERE c.status = 'Pending'
ORDER BY priority DESC, c.cart_id ASC
LIMIT 10
";

$res = $con->query($sql);

while ($row = $res->fetch_assoc()) {

  echo "
  <tr class='pending'>
    <td>
      <b>Cart #{$row['cart_id']}</b><br>
      {$row['lname']}, {$row['fname']}<br>
      Priority: {$row['priority_type']}
    </td>

    <td>
      Total: ₱".number_format($row['total_price'],2)."
    </td>

    <td>
      <form method='POST'>
        <input type='hidden' name='cart_id' value='{$row['cart_id']}'>
        <button name='complete_order' class='checkout-button'>
          Complete Order
        </button>
      </form>
    </td>
  </tr>";
}
?>

</table>

</center>
</body>
</html>