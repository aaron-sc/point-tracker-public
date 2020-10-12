<?php

include "common.php";
$Id=$_POST["Id"];
$dateToRecord=$_POST["dateToRecord"];


session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $SERVER."logActivity.php") !== FALSE && $_POST['token'] == $_SESSION['token']) {
	if(add_activity_to_user(get_uid_cookie($COOKIE_USER), $Id, $dateToRecord) == TRUE) {
		echo "Success! Your activity is pending review!";
	}
	else {
		echo "Error!";
	}
}
else {
    echo "Not a valid request!";
}


?>