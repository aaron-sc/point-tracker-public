<?php
include "common.php";
$Id=$_POST["Id"];


session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==$SERVER."verifyActivity.php" && $_POST['token'] == $_SESSION['token']) {
    if(deny_activity($Id) == FALSE) {
        echo "Error!";
    }
    else {
        echo "Success! Denied the activity!";
    }
}
else {
    echo "Not a valid request!";
}
?>