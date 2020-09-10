<?php

function get_FRC_team_user($userId) {
    try {

		$connection = create_PDO_connection();

		$sql = "SELECT TeamNumber
          FROM Users
		  WHERE Id = :uid";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':uid', $userId, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result[0][0];
	} catch (PDOException $error) {
		return $error->getMessage();
	}
}

function get_FRC_team_name($teamId) {
    try {

		$connection = create_PDO_connection();

		$sql = "SELECT Name
          FROM FRCTeams
		  WHERE TeamNumber = :id";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':id', $teamId, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result[0][0];
	} catch (PDOException $error) {
		return $error->getMessage();
	}
}

function get_all_FRC_Teams_option($selectedTeam = 3216, $disabled = FALSE) {
	try {
		$SQL='SELECT * FROM FRCTeams';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
        $statement->execute();
        if($disabled) {
            $options="<select disabled class='FRCTeams' name='FRCTeams' id='FRCTeams'>"; 
        } else {
            $options="<select class='FRCTeams' name='FRCTeams' id='FRCTeams'>"; 
        }
		
		$result = $statement->fetchAll();
		foreach ($result as $frc) {
			$name=$frc["Name"]; 
			$Id=(int) $frc["TeamNumber"];
			
			if($selectedTeam == $Id) {
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

function check_if_user_is_not_in_team($userId) {
    try {

		$connection = create_PDO_connection();

		$sql = "SELECT TeamNumber
          FROM Users
		  WHERE Id = :uid";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':uid', $userId, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		if($result[0][0] === "0") {
			return TRUE;
		}
		else {
			return FALSE;
		}
	} catch (PDOException $error) {
		echo $error->getMessage();
		return FALSE;
	}
}

function change_team($userId, $teamnum) {
    try {
		$SQL = 'UPDATE Users SET TeamNumber = :teamnum WHERE Id = :id';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':teamnum', $teamnum, PDO::PARAM_STR);
		$statement->bindParam(':id', $userId, PDO::PARAM_STR);
		$statement->execute();

		return TRUE;
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}

function reset_user_team($userId) {
    try {
		$SQL = 'UPDATE Users SET TeamNumber = 0 WHERE Id = :id';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':id', $userId, PDO::PARAM_STR);
		$statement->execute();

		return TRUE;
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}
?>