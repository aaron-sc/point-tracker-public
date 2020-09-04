<html>
<title> Log Activities </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

</html>

<?php
include "common.php";
deny_if_not_logged_in($COOKIE_USER);

echo do_navbar($isADMIN, 1);
$result = get_visible_activities();
$userId = get_user_id(get_username_cookie($COOKIE_USER));

session_start();
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable
?>



<html>


<style>
    .notfound {
        display: none;
    }
</style>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="styles.css">

<input type='text' id='js-txt_searchall' placeholder='Search all...'>&nbsp;
<input type='text' id='js-txt_name' placeholder='Search by name...'>

<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td id="theadOverider"> Activity Name </td>
            <td id="theadOverider"> Activity Desciption </td>
            <td id="theadOverider"> Category </td>
            <td id="theadOverider"> Priority </td>
            <td id="theadOverider"> Point Value </td>
        </tr>
    </thead>
    <tbody>
        <tr class='notfound'>
            <td colspan='4'>No record found</td>
        </tr>
        <?php foreach ($result as $activity) { ?>
            <tr>
                <td><?php echo escape($activity["Name"]); ?></td>
                <td> <?php echo escape($activity["Description"]); ?> </td>
                <td> <?php echo escape($activity["CatName"]); ?> </td>
                <td> <?php echo get_priority((int) escape($activity["Priority"])); ?> </td>
                <td> <?php echo escape($activity["PV"]); ?> </td>
                <td> <button class="js-log" type="submit" <?php if (check_if_activity_can_only_be_logged_once($userId, escape($activity["Id"]))) {
                                                                        echo "disabled";
                                                                    } ?> id=<?php echo escape($activity["Id"]); ?>> <?php if (activity_previously_logged($userId, escape($activity["Id"]))) {
                                                                        echo "Log Again";
                                                                    } else {echo "Log Activity";} ?> </button> </td>
            </tr>

        <?php } ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $(".js-log").click(function() {
            // Get the ID of the button
            var butt = $(this);
            var Id = butt.attr('id');


            var tr = $(this).parent().parent();

            var request = {
                Id: Id,
                token: '<?php echo $token; ?>',
                is_ajax: 1
            };

            // Confirm
            var toLog = confirm("Are you sure you want to log this?");
            // log the record
            if (toLog) {
                $.ajax({
                    url: 'userActivity.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response);
                        location.reload();
                    }
                });
            }
        });

        // Search all columns
        $('#js-txt_searchall').keyup(function() {
            // Search Text
            var search = $(this).val();

            // Hide all table tbody rows
            $('table tbody tr').hide();

            // Count total search result
            var len = $('table tbody tr:not(.notfound) td:contains("' + search + '")').length;

            if (len > 0) {
                // Searching text in columns and show match row
                $('table tbody tr:not(.notfound) td:contains("' + search + '")').each(function() {
                    $(this).closest('tr').show();
                });
            } else {
                $('.notfound').show();
            }

        });

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

    });

    // Case-insensitive searching (Note - remove the below script for Case sensitive search )
    $.expr[":"].contains = $.expr.createPseudo(function(arg) {
        return function(elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });
</script>

</html>