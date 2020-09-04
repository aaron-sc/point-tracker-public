<?php
include "config.php";

include "Users/UsersDB.php";
include "Auth/auth.php";
include "Activities/ActivitiesDB.php";
include "Templates/html.php";
include "Points/points.php";
include "Categories/CategoriesDB.php";

$isADMIN = is_admin(get_username_cookie($COOKIE_USER));
$SERVER = "https://points.mrt3216.org/";

//escapes html for output
function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}



?>