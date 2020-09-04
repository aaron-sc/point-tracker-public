<?php 
function get_all_categories_option($selectedId = 3) {
	try {
		$SQL='SELECT * FROM Categories';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
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

function get_all_categories() {
	try {
		$SQL='SELECT * FROM Categories';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
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
	try {
		$connection = create_PDO_connection();

		$new_activity = "INSERT INTO Categories
      (Name) VALUES
      (:name)";

		$statement = $connection->prepare($new_activity);

		$statement->bindParam(':name', $name, PDO::PARAM_STR);

		$statement->execute();
		return TRUE;
	} catch (PDOException $error) {
		return $new_activity . "<br>" . $error->getMessage();
	}
}

function check_if_category_exists($name)
{
	try {
		$connection = create_PDO_connection();

		$new_activity = "SELECT Name FROM Categories WHERE Name = :name";

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
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT Categories.Name, SUM(Activities.PV) AS CatPoints, COUNT(Activities.Id) AS CatActivities
		FROM UserActivity
		JOIN Activities ON UserActivity.ActivityId = Activities.Id
		JOIN Categories ON Activities.CategoryId = Categories.Id
		WHERE UserActivity.UserId = :id
		AND UserActivity.Approved = 1
		GROUP BY Categories.Name";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':id', $uid, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
		
	} catch (PDOException $error) {
		echo $error->getMessage();
		return FALSE;
	}
}

function category_breakdown_all() {
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT Categories.Name, SUM(Activities.PV) AS CatPoints, COUNT(Activities.Id) AS CatActivities
		FROM UserActivity
		JOIN Activities ON UserActivity.ActivityId = Activities.Id
		JOIN Categories ON Activities.CategoryId = Categories.Id
		WHERE UserActivity.Approved = 1
		GROUP BY Categories.Name";

		$statement = $connection->prepare($sql);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
		
	} catch (PDOException $error) {
		echo $error->getMessage();
		return FALSE;
	}
}
?>