<?php 
function get_all_categories_option($selectedId = 1) {
	global $COOKIE_USER;
	try {
		$SQL='SELECT * FROM Categories WHERE TeamNumber = :teamnum';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_uid_cookie($COOKIE_USER)), PDO::PARAM_STR);
		$statement->execute();
		$options="<select class='js-cat' name='categories' id='categories'>"; 
		$result = $statement->fetchAll();
		foreach ($result as $cat) {
			$name=$cat["Name"]; 
			$Id=(int) $cat["Id"];
			
			if($selectedId == $Id) {
				$options.="<option selected='selected' value=".$Id.">".$name."</option>"; 
			}
			else {
				$options.="<option value=".$Id.">".$name."</option>"; 
			}
				
			
		}
		$options.= "</select>";
		return $options;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}

}

function get_all_categories_team() {
	global $COOKIE_USER;
	try {
		$SQL='SELECT * FROM Categories WHERE TeamNumber = :teamnum';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_uid_cookie($COOKIE_USER)), PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetchAll();
		return $result;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}

}

function add_category($name)
{
	global $COOKIE_USER;
	try {
		$connection = create_PDO_connection();

		$new_activity = "INSERT INTO Categories
      (Name, TeamNumber) VALUES
      (:name, :teamnum)";

		$statement = $connection->prepare($new_activity);

		$statement->bindParam(':name', $name, PDO::PARAM_STR);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_uid_cookie($COOKIE_USER)), PDO::PARAM_STR);

		$statement->execute();
		return TRUE;
	} catch (PDOException $error) {
		return $new_activity . "<br>" . $error->getMessage();
	}
}

function check_if_category_exists($name)
{
	global $COOKIE_USER;
	try {
		$connection = create_PDO_connection();

		$new_activity = "SELECT Name FROM Categories WHERE Name = :name AND TeamNumber = :teamnum";

		$statement = $connection->prepare($new_activity);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_uid_cookie($COOKIE_USER)), PDO::PARAM_STR);
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



function update_category($name, $id) {
	try {
		$SQL='UPDATE Categories SET Name = :name WHERE Id = :id';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':name', $name, PDO::PARAM_STR);
		$statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();
		
		return TRUE;
		}
		catch(PDOException $error) {
			echo $SQL . "<br>" . $error->getMessage();
			return FALSE;
		}
}

function get_category_id($actid) {

	try {

		$connection = create_PDO_connection();

		$sql = "SELECT CategoryId FROM Activities WHERE Id = :id";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':id', $actid, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result[0][0];
		
	} catch (PDOException $error) {
		echo $error->getMessage();
		return FALSE;
	}
	
}

function category_breakdown_user($uid) {
	global $COOKIE_USER;
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT Categories.Name, SUM(Activities.PV) AS CatPoints, COUNT(Activities.Id) AS CatActivities
		FROM UserActivity
		JOIN Activities ON UserActivity.ActivityId = Activities.Id
		JOIN Categories ON Activities.CategoryId = Categories.Id
		WHERE UserActivity.UserId = :id
		AND UserActivity.Approved = 1 AND Activities.TeamNumber = :teamnum
		GROUP BY Categories.Name";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':id', $uid, PDO::PARAM_STR);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_uid_cookie($COOKIE_USER)), PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
		
	} catch (PDOException $error) {
		echo $error->getMessage();
		return FALSE;
	}
}

function category_breakdown_all() {
	global $COOKIE_USER;
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT Categories.Name, SUM(Activities.PV) AS CatPoints, COUNT(Activities.Id) AS CatActivities
		FROM UserActivity
		JOIN Activities ON UserActivity.ActivityId = Activities.Id
		JOIN Categories ON Activities.CategoryId = Categories.Id
		WHERE UserActivity.Approved = 1 AND Categories.TeamNumber = :teamnum
		GROUP BY Categories.Name";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_uid_cookie($COOKIE_USER)), PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
		
	} catch (PDOException $error) {
		echo $error->getMessage();
		return FALSE;
	}
}
?>