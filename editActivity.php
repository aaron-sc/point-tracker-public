<?php

include "common.php";
$Id=$_POST["Id"];
$name=$_POST["name"];
$des=$_POST["des"];
$pv=$_POST["pv"];
$visibility=$_POST["visibility"];
$onelog=$_POST["onelog"];
$cat = $_POST["cat"];
$pri = $_POST["pri"];
session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==$SERVER."editActivities.php" && $_POST['token'] == $_SESSION['token']) {
    if(update_activity($name,$des,$pv,$Id,$visibility, $onelog, $pri, $cat) == FALSE) {
        echo "Error!";
    }
    else {
        echo "Success!";
    }
}
else {
    echo "Not a valid request!";
}
?>

