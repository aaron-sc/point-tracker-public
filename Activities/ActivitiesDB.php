<?php

// include "../config.php";
$priorities = ["Low", "Normal", "High"];
function priority_dropdown($selected){
	global $priorities;
	$options = "<select class='js-pri' name='priority' id='priority'>";
	for ($i = 0; $i <3; $i++) {
		if($i==$selected) {
			$options .= "<option selected='selected' value=".$i.">".$priorities[$i]." Priority</option>";
		}
		else {
			$options .= "<option value=".$i.">".$priorities[$i]." Priority</option>";
		}
	}
	$options .= "</select>";
	return $options;
}

function get_priority($selected){
	global $priorities;
	$toReturn = $priorities[$selected] . " Priority";
	return $toReturn;
}

function add_activity($name, $points, $desciption, $visible, $onelog, $prioity, $cat)
{
	global $COOKIE_USER;
	try {
		$connection = create_PDO_connection();

		$new_activity = "INSERT INTO Activities
      (Name, Description, PV, UserId, Visible, OneLog, CategoryId, Priority, TeamNumber) VALUES
      (:name, :description, :pv, :Id, :vis, :one, :cat, :pri, :teamnum)";

		$statement = $connection->prepare($new_activity);
		
		$statement->bindParam(':name', $name, PDO::PARAM_STR);
		$statement->bindParam(':pv', $points, PDO::PARAM_STR);
		$statement->bindParam(':description', $desciption, PDO::PARAM_STR);
		$statement->bindParam(':Id', get_user_id(get_username_cookie($COOKIE_USER)), PDO::PARAM_STR);
		$statement->bindParam(':vis', $visible, PDO::PARAM_STR);
		$statement->bindParam(':one', $onelog, PDO::PARAM_STR);
		$statement->bindParam(':cat', $cat, PDO::PARAM_STR);
		$statement->bindParam(':pri', $prioity, PDO::PARAM_STR);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
		$statement->execute();
		return TRUE;
	} catch (PDOException $error) {
		echo $new_activity . "<br>" . $error->getMessage();
		return FALSE;
	}
}

function add_activity_to_user($userid, $activityid)
{
	try {
		$connection = create_PDO_connection();

		$new_user_activity = "INSERT INTO UserActivity (UserId, ActivityId) VALUES (:uid,:actid)";

		$statement = $connection->prepare($new_user_activity);

		$statement->bindParam(':uid', $userid, PDO::PARAM_STR);
		$statement->bindParam(':actid', $activityid, PDO::PARAM_STR);
		$statement->execute();
		return TRUE;
	} catch (PDOException $error) {
		return $new_user_activity . "<br>" . $error->getMessage();
	}
}

function get_visible_activities() {
	global $COOKIE_USER;
	try {
		$SQL='SELECT Activities.Id, Activities.Name, Activities.Description, Activities.PV, Activities.Priority, Categories.Name AS CatName
		FROM Activities
		JOIN Categories ON Activities.CategoryId = Categories.Id WHERE Visible = 1 AND Activities.TeamNumber = :teamnum 
		ORDER BY Activities.Priority DESC';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
		$statement->execute();
		
		$result = $statement->fetchAll();
		return $result;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}

}

function get_all_activities_team() {
	global $COOKIE_USER;
	try {
		$SQL='SELECT * FROM Activities WHERE TeamNumber = :teamnum ';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
		$statement->execute();
		
		$result = $statement->fetchAll();
		return $result;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}

}

function count_activities_to_be_verified($UserId) {
	global $COOKIE_USER;
	try {
		$SQL='SELECT DISTINCT UserActivity.Id AS Id, Users.Fname, Users.Lname, Activities.Name AS ActivityName, Activities.PV AS ActivityPV 
		FROM UserActivity
		LEFT OUTER JOIN Users ON UserActivity.UserId = Users.Id 
		LEFT OUTER JOIN Activities On UserActivity.ActivityId = Activities.Id 
		WHERE Activities.UserId = :Id AND UserActivity.Approved = 0 AND Activities.TeamNumber = :teamnum';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':Id', $UserId, PDO::PARAM_STR);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
		$statement->execute();
		
		$result = $statement->fetchAll();
		return count($result);
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}
}


// Return true if multiple times
function check_if_activity_can_only_be_logged_once($UserId, $ActId) {
	global $COOKIE_USER;
	try {
		$SQL='SELECT DISTINCT UserActivity.Id AS Id, Users.Fname, Users.Lname, Activities.Name AS ActivityName, Activities.PV AS ActivityPV, Activities.OneLog
		FROM UserActivity
		LEFT OUTER JOIN Users ON UserActivity.UserId = Users.Id 
		LEFT OUTER JOIN Activities On UserActivity.ActivityId = Activities.Id 
		WHERE UserActivity.UserId = :Id AND Activities.OneLog = 1 AND Activities.Id = :actid AND Activities.TeamNumber = :teamnum;';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':Id', $UserId, PDO::PARAM_STR);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
		$statement->bindParam(':actid', $ActId, PDO::PARAM_STR);
		$statement->execute();
		
		$result = $statement->fetchAll();
		if (count($result) >= 1) {
			return TRUE;
		}
		else {
			return FALSE;
		}
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}
}

// Return true if multiple times
function activity_previously_logged($UserId, $ActId) {
	global $COOKIE_USER;
	try {
		$SQL='SELECT DISTINCT UserActivity.Id AS Id, Users.Fname, Users.Lname, Activities.Name AS ActivityName, Activities.PV AS ActivityPV
		FROM UserActivity
		LEFT OUTER JOIN Users ON UserActivity.UserId = Users.Id 
		LEFT OUTER JOIN Activities On UserActivity.ActivityId = Activities.Id 
		WHERE UserActivity.UserId = :Id AND Activities.Id = :actid AND Activities.TeamNumber = :teamnum';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':Id', $UserId, PDO::PARAM_STR);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
		$statement->bindParam(':actid', $ActId, PDO::PARAM_STR);
		$statement->execute();
		
		$result = $statement->fetchAll();
		if (count($result) >= 1) {
			return TRUE;
		}
		else {
			return FALSE;
		}
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}
}

function verify_activities($UserId) {
	global $COOKIE_USER;
	try {
		$SQL='SELECT DISTINCT UserActivity.Id AS Id, Activities.Priority AS Priority, Users.Fname, Users.Lname, Activities.Name AS ActivityName, Activities.PV AS ActivityPV 
		FROM UserActivity
		LEFT OUTER JOIN Users ON UserActivity.UserId = Users.Id 
		LEFT OUTER JOIN Activities On UserActivity.ActivityId = Activities.Id 
		WHERE Activities.UserId = :Id AND UserActivity.Approved = 0 AND Activities.TeamNumber = :teamnum';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
		$statement->bindParam(':Id', $UserId, PDO::PARAM_STR);
		$statement->execute();
		
		$result = $statement->fetchAll();
		return $result;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}

}

function deny_activity($Id) {
	try {
		$SQL='DELETE FROM UserActivity WHERE Id = :ActId';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':ActId', $Id, PDO::PARAM_STR);
		$statement->execute();
		
		return TRUE;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}

}


function acceptActivity($Id) {
	try {
		$SQL='UPDATE UserActivity SET Approved = 1 WHERE Id = :ActId';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':ActId', $Id, PDO::PARAM_STR);
		$statement->execute();
		
		return TRUE;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}

}

function view_user_activities($UserId) {
	global $COOKIE_USER;
	try {
		$SQL='SELECT DISTINCT Categories.Name AS CatName, UserActivity.Approved AS Approved, UserActivity.Id AS Id, Users.Fname, Users.Lname, Activities.Name AS ActivityName, Activities.PV AS ActivityPV 
		FROM UserActivity
		LEFT OUTER JOIN Users ON UserActivity.UserId = Users.Id 
		LEFT OUTER JOIN Activities On UserActivity.ActivityId = Activities.Id
        LEFT OUTER JOIN Categories ON Activities.CategoryId = Categories.Id
		WHERE UserActivity.UserId = :Id AND Activities.TeamNumber = :teamnum';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
		$statement->bindParam(':Id', $UserId, PDO::PARAM_STR);
		$statement->execute();
		
		$result = $statement->fetchAll();
		return $result;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}

}

function delete_activity($Id) {
	try {
		$SQL='DELETE FROM Activities WHERE Id = :ActId';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':ActId', $Id, PDO::PARAM_STR);
		$statement->execute();
		
		return TRUE;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}
}

function update_activity($name, $des, $pv, $id, $vis, $onelog, $prioity, $catId) {
	try {
		$SQL='UPDATE Activities SET Name = :name, Description = :des, PV = :pv, Visible = :vis, OneLog = :onelog, Priority = :pri, CategoryId = :catId WHERE Id = :id';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':name', $name, PDO::PARAM_STR);
		$statement->bindParam(':des', $des, PDO::PARAM_STR);
		$statement->bindParam(':pv', $pv, PDO::PARAM_STR);
		$statement->bindParam(':vis', $vis, PDO::PARAM_STR);
		$statement->bindParam(':onelog', $onelog, PDO::PARAM_STR);
		$statement->bindParam(':pri', $prioity, PDO::PARAM_STR);
		$statement->bindParam(':catId', $catId, PDO::PARAM_STR);
		$statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();
		
		return TRUE;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}

	}

	function user_activity($uid) {
		global $COOKIE_USER;
		try {
			$SQL='SELECT Users.Id, Users.Fname, Users.Lname, SUM(Activities.PV) AS Points, COUNT(UserActivity.Id) AS Activities
			FROM UserActivity
			JOIN Activities ON UserActivity.ActivityId = Activities.Id
			JOIN Users ON UserActivity.UserId = Users.Id
			WHERE UserActivity.UserId = :id
			AND UserActivity.Approved = 1 AND Activities.TeamNumber = :teamnum
			GROUP BY Users.Id;';
			$connection = create_PDO_connection();
			$statement = $connection->prepare($SQL);
			$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
			$statement->bindParam(':id', $uid, PDO::PARAM_STR);
			$statement->execute();

			$result = $statement->fetchAll();
			return $result;
			}
			catch(PDOException $error) {
				echo $SQL . "<br>" . $error->getMessage();
				return FALSE;
			}
	}

	function all_activity() {
		global $COOKIE_USER;
		try {
			$SQL='SELECT Users.Id AS Id, Users.Fname, Users.Lname, SUM(Activities.PV) AS Points, COUNT(UserActivity.Id) AS Activities
			FROM UserActivity
			JOIN Activities ON UserActivity.ActivityId = Activities.Id
			JOIN Users ON UserActivity.UserId = Users.Id
			WHERE UserActivity.Approved = 1 AND Activities.TeamNumber = :teamnum
			GROUP BY Users.Id;';
			$connection = create_PDO_connection();
			$statement = $connection->prepare($SQL);
			$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
			$statement->execute();

			$result = $statement->fetchAll();
			return $result;
			}
			catch(PDOException $error) {
				echo $SQL . "<br>" . $error->getMessage();
				return FALSE;
			}
	}

	function user_activity_in_category($uid, $catid) {
		global $COOKIE_USER;
		try {
			$SQL='SELECT UserActivity.Id, Users.Fname, Users.Lname, SUM(Activities.PV) AS Points, COUNT(UserActivity.Id) AS Activities
			FROM UserActivity
			JOIN Activities ON UserActivity.ActivityId = Activities.Id
			JOIN Users ON UserActivity.UserId = Users.Id
            WHERE UserActivity.UserId = :uid
            AND
            Activities.CategoryId = :catid AND Activities.TeamNumber = :teamnum
			GROUP BY UserActivity.Id;';
			$connection = create_PDO_connection();
			$statement = $connection->prepare($SQL);
			$statement->bindParam(':uid', $uid, PDO::PARAM_STR);
			$statement->bindParam(':teamnum', get_FRC_team_user(get_user_id(get_username_cookie($COOKIE_USER))), PDO::PARAM_STR);
			$statement->bindParam(':catid', $catid, PDO::PARAM_STR);
			$statement->execute();

			$result = $statement->fetchAll();
			return $result;
			}
			catch(PDOException $error) {
				echo $SQL . "<br>" . $error->getMessage();
				return FALSE;
			}
	}

	function check_if_activity_exists($name)
{
	try {
		$connection = create_PDO_connection();

		$new_activity = "SELECT Name FROM Activities WHERE Name = :name";

		$statement = $connection->prepare($new_activity);

		$statement->bindParam(':name', $name, PDO::PARAM_STR);

		$statement->execute();
		$result = $statement->fetchAll();

		if (count($result) >= 1) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	} catch (PDOException $error) {
		echo $new_activity . "<br>" . $error->getMessage();
		return FALSE;
	}
}
?>