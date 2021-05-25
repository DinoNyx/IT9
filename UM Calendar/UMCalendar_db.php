<?php
class Database{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $databasename = "UMCalendar_db";
    public $connection;

    private $idLists = [];
    private $nameLists = [];
    private $passwordLists = [];

    private $SYLists = [];
    private $adminLists = [];

    public function __construct(){
        @$this->connection = mysqli_connect($this->servername,$this->username,$this->password);
        if(!$this->connection) die ('Connection failed: ' . mysqli_connect_error());
        else{
            $isConnected = mysqli_select_db($this->connection,$this->databasename);

            if(!$isConnected){
                $sqlQuery = "CREATE DATABASE IF NOT EXISTS UMCalendar_db";
                mysqli_query($this->connection,$sqlQuery);
                echo "Database has been created succesfully!";
            }
        }
    } 

    public function createAccountTable(){
        $sqlQuery = "CREATE TABLE IF NOT EXISTS accounts (
        ID INT(6) PRIMARY KEY UNIQUE NOT NULL,
        NAME VARCHAR(100) NOT NULL,
        PASSWORD_HASH  CHAR(40) NOT NULL,
        EMAIL VARCHAR(50) NOT NULL,
        CONTACTNUMBER VARCHAR(11) NOT NULL,
        TYPE VARCHAR(7) NOT NULL)";
        mysqli_query($this->connection, $sqlQuery);

        $sqlQuery2 = "INSERT INTO accounts(ID,NAME,PASSWORD_HASH,EMAIL,CONTACTNUMBER,TYPE) VALUES(
        '071946','DEFAULT ADMIN','GloryToUM','umindanao@gmail.com','0843701809','Admin')";
        mysqli_query($this->connection, $sqlQuery2);
    }

    public function createEventTable(){
        $sqlQuery = "CREATE TABLE IF NOT EXISTS events (
        EVENT_ID INT(11) UNIQUE NOT NULL, 
        EVENT_NAME VARCHAR(50) NOT NULL,
        EVENT_START DATE NOT NULL,
        EVENT_END DATE NOT NULL,
        TYPE VARCHAR(20) NOT NULL)";
        mysqli_query($this->connection, $sqlQuery);
    }

    public function createAdministrator(){
        $sqlQuery = "CREATE TABLE IF NOT EXISTS admin(
        ADMIN_CODE VARCHAR(10) UNIQUE NOT NULL)";
        mysqli_query($this->connection, $sqlQuery);

        $sqlQuery2 = "INSERT INTO admin(ADMIN_CODE) VALUES(
        'DEFAULT_ADMIN_CODE')";
        mysqli_query($this->connection, $sqlQuery2);
    }


    public function createSYTable(){
        $sqlQuery = "CREATE TABLE IF NOT EXISTS SY(
        ID INT AUTO_INCREMENT UNIQUE NOT NULL,
        SCHOOL_YEAR VARCHAR(12) UNIQUE NOT NULL)";
        mysqli_query($this->connection, $sqlQuery);
    }

    public function recordUser()
    {
        $sqlQuery = "SELECT * FROM accounts";
        $resultSet = mysqli_query($this->connection, $sqlQuery);
        echo "<br>";
        if(mysqli_num_rows($resultSet) > 0){
            while($row = mysqli_fetch_assoc($resultSet))
            {
                $this->idLists[] = $row['ID'];
                $this->nameLists[] = $row['NAME'];
                $this->passwordLists[] = $row['PASSWORD_HASH'];
            }
        }
    }

    public function recordEvent()
    {
        $sqlQuery = "SELECT EVENT_NAME,EVENT_START,EVENT_END,TYPE FROM events";
        $resultSet = mysqli_query($this->connection, $sqlQuery);
        echo "<br>";
        if(mysqli_num_rows($resultSet) > 0){
            while($row = mysqli_fetch_assoc($resultSet))
            {
                $this->eventLists[] = $row['EVENT_NAME'];
                $this->startLists[] = $row["EVENT_START"];
                $this->endLists[] = $row["EVENT_END"];
                $this->eventtypeLists[] = $row["TYPE"];                
            }
        }
    } 

    public function recordCode()
    {
        $sqlQuery = "SELECT * FROM admin";
        $resultSet = mysqli_query($this->connection, $sqlQuery);
        echo "<br>";
        if(mysqli_num_rows($resultSet) > 0){
            while($row = mysqli_fetch_assoc($resultSet))
            {
                $this->adminLists[] = $row['ADMIN_CODE'];

            }
        }
    }

    public function recordSY()
    {
        $sqlQuery = "SELECT * FROM sy";
        $resultSet = mysqli_query($this->connection, $sqlQuery);
        echo "<br>";
        if(mysqli_num_rows($resultSet) > 0){
            while($row = mysqli_fetch_assoc($resultSet))
            {
                $this->SYLists[] = $row['SCHOOL_YEAR'];
            }
        }else{
            echo "error here";
        }
    }
    public function addUser($id, $name, $newpwd, $email, $cnumber, $acctype)
    {
        $sqlQuery = "INSERT INTO accounts(ID, NAME, PASSWORD_HASH, EMAIL, CONTACTNUMBER, TYPE) VALUES ('$id','$name','$newpwd','$email','$cnumber','$acctype')";
        mysqli_query($this->connection, $sqlQuery);
        $this->recordUser();
    }

    public function addEvent($id, $name, $start, $end, $type)
    {
        $sqlQuery = "INSERT INTO events(EVENT_ID,EVENT_NAME,EVENT_START,EVENT_END,TYPE) VALUES ('$id','$name','$start','$end','$type')";
        mysqli_query($this->connection, $sqlQuery);
        $this->recordEvent();
    }

    public function addCode($code)
    {
        $sqlQuery = "INSERT INTO admin(ADMIN_CODE) VALUES ('$code')";
        mysqli_query($this->connection, $sqlQuery);
        $this->recordCode();
    }

    public function updateSY($sy)
    {
        $sqlQuery = "SELECT * FROM sy";
        $resultSet = mysqli_query($this->connection, $sqlQuery);
        $row = mysqli_fetch_array($resultSet, MYSQLI_ASSOC) ;
        if(is_null($row['SCHOOL_YEAR'])){
            $sqlQuery = "INSERT INTO sy(SCHOOL_YEAR) VALUES('$sy')";            
        }else{
            $sqlQuery = "UPDATE sy SET SCHOOL_YEAR = '$sy' WHERE ID = 1";
        }
        mysqli_query($this->connection, $sqlQuery);
        $this->recordSY();
    }

    public function deleteEvent($id)
    {
        $sqlQuery = "DELETE FROM events WHERE EVENT_ID = '$id'";
        mysqli_query($this->connection, $sqlQuery);
    }

    public function deleteAcc($id)
    {
        $sqlQuery = "DELETE FROM accounts WHERE ID = '$id'";
        mysqli_query($this->connection, $sqlQuery);
    }

    public function editEvent($id, $name, $start, $end, $type)
    {
        $sqlQuery = "UPDATE events SET EVENT_NAME = '$name', EVENT_START = '$start', EVENT_END = '$end', TYPE = '$type' WHERE EVENT_ID = '$id'";
        mysqli_query($this->connection, $sqlQuery);
    }

    public function editAccount($id, $name, $pwd, $email, $cnumber)
    {
        $sqlQuery = "UPDATE accounts SET NAME = '$name', PASSWORD_HASH = '$pwd', EMAIL = '$email', CONTACTNUMBER = '$cnumber' WHERE ID = '$id'";
        mysqli_query($this->connection, $sqlQuery);
    }

    public function getIDLists()
    {
        $this->recordUser();
        return $this->idLists;
    }
    public function getPasswordLists()
    {
        $this->recordUser();
        return $this->passwordLists;
    }
    public function getAdminLists()
    {
        $this->recordCode();
        return $this->adminLists;
    }
    public function getSYLists()
    {
        $this->recordSY();
        return $this->SYLists;
    }

    public function showEvents_FSEM(){
        $sqlquery = "SELECT EVENT_NAME,EVENT_START,EVENT_END FROM events WHERE TYPE = 'First Semester' ORDER BY EVENT_START ASC";
        $resultSet = mysqli_query($this->connection, $sqlquery);

        if ($resultSet-> num_rows > 0){
          while($row = $resultSet-> fetch_assoc()){
            $datestart = date_create($row['EVENT_START']);
            $dateend = date_create($row['EVENT_END']);  
            echo "<tr><td>" . $row["EVENT_NAME"] . "</td><td>" .   date_format($datestart,"F d, Y") . " to</td><td>" .   date_format($dateend,"F d, Y") . "</td><td>" . "</td></tr>";
        }
        echo "</table>";
    }
    else{
      echo "No events recorded in First Semester.";
  }

  $this->connection-> close();
}
public function showEvents_SSEM(){
    $sqlquery = "SELECT EVENT_NAME,EVENT_START,EVENT_END FROM events WHERE TYPE = 'Second Semester' ORDER BY EVENT_START ASC";
    $resultSet = mysqli_query($this->connection, $sqlquery);

    if ($resultSet-> num_rows > 0){
      while($row = $resultSet-> fetch_assoc()){
        $datestart = date_create($row['EVENT_START']);
        $dateend = date_create($row['EVENT_END']);  
        echo "<tr><td>" . $row["EVENT_NAME"] . "</td><td>" .   date_format($datestart,"F d, Y") . " to</td><td>" .  date_format($dateend,"F d, Y") . "</td><td>" . "</td></tr>";
    }
    echo "</table>";
}
else{
    echo "No events recorded in Second Semester.";
}

$this->connection-> close();
}

public function showEvents_SUM(){
    $sqlquery = "SELECT EVENT_NAME,EVENT_START,EVENT_END FROM events WHERE TYPE = 'Summer' ORDER BY EVENT_START ASC";
    $resultSet = mysqli_query($this->connection, $sqlquery);

    if ($resultSet-> num_rows > 0){
      while($row = $resultSet-> fetch_assoc()){
        $datestart = date_create($row['EVENT_START']);
        $dateend = date_create($row['EVENT_END']);  
        echo "<tr><td>" . $row["EVENT_NAME"] . "</td><td>" .   date_format($datestart,"F d, Y") . " to</td><td>" .  date_format($dateend,"F d, Y") . "</td><td>" . "</td></tr>";
    }
    echo "</table><br>";
}
else{
    echo "No events recorded in Summer.";
}

$this->connection-> close();
}
public function displayStudents(){
    $sqlQuery = "SELECT ID,NAME,PASSWORD_HASH,EMAIL,CONTACTNUMBER,TYPE FROM accounts WHERE TYPE = 'Student' ORDER BY NAME ASC";
    $resultSet = mysqli_query($this->connection, $sqlQuery);

    if ($resultSet-> num_rows > 0){
        while($row = $resultSet-> fetch_assoc()){
            echo "<tr class = 'tr-admin'><td>" . $row["ID"] . "</td><td>" .  $row["NAME"] . "</td><td>" .  $row["PASSWORD_HASH"] . "</td><td>" . $row["EMAIL"] . "</td><td>" .  $row["CONTACTNUMBER"] . "</td><td>" . $row["TYPE"] . "</td></tr>";
        }
        echo '</table>';
    }
    else{
        echo "No students recorded.";
    }

    $this->connection-> close();
}

public function displayFaculty(){
    $sqlQuery = "SELECT ID,NAME,PASSWORD_HASH,EMAIL,CONTACTNUMBER,TYPE FROM accounts WHERE TYPE = 'Faculty' ORDER BY NAME ASC";
    $resultSet = mysqli_query($this->connection, $sqlQuery);

    if ($resultSet-> num_rows > 0){
        while($row = $resultSet-> fetch_assoc()){
            echo "<tr class = 'tr-admin'><td>" . $row["ID"] . "</td><td>" .  $row["NAME"] . "</td><td>" .  $row["PASSWORD_HASH"] . "</td><td>" . $row["EMAIL"] . "</td><td>" .  $row["CONTACTNUMBER"] . "</td><td>" . $row["TYPE"] . "</td></tr>";
        }
        echo "</table>";
    }
    else{
        echo "No faculty recorded.";
    }

    $this->connection-> close();
}

public function displayAdmins(){
    $sqlQuery = "SELECT ID,NAME,PASSWORD_HASH,EMAIL,CONTACTNUMBER,TYPE FROM accounts WHERE TYPE = 'Admin' ORDER BY NAME ASC";
    $resultSet = mysqli_query($this->connection, $sqlQuery);

    if ($resultSet-> num_rows > 0){
        while($row = $resultSet-> fetch_assoc()){
            echo "<tr class = 'tr-admin'><td>" . $row["ID"] . "</td><td>" .  $row["NAME"] . "</td><td>" . $row["PASSWORD_HASH"] . "</td><td>" . $row["EMAIL"] . "</td><td>" .  $row["CONTACTNUMBER"] . "</td><td>" . $row["TYPE"] . "</td></tr>";
        }
        echo "</table>";
    }
    else{
        echo "No admin recorded.";
    }

    $this->connection-> close();
}
}
?>