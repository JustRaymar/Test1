<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MENU Admin - Manage Users</title>
  <link rel="stylesheet" href="styles6.css">
  <style>
    .tab-btn { padding:10px 20px; cursor:pointer; }
    .tab-btn.active { background:#333; color:#fff; }
    .tab { display:none; }
    .tab.active { display:block; }
    form.inline { display:inline; }
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
   TOGGLE CREDIT STATUS
===================== */
if (isset($_POST['toggle_credit'])) {
  $credit_id = (int)$_POST['credit_id'];
  $new_state = (int)$_POST['new_state'];

  $con->query("
    UPDATE credit
    SET active = $new_state
    WHERE credit_id = $credit_id
  ");

  echo "<script>window.location='MENUAdmin.php';</script>";
  exit();
}

/* =====================
   SAVE CHANGES
===================== */
if (isset($_POST['btn_save'])) {
  $user_id  = $_POST['user_id'];
  $email    = $_POST['email'];
  $password = $_POST['password'];
  $fname    = $_POST['fname'];
  $mname    = $_POST['mname'];
  $lname    = $_POST['lname'];
  $type     = $_POST['type'];
  $priority = $_POST['priority'] ?? null;

  $con->query("
    UPDATE user
    SET email='$email', password='$password'
    WHERE user_id=$user_id
  ");

  if ($type === "customer") {
    $con->query("
      UPDATE customer
      SET fname='$fname', mname='$mname', lname='$lname',
          priority_type='$priority'
      WHERE user_id=$user_id
    ");
  }

  if ($type === "staff" || $type === "other") {
    $con->query("
      UPDATE personnel
      SET fname='$fname', mname='$mname', lname='$lname'
      WHERE user_id=$user_id
    ");
  }

  echo "<script>window.location='MENUAdmin.php';</script>";
  exit();
}
?>

<header>
	<img src="MenuLOGO.png" alt="Header Image" class="header-image" />
	<a href="MENUAdmin.php" class="active-button"><p class="header-logout">MANAGE USERS</p></a>
	<a href="MENUAdminTransactions.php" class="header-button"><p class="header-logout">TRANSACTION LOGS</p></a>
	<a href="MENUAdminCredit.php" class="header-button"><p class="header-logout">LOAD CREDIT</p></a>
	<a href="logout.php" class="header-button"><p class="header-logout">LOGOUT</p></a>
</header>

<center>

<button class="tab-btn active" onclick="showTab('customers',this)">Customers</button>
<button class="tab-btn" onclick="showTab('staff',this)">Staff</button>
<button class="tab-btn" onclick="showTab('other',this)">Other</button>

<!-- =====================
     CUSTOMERS TAB
===================== -->
<div id="customers" class="tab active">
<h2>Customers</h2>
<table border="1">
<tr>
  <th>User ID</th>
  <th>Name</th>
  <th>Email</th>
  <th>Priority</th>
  <th>Credit ID</th>
  <th>Credit Status</th>
  <th>Action</th>
</tr>

<?php
$sql = "
SELECT 
  u.user_id, u.email, u.password,
  c.fname, c.mname, c.lname, c.priority_type,
  cr.credit_id, cr.active
FROM user u
JOIN customer c ON u.user_id = c.user_id
LEFT JOIN credit cr ON c.customer_id = cr.customer_id
";
$res = $con->query($sql);

while ($r = $res->fetch_assoc()) {
  $creditId = $r['credit_id'] ?? "—";
  $status = $r['credit_id'] === null ? "Not Linked" : ($r['active'] ? "Active" : "Disabled");

  echo "<tr>
    <td>{$r['user_id']}</td>
    <td>{$r['lname']}, {$r['fname']} {$r['mname']}</td>
    <td>{$r['email']}</td>
    <td>{$r['priority_type']}</td>
    <td>$creditId</td>
    <td>$status</td>
    <td>
      <button onclick='openEdit(
        {$r['user_id']},
        \"customer\",
        \"{$r['email']}\",
        \"{$r['password']}\",
        \"{$r['fname']}\",
        \"{$r['mname']}\",
        \"{$r['lname']}\",
        \"{$r['priority_type']}\"
      )'>Edit</button>";

  if ($r['credit_id'] !== null) {
    $newState = $r['active'] ? 0 : 1;
    $label = $r['active'] ? "Disable Credit" : "Enable Credit";

    echo "
      <form method='POST' class='inline'>
        <input type='hidden' name='credit_id' value='{$r['credit_id']}'>
        <input type='hidden' name='new_state' value='$newState'>
        <button name='toggle_credit'>$label</button>
      </form>";
  }

  echo "</td></tr>";
}
?>
</table>
</div>

<!-- =====================
     STAFF TAB
===================== -->
<div id="staff" class="tab">
<h2>Staff (Sellers)</h2>
<table border="1">
<tr><th>User ID</th><th>Name</th><th>Email</th><th>Action</th></tr>

<?php
$res = $con->query("
SELECT u.user_id,u.email,u.password,p.fname,p.mname,p.lname
FROM user u
JOIN personnel p ON u.user_id=p.user_id
WHERE p.role_id=1
");

while ($r = $res->fetch_assoc()) {
  echo "<tr>
    <td>{$r['user_id']}</td>
    <td>{$r['lname']}, {$r['fname']} {$r['mname']}</td>
    <td>{$r['email']}</td>
    <td><button onclick='openEdit(
      {$r['user_id']},\"staff\",
      \"{$r['email']}\",\"{$r['password']}\",
      \"{$r['fname']}\",\"{$r['mname']}\",\"{$r['lname']}\",\"\"
    )'>Edit</button></td>
  </tr>";
}
?>
</table>
</div>

<!-- =====================
     OTHER TAB
===================== -->
<div id="other" class="tab">
<h2>Admin & Owner</h2>
<table border="1">
<tr><th>User ID</th><th>Role</th><th>Name</th><th>Email</th><th>Action</th></tr>

<?php
$res = $con->query("
SELECT u.user_id,u.email,u.password,p.fname,p.mname,p.lname,r.role_name
FROM user u
JOIN personnel p ON u.user_id=p.user_id
JOIN personnel_roles r ON p.role_id=r.role_id
WHERE p.role_id IN (2,3)
");

while ($r = $res->fetch_assoc()) {
  echo "<tr>
    <td>{$r['user_id']}</td>
    <td>{$r['role_name']}</td>
    <td>{$r['lname']}, {$r['fname']} {$r['mname']}</td>
    <td>{$r['email']}</td>
    <td><button onclick='openEdit(
      {$r['user_id']},\"other\",
      \"{$r['email']}\",\"{$r['password']}\",
      \"{$r['fname']}\",\"{$r['mname']}\",\"{$r['lname']}\",\"\"
    )'>Edit</button></td>
  </tr>";
}
?>
</table>
</div>

</center>

<!-- =====================
     EDIT MODAL
===================== -->
<div id="editModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeEdit()">&times;</span>

<form method="POST">
<input type="hidden" name="user_id" id="m_uid">
<input type="hidden" name="type" id="m_type">

<label>Email</label>
<input type="text" name="email" id="m_email">

<label>Password</label>
<input type="text" name="password" id="m_password">

<label>First Name</label>
<input type="text" name="fname" id="m_fname">

<label>Middle Name</label>
<input type="text" name="mname" id="m_mname">

<label>Last Name</label>
<input type="text" name="lname" id="m_lname">

<div id="priorityBox">
<label>Priority</label>
<select name="priority" id="m_priority">
  <option value="None">None (Student)</option>
  <option value="Faculty">Faculty</option>
  <option value="PWD">PWD</option>
</select>
</div>

<br>
<button name="btn_save">Save Changes</button>
</form>
</div>
</div>

<script>
function showTab(tab, btn){
  document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
  document.getElementById(tab).classList.add('active');
  btn.classList.add('active');
}

function openEdit(id,type,email,pw,fn,mn,ln,pri){
  m_uid.value=id;
  m_type.value=type;
  m_email.value=email;
  m_password.value=pw;
  m_fname.value=fn;
  m_mname.value=mn;
  m_lname.value=ln;

  priorityBox.style.display = type==="customer" ? "block" : "none";
  if(type==="customer") m_priority.value = pri || "None";

  editModal.style.display="block";
}

function closeEdit(){ editModal.style.display="none"; }
</script>

</body>
</html>