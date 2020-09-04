<html>
	<title> Sign Up </title>
	<link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
<html>

<?php

include "common.php";
/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */


if(get_username_cookie($COOKIE_USER)) {
	echo do_navbar($isADMIN, 1);
}
else {
	echo do_navbar($isADMIN, 0);
}



if (isset($_POST['submit'])) {
	$username = $_POST['uname'];
	$Fname = $_POST['fname'];
	$Lname = $_POST['lname'];
	$password = $_POST['pswd'];
	$passwordc = $_POST['pswdc'];

	$postADMIN = $_POST['isAdmin'];

	if (empty($postADMIN)){
		$admin = 0;
	}
	else {
		$admin = $_POST['isAdmin'];
	}

	if ((compare_passwords($password, $passwordc)) && user_exists(($username)) && $username != "") {
		if (sign_up_user($username, $password, $admin, $Fname, $Lname) === TRUE) {
			echo ("<div class='goodHeader'>".$username . " added!</div>");
		} else {
			echo "<div class='warningHeader'>An error occured</div>";
		}
	} else if (user_exists(($username)) && $username != "") {
		echo "<div class='warningHeader'>Passwords do not match!</div>";
	} 
	else if($username != "") {
		echo "<div class='warningHeader'>User already exists</div>";
	}
	else {
		echo "<div class='warningHeader'>Empty fields!</div>";
	}
}
?>


<html>
<link rel="stylesheet" href="styles.css">
<ul>
	<!-- <li><a href="index.html">Back</a></li> -->
</ul>

<?php
do_signup($isADMIN);
?>

</html>