<?php

include "common.php";
$Id=$_POST["Id"];


session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==$SERVER."editCategories.php" && $_POST['token'] == $_SESSION['token']) {
    if(delete_activity($Id) == FALSE) {
        echo "Error!";
    }
    else {
        echo "Success! Category and ALL ACTIVITES ASSCOIATED WITH THE CATEGORY HAVE BEEN DELETED!";
    }
}
else {
    echo "Not a valid request!";
}
?>

?>