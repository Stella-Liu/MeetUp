<?php
	include("past_events.html");
	require_once("sql_connect.php");
	$user = $_SESSION["uid"];

	$query = "SELECT e.event_id, title, description, a.rating FROM events AS e JOIN attend as a ON e.event_id = a.event_id WHERE a.username = ?";

	$stmt = mysqli_prepare($dbc, $query);

	$stmt->bind_param("s", $user);

	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($eid, $title, $description, $rating);

	$rows = $stmt->num_rows();
	if($rows == 0){
		echo "<p>You haven't been to any events!</p>";
	}else{
		echo "<p>These are the events you've been to up till now</p>";
	}

	// echo "<p>" . isset($_POST["eid"]) . "</p>";

	if (!empty($_POST)){

		$rate_id = $eid;
		$rate = $rating;

		if($rate <= 1 || $rate >= 5){
			echo "The rating has to be between 1 and 5";
		}
		else{

			$query2 = "UPDATE attend SET rating=? WHERE event_id=? AND username=?;";
			$stmt2 = mysqli_prepare($dbc, $query2);
			$stmt2->bind_param("iis", $rate, $rate_id, $user);
			$stmt2->execute();
		}

	}

	echo "<table>";
	echo "<tr><th>Title</th><th>Description</th><th>Rating:</th></tr>";

	while($stmt->fetch()){
		// display basic event info
		echo "<tr><td>" . $title . "</td><td>" . $description . "</td>";
		$rate_id = $eid;
		$rate = $rating;
		if ($rate_id==$eid & $rate > 0 & $rate < 6){
			$rating = $rate;
		}
		echo "<td>" . $rating . "</td>";
		// echo "<td><form action='rate.php' method='POST'>";
		echo "<td><input type='number' id='rate_" . $eid . "' max='5' min='1' size='2'/>";
		echo "<input type='submit' id='btn_" . $eid. "' value='Rate!'/>";
		echo "</td></tr>";
		// echo "</form></td></tr>";
		// // script to set button function
		echo "<script>$('#btn_" . $eid . "').click(function(){
			new_rating = $('#rate_" . $eid . "').val();
			post('past_events.php',{eid:" . $eid . ",'rating':new_rating});
		});</script>";
	}

	echo "</table>";
	
?>