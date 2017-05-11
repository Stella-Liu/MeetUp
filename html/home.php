<?php
	include("home.html");
  session_start();
  if(!$_SESSION['uid']){
    echo '<script type="text/javascript">
    window.location = "login.php"
    </script>';
  }
  echo '<h1>' . $_SESSION["uid"] . '</h1>';
?>