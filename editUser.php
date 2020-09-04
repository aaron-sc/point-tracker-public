<?php

include "common.php";
$Id=$_POST["Id"];
$firstname=$_POST["firstname"];
$lastname=$_POST["lastname"];
$username=$_POST["username"];
$admin=$_POST["admin"];

session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']==$SERVER."editUsers.php" || $_SERVER['HTTP_REFERER']==$SERVER."student.php" ) && $_POST['token'] == $_SESSION['token']) {
    if(edit_user($firstname,$lastname, $admin, $username, $Id) == FALSE) {
        echo "Error!";
    }
    else {
        echo "Success! If this was your user, please log in again!";
    }
}
else {
    echo "Not a valid request!";
}
?>

