<?php
	session_start();
	include("my_groups.html");
	if(!$_SESSION['uid']){
echo '<script type="text/javascript"> window.location = "login.php" </script>';
}
if(isset($_POST["view_groups"])){
$username = $_SESSION["uid"];
require_once("sql_connect.php");
$my_groups_query = 'SELECT groups.group_id as g, groups.group_name, groups.description FROM belongs_to JOIN groups ON (belongs_to.group_id = groups.group_id) WHERE belongs_to.username = "' . $username . '"';
$response9 = mysqli_query($dbc, $my_groups_query);
$my_rows = mysqli_num_rows($response9);
if($my_rows > 0){
while($m_row = mysqli_fetch_array($response9)){
echo '</br><label> Group Name: </label> </br>
<label>' . $m_row['group_name'] . '</label><br>
<div>Description: <br>'. $m_row['description'] . '</div>';
$gid = $m_row['g'];
echo "<input type='submit' id='btnleave_" . $gid . "' value='Leave Group'/>";
echo "<script>$('#btnleave_" . $gid . "').click(function(){
post('joinGroup.php',{'gid':" . $gid . ",'join':0});
});</script>";
echo "<br>";
}
}
else{
echo "You are in no groups!";
}
mysqli_close($dbc);
}
if ($_SERVER['REQUEST_METHOD']=='POST'){
require_once("sql_connect.php");
$username = $_SESSION["uid"];
$data_missing = 0;

if(empty($_POST["group_name"])){
$data_missing = 1;
}else{
$group_name = htmlspecialchars(strtolower($_POST["group_name"]));
}

if(empty($_POST["group_interest"])){
$data_missing = 1;
}else{
$interest_name = htmlspecialchars(strtolower($_POST["group_interest"]));
}

if(empty($_POST["description"])){
$description = "";
}else{
$description = htmlspecialchars($_POST["description"]);
}
if($data_missing){
exit;
}

// Check if group name exists
$group_check = 'SELECT group_name FROM groups WHERE group_name = "' . $group_name . '"';
$response = mysqli_query($dbc, $group_check) or die('error');
$num_rows = mysqli_num_rows($response);
if($num_rows != 0){
echo "Group Name already exists!";
exit;
}else{
$exist = 0;
}
if($exist == 0){
// Check if interest exists!
$interest_check = 'SELECT interest_name FROM interest WHERE interest_name = "' . $interest_name . '" ';
$response1 = mysqli_query($dbc, $interest_check);
$interest_rows = mysqli_num_rows($response1);
if($interest_rows == 0){
$interest_injection = 'INSERT INTO interest VALUES ("' . $interest_name . '") ' ;
$response2 = mysqli_query($dbc, $interest_injection);
$interest_affected = mysqli_affected_rows($dbc);
if($interest_affected != 1){
echo "Interest could not be added " . $interest_affected;
exit;
}
}
$group_injection = 'INSERT INTO groups (group_name, description, username) VALUES ("' . $group_name . '" , "' . $description .'", "' . $username . '")';
$response3 = mysqli_query($dbc, $group_injection);
$group_affected = mysqli_affected_rows($dbc);
if($group_affected == 1){
$group_added = 1;
}else{
echo "Group could not be added!";
exit;
}
}
if($group_added){
$get_group_id = 'SELECT group_id FROM groups WHERE group_name = "' . $group_name . '"' ;
$response4 = mysqli_query($dbc, $get_group_id);
$id_rows = mysqli_num_rows($response4);
if($id_rows == 1){
$row = mysqli_fetch_array($response4);
$group_id = $row['group_id'];
}else{
echo "Error! 1";
exit;
}
$about_injection = 'INSERT INTO about VALUES ("' . $interest_name . '" , "' . $group_id . '")';
$response5 = mysqli_query($dbc, $about_injection);
$about_rows = mysqli_affected_rows($dbc);
if($about_rows != 1){
echo "Error! 2";
exit;
}
$belongs_injection = 'INSERT INTO belongs_to VALUES ("' . $group_id .'", "' . $username . '", 1)';
$response6 = mysqli_query($dbc, $belongs_injection);
$belongs_rows = mysqli_affected_rows($dbc);
if($belongs_rows != 1){
echo "Error! 3";
exit;
}
}
echo "Group Added!";
mysqli_close($dbc);
}
?>