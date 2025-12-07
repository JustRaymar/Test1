<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>MENU Owner Order Statistics</title>
		<link rel="stylesheet" href="styles5.css" />
	</head>
	<body>
	<?php
		session_start();
		include("connection.php");
		if (!isset($_SESSION['user_id'])) {
			header("Location: MENULogin.php");
			exit();
		}
		//echo '<script>alert("Successfully logged in as '.$_SESSION['user_id'].'!");</script>';
		$sqlId = "SELECT * FROM sellers WHERE UserID = ".$_SESSION['user_id'];
		$resId = $con->query($sqlId);
		$rowId = $resId->fetch_assoc();
	?>
    <main>
	<header>
		<img src="MenuLOGO.png" alt="Header Image" class="header-image" />
		<a href="MENUOwner.php" class="header-button"><p class="header-logout">MY STORE PRODUCTS</p></a>
		<a href="MENUOwnerIncoming.php" class="header-button"><p class="header-logout">INCOMING ORDERS</p></a>
		<a href="MENUOwnerHistory.php" class="header-button"><p class="header-logout">ORDER HISTORY</p></a>
		<a href="MENUOwnerStats.php" class="active-button"><p class="header-logout">ORDER STATISTICS</p></a>
		<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
    </header>
	<center>
		<div>
			<div class="pie-chart-container">
			  <div class="pie-chart"></div>
			  <div class="hover-zone zone-a" data-img="modals/Pancit Canton.png"></div>
			  <div class="hover-zone zone-b" data-img="modals/Shawarma Rice.png"></div>
			  <div class="hover-zone zone-c" data-img="modals/Buttered Cheese Corn.png"></div>
			  <div class="center-circle">
				<span class="center-text">Most ordered</span>
				<img class="hover-image" src="" alt="Product image">
			  </div>
			</div>
			<div class="legend">
			  <div class="legend-item" data-img="modals/Pancit Canton_r.png">
				<span class="legend-color color-a"></span> Pancit Canton (40%)
			  </div>
			  <div class="legend-item" data-img="modals/Shawarma Rice_r.png">
				<span class="legend-color color-b"></span> Shawarma Rice (30%)
			  </div>
			  <div class="legend-item" data-img="modals/Buttered Cheese Corn_r.png">
				<span class="legend-color color-c"></span> Buttered Cheese Corn (30%)
			  </div>
			</div>
			<script>
				function setupHoverInteractions(container) {
				  const img = document.querySelector('.hover-image');
				  const text = container.querySelector('.center-text');
				  const triggers = [
					...container.querySelectorAll('.hover-zone'),
					...document.querySelectorAll('.legend-item')
				  ];

				  triggers.forEach(trigger => {
					trigger.addEventListener('mouseenter', () => {
					  img.src = trigger.dataset.img;
					  img.style.opacity = '1';
					  text.style.opacity = '0.5';
					});
					trigger.addEventListener('mouseleave', () => {
					  img.style.opacity = '0';
					  text.style.opacity = '1';
					});
				  });
				}

				document.querySelectorAll('.pie-chart-container').forEach(setupHoverInteractions);
			</script>
			<br/><br/><br/><br/><br/><br/><br/><br/>
			<?php
				//Top 3 most ordered items
				/*<table class='chart'>
				<tr>
				<th>Top Ordered Products</th>
				<th>Amount ordered</th>
				</tr>
				";
				$sqlA = "SELECT ProductName, SUM(Quantity) AS TotalOrdered FROM orders WHERE Status = 'Completed' GROUP BY ProductID ORDER BY TotalOrdered DESC LIMIT 3";
				$resA = $con->query($sqlA);
				if ($resA->num_rows>0) {
					while ($row=$resA->fetch_assoc()) {
						echo "
						<tr>
							<td><img src='modals/".$row['ProductName'].".png' class='img' alt='productimg'/></td>
							<td>".$row['TotalOrdered']."</td>
						</tr>
						";
					}
				}*/
				/*<table class='chart'>
				<tr>
				<th>Top Earning Products</th>
				<th>Profit amount</th>
				</tr>
				";
				$sqlB = "SELECT ProductName, SUM(OrderPrice) AS TotalPrices FROM orders WHERE Status = 'Completed' GROUP BY ProductID ORDER BY TotalPrices DESC LIMIT 3";
				$resB = $con->query($sqlB);
				if ($resB->num_rows>0) {
					while ($row=$resB->fetch_assoc()) {
						echo "
						<tr>
							<td><img src='modals/".$row['ProductName'].".png' class='img' alt='productimg'/></td>
							<td>₱".$row['TotalPrices']."</td>
						</tr>
						";
					}
				}
				echo "
				</table><br/><br/>";*/

				
				//Item most cancelled
				$sqlC = "SELECT ProductName, SUM(Quantity) AS TotalOrdered FROM orders WHERE Status = 'Cancelled' GROUP BY ProductID ORDER BY TotalOrdered DESC LIMIT 1;";
				$resC = $con->query($sqlC);
				$rowC=$resC->fetch_assoc();
				$itemC = $rowC['ProductName'];
				echo "
				<table class='chart'>
					<tr>
						<th colspan=6>Special stats</th>
					</tr>
					<tr>
						<td>Item with most orders cancelled</td>
						<td><img src='modals/".$itemC."_r.png' class='img' alt='productimg'/><p class='prodname'>".$itemC."</p></td>
				";
				
				//Item most completed
				$sqlD = "SELECT ProductName, SUM(Quantity) AS TotalOrdered FROM orders WHERE Status = 'Completed' GROUP BY ProductID ORDER BY TotalOrdered DESC LIMIT 1";
				$resD = $con->query($sqlC);
				$rowD=$resD->fetch_assoc();
				$itemD = $rowD['ProductName'];
				echo "
						<td>Item with most orders completed</td>
						<td><img src='modals/".$itemD."_r.png' class='img' alt='productimg'/><p class='prodname'>".$itemD."</p></td>
				";
				
				//Most ordered by customers with Priority
				$sqlE = "SELECT ProductName, SUM(Quantity) AS TotalOrdered FROM orders WHERE Status = 'Completed' and Priority = 1 GROUP BY ProductID ORDER BY TotalOrdered DESC LIMIT 1";
				$resE = $con->query($sqlC);
				$rowE=$resE->fetch_assoc();
				$itemE = $rowE['ProductName'];
				echo "
						<td>Item ordered most by customers with Priority</td>
						<td><img src='modals/".$itemE."_r.png' class='img' alt='productimg'/><p class='prodname'>".$itemE."</p></td>
					</tr>
				</table>
				";
			?>
			<p></p>
		</div>
	</center>
	</main>
	</body>
</html>