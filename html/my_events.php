<?php
	session_start();
	include("my_events.html");
	require_once("sql_connect.php");
	if(!$_SESSION['uid']){
		echo '<script type="text/javascript">
		window.location = "login.php"
		</script>';
	}
	else{
		$current_date = date("Y-m-d") . " 00:00:00";
		$end_date = date('Y-m-d', strtotime("+3 year")) . " 23:59:59";
		$user = $_SESSION['uid'];
		$query = 'SELECT events.event_id, title, start_time, end_time, description, lname FROM events JOIN attend ON (events.event_id = attend.event_id) WHERE ((start_time >= "' . $current_date . '") AND (end_time <= "' . $end_date . '") AND (attend.username = "' . $user . '") AND (attend.RSVP = 1))';
					
		$response = mysqli_query($dbc, $query) or die('error1');

		echo '<h2>Future Events Rsvp</h2>';
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
		mysqli_close($dbc);
	}
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$data_missing = 0;
					$zip_check = 0;
					$date_check = 0;
					if(empty($_POST["title"])){
					$data_missing = 1;
					}
					else{
						$title = htmlspecialchars(strtolower($_POST["title"]));
					}
					if(empty($_POST["group_name"])){
						$data_missing = 1;
					}
					else{
						$group_name = htmlspecialchars(strtolower($_POST["group_name"]));
					}
					if(empty($_POST["start_date"])){
					$data_missing = 1;
					}
					else{
						$start_date = $_POST["start_date"];
					}
					if(empty($_POST["start_time"])){
						$data_missing = 1;
					}
					else{
						$start_time = $_POST["start_time"];
					}
					if(empty($_POST["end_date"])){
						$data_missing = 1;
					}
					else{
						$end_date = $_POST["end_date"];
					}
					if(empty($_POST["end_time"])){
						$data_missing = 1;
					}
					else{
						$end_time = $_POST["end_time"];
					}
					if(empty($_POST["description"])){
						$description = "";
					}
					else{
						$description = $_POST["description"];
					}
					if (empty($_POST["location"])){
						$data_missing = 1;
					}else{
						$name = htmlspecialchars(strtolower($_POST["location"]));
					}
					if (empty($_POST["zip"])){
						$data_missing = 1;
					}else{
						$zip = $_POST["zip"];
						if($zip < 0 || $zip > 100000){
							$zip_check = 1;
						}
					}
					if (empty($_POST["street"])){
						$data_missing = 1;
					}
					else{
						$street = htmlspecialchars(strtolower($_POST["street"]));
					}
					if (empty($_POST["city"])){
						$data_missing = 1;
					}else{
						$city = htmlspecialchars(strtolower($_POST["city"]));
					}

					if ($data_missing == 1){
						echo "Please fill all fields!";
						exit;
					}
					if($zip_check == 1){
						echo "Invalid ZipCode";
						exit;
					}

				if($start_date > $end_date){
					$invalid_start = 1;
					echo "Start date must be before End Date";
					exit;
				}
				else if($start_date == $end_date){
					if($start_time > $end_time){
						echo "Start Time must be before End Time!";
						exit;
					}
				}

				$current_date = date("Y-m-d");

				if($start_date < $current_date){
					echo "Start Time can't be in the past!";
					exit;
				}

				$start_time = $start_time . ":00";
				$end_time = $end_time . ":00";

				$start_total = $start_date . " " . $start_time;
				$end_total = $end_date . " " . $end_time;

					//check if user is authorized
				$query = 'SELECT group_id, username FROM groups WHERE group_name = "' . $group_name . '"';

				$response = mysqli_query($dbc, $query) or die('error0');

				$rows = mysqli_num_rows($response);

				$if_auth = 0;

				if($rows == 1){
					while($row = mysqli_fetch_array($response)){
						$group_id = $row["group_id"];
						$username = $row["username"];
						if($username == $_SESSION["uid"]){
							$if_auth = 1;
						}
						else{
							echo "You are not authorized to create an event!";
							exit;
						}
					}
				}
				else{
					echo "Group does not exist";
				}

					//check if location exist
			$query2 = 'SELECT lname, zip FROM location WHERE (lname = "' . $location . '") AND zip = "' . $zip . '"';
				$response = mysqli_query($dbc, $query2) or die('error1');
				$rows2 = mysqli_num_rows($response);
				if($rows2 == 1){
					$event_query = 'INSERT INTO events(title, description, start_time, end_time, group_id, lname, zip) VALUES ("' . $title . '" ,"' . $description . '" ,
					"' . $start_total . '" ,"' . $end_total . '" ,"' . $group_id . '" ,"' . $location . '" ,"' . $zip . '" )';

					$response = mysqli_query($dbc, $event_query) or die('error2');

					$event_rows = mysqli_affected_rows($dbc);

					if($event_rows == 1){
						echo "Event has been created!";
						$event_created = 1;
					}
					else{
						echo "Sorry your event sucks!";
					}
				}
				else{
					$query = 'INSERT INTO location VALUES ("' . $name . '","' . $zip . '", "' . $street . '", "' . $city . '")';

					$response = mysqli_query($dbc, $query);
					$rows = mysqli_affected_rows($dbc);
					if($rows == 1){
						echo "Location Added!";
						echo '<script type="text/javascript">
							window.location = "make_event.php"
							</script>';
					}
					else{
						echo "Location already exists";
						exit;
					}

					$event_query = 'INSERT INTO events(title, description, start_time, end_time, group_id, lname, zip) VALUES ("' . $title . '" ,"' . $description . '" ,
					"' . $start_total . '" ,"' . $end_total . '" ,"' . $group_id . '" ,"' . $location . '" ,"' . $zip . '" )';

					$response = mysqli_query($dbc, $event_query) or die('error2');

					$event_rows = mysqli_affected_rows($dbc);

					if($event_rows == 1){
						echo "Event has been created!";
						$event_created = 1;
					}
					else{
						echo "Sorry your event sucks!";
					}	
				}
		mysqli_close($dbc);
	}
?>