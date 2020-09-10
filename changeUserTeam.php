<?php
include "common.php";
$Id=$_POST["Id"];


session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']==$SERVER."editUsers.php" || $_SERVER['HTTP_REFERER']==$SERVER."student.php" ) && $_POST['token'] == $_SESSION['token']) {
    if(reset_user_team($Id) == FALSE) {
        echo "Error!";
    }
    else {
        clear_username_cookie($COOKIE_USER);
        echo "Success! If this was your user, please change your team! Otherwise, the user will be redirected to reset their password!";
    }
}
else {
    echo "Not a valid request!";
}
?>