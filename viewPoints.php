<html>
<link rel="stylesheet" href="styles.css">
<title> Activity History </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

</html>


<?php
require "common.php";
deny_if_not_logged_in($COOKIE_USER);
echo do_navbar($isADMIN, 1);



if (get_points(get_user_id(get_username_cookie($COOKIE_USER))) != "") {
    echo ('<div class="normalHeader"> You currently have ' . get_points(get_user_id(get_username_cookie($COOKIE_USER))) . ' point(s)! Activity Log: </div>');
}
$result = view_user_activities(get_user_id(get_username_cookie($COOKIE_USER)));
if ($result == FALSE) {
    echo '<div class="viewPointsHeader"> You either have no pending/accepted activities, or there is an error! </div>';
    echo '<div class="viewPointsMessage"> If you believe this is a mistake, please contact an administrator </div>';
    die();
}

session_start();
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable
?>



<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="styles.css">
<br>

<input type='text' id='js-txt_searchall' placeholder='Search all...'>&nbsp;
<input type='text' id='js-txt_name' placeholder='Search by activity name...'>

<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td> Name</td>
            <td> Activity Name </td>
            <td> Point Value </td>
            <td> Category </td>
            <td> State </td>
            <td> Un-log </td>
        </tr>
    </thead>
    <tbody id="js-activitiestobeverified">
        <tr class='notfound'>
            <td colspan='4'>No record found</td>
        </tr>
        <?php foreach ($result as $activity) { ?>
            <tr id=<?php echo escape($activity["Id"]); ?>>
                <td><?php echo (escape($activity["Fname"]) . " " . escape($activity["Lname"])); ?></td>
                <td> <?php echo escape($activity["ActivityName"]); ?> </td>
                <td> <?php echo escape($activity["ActivityPV"]); ?> </td>
                <td> <?php echo escape($activity["CatName"]); ?> </td>
                <td> <?php if (escape($activity["Approved"]) == "0") {
                            echo "Not Verified";
                        } else {
                            echo "Verified";
                        } ?>
                <td> <button class="js-cancellog" type="submit" id=<?php echo escape($activity["Id"]); ?>> Un-log </button> </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $(".js-cancellog").click(function() {
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
            var toLog = confirm("Are you sure you want to un-log activity?");
            // Delete the record
            if (toLog) {
                $.ajax({
                    url: 'cancelLog.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response);
                        tr.remove();
                        location.reload();
                        
                    }
                });
            }
        });
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
        var len = $('table tbody tr:not(.notfound) td:nth-child(2):contains("' + search + '")').length;

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