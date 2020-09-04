<?php

/**
 * Configuration for database connection
 * Add this line to import the db into all php files: require "config.php";
 */
$dbhost       = "localhost";
$dbusername   = "web";
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