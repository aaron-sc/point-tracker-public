<html>
<title> Review Activites </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

</html>

<?php
include "common.php";
deny_if_not_logged_in($COOKIE_USER);
deny_if_not_admin($isADMIN);
echo do_navbar($isADMIN, 1);

$result = verify_activities(get_user_id(get_username_cookie($COOKIE_USER)));
$count = count_activities_to_be_verified(get_user_id(get_username_cookie($COOKIE_USER)));
if ($count == 0) {
    echo '<div class="verifyActivityHeader"> You have no activities to approve! </div>';
    die();
}
if ($result == FALSE) {
    echo '<div class="warningHeader"> An error occured! </div>';
    die();
}

session_start();
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable

?>


<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="styles.css">

<input type='text' id='js-txt_searchall' placeholder='Search all...' autofocus='autofocus' onfocus='this.select()'>&nbsp;
<input type='text' id='js-txt_name' placeholder='Search by activity name...'>

<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td id="theadOverider"> Name </td>
            <td id="theadOverider"> Activity Name </td>
            <td id="theadOverider"> Priority </td>
            <td id="theadOverider"> Point Value </td>
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
                <td> <?php echo get_priority((int) escape($activity["Priority"])); ?> </td>
                <td> <?php echo escape($activity["ActivityPV"]); ?> </td>
                <td> <button class="js-delete" type="submit" id=<?php echo escape($activity["Id"]); ?>> Delete </button> </td>
            </tr>

        <?php } ?>
    </tbody>
</table>

<button id="js-approveall">Approve All Activities</button>

<script>
    $(document).ready(function() {
        // DELETE
        $(".js-delete").click(function() {
            // Get the tr
            var tr = $(this).parent().parent();

            // Get the ID of the button
            var Id = $(this).attr('id');

            // Confirm
            var toDelete = confirm("Are you sure you want to delete this?");

            var request = {
                Id: Id,
                token: '<?php echo $token; ?>',
                is_ajax: 1
            };

            // Delete the record
            if (toDelete) {
                $.ajax({
                    url: 'denyActivity.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        tr.remove();
                        alert(response);
                        
                    }
                });
            }
        });
        // Approve
        $("#js-approveall").click(function() {
            // Get the tr
            var Ids = [];

            $("#js-activitiestobeverified tr").each(function() {
                Ids.push(this.id);
            });

            // Confirm
            var toApprove = confirm("Are you sure you approve all?");

            var request = {
                Ids: Ids,
                token: '<?php echo $token; ?>',
                is_ajax: 1
            };

            // Delete the record
            if (toApprove) {
                $.ajax({
                    url: 'acceptActivity.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        $("#js-activitiestobeverified").html("");
                        $("#js-approveall").hide();
                        alert(response);
                        

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