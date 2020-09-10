<html>
<title> About </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">
</html>

<?php
require "common.php";
deny_if_not_logged_in($COOKIE_USER);
echo do_navbar($isADMIN, 1);
?>

<!DOCTYPE html>
<html>



<body>
    <div class="normalHeader">About</div>
    <br>
    <br>
    <br>
    <br>
    <div class="normalHeader">This project was created by Aaron Santa Cruz and Wesley Sullivan<br>It is part of the FRC Team 3216</div>
</body>


</html>