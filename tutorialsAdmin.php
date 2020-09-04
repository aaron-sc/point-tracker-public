<?php

include "common.php";
deny_if_not_logged_in($COOKIE_USER);
deny_if_not_admin($isADMIN);
echo do_navbar($isADMIN, 1);

?>

<html>
    <link rel="stylesheet" href="styles.css">
    <div class="normalHeader">Recommended Order To Watch:</div>
    <ol>
        <li><a href="Misc/activity_tab.mp4">Activities</a><br></li><br>
        <li><a href="Misc/categories_tab.mp4">Categories</a><br></li><br>
        <li><a href="Misc/users_tab.mp4">Users</a><br></li><br>
        <li><a href="Misc/reset_password.mp4">Reset Password</a><br></li><br>
        <li><a href="Misc/points_and_stats.mp4">Points and Stats</a><br></li><br>
        <li><a href="Misc/everything_else.mp4">Everything Else</a></li><br>
    </ol>
</html>