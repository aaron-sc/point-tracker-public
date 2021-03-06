<?php

include ".../config.php";
function deny_if_not_logged_in($COOKIE_USER){
	if(!get_uid_cookie($COOKIE_USER)){
		header("Location: index.php");
	}
	if(check_if_pass_reset(get_uid_cookie($COOKIE_USER))) {
		clear_username_cookie($COOKIE_USER);
		header("Location: index.php");
	}
	if(check_if_user_is_not_in_team(get_uid_cookie($COOKIE_USER))) {
		clear_username_cookie($COOKIE_USER);
		header("Location: index.php");
	}
}

function deny_if_not_admin($is_admin){
	if(!$is_admin){
		header("Location: landing.php");
	}
}

function deny_request() {
	header("Location: tpdne.html");
}

function do_signup($is_admin)
{
	global $COOKIE_USER;
	if ($is_admin) {
		echo '<div class="container">
		<form method="post">
	
			<input type="text" id="uname" name="uname" placeholder="Username.." required="required" autofocus="autofocus" onfocus="this.select()">
			<input type="text" id="fname" name="fname" placeholder="First name..." required="required">
			<input type="text" id="lname" name="lname" placeholder="Last name..." required="required">
			<input type="password" pattern=".{6,60}" title="6 to 60 Characters" id="pswd" name="pswd" placeholder="Password.." required="required">
			<input type="password" pattern=".{6,60}" title="6 to 60 Characters" id="pswdc" name="pswdc" placeholder="Confirm password.." required=required>
			<label for="isAdmin">Admin:</label><input type="checkbox" id="isAdmin" name="isAdmin" checked><br>
			<br><label for="FRCTeams">FRC Team:</label><br>'.get_all_FRC_Teams_option(get_FRC_team_user(get_uid_cookie($COOKIE_USER)), TRUE).'
			<input type="submit" name="submit" value="submit">
	
		</form>
	</div>';
	} else {
		echo '<div class="container">
		<form method="post">
	
			<input type="text" id="uname" name="uname" placeholder="Username.." required="required" autofocus="autofocus" onfocus="this.select()">
			<input type="text" id="fname" name="fname" placeholder="First name..." required="required">
			<input type="text" id="lname" name="lname" placeholder="Last name..." required="required">
			<input type="password" pattern=".{6,60}" title="6 to 60 Characters" id="pswd" name="pswd" placeholder="Password.." required="required">
			<input type="password" pattern=".{6,60}" title="6 to 60 Characters" id="pswdc" name="pswdc" placeholder="Confirm password.." required="required">
			<label for="FRCTeams">FRC Team:</label>'.get_all_FRC_Teams_option().'
			<input type="submit" name="submit" value="submit">
		</form>

	</div>';
	}
}

function do_navbar($is_admin, $logged_in) /* add a page var to the function params */
{
	global $COOKIE_USER;
	$count = count_activities_to_be_verified(get_uid_cookie($COOKIE_USER));
	if(get_points(get_uid_cookie($COOKIE_USER)) != ""){
		$points = get_points(get_uid_cookie($COOKIE_USER));
	}
	else {
		$points = 0;
	}
	if ($is_admin && $logged_in) {

		
		return '
			<html>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="styles.css">
			<ul>
				<li><a href="landing.php">Home</a></li>
				<li>
					<div class="custom_dropdown">
						<button class="dropbtn">Activities 
				  			<i class="fa fa-caret-down"></i>
						</button>
						<div class="custom_dropdown_content">
							<a href="addActivity.php">Add an Activity</a>
							<a href="logActivity.php">Log An Activity</a>
							<a href="editActivities.php">Manage Activities</a>
						</div>
					</div>
				</li>

				<li>
				<div class="custom_dropdown">
					<button class="dropbtn">Categories 
						  <i class="fa fa-caret-down"></i>
					</button>
					<div class="custom_dropdown_content">
						<a href="addCategory.php">Add a Category</a>
						<a href="editCategories.php">Manage Categories</a>
					</div>
				</div>
			</li>

			<li>
				<div class="custom_dropdown">
					<button class="dropbtn">Users 
						  <i class="fa fa-caret-down"></i>
					</button>
					<div class="custom_dropdown_content">
						<a href="signUp.php">Add a User</a>
						<a href="editUsers.php">Manage Users</a>
					</div>
				</div>
			</li>
			<li>
				<div class="custom_dropdown">
					<button class="dropbtn">Points and Stats
						<i class="fa fa-caret-down"></i>
					</button>
					<div class="custom_dropdown_content">
						<a href="viewPoints.php" class="notification">
							<span>View Points</span>
							<span id="js-toreviewbadge" class="badge">'.$points.'</span>
						</a>
						<a href="catBreakdownUser.php">View Personal Breakdown</a>
						<a href="catBreakdownGlobal.php">View Global Breakdown</a>
						<a href="personalUserActivity.php">Your Stats</a>
						<a href="globalUserActivity.php">Global Stats</a>
					</div>
				</div>
			</li>
				<li>

				</li>
				<li>
					<a href="verifyActivity.php" class="notification">
						<span>Review Student Activities</span>
						<span id="js-toreviewbadge" class="badge">'.$count.'</span>
			  		</a>
				</li>				
				<li>
					<div class="custom_dropdown">
						<button class="dropbtn">Other 
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="custom_dropdown_content">
							<a href="Misc/terms.pdf">Terms of Use</a>
							<a href="tutorialsAdmin.php">Tutorials</a>
							<a href="https://www.mrt3216.org">MRT 3216\'s Website</a>
							<a href="mailto:team3216@gmail.com">Contact The Creators</a>
							<a href="about.php">About</a>
						</div>
					</div>
				</li>
				<li><a href="logout.php">Logout</a></li>
				<li class="myaccount"><a href="student.php">My Account</a></li>

			</ul>
		</html>
	';
	} elseif ($logged_in) {
		return '<html>
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<ul>
			<li><a href="landing.php">Home</a></li>
			<li>
				<div class="custom_dropdown">
					<button class="dropbtn">Points and Stats
						<i class="fa fa-caret-down"></i>
					</button>
					<div class="custom_dropdown_content">
						<a href="viewPoints.php" class="notification">
							<span>View Points</span>
							<span id="js-toreviewbadge" class="badge">'.$points.'</span>
						</a>
						<a href="catBreakdownUser.php">View Personal Breakdown</a>
						<a href="personalUserActivity.php">Your Stats</a>
					</div>
				</div>
			</li>
			<li><a href="logActivity.php">Log An Activity</a></li>
			<li>
				<div class="custom_dropdown">
					<button class="dropbtn">Other 
						<i class="fa fa-caret-down"></i>
					</button>
					<div class="custom_dropdown_content">
						<a href="Misc/terms.pdf">Terms of Use</a>
						<a href="tutorialsStudent.php">Tutorials</a>
						<a href="https://www.mrt3216.org">MRT 3216\'s Website</a>
						<a href="mailto:team3216@gmail.com">Contact The Creators</a>
						<a href="about.php">About</a>
						
					</div>
				</div>
			</li>

			<li class="myaccount"><a href="student.php">My Account</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        
        
    </html>';
	} else {
		return '<html>
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <ul>
			<li><a href="index.php">Log In</a></li>
			<li><a href="signUp.php">Sign Up</a></li>
			<li>
				<div class="custom_dropdown">
					<button class="dropbtn">Other 
						<i class="fa fa-caret-down"></i>
					</button>
					<div class="custom_dropdown_content">
						<a href="Misc/terms.pdf">Terms of Use</a>
						<a href="https://www.mrt3216.org">MRT 3216\'s Website</a>
						<a href="mailto:team3216@gmail.com">Contact The Creators</a>
					</div>
				</div>
			</li>
        </ul>
        
        
    </html>';
	}

	
}

?>