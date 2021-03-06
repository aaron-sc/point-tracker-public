<html>
	<title> Reset Password </title>
	<link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
<html>

<?php

include "common.php";

if (isset($_POST['submit'])) {

	$uname = $_POST['uname'];
    $password = $_POST['pswd'];
    $passwordc = $_POST['pswdc'];

	$reset = check_if_pass_reset(get_user_id($uname));

	if($reset) {
		if(compare_passwords($password, $passwordc)) {
            if(reset_password(get_user_id($uname), $password)) {
                echo "<div class=normalHeader> Success! Please Log In! </div>";
            }
            else {
                echo "<div class=warningHeader> An Error Occured! </div>";
            }
        }
    }
    else {
        echo "<div class=warningHeader> You are not eligible for a password reset, you can change yours on the \"My Account\" Page.</div>";
    }
}

echo do_navbar(0,0);

?>


<html>
<div class="container">
	<form method="post">
        <h2 class="normalHeader">Please Reset Your Password!</h2>
		<input type="text" id="uname" name="uname" placeholder="Username.." required="required">
        <input type="password" pattern=".{6,60}" title="6 to 60 Characters" id="pswd" name="pswd" placeholder="New Password.." required="required">
        <input type="password" pattern=".{6,60}" title="6 to 60 Characters" id="pswdc" name="pswdc" placeholder="Confirm New Password.." required="required">
		<input class="js-submit" type="submit" name="submit" value="submit">

	</form>
</div>


</html>