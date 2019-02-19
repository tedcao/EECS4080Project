<?php
function assign_task(){
    ?>
    <h1>Assign task page</h1>
    <?php
        // Grab current user information.
        $current_user = wp_get_current_user();
        $currentUser = $current_user->user_login;
        echo "<h3>You information</h3>";
        echo 'Username: ' . $current_user->user_login . '<br />';
        echo 'User email: ' . $current_user->user_email . '<br />';
        echo 'User first name: ' . $current_user->user_firstname . '<br />';
        echo 'User last name: ' . $current_user->user_lastname . '<br />';
        echo 'User display name: ' . $current_user->display_name . '<br />';
        echo 'User ID: ' . $current_user->ID . '<br />';

        
        //database connection
        $dbLocalhost = mysqli_connect("localhost", "root", "", "wordpress")
        or die("Could not connect: " . mysql_error());

        // check who gets supervise by the current user.
            echo "<h3>You are supervising the following users</h3>";
            $supervisorOf = "SELECT `user_login` FROM `wp_users` WHERE `supervisor`='$currentUser'";
            if ($info = mysqli_query($dbLocalhost, $supervisorOf))
            {
                echo '<form action="" method="post">';
                echo '<div class="form-group">';
                echo '<label for="user">Please select the user you want to assign task to </label>';
                echo "<select name='user' class='form-control'>";

               $userInfo = mysqli_fetch_assoc($info);
                foreach($userInfo as $user_info) {
                    echo "<option value='".$user_info."'>".$user_info."</option>";
                }
                echo '<br>';
                echo "</select><br>";
                echo '<input class="btn btn-primary btn-lg btn-block" type="submit" value="Submit">';
                echo '</div>';
                echo '</form>';
            }
            else
            {
                echo mysqli_error($dbLocalhost);
            }  


        //task information



        // get current user permission and permission of user that gets assign to task has. Find the difference, and list them.
        //current user capabilities
/*      $current_capability = array_keys(get_role(restrictly_get_current_user_role())->capabilities);
        foreach($current_capability as $curr_permissions){
            echo '<ul class="list-group">';
            echo '<li class="list-group-item">';
            echo $curr_permissions;
            echo '</li>';
            echo '<ul>';
        }
        echo '<br>';
        // capabilities of user who gets assigned task


        //diff of capabilities of two user
        */




}
//get current role and capability and save it, create a new role with new set of permissions and assign to this user for 2 h. after 2h, 
//role deleted and origional role assign back. 
?>