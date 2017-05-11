<?php
include("future.html");
if ($_SERVER['REQUEST_METHOD']=='POST'){
require_once("sql_connect.php");

$current_date = date("Y-m-d") . " 00:00:00";
$days3 = date('Y-m-d', strtotime("+3 day")) . " 23:59:59";
if(empty($_POST['start_date'])){
$start_date = $current_date;
}
else{
$start_date = $_POST['start_date'] . " 00:00:00";
}
if(empty($_POST['end_date'])){
$end_date = $days3;
}
else{
$end_date = $_POST['end_date'] . " 23:59:59";
}
if(empty($_POST["RSVP"])){
  $RSVP = 0;
}
else{
  $RSVP = 1;
}
$date_error = 0;
$user = $_SESSION["uid"];
$invalid_start = 0;
$location_empty = 0;
if(empty($_POST["location"])){
$location_empty = 1;
}
else{
$location = strtolower($_POST["location"]);
}
echo "Start date = " . $start_date . "</br>";
echo "End date = " . $end_date . "</br> </br>";

if((empty($start_date) && !(empty($end_date))) || (empty($end_date) && !(empty($start_date)))){
$date_error = 1;
}
if($date_error == 1){
echo "Both dates must be entered. If no dates are entered, the next 3 days will be viewed";
exit;
}
if($start_date > $end_date){
$invalid_start = 1;
echo "Start date must be before end date";
exit;
}

if($RSVP){
  if($location_empty == 1){
    $query = 'SELECT events.event_id, title, start_time, end_time, description, lname FROM events JOIN attend ON (events.event_id = attend.event_id) WHERE ((start_time >= "' . $start_date . '") AND (end_time <= "' . $end_date . '") AND (attend.username = "' . $user . '") AND (attend.RSVP = 1))';

    $response = mysqli_query($dbc, $query) or die('error1');
    if($response){
      while($row = mysqli_fetch_array($response)){
        echo '<label>' . $row['title'] . '</label><br>
        <label>Start Time:</label>' . $row['start_time'] . '<br>
        <label>End Time:</label>' . $row['end_time'] . '<br>
        <label>Location: </label>' . $row['lname'] . '<br>
        <div>Description<br>'. $row['description'] . '</div>';
        echo "</br>";

        $eid = $row['event_id'];
        echo "<input type='submit' id='btn_" . $eid . "' value='Cancel RSVP'/>";
        echo "<script>$('#btn_" . $eid . "').click(function(){
        post('rsvp.php',{eid:" . $eid . ", rsvp: 0});
        });</script>";
        echo "<br>";
        }
      }
    }
    else{
      $query = 'SELECT title, start_time, end_time, description, lname FROM events JOIN attend ON (events.event_id = attend.event_id) WHERE ((start_time >= "' . $start_date . '") AND (end_time <= "' . $end_date . '") AND (attend.username = "' . $user . '") AND (attend.RSVP = 1) AND (events.lname = "'. $location . '"))';

      $response = mysqli_query($dbc, $query) or die('error2');
      if($response){
        while($row = mysqli_fetch_array($response)){
          echo '<label>' . $row['title'] . '</label><br>
          <label>Start Time:</label>' . $row['start_time'] . '<br>
          <label>End Time:</label>' . $row['end_time'] . '<br>
          <label>Location: </label>' . $row['lname'] . '<br>
          <div>Description<br>'. $row['description'] . '</div>';
          echo "</br>";
          $eid = $row['event_id'];
          echo "<input type='submit' id='btn_" . $eid . "' value='Cancel RSVP'/>";
          echo "<script>$('#btn_" . $eid . "').click(function(){
          post('rsvp.php',{eid:" . $eid . ", rsvp: 0});
          });</script>";
          echo "<br>";
        }
    }
  }
}
else{
  if($location_empty == 1){
    $query = 'SELECT events.event_id, title, start_time, end_time, description, lname, attend.rsvp FROM events JOIN attend ON (events.event_id = attend.event_id) WHERE ((start_time >= "' . $start_date . '") AND (end_time <= "' . $end_date . '") AND (attend.username = "' . $user . '"))';
    $response = mysqli_query($dbc, $query) or die('error3');
    if($response){
    while($row = mysqli_fetch_array($response)){
      echo '<label>' . $row['title'] . '</label><br>
      <label>Start Time:</label>' . $row['start_time'] . '<br>
      <label>End Time:</label>' . $row['end_time'] . '<br>
      <label>Location: </label>' . $row['lname'] . '<br>
      <div>Description:<br>'. $row['description'] . '</div>';
      if ($row['rsvp']==0){
        $eid = $row['event_id'];
        echo "<input type='submit' id='btn_" . $eid . "' value='RSVP'/>";
        echo "<script>$('#btn_" . $eid . "').click(function(){
        post('rsvp.php',{eid:" . $eid . ", rsvp: 1});
        });</script>";
        echo "<br>";
      }else{
        $eid = $row['event_id'];
        echo "<input type='submit' id='btn_" . $eid . "' value='Cancel RSVP'/>";
        echo "<script>$('#btn_" . $eid . "').click(function(){
        post('rsvp.php',{eid:" . $eid . ", rsvp: 0});
        });</script>";
        echo "<br>";
      }
    }
    }
  }
  else{
    $query = 'SELECT events.event_id, title, start_time, end_time, description, lname, attend.rsvp FROM events JOIN attend ON (events.event_id = attend.event_id)WHERE ((start_time >= "' . $start_date . '") AND (end_time <= "' . $end_date . '") AND (attend.username = "' . $user . '") AND (attend.RSVP = 1) AND (events.lname = "'. $location . '"))';
    $response = mysqli_query($dbc, $query) or die('error4');
    if($response){
      while($row = mysqli_fetch_array($response)){
        echo '<label>' . $row['title'] . '</label><br>
        <label>Start Time:</label>' . $row['start_time'] . '<br>
        <label>End Time:</label>' . $row['end_time'] . '<br>
        <label>Location: </label>' . $row['lname'] . '<br>
        <div>Description<br>'. $row['description'] . '</div>';
        if ($row['rsvp']==0){
          $eid = $row['event_id'];
          echo "<input type='submit' id='btn_" . $eid . "' value='RSVP'/>";
          echo "<script>$('#btn_" . $eid . "').click(function(){
          post('rsvp.php',{eid:" . $eid . ", rsvp: 1});
          });</script>";
          echo "<br>";
        }else{
          $eid = $row['event_id'];
          echo "<input type='submit' id='btn_" . $eid . "' value='Cancel RSVP'/>";
          echo "<script>$('#btn_" . $eid . "').click(function(){
          post('rsvp.php',{eid:" . $eid . ", rsvp: 0});
          });</script>";
          echo "<br>";
        }
      }
    }
  }
}
mysqli_close($dbc);
}


?>