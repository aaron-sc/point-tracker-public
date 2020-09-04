<?php

include "common.php";
$Id=$_GET["Id"];

if(@isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==$SERVER."globalUserActivity.php" && isset($Id)) {
    deny_if_not_admin($isADMIN);
    deny_if_not_logged_in($COOKIE_USER);
    echo do_navbar($isADMIN, 1);
    echo("<div class='normalHeader'> Detailed View For: ".get_name($Id)."</div>");

}
else {
    echo "Not a valid request!";
}

$result = category_breakdown_user($Id);
if ($result == FALSE) {
    echo '<div class="viewPointsHeader"> No data to show you! :( </div>';
    echo '<div class="viewPointsMessage"> If you believe this is a mistake, please contact an administrator </div>';
    die();
}
?>



<html>
<title> User Details </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">


<link rel="stylesheet" href="styles.css">
<br>
<input type='text' id='js-txt_name' placeholder='Search by category...'>

<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td> Category Name</td>
            <td> Points Earned In Category </td>
            <td> Activities Logged In Category </td>
        </tr>
    </thead>
    <tbody id="js-activitiestobeverified">
        <tr class='notfound'>
            <td colspan='4'>No record found</td>
        </tr>
        <?php foreach ($result as $activity) { ?>
            <tr>
                <td> <?php echo escape($activity["Name"]); ?> </td>
                <td> <?php echo escape($activity["CatPoints"]); ?> </td>
                <td> <?php echo escape($activity["CatActivities"]); ?> </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>

    // Search on name column only
    $('#js-txt_name').keyup(function() {
        // Search Text
        var search = $(this).val();

        // Hide all table tbody rows
        $('table tbody tr').hide();

        // Count total search result
        var len = $('table tbody tr:not(.notfound) td:nth-child(1):contains("' + search + '")').length;

        if (len > 0) {
            // Searching text in columns and show match row
            $('table tbody tr:not(.notfound) td:contains("' + search + '")').each(function() {
                $(this).closest('tr').show();
            });
        } else {
            $('.notfound').show();
        }

    });

    // Case-insensitive searching (Note - remove the below script for Case sensitive search )
    $.expr[":"].contains = $.expr.createPseudo(function(arg) {
        return function(elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });
</script>

</html>