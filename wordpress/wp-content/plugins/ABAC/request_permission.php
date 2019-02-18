<!DOCTYPE html>
<head>
<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<?php
function request_permission(){
    ?>
    <h1>Request Permission Page</h1>
    <?php
    $current_user = wp_get_current_user();
    $capability_set = array_keys(get_role('administrator')->capabilities);
    $current_capability = array_keys(get_role(restrictly_get_current_user_role())->capabilities);
    $diff = array_values(array_diff($capability_set,$current_capability)); //permission difference

    //list permission current role have
    echo 'Hi, ' . $current_user->user_login . '<br><br> Here is the permission you currently have: ';
    foreach($current_capability as $curr_permissions){
        echo '<ul class="list-group">';
        echo '<li class="list-group-item">';
        echo $curr_permissions;
        echo '</li>';
        echo '<ul>';
    }
    echo '<br>';

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
        echo $permissions;
        }
    }

    //判断当前页面的权限需求是否为legal的，通过判断在task table 中是否存在这一条项目

?>