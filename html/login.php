<?php
	include("login.html");
	if ($_SERVER['REQUEST_METHOD']=='POST'){
		require_once("sql_connect.php");
		$user = htmlspecialchars(strtolower($_POST["user"]));
		$pass = sha1($_POST["pass"]);
		$query = "SELECT username, password FROM member WHERE username = ? AND password = ?;";
		$statement = mysqli_prepare($dbc, $query);
		mysqli_stmt_bind_param($statement, "ss", $user, $pass);
		mysqli_stmt_execute($statement);
		mysqli_stmt_store_result($statement);
		$affected_rows = mysqli_stmt_num_rows($statement);
		if ($affected_rows == 1){
			echo '<script type="text/javascript"> window.location = "home.html"</script>';
			mysqli_stmt_close($statement);
			session_start();
			$_SESSION["uid"] = $user;
			mysqli_close($dbc);
		}else{
			echo "Login Unsuccessful";
			echo mysqli_error($dbc);
		}
	}
?>