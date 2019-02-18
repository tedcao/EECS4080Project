<?php

function request_permission(){
    ?>
    <h1>Request Permission Page</h1>
    <?php
    $capability_set = get_role(restrictly_get_current_user_role())->capabilities;
    $current_capability = array_keys($capability_set);

}

?>
