<?php
function assign_task(){
    ?>
    <h1>Assign task page</h1>
    <?php
    // get current user name
        $current_user = wp_get_current_user();
        $currentUser = $current_user->user_login;

    //database connection
        require_once("connect-db.php");

        // Grab current user information.
        echo "<h3>You information</h3>";
        $userInfo = "SELECT * FROM `wp_users` WHERE `user_login` = '$currentUser'";
        $row;
        if ($result = mysqli_query($dbLocalhost, $userInfo))
        {
            while ($row = mysqli_fetch_row($result))
            {
                echo "<p>Name: $row[4]</p>";
                echo "<p>User Login: $row[1]</p>";
                echo "<p>User Email: $row[5]</p>";
                echo "<p>Supervisor: $row[6]</p>";

            // check who gets supervise by the current user.
                echo "<h3>You are supervising the following users</h3>";
                $supervisorOf = "SELECT * FROM `wp_users` WHERE `supervisor`='$row[4]'";
                if ($info = mysqli_query($dbLocalhost, $supervisorOf))
                {
                    while ($array = mysqli_fetch_row($info))
                    {
                        echo "<input type='checkbox' name='full_name'>Name: $array[4]";
                        echo "<p>User login: $array[1]</p>";
                        echo "<p><nbsp><nbsp>User email: $array[5]</p>";
                        echo "<p><nbsp><nbsp>Supervisor: $array[6]</p>";
                      
                    }
                }
                else
                {
                    echo mysqli_error($dbLocalhost);
                }
            }
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