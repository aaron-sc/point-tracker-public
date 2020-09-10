
<html>
    <title> Manage Activities </title>
    <link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
</html>

<?php
include "common.php";
deny_if_not_logged_in($COOKIE_USER);
deny_if_not_admin($isADMIN);

echo do_navbar($isADMIN, 1);
$result = get_all_activities_team();

if(empty($result)) {
    echo '<div class="viewPointsHeader"> No data to show you! :( </div>';
    echo '<div class="viewPointsMessage"> If you believe this is a mistake, please contact an administrator </div>';
    die();
}

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

<input type='text' id='js-txt_searchall' placeholder='Search all...' autofocus='autofocus' onfocus='this.select()'>&nbsp;
<input type='text' id='js-txt_name' placeholder='Search by name...'>

<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td id="theadOverider"> Activity Name </td>
            <td id="theadOverider"> Activity Desciption </td>
            <td id="theadOverider"> Point Value </td>
            <td id="theadOverider"> Category </td>
            <td id="theadOverider"> Priority </td>
            <td id="theadOverider"> Visible </td>
            <td id="theadOverider"> Loggable Once </td>
            <td id="theadOverider"> Delete </td>
            <td id="theadOverider"> Edit </td>

        </tr>
    </thead>
    <tbody>
        <tr class='notfound'>
            <td colspan='4'>No record found</td>
        </tr>
        <?php foreach ($result as $activity) { ?>
            <tr>
                <td class="notext"> <?php echo escape($activity["Name"]); ?> <input type="text" class="js-name" value="<?php echo escape($activity["Name"]); ?>"></td>
                <td class="notext"> <?php echo escape($activity["Description"]); ?> <input type="text" class="js-des" value="<?php echo escape($activity["Description"]); ?>"> </td>
                <td class="notext"> <?php echo escape($activity["PV"]); ?> <input type="number" class="js-pv" value=<?php echo escape($activity["PV"]); ?>> </td>
                <td class="notext"> <?php echo get_all_categories_option(get_category_id(escape($activity["Id"]))) ?> </td>
                <td class="notext"> <?php echo priority_dropdown((escape($activity["Priority"]))) ?> </td>
                <td class="notext"> <input type="checkbox" class="js-visibility" <?php if (escape($activity["Visible"])) {
                                                                                        echo "checked";
                                                                                    } ?>> </td>
                <td class="notext"> <input type="checkbox" class="js-onelog" <?php if (escape($activity["OneLog"])) {
                                                                                    echo "checked";
                                                                                } ?>> </td>
                <td class="notext"> <button disabled class="js-delete" type="submit" id=<?php echo escape($activity["Id"]); ?>> Delete Activity </button> </td>
                <td class="notext"> <button class="js-edit" type="submit" id=<?php echo escape($activity["Id"]); ?>> Edit Activity </button> </td>
            </tr>

        <?php } ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $(".js-delete").click(function() {
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
            var toLog = confirm("Are you sure you want to delete this activity?");
            // Delete the record
            if (toLog) {
                $.ajax({
                    url: 'deleteActivity.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response);
                        tr.remove();

                    }
                });
            }
        });

        // Edit
        $(".js-edit").click(function() {
            // Get the ID of the button
            var butt = $(this);
            var Id = butt.attr('id');
            var row = $(this).closest("tr");
            var name = row.find(".js-name").val();
            var des = row.find(".js-des").val();
            var visibility = row.find(".js-visibility");
            var onelog = row.find(".js-onelog");
            var cat = row.find(".js-cat").val();
            var pri = row.find(".js-pri").val();
            var pv = row.find(".js-pv").val();

            if (visibility.prop('checked')) {
                visibility = 1;
            } else {
                visibility = 0;
            }

            if (onelog.prop('checked')) {
                onelog = 1;
            } else {
                onelog = 0;
            }

            var request = {
                Id: Id,
                name: name,
                des: des,
                pv: pv,
                cat: cat,
                pri:pri,
                visibility: visibility,
                onelog: onelog,
                token: '<?php echo $token; ?>',
                is_ajax: 1
            };

            // Confirm
            var toLog = confirm("Are you sure you want to edit this activity?");
            // Delete the record
            if (toLog) {
                $.ajax({
                    url: 'editActivity.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
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