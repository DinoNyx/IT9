<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
<title>UM Calendar - Accounts</title>
<link rel="icon" href="UM logo.ico" type="image/icon type"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link type="javascript" rel="stylesheet" href="UMCalendar.js?v=<?php echo time(); ?>"/>
<link type="text/css" rel="stylesheet" href="UMCalendar.css?v=<?php echo time(); ?>"/>
</head>
<body class = 'bdy-settings'>
<div class="container-lg div-settings center">
	<h5>Students' accounts</h5>
	<table class = "tbl-admin">
		<tr class = "tr-admin">
			<th class = "th-admin">ID</th>
			<th class = "th-admin">Name</th>
			<th class = "th-admin">Password</th>
			<th class = "th-admin">Email</th>
			<th class = "th-admin">Contact Number</th>
			<th class = "th-admin">Category</th>
		</tr>

		<?php
		require('UMCalendar_db.php');
		$db = new Database();
		$db->displayStudents();
		?>
	<h5>Faculty accounts</h5>
	<table class = "tbl-admin">
		<tr class = "tr-admin">
			<th class = "th-admin">ID</th>
			<th class = "th-admin">Name</th>
			<th class = "th-admin">Password</th>
			<th class = "th-admin">Email</th>
			<th class = "th-admin">Contact Number</th>
			<th class = "th-admin">Category</th>
		</tr>

		<?php
		$db = new Database();
		$db->displayFaculty();
		?>
	<h5>Admin accounts</h5>
	<table class = "tbl-admin">
		<tr class = "tr-admin">
			<th class = "th-admin">ID</th>
			<th class = "th-admin">Name</th>
			<th class = "th-admin">Password</th>
			<th class = "th-admin">Email</th>
			<th class = "th-admin">Contact Number</th>
			<th class = "th-admin">Category</th>
		</tr>

		<?php
		$db = new Database();
		$db->displayAdmins();
		?>

<hr color = "#800">
<h4>Account settings</h4>
<form method="POST">
	<label>Delete account: </label>
	<input class = 'num-accounts' type="text" name="delid" onkeypress='validate(event)'/>
	<button name = 'delacc' class='btn-accounts'><i class='fa fa-trash'></i></button><br>
</form>
<form method = "POST">
	<label>Edit account: </label>
	<input class = 'num-accounts' type="text" name="editid" onkeypress='validate(event)' placeholder="ID" required/>
	<input type="text" name="name" placeholder = "Name" required/>
	<input class = 'email-accounts' type="email" name="email" placeholder = "Email" required/>
	<input class = 'email-accounts' type="password" name="pwd" placeholder = "New password" required/>
	<input type="text" name="cnumber" placeholder = "Contact Number" onkeypress='validate(event)'required/>
	<button name = 'editacc' class='btn-accounts'>Edit</button>
</form>
<hr color = '#800'>
<h4>Add admin account</h4>
<form method="POST">
	<input class = 'num-accounts' type="text" name="newid" onkeypress='validate(event)' placeholder="ID" required/>
	<input type="text" name="adminname" placeholder = "Name" required/>
	<input class = 'email-accounts' type="email" name="adminemail" placeholder = "Email" required/>
	<input class = 'email-accounts' type="password" name="adminpwd" placeholder = "Password" required/>
	<input class = 'email-accounts' type="password" name="admincpwd" placeholder = "Confirm Password" required/>
	<input type="text" name="admincnumber" placeholder = "Contact Number" onkeypress='validate(event)'required/>
	<button name = 'register' class='btn-accounts'>Register</button>
</form>

<br>
		<form action = "UMCalendar_settings.php">
 		<button>Return</button>
	</form>
	</div>
</body>
<script type="text/javascript">
	function validate(evt) {
	var theEvent = evt || window.event;

  	if (theEvent.type === 'paste') {
  	key = event.clipboardData.getData('text/plain');
  	} else {

  	var key = theEvent.keyCode || theEvent.which;
  	key = String.fromCharCode(key);
}
var regex = /[0-9]|\./;
if( !regex.test(key) ) {
	theEvent.returnValue = false;
	if(theEvent.preventDefault) theEvent.preventDefault();
}
}
</script>
</html>

<?php
if ($_SESSION['accntType'] != 'Admin'){
	echo '<script type="text/javascript">
	location.replace("UMCalendar_auth.php");
	</script>';
}
if(isset($_POST['delacc'])){

  $id = $_POST['delid'];

  $db = new Database();
  $insResult = $db->deleteAcc($id);
  if($insResult == 0)
  {
    echo '<script> alert ("Account has been deleted.")</script>';
    echo "<meta http-equiv='refresh' content='0'>";
  }
  else
  {
    echo '<script> alert ("Error!")</script>'. $insResult;
  }
}

if(isset($_POST['editacc']))
{
  $id = $_POST['editid'];
  $name = $_POST['name'];
  $pwd = $_POST['pwd'];
  $email = $_POST['email'];
  $cnumber = $_POST['cnumber'];

  $db = new Database();
  $insResult = $db->editAccount($id, $name, $pwd, $email, $cnumber);
  if($insResult == 0)
  {
    echo '<script> alert ("Account has been edited.")</script>';
    echo "<meta http-equiv='refresh' content='0'>";
  } 
  else{echo '<script> alert ("Error!")</script>'. $insResult;}
}

if(isset($_POST['register']))
{
  $id = $_POST['newid'];
  $name = $_POST['adminname'];
  $pwd = $_POST['adminpwd'];
  $email = $_POST['adminemail'];
  $cnumber = $_POST['admincnumber'];
  $type = 'Admin';
  $db = new Database();
  if ($_POST["adminpwd"] === $_POST["admincpwd"]) {
  	$insResult = $db->addUser($id, $name, $pwd, $email, $cnumber, $type);
  	if($insResult == 0)
  	{
  		echo '<script> alert ("Admin account has been added.")</script>';
  		echo "<meta http-equiv='refresh' content='0'>";
  	} 
  	else{echo '<script> alert ("Error!")</script>'. $insResult;}
  }
  else{
		echo "<script>alert('Password does not match!')</script>";
	}
}
?>