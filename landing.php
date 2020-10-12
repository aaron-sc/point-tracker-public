<html>
    <link rel="stylesheet" href="styles.css">
	<title> Home </title>
	<link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</html>

<?php
require "common.php";

//get-client-ip
$client_ip = $_SERVER['REMOTE_ADDR'];
//sets location for successful client logins
$client_log_success = "/murch/log/client-and-user.txt";
$client_username = get_first_name(get_uid_cookie($COOKIE_USER));
$client_user_id = get_uid_cookie($COOKIE_USER);
$t=time();
$datetime = date("Y-m-d H:s",$t);

$username_and_client = $datetime . ":" . $client_user_id . ":" . $client_username . ":" . $client_ip;
file_put_contents($client_log_success, $username_and_client . "\n", FILE_APPEND);

if (isset($_GET['message'])) {
	$param = $_GET['message'];
	if($param = "admin") {
		echo "<div class='warningHeader'>You are not an admin! Contact an administrator if you think this is a mistake!</div>";
	}
}


deny_if_not_logged_in($COOKIE_USER);

echo do_navbar($isADMIN, 1);

echo '<div class="welcomeHeader"> Welcome ' . get_first_name(get_uid_cookie($COOKIE_USER)) . '!</div>';
echo '<div class="welcomeHeader"> You are a part of FRC Team ' . get_FRC_team_name(get_FRC_team_user(get_uid_cookie($COOKIE_USER))) . '!</div>';
?>
