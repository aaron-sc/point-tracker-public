<?php
include "common.php";
$Ids=$_POST["Ids"];
$success = TRUE;


session_start();
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && @isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==$SERVER."verifyActivity.php" && $_POST['token'] == $_SESSION['token']) {
    foreach ($Ids as $Id) {
        if(acceptActivity($Id) == FALSE) {
            $success = FALSE;
            echo $success;
        }
    }  
    if($success == FALSE) {
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