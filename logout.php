<html>
<title> Logout </title>
<link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
</html>


<?php
include "common.php";
clear_username_cookie($COOKIE_USER);
echo do_navbar(0,0);
?>

<html>

<body>
<div class='goodHeader'>Logged Out! You can log back in with the buttons above.</div>
</body>

</html>