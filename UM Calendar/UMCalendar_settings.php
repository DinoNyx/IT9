<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
  <title>UM Calendar - Settings</title>
  <link rel="icon" href="UM logo.ico" type="image/icon type"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link type="javascript" rel="stylesheet" href="UMCalendar.js?v=<?php echo time(); ?>"/>
  <link type="text/css" rel="stylesheet" href="UMCalendar.css?v=<?php echo time(); ?>"/>
</head>

<body class = "bdy-settings">
  <div class="container-lg div-settings center">
    <h3>Settings</h3>
    <form method="post" action="UMCalendar_settings.php">
      <label>Set school year:</label>
      <input type="text" name="SY" id = "SY"/>
      <button name ='save' id ='save'>Save</button>
    </form>
    
    <form method="post" action="UMCalendar_settings.php">
      <label>Add new administrator code:</label>
      <input type="text" name="code" id = "code"/>
      <button name ='admin' id ='admin'>Add</button>
    </form>
    
    <table class = "tbl-admincodes center">
      <tr class = "tr-admin">
        <th class = "th-admin">Administrator Codes</th>
      </tr>

      <?php
      $conn = mysqli_connect("localhost", "root", "","UMCalendar_db");
      if($conn-> connect_error){
        die("Connection failed:" . $conn-> connect_error);
      }

      $sqlquery = "SELECT * FROM admin ORDER BY ADMIN_CODE ASC";
      $result = $conn-> query($sqlquery);

      if ($result-> num_rows > 0){
        while($row = $result-> fetch_assoc()){
          echo "<tr class = 'tr-admin'><td>" . $row["ADMIN_CODE"] . "</td></tr>";
        }
        echo "</table>";
      }
      else{
        echo "No events recorded.";
      }

      $conn-> close();
      ?>

      <hr color="#800" style="height:1px">

      <h3>Event settings</h3>
      <h5>Add event</h5>
      <form method="post" action="UMCalendar_settings.php">
        <label>ID:</label>
        <input type="number" name="eventid" id = "eventid" min = "1"required/>

        <label>Event name:</label>
        <input type="text" name="event" id = "event" required/>

        <label>During:</label>
        <select class=  "adminselect" name = "event_type" id = "event_type">
          <option value = "First Semester">First Semester</option>
          <option value = "Second Semester">Second Semester</option>
          <option value = "Summer">Summer</option>
        </select><br>

        <label>Event starts:</label>
        <input type="date" name="event_start" id = "event_start" required/>
        <label>Event ends:</label>
        <input type="date" name="event_ends" id = "event_ends" required/>
        <button name = 'add' id ='add'>Add event</button><br>
      </form>

      <hr color="#800">

      <form method="post" action="UMCalendar_settings.php">
        <h5>Delete event</h5>
        <label>Delete ID:</label>
        <input type="number" name="delid" id = "delid" min = "0" required />
        <button name = 'del' id ='del'>Delete event</button>
      </form>

      <hr color="#800">

      <form method="post" action="UMCalendar_settings.php">
        <h5>Edit event</h5>
        <label>ID:</label>
        <input type="number" name="editid" id = "editid" min = "0" required />

        <label>Event name:</label>
        <input type="text" name="editevent" id = "editevent" required/>

        <label>During:</label>
        <select class = "adminselect" name = "edit_type" id = "edit_type">
          <option value = "First Semester">First Semester</option>
          <option value = "Second Semester">Second Semester</option>
          <option value = "Summer">Summer</option>
        </select><br>

        <label>Event starts:</label>
        <input type="date" name="edit_start" id = "edit_start" required/>
        <label>Event ends:</label>
        <input type="date" name="edit_end" id = "edit_end" required/>

        <button name = 'edit' id ='edit'>Edit event</button>
      </form>

      <hr color="#800" style="height:1px">

      <h3>Current Events</h3>
      <table class = "tbl-admin">
        <tr class = "tr-admin">
          <th class = "th-admin">ID</th>
          <th class = "th-admin">Event Name</th>
          <th class = "th-admin">Event Start</th>
          <th class = "th-admin">Event End</th>
          <th class = "th-admin">During</th>
        </tr>

        <?php
        $conn = mysqli_connect("localhost", "root", "","UMCalendar_db");
        if($conn-> connect_error){
          die("Connection failed:" . $conn-> connect_error);
        }

        $sqlquery = "SELECT EVENT_ID,EVENT_NAME,EVENT_START,EVENT_END,TYPE FROM events ORDER BY EVENT_START,TYPE ASC";
        $result = $conn-> query($sqlquery);

        if ($result-> num_rows > 0){
          while($row = $result-> fetch_assoc()){
            echo "<tr class = 'tr-admin'><td>" . $row["EVENT_ID"] . "</td><td>" .  $row["EVENT_NAME"] . "</td><td>" .  $row["EVENT_START"] . "</td><td>" .  $row["EVENT_END"] . "</td><td>" . $row["TYPE"] . "</td></tr>";
          }
          echo "</table>";
        }
        else{
          echo "No events recorded.";
        }

        $conn-> close();
        ?>

        <form method = "POST">
          <br>
          <button name = 'return'>Return</button>
          <button name = "accbtn">Accounts</button>
        </form>
      </div>
    </body>
    </html>
    <?php
    if(isset($_POST['add']))
    {
      require('UMCalendar_db.php');

      $id = $_POST['eventid'];
      $name = $_POST['event'];
      $start = $_POST['event_start'];
      $end = $_POST['event_ends'];
      $event_type = $_POST['event_type'];

      $db = new Database();
      $db->createEventTable();
      $insResult = $db->addEvent($id, $name, $start, $end, $event_type);
      if($insResult == 0)
      {
        echo '<script> alert ("New event has been added.")</script>';
        echo "<meta http-equiv='refresh' content='0'>";
      } 
      else
      {
        echo '<script> alert ("Error!")</script>'. $insResult;
      }
    }

    if(isset($_POST['admin']))
    {
      require('UMCalendar_db.php');

      $code = $_POST['code'];

      $db = new Database();
      $db->createAdministrator();
      $insResult = $db->addCode($code);
      if($insResult == 0)
      {
        echo '<script> alert ("New administrator code has been added.")</script>';
        echo "<meta http-equiv='refresh' content='0'>";
      }
      else
      {
        echo '<script> alert ("Error!")</script>'. $insResult;
      }
    }

    if(isset($_POST['del']))
    {
      require('UMCalendar_db.php');

      $id = $_POST['delid'];

      $db = new Database();
      $insResult = $db->deleteEvent($id);
      if($insResult == 0)
      {
        echo '<script> alert ("Event has been deleted.")</script>';
        echo "<meta http-equiv='refresh' content='0'>";
      }
      else
      {
        echo '<script> alert ("Error!")</script>'. $insResult;
      }
    }

    if(isset($_POST['edit']))
    {
      require('UMCalendar_db.php');

      $id = $_POST['editid'];
      $name = $_POST['editevent'];
      $start = $_POST['edit_start'];
      $end = $_POST['edit_end'];
      $event_type = $_POST['edit_type'];

      $db = new Database();
      $insResult = $db->editEvent($id, $name, $start, $end, $event_type);
      if($insResult == 0)
      {
        echo '<script> alert ("Event has been edited.")</script>';
        echo "<meta http-equiv='refresh' content='0'>";
      } 
      else{echo '<script> alert ("Error!")</script>'. $insResult;}
    }

    if(isset($_POST['save']))
    {
      require('UMCalendar_db.php');

      $sy = $_POST['SY'];

      $db = new Database();
      $db->createSYTable();    
      $insResult = $db->updateSY($sy);
      if($insResult == 0)
      {
        echo '<script> alert ("School year has been updated.")</script>';
      }
      else
      {
        echo '<script> alert ("Error!")</script>'. $insResult;
      }
    }

    if(isset($_POST['accbtn']))
    {
      echo '<script type="text/javascript">
      location.replace("UMCalendar_accounts.php");
      </script>';
    }
    if(isset($_POST['return']))
    {
      echo '<script type="text/javascript">
      location.replace("UMCalendar_mainpage.php");
      </script>';
    }

    if ($_SESSION['accntType'] != 'Admin'){
      echo '<script type="text/javascript">
      location.replace("UMCalendar_auth.php");
      </script>';}
      ?>