<html>
    <link rel="stylesheet" href="styles.css">
	<title> Home </title>
	<link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
</html>

<?php

if (isset($_GET['message'])) {
	$param = $_GET['message'];
	if($param = "admin") {
		echo "<div class='warningHeader'>You are not an admin! Contact an administrator if you think this is a mistake!</div>";
	}
}

require "common.php";
deny_if_not_logged_in($COOKIE_USER);

echo do_navbar($isADMIN, 1);

echo '<div class="welcomeHeader"> Welcome ' . get_first_name(get_user_id(get_username_cookie($COOKIE_USER))) . '!</div>';
?>
