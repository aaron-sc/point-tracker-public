<?php

include "common.php";
deny_if_not_logged_in($COOKIE_USER);
echo do_navbar($isADMIN, 1);

?>

<html>
    <link rel="stylesheet" href="styles.css">
    <div class="normalHeader">Recommended Order To Watch:</div>
    <ol>
        <li><a href="Misc/points_and_stats_student.mp4">Points and Stats</a><br></li><br>
        <li><a href="Misc/log_activity_student.mp4">Log Activities</a><br></li><br>
        <li><a href="Misc/everything_else_student.mp4">Everything Else</a></li><br>
    </ol>
</html>