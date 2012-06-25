<?php

/*
	Video Training Dashboard Widget
	-------------------------------
	
	Plugin Name: Video Training Dashboard Widget
	Plugin URI: http://scott.ee
	Description: A dashboard widget that provides one click access to WordPress video training. The videos are from the <a href="http://wp.tutsplus.com/tutorials/wp101-video-training-part-1-the-dashboard/">WPtuts+ WP101 tutorial series</a>.
	Author: Scott Evans
	Version: 1.0
	Author URI: http://scott.ee

*/

	/** define some constants **/
	define('WPVT_JS_URL',plugins_url('/assets/js',__FILE__));
	define('WPVT_CSS_URL',plugins_url('/assets/css',__FILE__));
	define('WPVT_IMAGES_URL',plugins_url('/assets/images',__FILE__));
	define('WPVT_PATH', dirname(__FILE__));
	define('WPVT_BASE', plugin_basename(__FILE__));
	define('WPVT_FILE', __FILE__);

	/** load language files **/
	load_plugin_textdomain( 'wpvt', false, dirname(WPVT_BASE) . '/assets/languages/' );
	
	/** load the widget for admin only **/
	if (is_admin()) { include(WPVT_PATH . '/assets/inc/wpvt-widget.php'); }
		
?>