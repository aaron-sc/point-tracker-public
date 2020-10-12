<?php
include "common.php";
deny_if_not_logged_in($COOKIE_USER);

echo do_navbar($isADMIN, 1);
$result = get_student_details(get_uid_cookie($COOKIE_USER));

echo '<div class="normalHeader"> Welcome ' . get_first_name(get_uid_cookie($COOKIE_USER)) . '! Here are your details!</div><br><br><br><br>';

session_start();
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable
?>



<html>

<title> My Account </title>
<link rel="icon" type="image/png" href="Templates/title_bar_image.png">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="styles.css">
<table style="width:100%" border=1 frame=void rules=all>

    <thead>
        <tr>
            <td id="theadOverider"> First Name </td>
            <td id="theadOverider"> Last Name </td>
            <td id="theadOverider"> Admin </td>
            <td id="theadOverider"> Username </td>
            <td id="theadOverider"> Edit </td>
            <td id="theadOverider"> Reset </td>
            <td id="theadOverider"> Team </td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $user) { ?>
            <tr>
                <td><input type="text" class="js-firstname" value="<?php echo escape($user["Fname"]); ?>"></td>
                <td><input type="text" class="js-lastname" value="<?php echo escape($user["Lname"]); ?>"></td>
                <td><input type="checkbox" class="js-admin" <?php if (escape($user["Admin"])) {
                                                                echo "checked";
                                                            } ?> disabled></td>
                <td><input type="text" class="js-username" value="<?php echo escape($user["Uname"]); ?>"></td>
                <td> <button class="js-edit" type="submit" id="<?php echo escape($user["Id"]); ?>"> Edit User </button> </td>
                <td> <button class="js-resetpass" type="submit" id="<?php echo escape($user["Id"]); ?>"> Reset Password </button> </td>
                <td> <button class="js-changeteam" type="submit" id="<?php echo escape($user["Id"]); ?>"> Change Team </button> </td>
            </tr>

        <?php } ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        // Reset password
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
            var reset = confirm("Are you sure you want to reset your password?");
            if (reset) {
                $.ajax({
                    url: 'resetUserPass.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response);
                        window.location.href = "index.php?message=resetpass";
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
            var edit = confirm("Are you sure you want to edit your details?");
            if (edit) {
                $.ajax({
                    url: 'editUser.php',
                    method: 'POST',
                    data: request,
                    success: function(response) {
                        alert(response)
                        window.location.href = "index.php?message=login";
                    }
                });
            }
        });
    });
</script>

</html>