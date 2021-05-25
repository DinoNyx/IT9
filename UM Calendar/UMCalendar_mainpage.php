<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
	<title>UM Calendar - Main</title>
	<link rel="icon" href="UM logo.ico" type="image/icon type"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link type="javascript" rel="stylesheet" href="UMCalendar.js?v=<?php echo time(); ?>"/>
	<link type="text/css" rel="stylesheet" href="UMCalendar.css?v=<?php echo time(); ?>"/>
</head>
<body class = "bdy-main">
	<div class = "container-lg row">
		<div class="col-12 col-md-8">
			<form action="UMCalendar_homepagelogin.php">
				<button class="btn-main" name = "Logout">Logout</button>
			</form>
		</div>

		<div class="col-6 col-md-4">
			<center>
				<h4>University of Mindanao</h4>
				<h2><b>SCHOOL CALENDAR</b></h2>
				<?php
				require('UMCalendar_db.php');
				$db = new Database();
				$school_year = $db->getSYLists();
				echo '<h5>School year '. implode($school_year) . '</h5>';
				?>
			</center>

			<table class = "tbl-main">
				<tr>
					<th colspan="3">First Semester</th>
				</tr>

				<?php
				$db = new Database();
				$db->showEvents_FSEM();
				?>
			<br>
			<table class = "tbl-main">
				<tr>
					<th colspan="3">Second Semester</th>
				</tr>

				<?php
				$db = new Database();
				$db->showEvents_SSEM();
				?>
				<br>
			<table class = "tbl-main">
				<tr>
					<th colspan="3">Summer</th>
				</tr>

				<?php
				$db = new Database();
				$db->showEvents_SUM();
				?>
		</div>
	</div>
</body>
</html>
<?php
echo '<p class = "loginAs">Welcome, ' . $_SESSION["accntName"]. "! You are logged in as " . $_SESSION["accntType"]. ' mode.</p>';
if(isset($_POST['logout'])){
	session_destroy();
}  
if ($_SESSION['accntType'] == null){
	echo '<script>alert("Page is restricted to UM students and faculty only.")</script>';
	echo '<script type="text/javascript">
	location.replace("UMCalendar_homepagelogin.php");
	</script>';
}
if($_SESSION['accntType']=='Admin'){
	echo "<form action='UMCalendar_auth.php'>";
	echo "<button class = 'btn-main-admin' name = 'settings'>Settings</button";
	echo "</form>";
}	
?>