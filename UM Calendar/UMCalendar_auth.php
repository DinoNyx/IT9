<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
  <title>UM Calendar - Authentication</title>
  <link rel="icon" href="UM logo.ico" type="image/icon type">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link type="text/css" rel="stylesheet" href="UMCalendar.css?v=<?php echo time(); ?>"/>
</head>
<body class="bdy-settings">
  <?php
  if($_SESSION['accntType']=='Admin')
  {
    echo "<form method = 'POST'>";
    echo "<div class ='div-auth'><h3>Enter admin code</h3>";
    echo "<input type = 'text' name = 'code'/>&nbsp";
    echo "<button name = 'confirm'>Confirm</button></form>";
    echo '<form action = "UMCalendar_mainpage.php">
    <br><button>Return</button></form></div>';
  }
else{
 echo "<div class ='div-auth'><h3>Access restricted.</h3>";
 echo '<form action = "UMCalendar_mainpage.php">
 <button>Return</button></form></div>';
}
?>
</body>
</html>

<?php
if(isset($_POST['confirm'])){
  require('UMCalendar_db.php');

  $code = $_POST['code'];
  $db = new Database();
  $db->createAdministrator();
  $adminlist = $db->getAdminLists();
  $doesExist = false;
  for($i = 0; $i < sizeof($adminlist); $i++)
  {
    if($code == $adminlist[$i])
    {
      $doesExist = true;
      break;
    }
  }
  if($doesExist)
  {
    echo '<script type="text/javascript">
    location.replace("UMCalendar_settings.php");
    </script>';
  }
  else
  {
    echo "<script>alert('Invalid admin code!')</script>";
  }
}?>
