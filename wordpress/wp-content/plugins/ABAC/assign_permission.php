<?php
global $current_user, $selected_user, $task_description, $current_selected_permissions, $current_user_name, $time;
function connect_sql(){
    $dbLocalhost = mysqli_connect("localhost", "root", "root", "wordpress")
        or die("Could not connect: " . mysql_error());
    return $dbLocalhost;
}
function print_current_user_information(){
        // Grab current user information and print out all the information.
        $current_user = wp_get_current_user();
        $currentUser = $current_user->user_login;
        echo "<h3>Your information</h3>";
        echo '<b>Username: </b>' . $current_user->user_login . '<br />';
        echo '<b>User email: </b>' . $current_user->user_email . '<br />';
        echo '<b>User first name: </b>' . $current_user->user_firstname . '<br />';
        echo '<b>User last name: </b>' . $current_user->user_lastname . '<br />';
        echo '<b>User display name: </b>' . $current_user->display_name . '<br />';
        echo '<b>User ID: </b>' . $current_user->ID . '<br />';
        return $currentUser;
}
function assign_task(){
    ?>
    <h1>Assign task page</h1>
    <?php
        //print out current user information
        $currentUser = print_current_user_information();
        //database connection
        $dbLocalhost = connect_sql();
        // check who gets supervise by the current user.
            
            $supervisorOf = "SELECT `user_login` FROM `wp_users` WHERE `supervisor`='$currentUser'";
            if ($info = mysqli_query($dbLocalhost, $supervisorOf)){      
            //    echo '<h4>Data retrived successfully</h4>';
                echo "<h3>You are supervising the following users</h3>";
                $userInfo = mysqli_fetch_assoc($info);
                foreach($userInfo as $user_info){
                    echo '<ul class="list-group">';
                    echo '<li class="list-group-item">';
                    echo $user_info;
                    echo '</li>';
                    echo '<ul>';
                }
            }
            else{
                echo mysqli_error($dbLocalhost);
            }  
        
            echo '<form name = "first" action="" method="post">';
            echo '<div class="form-group">';
            echo "<br />";
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
                // echo $selected_user;
                $user_role = get_user_by('login', $selected_user)->roles[0]; //get curreny user role
                $capability_set = array_keys(get_role($user_role)->capabilities); //get selected user capability set
                $current_capability = array_keys(get_role(restrictly_get_current_user_role())->capabilities);
                $diff = array_values(array_diff($current_capability,$capability_set)); //permission difference between selected user and current user
                
                echo '<form name="second" action="" method="post">';
                echo '<div class="form-group">';
                echo '<label for="current_selected_permissions">Please select the permission you want to assign the person. </label><br>';
                
                echo "<select name='current_user_name' class='form-control'>";
                echo "<option value='".$selected_user."'>".$selected_user."</option>";
                echo "</select><br>";
                
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
            if(isset($_POST['current_selected_permissions'],$_POST['task_description'],$_POST['current_user_name'])){
                $current_selected_permissions=$_POST['current_selected_permissions'];
                $task_description=$_POST['task_description'];
                $current_user_name = $_POST['current_user_name'];
            }
            if (!empty($current_selected_permissions) && !empty($task_description) && !empty($currentUser)){
                $taskInfo = "INSERT INTO `wp_task` VALUES ('','$currentUser','$current_user_name','$current_selected_permissions','$task_description', CURRENT_TIMESTAMP)";
                if ($result = mysqli_query($dbLocalhost, $taskInfo))
                {
                    echo "<p>Task has been assigned.</p>";
                }
                else
                {
                    echo "<p>Error, task haven't been assigned.</p>";
                    echo mysqli_error($dbLocalhost);
                }
            }            
}

            // adds the selected_permission to the selected user for 2 hours.
            function add_selected_permission()
            {
                $start_time = time();
                $current_user_name->add_cap('$current_selected_permissions');

                if((time() - $start_time) > 7200) {
                    $current_user_name->remove_cap('$current_selected_permissions');
                }
                
            }

?>