<?php

	session_start();

	require_once("sql_connect.php");

	if ($_POST['rsvp']==1){
		$user = $_SESSION['uid'];
		$eid = $_POST['eid'];
		$query = 'SELECT username FROM events JOIN attend ON (events.event_id = attend.event_id) WHERE ((events.event_id = "'.$eid .'") AND (attend.username = "' . $user . '") AND (attend.RSVP = 0))';

    	$response = mysqli_query($dbc, $query) or die('error1');
    	if($response){
      		while($row = mysqli_fetch_array($response)){
				$username = $row['username'];
			}
      		if($username == null){
				$query = "INSERT INTO attend (event_id, username, rsvp, rating) VALUES (?,?,1,0);";
			}
			else{
				$query = "UPDATE attend SET rsvp='1' WHERE event_id=? AND username=?;";
			}
    	}

	}else{
		$query = "UPDATE attend SET rsvp='0' WHERE event_id=? AND username=?;";
	}

	$statement = mysqli_prepare($dbc, $query);
	mysqli_stmt_bind_param($statement, "is", $_POST['eid'], $_SESSION['uid']);
	mysqli_stmt_execute($statement);
	$affected_rows = mysqli_stmt_affected_rows($statement);
	if ($affected_rows > 0){
		echo "Action performed for " . $_POST['etitle'];
		mysqli_stmt_close($statement);
		mysqli_close($dbc);

		echo '<script type="text/javascript">
		window.location = "future.php"
		</script>';
	}else{
		echo "Affected rows is not > 0, ";
		echo mysqli_error($dbc);
	}
?>