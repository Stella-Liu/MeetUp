<?php
include("index_2.html");
		//Code for searching meetup between date range
			if(isset($_POST['date_submit'])){
			$data_missing = array();
			if(empty($_POST['start_date'])){
				$data_missing[] = 'Start Date';
			}
			else{
				$start = htmlspecialchars($_POST['start_date'] . " 00:00:00");
			}
			if(empty($_POST['end_date'])){
				$data_missing[] = 'End Date';
			}
			else{
				$end = htmlspecialchars($_POST['end_date'] . " 23:59:59");
			}
			if ($_SERVER['REQUEST_METHOD']=='POST'){
				require_once("sql_connect.php");
				if(empty($data_missing)){
					require_once('sql_connect.php');
					$query = 'SELECT title, start_time, end_time, description FROM events WHERE (start_time >= "' . $start . '") AND (end_time <= "' . $end . '")';
					$response = mysqli_query($dbc, $query) or die('error');
					if($response){
						while($row = mysqli_fetch_array($response)){
							echo '<div class="meetup">
							<label>' . $row['title'] . '</label><br>
							<label>Start Time:</label>' . $row['start_time'] . '<br>
							<label>End Time:</label>' . $row['end_time'] . '<br>
							<div>Description<br>'. $row['description'] . '</div></div>';
							echo "</br>";
						}
					}
					else{
						echo "No events";
					}
				}
				else{
						echo 'You need to enter the following data<br>';
						foreach($data_missing as $missing){
							echo "$missing<br>";
						}
					}
				}
				mysqli_close($dbc);
			}
		?>