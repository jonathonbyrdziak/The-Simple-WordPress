<?php
/*
Plugin Name: HTML Emails
Plugin URI: http://digitalize.ca
Description: Converts the default fugly plain text email notifications into fully customizable, sweet lookin' HTML emails.
Author: Mohammad Jangda
Version: 1.0
Author URI: http://digitalize.ca

Copyright 2010 Mohammad Jangda

Credits:

* Blockquote image borrowed from the amazing Wu Wei theme by Jeff Ngan (http://wordpress.org/extend/themes/wu-wei)
* Plain Text conversion script by Jon Abernathy aka Chuggnutt (http://www.chuggnutt.com/html2text.php)
* Email styling inspired by Wordpress.com Blog Subscription Notifications (http://en.support.wordpress.com/blog-subscriptions/)
* Automattic engineers and WordPress community coders continuing to motivate and inspire me and everyone else in the community

License:

GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

define( HTMLIZE_VERSION, 1.0 );
define( HTMLIZE_BASE_TEMPLATE, 'html_email.php' );
define( HTMLIZE_FOLDER, dirname( plugin_basename( __FILE__ ) ) );
define( HTMLIZE_PATH, dirname( __FILE__ ) );
define( HTMLIZE_URL, plugins_url( HTMLIZE_FOLDER ) );
define( HTMLIZE_TEMPLATE_DIR, HTMLIZE_PATH . '/templates/' );

require_once('includes/utils.php');
require_once('includes/emails.php');
require_once('includes/class.html2text.php'); 

add_action('init', 'htmlize');

// Hook into wp_mail to create alt-body version
add_action( 'phpmailer_init', 'htmlize_pre_send' );

function htmlize() {
	//Load any translation files needed:
	htmlize_localize();

	//htmlize_debug();
}

function htmlize_localize() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain('html-emails', false, $plugin_dir . '/langs/');
}

function htmlize_message( $data ) {

	extract( $data );

	if( !$parent_template ) $parent_template = HTMLIZE_BASE_TEMPLATE;

	$template = htmlize_get_template( $parent_template );
	
	ob_start();
	@include( $template );
	$message = ob_get_contents();
	ob_end_clean();
	
	return $message;
}

function htmlize_message_body( $templates, $data ) {	
	extract( $data );
	$template = htmlize_get_template( $templates );
	@include( $template );
}

function htmlize_get_template( $template_names ) {
	if( !is_array( $template_names ) ) $template_names = array( $template_names );
	$template = htmlize_locate_template( $template_names );
	return $template;
}

function htmlize_locate_template( $template_names ) {
	
	// Look through theme and child theme
	$template = locate_template( $template_names );
	
	// Look through content dir
	if( !$template )
		$template = htmlize_locate_template_in_dir( $template_names, WP_CONTENT_DIR );
	
	// Look through plugin dir
	if( !$template )
		$template = htmlize_locate_template_in_dir( $template_names, HTMLIZE_TEMPLATE_DIR );
	
	return $template;
}

function htmlize_locate_template_in_dir( $template_names, $dir ) {
	$template = '';
	
	foreach( $template_names as $template_name ) {
		if( file_exists( trailingslashit( $dir ) . $template_name ) ) {
			$template = trailingslashit( $dir ) . $template_name;
			break;
		}
	}
	
	return $template;
}

function htmlize_pre_send( $phpmailer ) {
	// Create plain text version of email if it doesn't exist
	if( $phpmailer->ContentType == 'text/html' && $phpmailer->AltBody == '') {
		$plain_text_message = htmlize_get_plain_text_message( $phpmailer->Body );
		$phpmailer->AltBody = $plain_text_message;
	}
}

?>
