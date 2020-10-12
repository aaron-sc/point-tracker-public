<?php
include "config.php";

include "Users/UsersDB.php";
include "Auth/auth.php";
include "Activities/ActivitiesDB.php";
include "Templates/html.php";
include "Points/points.php";
include "Categories/CategoriesDB.php";
include "FRCTeams/FRCTeamsDB.php";

$isADMIN = is_admin(get_uid_cookie($COOKIE_USER));

//  Change this if your domain changes (or if you're using an IP)
// $SERVER = "https://points.mrt3216.org/";
$SERVER = "http://localhost/";

//escapes html for output
function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}



?>