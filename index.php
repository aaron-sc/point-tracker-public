<html>
	<title> Log In </title>
	<link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
<html>

<?php
include "common.php";

if (isset($_POST['submit'])) {

	$uname = $_POST['uname'];
	$password = $_POST['pswd'];

	$reset = check_if_pass_reset(get_user_id($uname));
	$login = log_in_user($uname, $password);

	if($reset) {
		echo "<div class='warningHeader'>PLEASE RESET YOUR PASSWORD!</div>";
	} else if ($login == TRUE) {
		setcookie($COOKIE_USER, $uname, time() + (86400 * 14)); 
		header("Location: landing.php");
	}
	else {
		echo "<div class='warningHeader'>User doesn't exist</div>";
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
}



if(get_username_cookie($COOKIE_USER) && !isset($_GET['message'])){
	header("Location: landing.php");
}

echo do_navbar(0,0);

?>


<html>
<div class="container">
	<form method="post">

		<input type="text" id="uname" name="uname" placeholder="Username.." required="required">
		<input type="password" id="pswd" name="pswd" placeholder="Password.." required="required">
		<input type="submit" name="submit" value="Log In">
	</form>
	<a href="resetPass.php"><button>Reset Password</button></a>
</div>


</html>