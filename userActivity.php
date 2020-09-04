<?php

include "common.php";
$Id=$_POST["Id"];


session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==$SERVER."logActivity.php" && $_POST['token'] == $_SESSION['token']) {
	if(add_activity_to_user(get_user_id(get_username_cookie($COOKIE_USER)), $Id) == TRUE) {
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