<?php

function get_points($u_id)
{
	global $COOKIE_USER;
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT SUM(Activities.PV) AS Points
        FROM UserActivity
        JOIN Activities ON Activities.Id = UserActivity.ActivityId
        JOIN Users ON Users.Id = UserActivity.UserId
        WHERE Users.Id = :Id
        AND UserActivity.Approved = 1
		AND Activities.TeamNumber = :teamnum;";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':Id', $u_id, PDO::PARAM_STR);
		$statement->bindParam(':teamnum', get_FRC_team_user(get_uid_cookie($COOKIE_USER)), PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();

		return $result[0][0];
	} catch (PDOException $error) {
		return $error->getMessage();
	}
}

?>