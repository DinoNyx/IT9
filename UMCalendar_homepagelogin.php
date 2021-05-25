<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
	<title>UM Calendar - Login</title>
	<link rel="icon" href="UM logo.ico" type="image/icon type"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link type="javascript" rel="stylesheet" href="UMCalendar.js?v=<?php echo time(); ?>"/>
	<link type="text/css" rel="stylesheet" href="UMCalendar.css?v=<?php echo time(); ?>"/>
</head>
<body background="background.png" class = "bdy-homepage">
</body>
<div class = "container-lg center" style="margin-top: 0px">
	<h1>University of Mindanao School Calendar</h1>
	
	<div class="container-sm center div_loginregister">
		<span>LOGIN</span>
		<form method="post" action="UMCalendar_homepagelogin.php">
			<input class = "txt-login" type="text" name="id" id = "id" placeholder="Enter school ID...">
			<input type="password" name="pwd" id = "pwd" placeholder="Enter password...">
			<button class = "btn-homepage"id="login" name = "login">Login</button>
		</form>
	</div>
	<br>
	<div class="container-sm center div_loginregister">
		REGISTER
		<form method="post" action="UMCalendar_homepagelogin.php">
			<input class = "txt-login" type="text" name="idreg" id = "idreg" placeholder="Enter school ID..." minlength="6" maxlength="6" onkeypress='validate(event)' required>
			<input class = "txt-login" type="text" name="name" id = "name" placeholder="Enter full name..." required>
			<input type="password" name="newpwd" id = "newpwd" placeholder="Enter new password..."required>
			<input type="password" name="confirmpwd" id = "confirmpwd" placeholder="Confirm new password..." minlength="8"required>
			<span id="message" style="font-size: 15px"></span>		
			<input class = "email-homepage" type="email" name="email" id = "email" placeholder="Enter email..."required>
			<input class = "txt-login" type="text" name="cnumber" id = "cnumber" placeholder="Enter contact number..."
			onkeypress='validate(event)' minlength = "11" maxlength= "11" required>

			<select class = "slct-register"name = "acctype">
				<option value="Student">Student</option>
				<option value="Faculty">Faculty</option>
			</select><br>
			<button class = "btn-homepage" id="register" name = "register">Register</button><br>

		</form>
	</div>
</div>

<script type="text/javascript">
	$('#newpwd, #confirmpwd').on('keyup', function () {
		if ($('#newpwd').val() == $('#confirmpwd').val()) {
			$('#message').html('').css('color', 'green');
		} else 
		$('#message').html('Not Matching').css('color', 'red');
	});

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
if(isset($_POST['login'])){
	require('UMCalendar_db.php');

	$id = $_POST['id'];
	$pwd = $_POST['pwd'];  
	$db = new Database();

	$idLists = $db->getIDLists();
	$passwordLists = $db->getPasswordLists();
	$doesExist = false;
	for($i = 0; $i < sizeof($idLists); $i++)
	{
		if($id == $idLists[$i] and $pwd == $passwordLists[$i])
		{
			$doesExist = true;
			break;
		}
	}
	if($doesExist)
	{
		$conn = mysqli_connect("localhost", "root", "","UMCalendar_db");
		$accountType = "";
		$accountName = "";
		$sqlQuery = "SELECT NAME,TYPE FROM accounts WHERE ID = '$id'";
		$resultSet = mysqli_query($conn, $sqlQuery);
		if ($resultSet-> num_rows > 0){
			while($row = $resultSet-> fetch_assoc()){
				$accountName = $row['NAME'];
				$accountType = $row['TYPE'];
			}
		}
		$_SESSION['accntName'] = $accountName;
		$_SESSION['accntType'] = $accountType;
		echo '<script type="text/javascript">
		location.replace("UMCalendar_mainpage.php");
		</script>';
	}
	else
	{
		echo '<script> alert ("Incorrect ID or password!")</script>';
	}
}
if(isset($_POST['register']))
{
	require('UMCalendar_db.php');

	$id = $_POST['idreg'];
	$name = $_POST['name'];
	$pwd = $_POST['newpwd'];;
	$email = $_POST['email'];
	$cnumber = $_POST['cnumber'];
	$acctype = $_POST['acctype'];

	$db = new Database();
	$db->createAccountTable();
	if ($_POST["newpwd"] === $_POST["confirmpwd"]) {
		$insResult = $db->addUser($id, $name, $pwd, $email, $cnumber, $acctype);
		if($insResult == 0)
		{
			echo "<script>alert('Account has been created!')</script>";
			echo "<meta http-equiv='refresh' content='0'>";
		}
		else
		{
			echo '<script> alert ("Error!")</script>'. $insResult;
		}
	}
	else{
		echo "<script>alert('Password does not match!')</script>";
	}
}?>