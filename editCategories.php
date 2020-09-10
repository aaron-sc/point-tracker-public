<?php
include "common.php";
deny_if_not_logged_in($COOKIE_USER);
deny_if_not_admin($isADMIN);

echo do_navbar($isADMIN, 1);
$result = get_all_categories_team();

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

<title> Edit Categories </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

<style>
    .notfound {
        display: none;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="styles.css">

<input type='text' id='js-txt_name' placeholder='Search by name...'>

<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td id="theadOverider"> Category Name </td>
            <td id="theadOverider"> Delete </td>
            <td id="theadOverider"> Edit </td>

        </tr>
    </thead>
    <tbody>
        <tr class='notfound'>
            <td colspan='4'>No record found</td>
        </tr>
        <?php foreach ($result as $cat) { ?>
            <tr>
                <td class="notext"> <?php echo escape($cat["Name"]); ?> <input type="text" class="js-name" value="<?php echo escape($cat["Name"]); ?>"></td>
                <td class="notext"> <button disabled class="js-delete" type="submit" id=<?php echo escape($cat["Id"]); ?>> Delete Category </button> </td>
                <td class="notext"> <button class="js-edit" type="submit" id=<?php echo escape($cat["Id"]); ?>> Edit Category </button> </td>
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
            var toLog = confirm("Are you sure you want to delete this category? WARNING: THIS WILL DELETE ALL ACTIVITIES ASSOCIATED WITH THIS CATEGORY!");
            // Delete the record
            if (toLog) {
                $.ajax({
                    url: 'deleteCategory.php',
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



            var request = {
                Id: Id,
                name: name,
                token: '<?php echo $token; ?>',
                is_ajax: 1
            };

            // Confirm
            var toLog = confirm("Are you sure you want to edit this category?");
            // Delete the record
            if (toLog) {
                $.ajax({
                    url: 'editCategory.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response);
                        

                    }
                });
            }
        });
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