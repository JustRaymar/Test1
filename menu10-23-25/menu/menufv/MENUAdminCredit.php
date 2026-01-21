<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>MENU Admin - Credit Loading</title>
		<link rel="stylesheet" href="styles6.css" />
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
				<a href="MENUAdmin.php" class="header-button"><p class="header-logout">MANAGE USERS</p></a>
				<a href="MENUAdminTransactions.php" class="header-button"><p class="header-logout">TRANSACTION LOGS</p></a>
				<a href="MENUAdminCredit.php" class="active-button"><p class="header-logout">LOAD CREDIT</p></a>
				<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
		</header>

			<center>

				<!-- CREDIT LOADING PANEL -->
				<table border="1px">
					<tr>
						<th colspan="2">CREDIT LOADING</th>
					</tr>

					<tr>
						<td width="30%"><b>User ID</b></td>
						<td>
							<input type="text" placeholder="Enter User ID" />
						</td>
					</tr>

					<tr>
						<td><b>Account Name</b></td>
						<td>
							<input type="text" placeholder="Auto-filled user name" disabled />
						</td>
					</tr>

					<tr>
						<td><b>Current Balance</b></td>
						<td>
							<input type="text" placeholder="₱0.00" disabled />
						</td>
					</tr>

					<tr>
						<td><b>Load Amount (₱)</b></td>
						<td>
							<input type="text" placeholder="Enter amount to load" />
						</td>
					</tr>

					<tr>
						<td><b>Remarks</b></td>
						<td>
							<input type="text" placeholder="Optional note (e.g. Manual adjustment)" />
						</td>
					</tr>

					<tr>
						<td colspan="2" align="center">
							<button class="checkout-button">CONFIRM LOAD</button>
							<button class="clear-button">CLEAR</button>
						</td>
					</tr>
				</table>

				<br />

				<!-- CREDIT LOAD HISTORY -->
				<table border="1px">
					<tr>
						<th colspan="5">CREDIT LOAD HISTORY</th>
					</tr>
					<tr>
						<th class="history">User</th>
						<th class="history">Previous Balance</th>
						<th class="history">Loaded Amount</th>
						<th class="history">New Balance</th>
						<th class="history">Date</th>
					</tr>

					<tr class="complete">
						<td>UID 1023</td>
						<td>₱500.00</td>
						<td>₱200.00</td>
						<td>₱700.00</td>
						<td>2026-01-18</td>
					</tr>

					<tr class="complete">
						<td>UID 1011</td>
						<td>₱1,200.00</td>
						<td>₱300.00</td>
						<td>₱1,500.00</td>
						<td>2026-01-17</td>
					</tr>

					<tr class="complete">
						<td>UID 1008</td>
						<td>₱250.00</td>
						<td>₱150.00</td>
						<td>₱400.00</td>
						<td>2026-01-16</td>
					</tr>
				</table>

				<br />

				<div class="pagination">
					<a href="#" class="active">1</a>
					<a href="#">2</a>
					<a href="#">3</a>
				</div>

			</center>
		</main>

	</body>
</html>