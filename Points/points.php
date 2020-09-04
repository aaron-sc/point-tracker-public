<?php

function get_points($u_id)
{
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT SUM(Activities.PV) AS Points
        FROM UserActivity
        JOIN Activities ON Activities.Id = UserActivity.ActivityId
        JOIN Users ON Users.Id = UserActivity.UserId
        WHERE Users.Id = :Id
        AND UserActivity.Approved = 1;";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':Id', $u_id, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();

		return $result[0][0];
	} catch (PDOException $error) {
		return $error->getMessage();
	}
}

?>