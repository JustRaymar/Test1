<?php
	session_start();
	include("connection.php");

	if (!isset($_SESSION['user_id'])) {
	  http_response_code(403);
	  exit("Unauthorized");
	}

	if (!isset($_GET['cart_id'])) {
	  http_response_code(400);
	  exit("Missing cart ID");
	}

	$cart_id = (int)$_GET['cart_id'];
	$user_id = (int)$_SESSION['user_id'];

	$sqlCart = "
	  SELECT cart_id, total_price, status
	  FROM cart
	  WHERE cart_id = $cart_id
	  AND customer_id = $user_id
	";
	$resCart = $con->query($sqlCart);

	if ($resCart->num_rows === 0) {
	  exit("<p>Order not found.</p>");
	}

	$cart = $resCart->fetch_assoc();

	$sqlItems = "
	  SELECT
		cd.quantity,
		cd.unit_price,
		cd.total_price,
		cd.datetime,
		p.product_name,
		s.store_name
	  FROM cart_details cd
	  JOIN product p ON cd.product_id = p.product_id
	  JOIN store s ON cd.store_id = s.store_id
	  WHERE cd.cart_id = $cart_id
	  ORDER BY cd.datetime ASC
	";
	$resItems = $con->query($sqlItems);

	if ($resItems->num_rows === 0) {
	  exit("<p>No items found for this order.</p>");
	}
?>

<!-- ORDER DETAILS UI -->
<table class="receipt">
  <tr>
    <td><strong>Order ID:</strong></td>
    <td><?= $cart_id ?></td>
  </tr>
  <tr>
    <td><strong>Status:</strong></td>
    <td><?= htmlspecialchars($cart['status']) ?></td>
  </tr>
</table>

<br>

<table class="receipt" border="1" width="100%">
  <tr>
    <th>Product</th>
    <th>Store</th>
    <th>Qty</th>
    <th>Unit Price</th>
    <th>Total</th>
  </tr>

<?php
$grandTotal = 0;

while ($row = $resItems->fetch_assoc()):
  $grandTotal += $row['total_price'];
?>
  <tr>
    <td><?= htmlspecialchars($row['product_name']) ?></td>
    <td><?= htmlspecialchars($row['store_name']) ?></td>
    <td>x<?= $row['quantity'] ?></td>
    <td>₱<?= number_format($row['unit_price'], 2) ?></td>
    <td>₱<?= number_format($row['total_price'], 2) ?></td>
  </tr>
<?php endwhile; ?>

  <tr>
    <td colspan="4" style="text-align:right;"><strong>Grand Total:</strong></td>
    <td><strong>₱<?= number_format($grandTotal, 2) ?></strong></td>
  </tr>
</table>