<?php
global $current_user, $selected_user, $task_description, $current_selected_permissions, $time;


function assign_task(){
    ?>
    <h1>Assign task page</h1>
    <?php
         //task information as global variables
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
        $dbLocalhost = mysqli_connect("localhost", "root", "root", "wordpress")
        or die("Could not connect: " . mysql_error());

        // check who gets supervise by the current user.
            echo "<h3>You are supervising the following users</h3>";
            $supervisorOf = "SELECT `user_login` FROM `wp_users` WHERE `supervisor`='$currentUser'";
            if ($info = mysqli_query($dbLocalhost, $supervisorOf))
            {      
                echo 'Data retrived successfully';
                $userInfo = mysqli_fetch_assoc($info);
                foreach($userInfo as $user_info){
                    echo '<ul class="list-group">';
                    echo '<li class="list-group-item">';
                    echo $user_info;
                    echo '</li>';
                    echo '<ul>';
                }
            }
            else
            {
                echo mysqli_error($dbLocalhost);
            }  
        
            echo '<form action="" method="post">';
            echo '<div class="form-group">';
            echo '<label for="user">Please select the user you want to assign task to </label>';
            echo "<select name='user' class='form-control'>";

            foreach($userInfo as $user_info) {
                echo "<option value='".$user_info."'>".$user_info."</option>";
            }
            echo '<br>';
            echo "</select><br>";
            echo '<input class="btn btn-primary btn-lg btn-block" type="submit" value="Select the person you want to assign task.">';
            echo '</div>';
            echo '</form>';

            //if exist valid input, then assign value to $user and show the list of the permissions
            if(isset($_POST['user'])) {
                $selected_user =$_POST["user"];
                $user_role = get_user_by('login', $selected_user)->roles[0]; //get curreny user role
                $capability_set = array_keys(get_role($user_role)->capabilities); //get selected user capability set
                $current_capability = array_keys(get_role(restrictly_get_current_user_role())->capabilities);
                $diff = array_values(array_diff($current_capability,$capability_set)); //permission difference between selected user and current user
                
                echo '<form action="" method="post">';
                echo '<div class="form-group">';
                echo '<label for="current_selected_permissions">Please select the permission you want to assign the person. </label>';
                echo "<select name='current_selected_permissions' class='form-control'>";
                foreach($diff as $diff_permissions){
                    echo "<option value='".$diff_permissions."'>".$diff_permissions."</option>";
                }
                echo '</select><br>';
                echo '<label class="sr-only" for="task_description">Please enter the task description.</label>';
                echo '<input type="text" class="form-control" name="task_description" placeholder="Please enter the task description."><br>';
                echo '<input class="btn btn-primary btn-lg btn-block" type="submit" value="Submit">';
                echo '</div>';
                echo '</form>';
            }

            //assign selected permission and task information
            if(isset($_POST['current_selected_permissions'],$_POST['task_description'])){
                global $current_selected_permissions, $task_description;
                $current_selected_permissions=$_POST['current_selected_permissions'];
                $task_description=$_POST['task_description'];
            }
}

//get current role and capability and save it, create a new role with new set of permissions and assign to this user for 2 h. after 2h, 
//role deleted and origional role assign back. 
?>