<?php 

/**
 * Method adds our themes stylesheet to the login page
 */
function activate_admin_init()
{
	echo '<link rel="stylesheet" id="wp-admin-custom-css" href="'
		.WPMU_PLUGIN_URL.'/utilities/css/default.css'.'" type="text/css" media="all">';
}

/**
 * Method returns the home url for the website
 */
function activate_home_url() {
	return get_bloginfo('home');
}

/**
 * Method returns the websites name
 */
function activate_websitename() {
	return get_bloginfo('name');
}
function activate_wp_admin_css( $link, $file )
{
	if (in_array($file, array('wp-admin','colors-fresh','wp-admin-rtl','colors-fresh-rtl'))) return '';
	return $link;
}
function activate_blank() {
	return '';
}

add_action('login_enqueue_scripts', 'activate_admin_init');
add_filter('login_headerurl', 'activate_home_url');
add_filter('login_headertitle', 'activate_websitename');
add_filter('style_loader_tag', 'activate_wp_admin_css', 100, 2);
add_filter('the_generator', 'activate_blank');