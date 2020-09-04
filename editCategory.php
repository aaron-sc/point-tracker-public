<?php

include "common.php";

$id=$_POST["Id"];
$name=$_POST["name"];

// TODO: EDIT ACTIVITY

session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==$SERVER."editCategories.php" && $_POST['token'] == $_SESSION['token']) {
    if(update_category($name, $id) == FALSE) {
        echo "Error!";
    } 
    else {
        echo "Success! The category has been updated!";
    }
}
else {
    echo "Not a valid request!";
}
?>

