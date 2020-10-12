<html>
<link rel="stylesheet" href="styles.css">
<title> User Stats </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

</html>


<?php
require "common.php";
deny_if_not_logged_in($COOKIE_USER);
echo do_navbar($isADMIN, 1);


$result = user_activity(get_uid_cookie($COOKIE_USER));
if ($result == FALSE) {
    echo '<div class="viewPointsHeader"> No data to show you! :( </div>';
    echo '<div class="viewPointsMessage"> If you believe this is a mistake, please contact an administrator </div>';
    die();
}
?>



<html>
<link rel="stylesheet" href="styles.css">
<br>
<div class="normalHeader"> Your Stats </div>
<br>

<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td> Name</td>
            <td> Points You've Earned</td>
            <td> Activities You've Logged</td>
        </tr>
    </thead>
    <tbody id="js-activitiestobeverified">
        <?php foreach ($result as $activity) { ?>
            <tr>
                <td> <?php echo escape($activity["Fname"]) . " " . escape($activity["Lname"]); ?> </td>
                <td> <?php echo escape($activity["Points"]); ?> </td>
                <td> <?php echo escape($activity["Activities"]); ?> </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</html>