<html>
	<title> Log In </title>
	<link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
<html>

<?php
include "common.php";


 //this is for requests, not seccuessfull logins
 //get-client-ip
 $client_ip = $_SERVER['REMOTE_ADDR'];
 //set file location
 $client_log = "/murch/log/access-log.txt";
 //write client ID to log file
 file_put_contents($client_log, $client_ip . "\n", FILE_APPEND);

 

if (isset($_POST['submit'])) {

	

	$uname = $_POST['uname'];
	$password = $_POST['pswd'];
	$uid = get_user_id($uname);
	$reset = check_if_pass_reset(get_user_id($uname));
	$team = check_if_user_is_not_in_team(get_user_id($uname));
	$login = log_in_user($uname, $password);

	

	if(!$reset && !$team && $login) {
		setcookie($COOKIE_USER, $uid, time() + (86400 * 14)); 
		header("Location: landing.php");
		
	} else if ($reset) {
		echo "<div class='warningHeader'>PLEASE RESET YOUR PASSWORD!</div>";
	}
	else if($login == TRUE) {
		echo "<div class='warningHeader'>Go choose a new FRC Team!</div>";
	}
	else {
		echo "<div class='warningHeader'>User doesn't exist, or password is incorrect</div>";
		
	}
}

if (isset($_GET['message'])) {
	$param = $_GET['message'];
	if($param == "login") {
		echo "<div class='warningHeader'>PLEASE LOG IN OR SIGN UP</div>";
	}
	if($param == "resetpass" && !$reset) {
		echo "<div class='warningHeader'>PLEASE RESET YOUR PASSWORD!</div>";
	}
	if($param == "newteam" && !$team) {
		echo "<div class='warningHeader'>Go Choose a new FRC Team!</div>";
	}
}



if(get_uid_cookie($COOKIE_USER) && !isset($_GET['message'])){
	header("Location: landing.php");
}

echo do_navbar(0,0);

?>


<html>
<div class="container">
	<form method="post">

		<input type="text" id="uname" name="uname" placeholder="Username.." required="required" autofocus="autofocus" onfocus="this.select()">
		<input type="password" id="pswd" name="pswd" placeholder="Password.." required="required">
		<input type="submit" name="submit" value="Log In">
	</form>
	<a href="resetPass.php"><button>Reset Password</button></a>
	<br>
	<br>
	<a href="changeTeam.php"><button>Change Team</button></a>
</div>


</html>