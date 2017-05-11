<?php
include("interest.html");
			$currentuser = $_SESSION["uid"];
			require_once("sql_connect.php");

			$query = "SELECT interest_name FROM interested_in WHERE username = ?;";

			$statement = mysqli_prepare($dbc, $query);
			
			mysqli_stmt_bind_param($statement, "s", $currentuser);
			
			$statement->execute();

			$statement->store_result();

			$affected_rows = mysqli_stmt_num_rows($statement);

			if ($affected_rows == 0){
				echo "You have no interests";
			}
			else{
				echo "You have " . $affected_rows . " interests!";
			}

			echo "</br> </br>" . "Interests: " ;

			$statement->bind_result($interest_name);
			 //$statement->fetch();

			echo "<ul>";
			
			while($row = $statement->fetch()){
				echo "<li>" . $interest_name . "</li>";
			}

			echo "</ul>";

			mysqli_stmt_close($statement);

			// inserting interests
			if(empty($_POST["interest"])){
				exit;
			}
			else{
				$interest = strtolower($_POST["interest"]);
				$query2 = "INSERT INTO interest VALUES (?)";

				$statement2 = mysqli_prepare($dbc, $query2);

				mysqli_stmt_bind_param($statement2, "s", $interest);

				mysqli_stmt_execute($statement2);

				$row = mysqli_stmt_affected_rows($statement2);

				$query3 = "INSERT INTO interested_in VALUES (?,?);";
				$statement3 = mysqli_prepare($dbc, $query3);
				mysqli_stmt_bind_param($statement3, "ss", $currentuser, $interest);
				mysqli_stmt_execute($statement3);
				$row2 = mysqli_stmt_affected_rows($statement3);
				if($row2 == 1){
					echo "Interest added successfully";
					echo '<script type="text/javascript">
		       					window.location = "interest.php"
		  				</script>';
				}
				mysqli_stmt_close($statement2);
				mysqli_stmt_close($statement3);
				mysqli_close($dbc);
			}	
	?>