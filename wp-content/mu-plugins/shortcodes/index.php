<?php 
/**
 * @Author	Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package Wordpress
 * @SubPackage Ezine Scraper
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 *  
 * Plugin Name: WYSIWYG Shortcode
 * Plugin URI: http://www.5twentystudios.com
 * Description: Upgrades the WYSIWYG editor
 * Version: 1.0.0
 * Author: 5Twenty Studios
 * Author URI: http://www.5twentystudios.com
 * 
 */

defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Initializing 
 * 
 * The directory separator is different between linux and microsoft servers.
 * Thankfully php sets the DIRECTORY_SEPARATOR constant so that we know what
 * to use.
 */
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

/**
 * Initializing 
 * 
 * The directory separator is different between linux and microsoft servers.
 * Thankfully php sets the DIRECTORY_SEPARATOR constant so that we know what
 * to use.
 */
defined("SHO_VERSION") or define("SHO_VERSION", '1.0.0');

/**
 * Initialize Localization
 * 
 * @tutorial http://codex.wordpress.org/I18n_for_WordPress_Developers
 * function call loads the localization files from the current folder
 */
//if (function_exists('load_theme_textdomain')) load_theme_textdomain('sho');

/**
 * Set Dates Default Timezone
 * 
 * The server has a timezone, mysql has a timezone, php has a timezone and wordpress 
 * it's own timezone. The following setting will synchronize the wordpress timezone
 * with the php timezone. This program uses the php timezone for publishing settings.
 */
$default_timezone = ($t=get_site_option('timezone_string', 'UTC'))?$t:'America/Los_Angeles';
if (function_exists('date_default_timezone_set')) date_default_timezone_set( $default_timezone );
if (function_exists('ini_set')) ini_set('date.timezone', get_site_option('timezone_string', 'UTC'));

/**
 * User Control Level
 * 
 * Allows the developer to hook into this system and set the access level for this plugin.
 * If the user does not have the capability to view this plguin, they may still be
 * able to view the default widget area. This will not cause problems with the script,
 * however the editing user will not be able to add or delete viewable pages to the 
 * widget.
 * 
 * @TODO need to set this to call get_option from the db
 */
defined("SHO_ACCESS_CAPABILITY") or define("SHO_ACCESS_CAPABILITY", "edit_posts" );

/**
 * Startup
 * 
 * This block of functions is only preloading a set of functions that I've prebuilt
 * and that I use throughout my websites.
 * 
 * @TODO Need to test this system while it's using the bootstrap file, currently it's being 
 * overridden by the 520 plugin
 * 
 * @copyright Proprietary Software, Copyright Byrd Incorporated. All Rights Reserved
 * @since 1.0
 */
require_once dirname(__file__).DS."bootstrap.php";
require_once dirname(__file__).DS."shortcodes.php";

/**
 * Initialize the Framework
 * 
 */
set_controller_path( dirname( __FILE__ ) );
sho_initialize();