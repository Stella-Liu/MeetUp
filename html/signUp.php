<?php
	include("signUp.html");
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$pass_check = 0;
		$data_missing = 0;
		$zip_check = 0;
		if (empty($_POST["first"])){
			$data_missing = 1;
		}else{
			$first = htmlspecialchars($_POST["first"]);
		}
		if (empty($_POST["last"])){
			$data_missing = 1;
		}else{
			$last = htmlspecialchars($_POST["last"]);
		}
		if (empty($_POST["pass"])){
			$data_missing = 1;
		}
		else{
			if($_POST["pass"] != $_POST["confirm_pass"]){
				echo "Passwords do not match!";
				exit;
			}

			$stringlength = strlen($_POST["pass"]);
			if($stringlength < 6 || $stringlength > 24){
				$pass_check = 3;
			}else{
				$pass = sha1($_POST["pass"]);
			}
		}

		if (empty($_POST["user"])){
			$data_missing = 1;
		}else{
			$user = htmlspecialchars(strtolower($_POST["user"]));
		}
		if (empty($_POST["zip"])){
			$data_missing = 1;
		}else{
			//$zip = $_POST["zip"];
			if( 0 >= ($_POST["zip"]) || ($_POST["zip"]) > 100000){
				$zip_check = 2;
			}
			else{
				$zip = htmlspecialchars($_POST["zip"]);
			}
		}
		if ($data_missing == 1){
			echo "Please fill all fields!";
			exit;
		}
		if($zip_check == 2){
			echo "Invalid ZipCode";
			exit;
		}
		if($pass_check == 3){
			echo "Password should be between 6 and 24 characters";
			exit;
		}

		require_once("sql_connect.php");
		$query = "INSERT INTO member (username, password, firstname, lastname, zipcode) VALUES (?, ?, ?, ?, ?);";
		$statement = mysqli_prepare($dbc, $query);
		mysqli_stmt_bind_param($statement, "ssssi", $user, $pass, $first, $last, $zip);
		mysqli_stmt_execute($statement);
		$affected_rows = mysqli_stmt_affected_rows($statement);
		if ($affected_rows > 0){
			mysqli_stmt_close($statement);
			mysqli_close($dbc);
			echo '<script type="text/javascript">
			window.location = "login.php"
			</script>';
			//header("Location: https://http://localhost:8888/login.php");
			//echo "Member added";
			exit;
		}else{
			echo mysqli_error($dbc) . "<br/>";
		}
	}
?>