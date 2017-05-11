<?php
include("index.html");
			//Search Interest
if ($_SERVER['REQUEST_METHOD']=='POST'){
			if(isset($_POST['interest_submit'])){
				$interest = htmlspecialchars(strtolower($_POST['interest']));
				if ($_SERVER['REQUEST_METHOD']=='POST'){
					require_once("sql_connect.php");
					$query = "SELECT g.group_name as title, g.description as description FROM groups as g JOIN about as a On g.group_id = a.group_id WHERE a.interest_name = ?;";
					$statement = mysqli_prepare($dbc, $query);
					mysqli_stmt_bind_param($statement, "s", $interest);
					$statement->execute();
					$statement->store_result();
					$affected_rows = mysqli_stmt_num_rows($statement);
					if ($affected_rows == 0){
						echo "No groups with this interest :(";
					}
					$statement->bind_result($title, $description);
					$statement->fetch();
					echo "<ul>";
										echo "<li> Title:  " . $title . " </li>";
										echo "<li> Description:  " . $description . " </li> </br> </br>";
					echo "</ul>";
				}
				mysqli_close($dbc);
			}
		}
?>