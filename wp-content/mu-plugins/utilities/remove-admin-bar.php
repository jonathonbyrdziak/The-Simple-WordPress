<?php 
/**
 * @package Admin Bar Removal
 * @subpackage WordPress PlugIn
 * @since 3.1.0
 * @version 2012.0321-BUGFIX.0000-DEVELOPMENTAL
 * @author sLa
 * @license GPLv2
 *
 * Completely Disable Admin Bar Frontend, Backend, and Remove Code for minimal memory load.
 */

if (is_admin()) return;

if(!function_exists('add_action')){header('Status 403 Forbidden');header('HTTP/1.0 403 Forbidden');header('HTTP/1.1 403 Forbidden');exit();};
function wpabr_footer_log(){echo"\n<!--Plugin Admin Bar Removal 2012.0321-BUGFIX.0000-DEVELOPMENTAL Active-->";};
add_action('wp_head','wpabr_footer_log');
add_action('wp_footer','wpabr_footer_log');
function wpabr_rac(){echo'<style type="text/css">body.admin-bar #wpcontent,body.admin-bar #adminmenu{padding-top:0px}</style>';};
add_action('admin_print_styles','wpabr_rac',21);
function wpabr_ruppoabpc(){echo'<style type="text/css">.show-admin-bar{display:none}</style>';};
add_action('admin_print_styles-profile.php','wpabr_ruppoabpc');
add_filter('init','wpabr_init');
function wpabr_init(){add_filter('show_admin_bar','__return_false' );};
show_admin_bar(false);
wp_deregister_script('admin-bar');
wp_deregister_style('admin-bar');
remove_filter('wp_head','wp_admin_bar');
remove_filter('wp_footer','wp_admin_bar');
remove_filter('admin_head','wp_admin_bar');
remove_filter('admin_footer','wp_admin_bar');
remove_filter('wp_head','wp_admin_bar_class');
remove_filter('wp_footer','wp_admin_bar_class');
remove_filter('admin_head','wp_admin_bar_class');
remove_filter('admin_footer','wp_admin_bar_class');
//
//foreach(array('wp_header','wp_admin_bar_render')as$filter);add_action($filter,'wp_admin_bar_render',1000);
//foreach(array('wp_footer','wp_admin_bar_render')as$filter);add_action($filter,'wp_admin_bar_render',1000);
//
//remove_action('wp_before_admin_bar_render','wp_admin_bar_me_separator',10);
//remove_action('wp_before_admin_bar_render','wp_admin_bar_my_account_menu',20);
//remove_action('wp_before_admin_bar_render','wp_admin_bar_my_blogs_menu',30);
//remove_action('wp_before_admin_bar_render','wp_admin_bar_blog_separator',40);
//remove_action('wp_before_admin_bar_render','wp_admin_bar_bloginfo_menu',50);
//remove_action('wp_before_admin_bar_render','wp_admin_bar_edit_menu',100);
//
remove_action('wp_head','wp_admin_bar_render',1000);
remove_filter('wp_head','wp_admin_bar_render',1000);
remove_action('wp_footer','wp_admin_bar_render',1000);
remove_filter('wp_footer','wp_admin_bar_render',1000);
remove_action('admin_head','wp_admin_bar_render',1000);
remove_filter('admin_head','wp_admin_bar_render',1000);
remove_action('admin_footer','wp_admin_bar_render',1000);
remove_filter('admin_footer','wp_admin_bar_render',1000);
remove_action('init','wp_admin_bar_init');
remove_filter('init','wp_admin_bar_init');
remove_action('wp_head','wp_admin_bar_css');
remove_action('wp_head','wp_admin_bar_dev_css');
remove_action('wp_head','wp_admin_bar_rtl_css');
remove_action('wp_head','wp_admin_bar_rtl_dev_css');
remove_action('admin_head','wp_admin_bar_css');
remove_action('admin_head','wp_admin_bar_dev_css');
remove_action('admin_head','wp_admin_bar_rtl_css');
remove_action('admin_head','wp_admin_bar_rtl_dev_css');
remove_action('wp_footer','wp_admin_bar_js');
remove_action('wp_footer','wp_admin_bar_dev_js');
remove_action('admin_footer','wp_admin_bar_js');
remove_action('admin_footer','wp_admin_bar_dev_js');
remove_action('wp_ajax_adminbar_render','wp_admin_bar_ajax_render');
remove_filter('wp_ajax_adminbar_render','wp_admin_bar_ajax_render');
remove_action('personal_options','_admin_bar_pref');
remove_filter('personal_options','_admin_bar_pref');
remove_action('personal_options','_get_admin_bar_pref');
remove_filter('personal_options','_get_admin_bar_pref');
remove_filter('locale','wp_admin_bar_lang');
remove_filter('admin_footer','wp_admin_bar_render')?>
<?php
if ( !function_exists('hide_admin_bar_search') ) {
        function hide_admin_bar_search () { ?>
                <style type="text/css">
                #wpadminbar #adminbarsearch {
                        display: none;
                }
                </style>
                <?php
        }
        add_action('admin_head', 'hide_admin_bar_search');
        add_action('wp_head', 'hide_admin_bar_search');
}
add_action('wp_before_admin_bar_render','wpabnr');
function wpabnr(){global $wp_admin_bar;
$wp_admin_bar->remove_menu('get-shortlink');
$wp_admin_bar->remove_menu('dashboard');
$wp_admin_bar->remove_menu('my-account-with-avatar');
$wp_admin_bar->remove_menu('appearance');
$wp_admin_bar->remove_menu('themes');
$wp_admin_bar->remove_menu('widgets');
$wp_admin_bar->remove_menu('menus');
$wp_admin_bar->remove_menu('background');
$wp_admin_bar->remove_menu('header');
$wp_admin_bar->remove_menu('wrap');
$wp_admin_bar->remove_menu('search');
$wp_admin_bar->remove_menu('button');
$wp_admin_bar->remove_menu('adminbarsearch');
$wp_admin_bar->remove_menu('wp-logo');
$wp_admin_bar->remove_menu('wp-logo-default');
$wp_admin_bar->remove_menu('wp-logo-external');
$wp_admin_bar->remove_menu('comments');
$wp_admin_bar->remove_menu('about');
$wp_admin_bar->remove_menu('wporg');
$wp_admin_bar->remove_menu('documentation');
$wp_admin_bar->remove_menu('support-forums');
$wp_admin_bar->remove_menu('feedback');
$wp_admin_bar->remove_menu('site-name');
$wp_admin_bar->remove_menu('site-name-default');
$wp_admin_bar->remove_menu('view-site');
$wp_admin_bar->remove_menu('comments');
$wp_admin_bar->remove_menu('new-content');
$wp_admin_bar->remove_menu('new-content-default');
$wp_admin_bar->remove_menu('new-post');
$wp_admin_bar->remove_menu('new-media');
$wp_admin_bar->remove_menu('new-link');
$wp_admin_bar->remove_menu('new-page');
$wp_admin_bar->remove_menu('new-user');
$wp_admin_bar->remove_menu('updates');
$wp_admin_bar->remove_menu('top-secondary');
$wp_admin_bar->remove_menu('my-account');
$wp_admin_bar->remove_menu('user-actions');
$wp_admin_bar->remove_menu('user-info');
$wp_admin_bar->remove_menu('edit-profile');
$wp_admin_bar->remove_menu('logout');
$wp_admin_bar->remove_menu('search');
$wp_admin_bar->remove_menu('network-admin');
$wp_admin_bar->remove_menu('w3tc');
$wp_admin_bar->remove_menu('w3tc-default');
$wp_admin_bar->remove_menu('w3tc-empty-caches');
$wp_admin_bar->remove_menu('w3tc-faq');
$wp_admin_bar->remove_menu('w3tc-support');
$wp_admin_bar->remove_menu('cloudflare');
$wp_admin_bar->remove_menu('cloudflare-default');
$wp_admin_bar->remove_menu('cloudflare-my-websites');
$wp_admin_bar->remove_menu('cloudflare-analytics');
$wp_admin_bar->remove_menu('cloudflare-account');
}