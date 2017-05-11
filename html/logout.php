<?php
	session_start();

	if($_SESSION["uid"] != ''){
		require_once("sql_connect.php");
		$current_user = $_SESSION["uid"];
		$_SESSION = array();
		session_destroy();

		echo '<script type="text/javascript"> window.location = "index.php"</script>';
	}else{
		echo '<script type="text/javascript"> window.location = "login.php"</script>';
	}
?>