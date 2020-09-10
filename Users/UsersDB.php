<?php

// include "../config.php"; //causes error but idk why



function sign_up_user($uname, $pswd, $admin, $fName, $lName, $teamnum)
{
	try {

		$connection = create_PDO_connection();

		$USERNAME = $uname;
		$PASSWORD = password_hash($pswd, PASSWORD_DEFAULT);
		$ADMIN = $admin;

		$new_user = "INSERT INTO Users 
		(Uname, Password, Admin, FName, LName, TeamNumber) VALUES 
		(:username, :password, :admin, :FName, :LName, :teamnum);";

		$statement = $connection->prepare($new_user);

		$statement->bindParam(':username', $USERNAME, PDO::PARAM_STR);
		$statement->bindParam(':FName', $fName, PDO::PARAM_STR);
		$statement->bindParam(':LName', $lName, PDO::PARAM_STR);
		$statement->bindParam(':password', $PASSWORD, PDO::PARAM_STR);
		$statement->bindParam(':admin', $ADMIN, PDO::PARAM_STR);
		$statement->bindParam(':teamnum', $teamnum, PDO::PARAM_STR);
		$statement->execute();

		return TRUE;
	} catch (PDOException $error) {
		echo $error->getMessage();
		return $error->getMessage();
	}
}

function log_in_user($uname, $pswd)
{
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT Password
          FROM Users
		  WHERE Uname = :uname";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':uname', $uname, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		foreach ($result as $row) {
			if (password_verify($pswd, escape($row["Password"])) && !empty($pswd) && isset($pswd)) {
				return TRUE;
			}
		}
	} catch (PDOException $error) {
		echo $error->getMessage();
		return FALSE;
	}
}

function check_if_pass_reset($uid)
{
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT Password
          FROM Users
		  WHERE Id = :uid";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':uid', $uid, PDO::PARAM_STR);
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

function user_exists($uname)
{
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT *
          FROM Users
		  WHERE Uname = :uname";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':uname', $uname, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		return empty($result);
	} catch (PDOException $error) {
		return $error->getMessage();
	}
}

function is_admin($uname)
{
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT Admin
          FROM Users
          WHERE Uname = :uname";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':uname', $uname, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();

		foreach ($result as $row)
			if (escape($row["Admin"] == 1)) {
				return TRUE;
			}
	} catch (PDOException $error) {
		return $error->getMessage();
	}
}

function get_username_cookie($COOKIE_USER)
{
	if (isset($_COOKIE[$COOKIE_USER])) {
		return ($_COOKIE[$COOKIE_USER]);
	} else {
		return FALSE;
	}
}

function clear_username_cookie($COOKIE_USER)
{
	setcookie($COOKIE_USER, "", time() - 3600);
}

function get_user_id($uname)
{
	try {

		$connection = create_PDO_connection();

		$sql = "SELECT Id
          FROM Users
		  WHERE Uname = :uname";

		$statement = $connection->prepare($sql);
		$statement->bindParam(':uname', $uname, PDO::PARAM_STR);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result[0][0];
	} catch (PDOException $error) {
		return $error->getMessage();
	}
}

function get_all_users()
{
	try {
		$SQL = 'SELECT * FROM Users';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}

function get_student_details($uid)
{
	try {
		$SQL = 'SELECT * FROM Users WHERE Id = :uid';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':uid', $uid, PDO::PARAM_STR);

		$statement->execute();

		$result = $statement->fetchAll();
		return $result;
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}

function delete_user($uid)
{
	try {
		$SQL = 'DELETE FROM Users WHERE Id = :uid';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':uid', $uid, PDO::PARAM_STR);
		$statement->execute();

		return TRUE;
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}

function edit_user($fname, $lname, $admin, $uname, $id)
{
	try {
		$SQL = 'UPDATE Users SET Fname = :fname, Lname = :lname, Admin = :admin, Uname = :uname WHERE Id = :id';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':fname', $fname, PDO::PARAM_STR);
		$statement->bindParam(':lname', $lname, PDO::PARAM_STR);
		$statement->bindParam(':admin', $admin, PDO::PARAM_STR);
		$statement->bindParam(':uname', $uname, PDO::PARAM_STR);
		$statement->bindParam(':id', $id, PDO::PARAM_STR);
		$statement->execute();

		return TRUE;
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}

function reset_password($uid, $password) {
	$pass = password_hash($password, PASSWORD_DEFAULT);
	try {
		$SQL = 'UPDATE Users SET Password = :pass WHERE Id = :id';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':pass', $pass, PDO::PARAM_STR);
		$statement->bindParam(':id', $uid, PDO::PARAM_STR);
		$statement->execute();

		return TRUE;
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}


function get_name($uid) {
	try {
		$SQL = 'SELECT Fname, Lname FROM Users Where Id = :id';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':id', $uid, PDO::PARAM_STR);
		$statement->execute();

		
		$result = $statement->fetchAll();
		
		if(!empty($result)){
			$name = $result[0]["Fname"] . " " . $result[0]["Lname"];
			return $name;
		}
		else {
			return "An error occured";
		}
		
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}

function get_first_name($uid) {
	try {
		$SQL = 'SELECT Fname FROM Users Where Id = :id';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':id', $uid, PDO::PARAM_STR);
		$statement->execute();

		
		$result = $statement->fetchAll();
		
		if(!empty($result)){
			$name = $result[0]["Fname"];
			return $name;
		}
		else {
			return "An error occured";
		}
		
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}

function reset_user_password($uid) {
	try {
		$SQL = 'UPDATE Users SET Password = 0 WHERE Id = :id';
		$connection = create_PDO_connection();
		$statement = $connection->prepare($SQL);
		$statement->bindParam(':id', $uid, PDO::PARAM_STR);
		$statement->execute();

		return TRUE;
	} catch (PDOException $error) {
		echo $SQL . "<br>" . $error->getMessage();
		return FALSE;
	}
}
?>