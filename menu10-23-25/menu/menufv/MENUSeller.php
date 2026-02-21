<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="UTF-8" />
	  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	  <title>MENU Seller Menu</title>
	  <link rel="stylesheet" href="styles4.css" />
	</head>
	<body>
	<?php
		session_start();
		include("connection.php");

		if (!isset($_SESSION['user_id'])) {
		  header("Location: MENULogin.php");
		  exit();
		}

		$sql = "
		  SELECT store_id 
		  FROM personnel 
		  WHERE user_id = ?
		";
		$stmt = $con->prepare($sql);
		$stmt->bind_param("i", $_SESSION['user_id']);
		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();

		if (!$user) {
		  die("Seller not linked to a store.");
		}

		$_SESSION['store_id'] = $user['store_id'];
	?>
	<main>
	<header>
	  <img src="MenuLOGO.png" alt="Header Image" class="header-image" />
		<a href="MENUSeller.php" class="active-button"><p class="header-logout">EDIT PRODUCTS</p></a>
		<a href="MENUOrders.php" class="header-button"><p class="header-logout">INCOMING ORDERS</p></a>
		<a href="MENUCompletedOrders.php" class="header-button"><p class="header-logout">RECENT ORDERS</p></a>
		<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
	</header>
	
	  <div class="grid-container" id="product-grid">
		<div class="grid-item add-product-tile" onclick="openAddProductModal()">
		  <img src="plus.png">
		  <h2>Add New Product</h2>
		</div>
	  </div>
	</main>

	<!-- ADD PRODUCT MODAL -->
	<div id="add-product-modal" class="modal">
	  <div class="modal-content">
		<span class="close" onclick="closeModal()">&times;</span>
		<h2>Add Product</h2>

		<form id="add-product-form">
		  <label>Product Name</label>
		  <input type="text" id="product-name" required>

		  <label>Quantity</label>
		  <input type="number" id="product-amount" min="1" value="1" required>

		  <label>Price</label>
		  <input type="number" id="product-price" step="0.01" required>

		  <label>Description</label>
		  <textarea id="product-description" required></textarea>

		  <button type="submit">Add</button>
		</form>
	  </div>
	</div>

	<!-- EDIT PRODUCT MODAL -->
	<div id="edit-product-modal" class="modal">
	  <div class="modal-content">
		<span class="close" onclick="closeModal()">&times;</span>
		<h2>Edit Product</h2>

		<form id="edit-product-form">
		  <input type="hidden" id="edit-product-id">

		  <label>Product Name</label>
		  <input type="text" id="edit-product-name" required>

		  <label>Quantity</label>
		  <input type="number" id="edit-product-amount" min="1" required>

		  <label>Price</label>
		  <input type="number" id="edit-product-price" step="0.01" required>

		  <label>Description</label>
		  <textarea id="edit-product-description" required></textarea>

		  <button type="submit">Save Changes</button>
		</form>
	  </div>
	</div>

	<script src="seller.js"></script>
	</body>
</html>