<!DOCTYPE html>
<head>
<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<?php
require_once("assign_permission.php");

function request_permission(){
    ?>
    <h1>Request Permission Page</h1>
    <?php
    $current_user = wp_get_current_user()->user_login;
    $capability_set = array_keys(get_role('administrator')->capabilities);
    $current_capability = array_keys(get_role(restrictly_get_current_user_role())->capabilities);
    $diff = array_values(array_diff($capability_set,$current_capability)); //permission difference

    printCurrentPermissions($current_capability,$current_user);

    //create the seleciton form and use post method pass data to same page
    echo '<form action="" method="post">';
    echo '<div class="form-group">';
    echo '<label for="permissions">Please select the permission you want to request. </label>';
    echo "<select name='permissions' class='form-control'>";
    foreach($diff as $diff_permissions){
        echo "<option value='".$diff_permissions."'>".$diff_permissions."</option>";
    }
    echo "</select><br>";
    echo '<input class="btn btn-primary btn-lg btn-block" type="submit" value="Submit">';
    echo '</div>';
    echo '</form>';


    if(isset($_POST['permissions'])) {
        $permissions=$_POST["permissions"];
        checkTask($permissions, $current_user);
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

//check whether current user have the task with this permission
    function checkTask($permissions, $current_user){
        // print_current_user_information();
        $dbLocalhost = connect_sql();
        $dbTask = "SELECT `id`, `assigner`, `receiver`, `permission`, `task`, `time` FROM `wp_task` WHERE `permission` = '$permissions' AND `receiver` = '$current_user'";
            if ($info = mysqli_query($dbLocalhost, $dbTask)){      
                // echo '<h4>Data retrived successfully</h4>';
                echo "<h2>You are having the following task</h2>";
                
                while($taskInfo = mysqli_fetch_array($info)){
                    createNewRole($taskInfo['receiver'], $taskInfo['permission']);
                    echo '<ul class="list-group">';
                    echo '<li class="list-group-item">';
                    echo $taskInfo['id'];
                    echo '<br>';
                    echo $taskInfo['assigner'];
                    echo '<br>';
                    echo $taskInfo['receiver'];
                    echo '<br>';
                    echo $taskInfo['permission'];
                    echo '<br>';
                    echo $taskInfo['task'];
                    echo '<br>';
                    echo $taskInfo['time'];
                    echo '</li>';
                    echo '<ul>';
                }
            }
            else{
                echo mysqli_error($dbLocalhost);
            }  
    }


//greate new role with new permission added
    function createNewRole($receiver, $permission){
        echo "<h1>Create New Role</h1>";
        echo $receiver;
        echo $permission;
    }

//assign role to user.
    function switchRole(){

    }

    //判断当前页面的权限需求是否为legal的，通过判断在task table 中是否存在这一条项目

?>