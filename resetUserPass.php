<?php
include "common.php";
$Id=$_POST["Id"];


session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']==$SERVER."editUsers.php" || $_SERVER['HTTP_REFERER']==$SERVER."student.php" ) && $_POST['token'] == $_SESSION['token']) {
    if(reset_user_password($Id) == FALSE) {
        echo "Error!";
    }
    else {
        echo "Success! If this was your user, please reset your password! Otherwise, the user will be redirected to reset their password!";
    }
}
else {
    echo "Not a valid request!";
}
?>