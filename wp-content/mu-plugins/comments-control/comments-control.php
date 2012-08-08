<?php
/*
Plugin Name: Comments Control
Plugin URI: http://premium.wpmudev.org/project/comments-control
Description: Fine tune comment throttling
Author: S H Mohanjith (Incsub)
Version: 1.0.0
Network: true
Author URI: http://premium.wpmudev.org
WDP ID: 260
*/

/*
Copyright 2007-2011 Incsub, (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

add_filter('comment_flood_filter', 'limit_comments_flood_filter', 10, 3);
add_action('update_wpmu_options', 'update_limit_comments_allowed_ips');
add_action('wpmu_options', 'limit_comments_wpmu_options');

function limit_comments_flood_filter($flood_die, $time_lastcomment, $time_newcomment) {
    global $user_id;
    
    if (intval($user_id) > 0) {
        return false;
    } else if (trim(get_site_option('limit_comments_allowed_ips')) != '' || trim(get_site_option('limit_comments_allowed_ips')) != '') {
        $_remote_addr = isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
        $_remote_addr = preg_replace('/\./', '\.', $_remote_addr);
        if (preg_match('/'.$_remote_addr.'/i', get_site_option('limit_comments_allowed_ips')) > 0) {
            return false;
        }
        if (preg_match('/'.$_remote_addr.'/i', get_site_option('limit_comments_denied_ips')) > 0) {
            return true;
        }
    }
    
    return $flood_die;
}

function limit_comments_wpmu_options() {
    echo '<h3>' . __('Comments') . '</h3>'; 
    echo '<table class="form-table">';
    echo '<tr valign="top">';
    echo '<td colspan="2">' . __('Allowed rules apply before denied rules') . '</td>';
    echo '</tr>';
    
    echo '<tr valign="top">'; 
    echo '<th scope="row">' . __('IP whitelist') . '</th>'; 
    echo '<td>';
    
    $allowed = stripslashes(get_site_option('limit_comments_allowed_ips'));
    
    echo "<textarea name='limit_comments_allowed_ips' id='limit_comments_allowed_ips' style='width:95%;' rows='7' cols='40'>";
    echo $allowed;
    echo "</textarea>";
    echo "<br/>";
    echo __('IPs for which comments will not be throttled. One IP per line or comma separated.')."<br/>";
    echo '</td>'; 
    echo '</tr>';
    
    echo '<tr valign="top">'; 
    echo '<th scope="row">' . __('IP blacklist') . '</th>'; 
    echo '<td>';
    
    $denied = stripslashes(get_site_option('limit_comments_denied_ips'));
    
    echo "<textarea name='limit_comments_denied_ips' id='limit_comments_denied_ips' style='width:95%;' rows='7' cols='40'>";
    echo $denied;
    echo "</textarea>";
    echo "<br/>";
    echo __('IPs for which comments will denied irrespective of rate of commenting. One IP per line or comma separated.')."<br/>";
    echo '</td>'; 
    echo '</tr>';
    
    echo '</table>';
}

function update_limit_comments_allowed_ips() {
    if(isset($_POST['limit_comments_allowed_ips'])) {
	update_site_option('limit_comments_allowed_ips', $_POST['limit_comments_allowed_ips']);
    }
    if(isset($_POST['limit_comments_denied_ips'])) {
	update_site_option('limit_comments_denied_ips', $_POST['limit_comments_denied_ips']);
    }
}

if ( !function_exists( 'wdp_un_check' ) ) {
	add_action( 'admin_notices', 'wdp_un_check', 5 );
	add_action( 'network_admin_notices', 'wdp_un_check', 5 );
	function wdp_un_check() {
		if ( !class_exists( 'WPMUDEV_Update_Notifications' ) && current_user_can( 'install_plugins' ) )
			echo '<div class="error fade"><p>' . __('Please install the latest version of <a href="http://premium.wpmudev.org/project/update-notifications/" title="Download Now &raquo;">our free Update Notifications plugin</a> which helps you stay up-to-date with the most stable, secure versions of WPMU DEV themes and plugins. <a href="http://premium.wpmudev.org/wpmu-dev/update-notifications-plugin-information/">More information &raquo;</a>', 'wpmudev') . '</a></p></div>';
	}
}