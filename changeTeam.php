<html>
	<title> Change FRC Team </title>
	<link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
<html>

<?php

include "common.php";

if (isset($_POST['submit'])) {

	$uname = $_POST['uname'];
    $teamnum = $_POST['FRCTeams'];

	$team = check_if_user_is_not_in_team(get_user_id($uname));

	if($team) {
            if(change_team(get_user_id($uname), $teamnum)){
                echo "<div class=normalHeader> Success! Please Log In! </div>";
            }
            else {
                echo "<div class=warningHeader> An Error Occured!</div>";
            }
    }
    else {
        echo "<div class=warningHeader> You are not eligible for a Team Change.</div>";
    }
}

echo do_navbar(0,0);

?>


<html>
<div class="container">
	<form method="post">
        <h2 class="normalHeader">Please Change Teams!</h2>
        <input type="text" id="uname" name="uname" placeholder="Username.." required="required">
        <label for="FRCTeams">FRC Team:</label>
        <?php echo(get_all_FRC_Teams_option(get_user_id(get_username_cookie($COOKIE_USER)))); ?>
		<input class="js-submit" type="submit" name="submit" value="submit">

	</form>
</div>
