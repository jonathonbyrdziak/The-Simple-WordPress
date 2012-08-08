<?php 
function wdp_un_check(){}

// Supports featured images on posts and pages
add_theme_support('post-thumbnails');

// Supports RSS feed links
add_theme_support('automatic-feed-links');

// Support replaces the standard jquery with the google api
function activate_init() 
{
	if (is_admin()) return false;
	
	// javascript
	wp_deregister_script('jquery');
	wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', false, '1.3.2');
	wp_enqueue_script('jquery');
	
	wp_register_script('jquery.ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js', array('jquery'), '1.8.18');
	wp_enqueue_script('jquery.ui');
}

add_action('init', 'activate_init');


function is_current_user_admin()
{
	$user = new WP_User( get_current_user_id() );
	if (key($user->caps) !== 'administrator')
		return false;
	return true;
}

function remove_menu_items() {
	global $menu;
	
	$restricted = array(
		'link-manager.php',
		//'tools.php',
	);
	
	foreach ((array)$menu as $key => $item) {
		if (!isset($item[2])) continue;
		
		if ($item[2] == 'Tools') {
			$item[2] = 'Advanced';
		}
		
		if (!in_array($item[2], $restricted)) continue;
		unset($menu[$key]);
	}
}


function crop($str, $len) {
    if ( strlen($str) <= $len ) {
        return $str;
    }

    // find the longest possible match
    $pos = 0;
    foreach ( array('. ', '? ', '! ') as $punct ) {
        $npos = strpos($str, $punct);
        if ( $npos > $pos && $npos < $len ) {
            $pos = $npos;
        }
    }

    if ( !$pos ) {
        // substr $len-3, because the ellipsis adds 3 chars
        return substr($str, 0, $len-3) . '...'; 
    }
    else {
        // $pos+1 to grab punctuation mark
        return substr($str, 0, $pos+1);
    }
}

add_action('admin_menu', 'remove_menu_items');

function annointed_admin_bar_remove() {
        global $wp_admin_bar;

        /* Remove their stuff */
        $wp_admin_bar->remove_menu('wp-logo');
}

add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);

function custom_logo() {
	echo '<style type="text/css">
	#header-logo { background-image: none !important; }
	</style>';
}

add_action('admin_head', 'custom_logo');

function custom_login_logo() {
	echo '<style type="text/css">
	h1 a { background-image:none !important; }
	</style>';
}

add_action('login_head', 'custom_login_logo');

function change_contactmethods( $contactmethods ) {
	$contactmethods['twitter'] = 'Twitter'; // Add Twitter
	$contactmethods['facebook'] = 'Facebook'; // Add Facebook
	unset($contactmethods['yim']); // Remove Yahoo IM
	unset($contactmethods['aim']); // Remove AIM
	unset($contactmethods['jabber']); // Remove Jabber

	return $contactmethods;
}

add_filter('user_contactmethods','change_contactmethods',10,1);

function new_contactmethods( $contactmethods ) {
	$contactmethods['twitter'] = 'Twitter'; // Add Twitter
	$contactmethods['facebook'] = 'Facebook'; // Add Facebook
	unset($contactmethods['yim']); // Remove Yahoo IM
	unset($contactmethods['aim']); // Remove AIM
	unset($contactmethods['jabber']); // Remove Jabber

	return $contactmethods;
}

add_filter('user_contactmethods','new_contactmethods',10,1);



function remove_metaboxes() {
	remove_meta_box( 'postcustom' , 'page' , 'normal' ); //removes custom fields for page
}
add_action( 'admin_menu' , 'remove_metaboxes' );


# 2.3 to 2.7:
add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );

# 2.8 to 3.0:
remove_action( 'wp_version_check', 'wp_version_check' );
remove_action( 'admin_init', '_maybe_update_core' );
add_filter( 'pre_transient_update_core', create_function( '$a', "return null;" ) );

# 3.0:
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

function remove_dashboard_widgets(){
	global$wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

function remove_submenus() {
	global $submenu;
	//echo '<pre>';print_r($submenu);
	unset($submenu['index.php'][10]); // Removes 'Updates'.
	unset($submenu['themes.php'][5]); // Removes 'Themes'.
	unset($submenu['plugins.php'][15]); // Removes 'Editor'.
	//unset($submenu['options-general.php'][15]); // Removes 'Writing'.
	//unset($submenu['options-general.php'][25]); // Removes 'Discussion'.
	unset($submenu['options-general.php'][30]); // Removes 'Media'.
	unset($submenu['options-general.php'][35]); // Removes 'Privacy'.
	unset($submenu['options-general.php'][40]); // Removes 'Permalinks'.
	//unset($submenu['edit.php'][16]); // Removes 'Tags'.
}

add_action('admin_menu', 'remove_submenus');
function remove_editor_menu() {
	remove_action('admin_menu', '_add_themes_utility_last', 101);
}

add_action('_admin_menu', 'remove_editor_menu', 1);

function customize_meta_boxes() {
	/* Removes meta boxes from Posts */
	remove_meta_box('postcustom','post','normal');
	remove_meta_box('trackbacksdiv','post','normal');
	remove_meta_box('commentstatusdiv','post','normal');
	remove_meta_box('commentsdiv','post','normal');
	//remove_meta_box('tagsdiv-post_tag','post','normal');
	remove_meta_box('postexcerpt','post','normal');
	/* Removes meta boxes from pages */
	remove_meta_box('postcustom','page','normal');
	remove_meta_box('trackbacksdiv','page','normal');
	remove_meta_box('commentstatusdiv','page','normal');
	remove_meta_box('commentsdiv','page','normal');
}

add_action('admin_init','customize_meta_boxes');

function custom_media_columns($defaults) {
	unset($defaults['comments']);
	return $defaults;
}

add_filter('manage_media_columns', 'custom_media_columns');



function custom_post_columns($defaults) {
	unset($defaults['comments']);
	return $defaults;
}

add_filter('manage_posts_columns', 'custom_post_columns');

function custom_pages_columns($defaults) {
	unset($defaults['comments']);
	return $defaults;
}

add_filter('manage_pages_columns', 'custom_pages_columns');

function custom_favorite_actions($actions) {
	unset($actions['edit-comments.php']);
	return $actions;
}

add_filter('favorite_actions', 'custom_favorite_actions');

