<html>
<title> Add Activity </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

</html>

<?php
include "common.php";

deny_if_not_logged_in($COOKIE_USER);


if (get_username_cookie($COOKIE_USER)) {
	echo do_navbar($isADMIN, 1);
} else {
	//echo do_navbar($isADMIN, 0);
}

if (isset($_POST['submit'])) {
	$name = $_POST['actName'];
	$points = $_POST['actPoints'];
	$description = $_POST['actDesc'];
	$cat = $_POST['categories'];
	$priority = $_POST['priority'];

	if (isset($_POST['visible'])) {
		$visible = 1;
	} else {

		$visible = 0;
	}

	if (isset($_POST['logonce'])) {
		$logonce = 1;
	} else {

		$logonce = 0;
	}
	if (add_activity($name, $points, $description, $visible, $logonce, $priority, $cat)) {
		echo "<div class='goodHeader'>Activity " . "'" . $name . "' created!</div>";
	} else {
		echo "<div class='warningHeader'>ERROR! Activity could not be created!</div>";
	}
}


?>


<html>
<link rel="stylesheet" href="styles.css">

<div class="container">
	<form action=addActivity.php method="POST">

		<input type="text" id="actName" name="actName" placeholder="Acitivity Name.." required="required" autofocus='autofocus' onfocus='this.select()'>
		<input type="number" min="1" id="actPoints" name="actPoints" placeholder="Activity Points.." required="required">
		<input type="text" id="actDesc" name="actDesc" placeholder="Activity Description.." required="required">
		<label for="categories">Category: </label>
		<?php echo get_all_categories_option(); ?>
		<br>
		<label for="priority">Priority: </label>
		<?php echo priority_dropdown(2); ?>
		<br>
		<label for="logonce">Users Can Only Log One Occurance: </label>
		<input type="checkbox" id="logonce" name="logonce">
		<br>
		<label for="visible">Visible: </label>
		<input type="checkbox" id="visible" name="visible" checked>
		<br>
		<br>
		<input type="submit" name="submit" value="Submit">

	</form>
</div>




</html>