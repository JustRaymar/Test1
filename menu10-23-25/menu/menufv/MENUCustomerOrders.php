<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="UTF-8" />
	  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	  <title>MENU – Pending Orders</title>
	  <link rel="stylesheet" href="styles3.css" />
	</head>

	<body>
		<?php
		session_start();
		include("connection.php");

		if (!isset($_SESSION['user_id'])) {
		  header("Location: MENULogin.php");
		  exit();
		}

		$user_id = intval($_SESSION['user_id']);
		?>

		<main>
		<header>
		  <img src="MenuLOGO.png" alt="Header Image" class="header-image" />
		  <a class="header-button" href="MENUHome.php"><p class="header-logout">VIEW MENU</p></a>
		  <a href="MENUCart.php" class="header-button"><p class="header-logout">VIEW CART</p></a>
		  <a href="MENUCustomerOrders.php" class="active-button"><p class="header-logout">MY ORDERS</p></a>
		  <a href="MENUCustomerHistory.php" class="header-button"><p class="header-logout">ORDER HISTORY</p></a>
		  <a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
		</header>

		<center>

		<table border="1">
		  <tr>
			<th colspan="4">PENDING ORDERS</th>
		  </tr>

		<?php
		/* ---------- Pagination ---------- */
		$limit = 5;
		$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
		$start = ($page - 1) * $limit;

		/* ---------- Main Query ---------- */
		$sql = "
			SELECT
				  c.cart_id,
				  c.total_price,
				  c.status,
				  MAX(cd.datetime) AS ordered_on,
				  SUM(cd.quantity) AS total_items
				FROM cart c
				JOIN cart_details cd ON c.cart_id = cd.cart_id
				WHERE c.customer_id = $user_id
				AND c.status = 'Pending'
				GROUP BY c.cart_id
				ORDER BY ordered_on DESC
				LIMIT $start, $limit
		";

		$res = $con->query($sql);

		if ($res && $res->num_rows > 0) {
		  while ($row = $res->fetch_assoc()) {

			echo "<tr class='pending'>";
			echo "<td><strong>Order #{$row['cart_id']}</strong></td>";
			echo "<td>x{$row['total_items']} items</td>";
			echo "<td>₱{$row['total_price']}</td>";
			echo "
			  <td>
				<button class='total-button' onclick='openModal(".$row['cart_id'].")'>
					  View Order
					</button>
			  </td>
			";
			echo "</tr>";
		  }
		} else {
		  echo "<tr><td colspan='4'>No pending orders.</td></tr>";
		}
		?>
		</table>

		<br/>

		<!-- Pagination -->
		<div class="pagination">
		<?php
		$count_sql = "
			SELECT COUNT(DISTINCT od.order_id) AS total
			FROM order_details od
			JOIN cart c ON od.cart_id = c.cart_id
			WHERE c.customer_id = $user_id
			AND od.status = 'Pending'
		";

		$count_res = $con->query($count_sql);
		$count_row = $count_res->fetch_assoc();
		$total_pages = ceil($count_row['total'] / $limit);

		for ($i = 1; $i <= $total_pages; $i++) {
		  if ($i == $page) {
			echo "<a class='active' href='MENUCustomerOrders.php?page=$i'>$i</a>";
		  } else {
			echo "<a href='MENUCustomerOrders.php?page=$i'>$i</a>";
		  }
		}
		?>
		</div>

		</center>
		</main>

		<!-- Modal -->
		<div id="orderModal" class="modal">
		  <div class="modal-content">
			<span class="close-modal" onclick="closeModal()">&times;</span>
			<div class="inner-modal" id="orderModalBody">
			  <!-- AJAX content -->
			</div>
		  </div>
		</div>

		<script>
		function openModal(cartId) {
		  fetch("getOrderDetails.php?cart_id=" + cartId)
			.then(res => res.text())
			.then(html => {
			  document.getElementById("orderModalBody").innerHTML = html;
			  document.getElementById("orderModal").style.display = "block";
			});
		}

		function closeModal() {
		  document.getElementById("orderModal").style.display = "none";
		}

		window.onclick = function (e) {
		  const modal = document.getElementById("orderModal");
		  if (e.target === modal) closeModal();
		};
		</script>

	</body>
</html>