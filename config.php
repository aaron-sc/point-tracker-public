<?php


$dbhost       = "localhost";
# After you create a sql user, put the username and password here.
$dbusername   = "";
$dbpassword   = "";
$dbname     = "point_tracker";
$dbdsn        = "mysql:host=$dbhost;dbname=$dbname";
$dboptions    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
              );
$PASSWORD_SALT = PASSWORD_DEFAULT;

function create_PDO_connection(){
	global $dbdsn, $dbusername, $dbpassword, $dboptions;
	return new PDO($dbdsn, $dbusername, $dbpassword, $dboptions);
}

?>