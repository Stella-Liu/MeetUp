<?php
	DEFINE ('DBUSER','root');
	DEFINE ('DBPASSWORD','');
	DEFINE ('DBHOST','localhost');
	DEFINE ('DBNAME','meetup');

	$dbc = @mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBNAME)
	OR die("Could not connect to database!" . mysqli_connect_error());
?>
