<!DOCTYPE html>
<head>
<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<?php
require_once("assign_permission.php");

//listing permissions current_user can request. main function
    function request_permission(){
        ?>
        <h1>Request Permission Page</h1>
        <?php
        $current_user = wp_get_current_user()->user_login;
        $capability_set = array_keys(get_role('administrator')->capabilities);
        $current_capability = array_keys(get_role(restrictly_get_current_user_role())->capabilities);
        $diff = array_values(array_diff($capability_set,$current_capability)); //permission difference

        echo '<div class="row">';
        echo '<div class="col-sm-6">';
        printCurrentPermissions($current_capability,$current_user);
        echo '</div>';
        echo '<div class="col-sm-6">'; 
        checkTask($current_user);
        echo '</div>';
        echo '</div>';

        //create the seleciton form and use post method pass data to same page
        echo '<div class="row">';
        echo '<form action="" method="post">';
        echo '<div class="form-group">';
        
        //permission
        echo '<div class="col-sm-4">';
        echo '<label for="permissions">Please select the permission you want to request. </label>';
        echo "<select name='permissions' id='permissions' class='form-control'>";
        foreach($diff as $diff_permissions){
            echo "<option value='".$diff_permissions."'>".$diff_permissions."</option>";
        }
        echo "</select><br>";
        echo '</div>';
        //assigner
        echo '<div class="col-sm-4">';
        echo '<label for="job_assigner_audit">Who assign you the task ?</label>';
        echo "<select name='job_assigner_audit' id='job_assigner_audit' class='form-control'>";
        $users = get_users( 'blog_id=1' );
        foreach ($users as $online) {
            echo "<option value='".$online->user_login."'>".$online->user_login."</option>";
        }
        echo "</select><br>";
        echo '</div>';
        //time
        echo '<div class="col-sm-4">';
        echo '<label for="request_permission_time">How long do you need the permission?</label>';
        echo '<textarea class="form-control" id="request_permission_time" name="request_permission_time"></textarea>';
        echo '</div>';
        echo '<input class="btn btn-primary btn-lg btn-block" type="submit" value="Submit">';
        echo '</div>';
        echo '</form>';
        echo '</div>';


        if(isset($_POST['permissions']) && isset($_POST['job_assigner_audit']) && isset($_POST['request_permission_time']) ) {
            $permissions=$_POST["permissions"];
            $job_assigner_audit=$_POST["job_assigner_audit"];
            $request_permission_time=$_POST["request_permission_time"];
            assignPermission($current_user, $permissions, $job_assigner_audit, $request_permission_time);
        }
    }

//list permission current role have
    function printCurrentPermissions($current_capability, $current_user){
        echo 'Hi, ' . $current_user . '<br><br> Here is the permission you currently have: ';
        echo '<ul class="list-group">';
            foreach($current_capability as $curr_permissions){
                echo '<li class="list-group-item">';
                echo $curr_permissions;
                echo '</li>'; 
            }
        echo '<ul>';
        echo '<br>';
    }

//check and print out current user tasks
    function checkTask($current_user){
        // print_current_user_information();
        $dbLocalhost = connect_sql();
        $dbTask = "SELECT `id`, `assigner`, `receiver`, `permission`, `task`, `time` FROM `wp_task` WHERE `receiver` = '$current_user'";
            if ($info = mysqli_query($dbLocalhost, $dbTask)){      
                // echo '<h4>Data retrived successfully</h4>';
                echo "<h2>You are having the following tasks.</h2>";
                while($taskInfo = mysqli_fetch_array($info)){
                    echo '<ul class="list-group">';
                    echo '<li class="list-group-item">';
                    echo 'Your task ID is:'.$taskInfo['id'];
                    echo '<br>';
                    echo 'Permission needed:'.$taskInfo['permission'];
                    echo '<br>';
                    echo 'Task detail:'.$taskInfo['task'];
                    echo '<br>';
                    echo 'Task was assigned to you on:'.$taskInfo['time'];
                    echo '</li>';
                    echo '<ul>';
                }
            }
            else{
                echo mysqli_error($dbLocalhost);
            }  
        mysqli_close($dbLocalhost);
    }


//assign permission to user
    function assignPermission($receiver, $permission, $job_assigner_audit, $request_permission_time){
        //check whether task with these permission exist
        if(task_existing($receiver, $permission)){
            echo "pass task check";
            echo "<br>";
            if(check_working($job_assigner_audit)){
                echo "pass working time check";
                echo "<br>";
                if (check_working_location()){
                    echo "pass location check";
                    echo "<br>";
                    add_permission($receiver,$permission);
                }else{
                    echo "fail location check, Please check with your task assigner";
                    echo "<br>";
                }
            }else{
                echo "fail working time check, Please check with your task assigner";
                echo "<br>";
            }
        }else{
            echo "fail task check";
            check_assigner_permission($receiver,$job_assigner_audit,$request_permission_time);

        }

        // $current_user = wp_get_current_user()->user_login;
        // $current_capability = array_keys(get_role(restrictly_get_current_user_role())->capabilities);
        // printCurrentPermissions($current_capability,$current_user);   
    }

//add permission to user
    function add_permission($receiver,$permission){
        $role = get_role($receiver);
        $current_time = time();
        $role->add_cap($permission);
       
        // $role->remove_cap($permission);  //command this line

        $dbLocalhost = connect_sql();
        $sql_task = "UPDATE `wp_task` SET `working_time`=CURRENT_TIMESTAMP WHERE `receiver` = '$receiver'AND `permission` = '$permission'";

        if ($dbLocalhost->query($sql_task) === TRUE) {
            echo "<h3>You are going to have this permission for 2 Hours</h3>";
        } else {
            echo "Error updating record: " . $dbLocalhost->error;
        }
        mysqli_close($dbLocalhost);
    }

//check whether task existing for this receiver with permission
    function task_existing($receiver, $permission){
        $dbLocalhost = connect_sql();
        $sql_task = "SELECT `id` FROM `wp_task` WHERE `receiver` = '$receiver'AND `permission` = '$permission'";
        $result = $dbLocalhost->query($sql_task);
        if ($result->num_rows > 0){
           return true;
        }else{
            return false;
        }
        mysqli_close($dbLocalhost);
    }


//check whether task assigner is working
    function check_working($job_assigner_audit){
        $online_users = online_users();
        if (in_array($job_assigner_audit, $online_users)){
            return true;
        }else{
            return false;
        }
    }

//check working location (not donw yet)
    function check_working_location(){
        return true; //always pass for now. 
    }

    function check_assigner_permission($receiver,$job_assigner_audit,$request_permission_time){
        echo "Request will be send to your IT manager.";
    }

//return online users (last log_in in 30 mins. )
    function online_users() {
        $users = get_users( 'blog_id=1' );
        $online_users = array();
        foreach ($users as $online) {
            
            $getLastLogin = (get_user_meta($online->ID, 'last_login', true));
            $lastLogin = new DateTime($getLastLogin);
            $since_start = $lastLogin->diff(new DateTime(current_time('mysql', 1)));
        
            $minutesSinceLogin = $since_start->i;
        
            // list every user and whether they logged in within the last 30 minutes
            if ($minutesSinceLogin > 15 ) {
                
            } else { 
                array_push($online_users,$online->user_login);
            }
        }
        return $online_users;
    }
    

?>