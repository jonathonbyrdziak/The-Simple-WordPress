<?php
/*
Plugin Name: Blue Admin
Plugin URI: http://tech.lineshjose.com/introducing-blue-admin/
Description: This is a simple and clear admin design that makes your WordPress administration section more clear and relaxed.
Version: 12.06.04
Author: Linesh Jose
Author URI: http://lineshjose.com
License: GPL2
*/
	$version='12.06.04';
	
	// For admmin side only //
	function bd_admin()
	{
		wp_register_style( 'blue-admin', plugin_dir_url(__FILE__) . 'style.css.php?t=a', false, $version );
		wp_enqueue_style( 'blue-admin' );
	}
	add_action('admin_enqueue_scripts', 'bd_admin');
	add_action('login_head', 'bd_admin');
	
	// For Client side only //
	function bd_client()
	{
		wp_register_style( 'blue-admin', plugin_dir_url(__FILE__) . 'style.css.php', false, $version );
		wp_enqueue_style( 'blue-admin' );
	}
	add_action('wp_enqueue_scripts', 'bd_client');


	function footer_credit()
	{
		echo '<span id="footer-thankyou">Thank you for creating with <a href="http://redrokk.com/">Red Rokk</a>.</span>
			<span id="footer-thankyou">This site is hosted by <a href="http://www.terack.com/">Terack</a>.</span>';
	}
	add_filter('admin_footer_text', 'footer_credit'); 
	

?>