<html>
<title> Add Category </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

</html>

<?php
include "common.php";

deny_if_not_logged_in($COOKIE_USER);


if (get_uid_cookie($COOKIE_USER)) {
    echo do_navbar($isADMIN, 1);
} else {
    //echo do_navbar($isADMIN, 0);
}

if (isset($_POST['submit'])) {
    $name = $_POST['catName'];
    if (!check_if_category_exists($name)) {
        if (add_category($name)) {
            echo "<div class='goodHeader'>Category " . "'" . $name . "' created!</div>";
        } else {
            echo "<div class='warningHeader'>ERROR! Unable to add category!</div>";
        }
    } else {
        echo "<div class='warningHeader'>Category " . "'" . $name . "' already exists!</div>";
    }
}


?>


<html>
<link rel="stylesheet" href="styles.css">

<div class="container">
    <form action=addCategory.php method="POST">

        <input type="text" id="catName" name="catName" placeholder="Category Name.." required="required" autofocus='autofocus' onfocus='this.select()'>
        <input type="submit" name="submit" value="Submit">

    </form>
</div>




</html>