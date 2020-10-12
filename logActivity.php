<html>
<title> Log Activities </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

</html>

<?php
include "common.php";
deny_if_not_logged_in($COOKIE_USER);

echo do_navbar($isADMIN, 1);
$result = get_visible_activities();
$userId = get_uid_cookie($COOKIE_USER);

if(empty($result)) {
    echo '<div class="viewPointsHeader"> No data to show you! :( </div>';
    echo '<div class="viewPointsMessage"> If you believe this is a mistake, please contact an administrator </div>';
    die();
}

session_start();
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable
date_default_timezone_set(get_team_timezone(get_FRC_team_user($userId)));
?>



<html>


<style>
    .notfound {
        display: none;
    }
</style>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="jquery-ui.css">

<input type='text' autofocus='autofocus' onfocus='this.select()' id='js-txt_searchall' placeholder='Search all...'>&nbsp;
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
        <?php foreach ($result as $activity) {
            if (!check_if_activity_can_only_be_logged_once($userId, escape($activity["Id"]))) { ?>
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
                                                                                                                    } else {
                                                                                                                        echo "Log Activity";
                                                                                                                    } ?> </button> </td>
                </tr>

        <?php }
        } ?>
    </tbody>
</table>

<div id="dialog-form" title="Log Activity">
  <p class="validateTips">Please Verify Info Below</p>
 
  <form>
    <fieldset>
      <label style="color:black;" for="datelog">Date Completed:</label>
      <input type="date" name="datelog" required="required"  id="datelog" max="<?php echo date('Y-m-d') ?>" min="<?php echo date('Y-m-d', strtotime("-2 week")); ?>" value="<?php echo date('Y-m-d') ?>" class="text ui-widget-content ui-corner-all">
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>

<script>
    $(document).ready(function() {
        var dialog, form, butt, Id, tr,
        date = $( "#datelog" ),
        dateToRecord,
        allFields = $( [] ).add( date ),
        tips = $( ".validateTips" );


        dialog = $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 400,
        width: 350,
        modal: true,
        buttons: {
            Cancel: function() {
            dialog.dialog( "close" );
            },
            Submit: function() {
                logActivity();
                dialog.dialog( "close" );
            }
        },
        close: function() {
            form[ 0 ].reset();
            allFields.removeClass( "ui-state-error" );
        }
        });
    
        form = dialog.find( "form" ).on( "submit", function( event ) {
            // If submitted (enter key)
            logActivity();
        });

        function logActivity() {
            date = new Date(date.val());
            day = date.getDate()+1;
            month = date.getMonth() + 1;
            year = date.getFullYear();
            dateToRecord = [month, day, year].join('/');

            var request = {
                Id: Id,
                dateToRecord: dateToRecord,
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
        }
        

        $('#dialog').dialog({
                autoOpen: false,
                title: 'Basic Dialog'
            });
            $('#contactUs').click(function () {
                $('#dialog').dialog('open');
            });
        $(".js-log").click(function() {
            // Get the ID of the button
            dialog.dialog( "open" );
            butt = $(this);
            Id = butt.attr('id');
            tr = $(this).parent().parent();
            
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