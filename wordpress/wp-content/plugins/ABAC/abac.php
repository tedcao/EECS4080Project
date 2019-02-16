<?php
/*
Plugin Name: ABAC
Plugin URI: 
Description: converting RBAC to ABAC by requesting for more permissions. 
Version: 1.0
Author: Ted
Author URI: 
*/
?>

<?php
//first of first, we might need to create many roles for different users. We can not use current role system cuz add role have global influence. 

add_action('admin_menu', 'abac_setup_menu');
 
//add page to this plugin page
function abac_setup_menu(){
    add_menu_page( 'ABAC page', 'Current role information', 'read', 'ABAC_menu', 'current_role_information' );
    add_submenu_page( 'ABAC_menu', 'Request permission', 'Request Permission', 'edit_published_posts', 'Request permission', 'request_permission' );
    add_submenu_page( 'ABAC_menu', 'Assign tasks', 'Assign Tasks', 'edit_published_posts', 'Assign Tasks', 'assign_task' );
}

 
//listing all the user information who logged in
function current_role_information(){
    $current_user = wp_get_current_user();
    echo 'Username: ' . $current_user->user_login . '<br />';
    echo 'User email: ' . $current_user->user_email . '<br />';
    echo 'User first name: ' . $current_user->user_firstname . '<br />';
    echo 'User last name: ' . $current_user->user_lastname . '<br />';
    echo 'User display name: ' . $current_user->display_name . '<br />';
    echo 'User ID: ' . $current_user->ID . '<br />';

    //return capability array sampel can be found in doc:(//Role capability return sample//)
    $capability_set = get_role(restrictly_get_current_user_role())->capabilities;
    //current capability this user have
    print_r($capability_set);
    $current_capability = array_keys($capability_set);

    echo $current_capability[0];

    //need mysql to retrive the corresponding user supervisor information. 
}

//get current user's role
function restrictly_get_current_user_role() {
    if( is_user_logged_in() ) {
      $user = wp_get_current_user();
      $role = ( array ) $user->roles;
      return $role[0];
    } else {
      return false;
    }
   }

function request_permission(){
    echo "Request permission page";
}

function assign_task(){
    echo "Assign task page";
}


//get current role and capability and save it, create a new role with nre set of permissions and assign to this user for 2 h. after 2h, 
//role deleted and origional role assign back. 

?>

<?php
/*  Copyright 2019  Ted  (email : tedcao@my.yorku.ca)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>