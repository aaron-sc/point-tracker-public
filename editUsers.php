<html>
    <title> Manage Users </title>
    <link rel="icon" type = "image/png" href = "Templates/title_bar_image.png">
</html>

<?php
include "common.php";
deny_if_not_logged_in($COOKIE_USER);
deny_if_not_admin($isADMIN);

echo do_navbar($isADMIN, 1);
$result = get_all_users();

session_start();
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable
?>



<html>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="styles.css">

<input type='text' id='js-txt_searchall' autofocus='autofocus' onfocus='this.select()' placeholder='Search all...'>&nbsp;
<input type='text' id='js-txt_name' placeholder='Search by username...'>
<br>

<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td id="theadOverider"> First Name </td>
            <td id="theadOverider"> Last Name </td>
            <td id="theadOverider"> Admin </td>
            <td id="theadOverider"> Username </td>
            <td id="theadOverider"> Delete </td>
            <td id="theadOverider"> Edit </td>
            <td id="theadOverider"> Reset </td>
            <td id="theadOverider"> Kick </td>
        </tr>
    </thead>
    <tbody>
        <tr class='notfound'>
            <td colspan='4'>No record found</td>
        </tr>
        <?php foreach ($result as $user) { ?>
            <tr>
                <td class="notext"> <?php echo escape($user["Fname"]); ?> <input type="text" class="js-firstname" value="<?php echo escape($user["Fname"]); ?>"></td>
                <td class="notext"> <?php echo escape($user["Lname"]); ?> <input type="text" class="js-lastname" value="<?php echo escape($user["Lname"]); ?>"></td>
                <td class="notext"><input type="checkbox" class="js-admin" <?php if (escape($user["Admin"])) {
                                                                                echo "checked";
                                                                            } ?>></td>
                <td class="notext"> <?php echo escape($user["Uname"]); ?> <input type="text" class="js-username" value="<?php echo escape($user["Uname"]); ?>"></td>
                <td> <button disabled class="js-delete" type="submit" id="<?php echo escape($user["Id"]); ?>"> Delete User </button> </td>
                <td> <button class="js-edit" type="submit" id="<?php echo escape($user["Id"]); ?>"> Edit User </button> </td>
                <td> <button class="js-resetpass" type="submit" id="<?php echo escape($user["Id"]); ?>"> Reset Password </button> </td>
                <td> <button class="js-changeteam" type="submit" id="<?php echo escape($user["Id"]); ?>"> Remove From Team </button> </td>

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
            var toLog = confirm("Are you sure you want to delete this user?");
            // Delete the record
            if (toLog) {
                $.ajax({
                    url: 'deleteUser.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response);
                        tr.remove();
                        location.reload()

                    }
                });
            }
        });

                // Change team
                $(".js-changeteam").click(function() {
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
            var changeTeam = confirm("Are you sure you want to change your FRC Team?");
            if (changeTeam) {
                $.ajax({
                    url: 'changeUserTeam.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response);
                        window.location.href = "index.php?message=newteam";
                    }
                });
            }
        });

        $(".js-resetpass").click(function() {
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
            var toLog = confirm("Are you sure you want to reset the password for this user?");
            if (toLog) {
                $.ajax({
                    url: 'resetUserPass.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response);
                        location.reload()
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
            var firstname = row.find(".js-firstname").val();
            var lastname = row.find(".js-lastname").val();
            var admin = row.find(".js-admin");
            var username = row.find(".js-username").val();

            if (admin.prop('checked')) {
                admin = 1;
            } else {
                admin = 0;
            }

            var request = {
                Id: Id,
                firstname: firstname,
                lastname: lastname,
                username: username,
                admin: admin,
                token: '<?php echo $token; ?>',
                is_ajax: 1
            };

            // Confirm
            var toLog = confirm("Are you sure you want to edit this user?");
            if (toLog) {
                $.ajax({
                    url: 'editUser.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response);
                        location.reload()
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
        var len = $('table tbody tr:not(.notfound) td:nth-child(4):contains("' + search + '")').length;

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