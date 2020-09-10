<html>
<link rel="stylesheet" href="styles.css">
<title> Category Breakdown </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

</html>


<?php
require "common.php";
deny_if_not_logged_in($COOKIE_USER);
echo do_navbar($isADMIN, 1);


$result = category_breakdown_user(get_user_id(get_username_cookie($COOKIE_USER)));
if ($result == FALSE) {
    echo '<div class="viewPointsHeader"> No data to show you! :( </div>';
    echo '<div class="viewPointsMessage"> If you believe this is a mistake, please contact an administrator </div>';
    die();
}
?>



<html>
<link rel="stylesheet" href="styles.css">
<br>
<div class="normalHeader"> Personal Breakdown Of Points and Activities </div>
<input type='text' id='js-txt_searchall' placeholder='Search all...' autofocus='autofocus' onfocus='this.select()'>&nbsp;
<input type='text' id='js-txt_name' placeholder='Search by category...'>

<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td> Category Name</td>
            <td> Points You've Earned In Category </td>
            <td> Activities You've Logged In Category </td>
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