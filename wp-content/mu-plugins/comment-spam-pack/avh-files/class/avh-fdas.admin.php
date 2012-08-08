<?php
class AVH_FDAS_Admin {
	/**
	 * Message management
	 *
	 */
	var $message = '';
	var $status = '';
	var $core;
	var $hooks = array ();

	/**
	 * PHP5 Constructor
	 *
	 * @return unknown_type
	 */
	function __construct () {
		// Initialize the plugin
		$this->core = & AVH_FDAS_Singleton::getInstance( 'AVH_FDAS_Core' );
		$this->_db = & AVH_FDAS_Singleton::getInstance( 'AVH_FDAS_DB' ); // This is a fix for "Report and Delete" link misbehavior.

		// Admin URL and Pagination
		$this->core->admin_base_url = $this->core->info['siteurl'] . '/wp-admin/admin.php?page=';
		if ( isset( $_GET['pagination'] ) ) {
			$this->core->actual_page = ( int ) $_GET['pagination'];
		}
		$this->installPlugin();

		// Admin menu
		add_action( 'network_admin_menu', array (&$this, 'actionAdminMenu' ) );

		// Add the ajax action
		add_action( 'wp_ajax_avh-fdas-reportcomment', array (&$this, 'actionAjaxReportComment' ) );

		// Add admin actions
		add_action( 'admin_action_blacklist', array (&$this, 'actionHandleBlacklistUrl' ) );
		add_action( 'admin_action_emailreportspammer', array (&$this, 'actionHandleEmailReportingUrl' ) );
		add_action( 'contextual_help', array( &$this, 'pluginFAQ' ), 10, 3 );

		// Add Filters
		add_filter( 'comment_row_actions', array (&$this, 'filterCommentRowActions' ), 10, 2 );
		add_filter( 'plugin_action_links_avh-first-defense-against-spam/avh-fdas.php', array (&$this, 'filterPluginActions' ), 10, 2 );

		// Register Styles and SCripts
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';

		add_action('admin_init', create_function('', 
			"
			wp_register_script('avhfdas-admin-js', '{$this->core->info['plugin_url']}js/avh-fdas.admin{$suffix}.js', array ('jquery'), '{$this->core->version}', true);
			wp_register_style('avhfdas-admin-css', '{$this->core->info['plugin_url']}css/avh-fdas.admin.css', array(), '{$this->core->version}', 'screen');
			"
		));

	}

	/**
	 * PHP4 Constructor - Intialize Admin
	 *
	 * @return
	 */
	function AVH_FDAS_Admin () {
		$this->__construct();
	}
	
	function load_styles () {
		wp_admin_css( 'css/dashboard' );
		wp_enqueue_style( 'avhfdas-admin-css' );
	}
	
	function load_scripts () {
		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );
		wp_enqueue_script( 'avhfdas-admin-js' );
	}

	/**
	 * Add the Tools and Options to the Management and Options page repectively
	 *
	 * @WordPress Action admin_menu
	 *
	 */
	function actionAdminMenu () {

		// Add menu system
		$folder = 'avh-settings';
		add_menu_page( 'AVH F.D.A.S', 'AVH F.D.A.S', 'manage_network_options', $folder, array (&$this, 'doMenuOverview' ) );
		$this->hooks['avhfdas_menu_overview'] = add_submenu_page( $folder, 'AVH First Defense Against Spam - WPMU DEV Version: ' . __( 'Overview', 'avh-fdas' ), __( 'Overview', 'avh-fdas' ), 'manage_network_options', $folder, array (&$this, 'doMenuOverview' ) );
		$this->hooks['avhfdas_menu_general'] = add_submenu_page( $folder, 'AVH First Defense Against Spam - WPMU DEV Version:' . __( 'General Options', 'avh-fdas' ), __( 'General Options', 'avh-fdas' ), 'manage_network_options', 'avh-fdas-general', array (&$this, 'doMenuGeneralOptions' ) );
		$this->hooks['avhfdas_menu_3rd_party'] = add_submenu_page( $folder, 'AVH First Defense Against Spam - WPMU DEV Version:' . __( '3rd Party Options', 'avh-fdas' ), __( '3rd Party Options', 'avh-fdas' ), 'manage_network_options', 'avh-fdas-3rd-party', array (&$this, 'doMenu3rdPartyOptions' ) );

		// Add actions for menu pages
		add_action( 'load-' . $this->hooks['avhfdas_menu_overview'], array (&$this, 'actionLoadPageHook_Overview' ) );
		add_action( 'load-' . $this->hooks['avhfdas_menu_general'], array (&$this, 'actionLoadPageHook_General' ) );
		add_action( 'load-' . $this->hooks['avhfdas_menu_3rd_party'], array (&$this, 'actionLoadPageHook_3rd_party' ) );

	}

	/**
	 * Setup everything needed for the Overview page
	 *
	 */
	function actionLoadPageHook_Overview () {
		add_meta_box( 'avhfdasBoxStats', __( 'Statistics', 'avhfdas' ), array (&$this, 'metaboxMenuOverview' ), $this->hooks['avhfdas_menu_overview'], 'normal', 'core' );

		add_filter( 'screen_layout_columns', array (&$this, 'filterScreenLayoutColumns' ), 10, 2 );

		add_action('admin_print_styles', array($this, 'load_styles'));
		add_action('admin_print_scripts', array($this, 'load_scripts'));
	}

	/**
	 * Menu Page Overview
	 *
	 * @return none
	 */
	function doMenuOverview () {
		global $screen_layout_columns;

		$hide2 = '';
		switch ( $screen_layout_columns )
		{
			case 2 :
				$width = 'width:49%;';
				break;
			default :
				/*
				$width = 'width:98%;';
				$hide2 = 'display:none;';
				*/
				$width = 'width:49%;';
				$hide2 = '';
		}

		echo '<div class="wrap avhfdas-wrap">';
		echo $this->displayIcon( 'index' );
		echo '<h2>' . __( 'AVH First Defense Against Spam - WPMU DEV Version - Overview', 'avhfdas' ) . '</h2>';
		echo '	<div id="dashboard-widgets-wrap">';
		echo '		<div id="dashboard-widgets" class="metabox-holder">';
		echo '			<div class="postbox-container" style="' . $width . '">' . "\n";
		do_meta_boxes( $this->hooks['avhfdas_menu_overview'], 'normal', '' );
		echo '			</div>';
		echo '			<div class="postbox-container" style="' . $hide2 . $width . '">' . "\n";
		do_meta_boxes( $this->hooks['avhfdas_menu_overview'], 'side', '' );
		echo '			</div>';
		echo '		</div>';
		echo '<form style="display: none" method="get" action="">';
		echo '<p>';
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
		echo '</p>';
		echo '</form>';
		echo '<br class="clear"/>';
		echo '	</div>'; //dashboard-widgets-wrap
		echo '</div>'; // wrap
	}

	/**
	 * Metabox Overview of settings
	 *
	 */
	function metaboxMenuOverview () {
		global $wpdb;

		echo '<p class="sub">';
		_e( 'Spam Statistics', 'avhfdas' );
		echo '</p>';

		echo '<div class="table">';
		echo '<table>';
		echo '<tbody>';
		echo '<tr class="first">';

		$data = $this->core->getData();
		$spam_count = $data['counters'];
		krsort( $spam_count );
		$have_spam_count_data = false;
		$output = '';
		foreach ( $spam_count as $key => $value ) {
			if ( '190001' == $key ) {
				continue;
			}
			$have_spam_count_data = true;
			$date = date_i18n( 'Y - F', mktime( 0, 0, 0, substr( $key, 4, 2 ), 1, substr( $key, 0, 4 ) ) );
			$output .= '<td class="first b">' . $value . '</td>';
			$output .= '<td class="t">' . sprintf( __( 'Spam stopped in %s', 'avhfdas' ), $date ) . '</td>';
			$output .= '<td class="b"></td>';
			$output .= '<td class="last"></td>';
			$output .= '</tr>';
		}
		if ( ! $have_spam_count_data ) {
			$output .= '<td class="first b">' . __( 'No statistics yet', 'avhfdas' ) . '</td>';
			$output .= '<td class="t"></td>';
			$output .= '<td class="b"></td>';
			$output .= '<td class="last"></td>';
			$output .= '</tr>';
		}

		echo $output;
		echo '</tbody></table></div>';
		echo '<div class="versions">';
		echo '<p>';
		if ( $this->core->options['general']['use_sfs'] || $this->core->options['general']['use_php'] ) {
			$checking_with_1 = ($this->core->options['general']['use_sfs'] ? '<span class="b">' . __( 'Stop Forum Spam', 'avhfdas' ) . '</span>' : '');

			$checking_with_and = '';
			$checking_with_2 = '';
			if ( $this->core->options['general']['use_php'] ) {
				$checking_with_and = $this->core->options['general']['use_sfs'] ? __( ' and ', 'avhfdas' ) : ' ';
				$checking_with_2 = '<span class="b">' . __( 'Project Honey Pot', 'avhfdas' ) . '</span>';
			}
			printf( __( 'Checking with %s %s %s', 'avhfdas' ), $checking_with_1, $checking_with_and, $checking_with_2 );
		}
		echo '</p></div>';
		echo '<p class="sub">';
		_e( 'IP Cache Statistics', 'avhfdas' );
		echo '</p>';
		echo '<br/>';
		echo '<div class="versions">';
		echo '<p>';
		if ( 0 == $this->core->options['general']['useipcache'] ) {
			$status = '<span class="b">' . __( 'disabled', 'avh-fdas' ) . '</span>';
			printf( __( 'IP caching is %s', 'avhfdas' ), $status );
			echo '</p></div>';
		} else {
			$status = '<span class="b">' . __( 'enabled', 'avh-fdas' ) . '</span>';
			printf( __( 'IP caching is %s', 'avhfdas' ), $status );
			echo '</p></div>';
			$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ip) from $wpdb->avhfdasipcache" ) );
			$count_clean = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ip) from $wpdb->avhfdasipcache WHERE spam=0" ) );
			$count_spam = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ip) from $wpdb->avhfdasipcache WHERE spam=1" ) );
			if ( false === $count ) {
				$count = 0;
			}
			if ( false === $count_clean ) {
				$count_clean = 0;
			}
			if ( false === $count_spam ) {
				$count_spam = 0;
			}

			$output = '';
			echo '<div class="table">';
			echo '<table>';
			echo '<tbody>';
			echo '<tr class="first">';
			$output .= '<td class="first b">' . $count . '</td>';
			$text = (1 == $count) ? __( 'IP', 'avhfdas' ) : __( 'IP\'s', 'avhfdas' );
			$output .= '<td class="t">' . sprintf( __( 'Total of %s in the cache', 'avhfdas' ), $text ) . '</td>';
			$output .= '<td class="b"></td>';
			$output .= '<td class="last"></td>';
			$output .= '</tr>';

			$output .= '<td class="first b">' . $count_clean . '</td>';
			$text = (1 == $count_clean) ? __( 'IP', 'avhfdas' ) : __( 'IP\'s', 'avhfdas' );
			$output .= '<td class="t">' . sprintf( __( 'Total of %s classified as clean', 'avhfdas' ), $text ) . '</td>';
			$output .= '<td class="b"></td>';
			$output .= '<td class="last"></td>';
			$output .= '</tr>';

			$output .= '<td class="first b">' . $count_spam . '</td>';
			$text = (1 == $count_spam) ? __( 'IP', 'avhfdas' ) : __( 'IP\'s', 'avhfdas' );
			$output .= '<td class="t">' . sprintf( __( 'Total of %s classified as spam', 'avhfdas' ), $text ) . '</td>';
			$output .= '<td class="b"></td>';
			$output .= '<td class="last"></td>';
			$output .= '</tr>';

			echo $output;
		}
		echo '</tbody></table></div>';

	}

	/**
	 * Setup the General page
	 *
	 */
	function actionLoadPageHook_General () {

		add_meta_box( 'avhfdasBoxGeneral', __( 'General', 'avhfdas' ), array (&$this, 'metaboxGeneral' ), $this->hooks['avhfdas_menu_general'], 'normal', 'core' );
		add_meta_box( 'avhfdasBoxIPCache', __( 'IP Caching', 'avhfdas' ), array (&$this, 'metaboxIPCache' ), $this->hooks['avhfdas_menu_general'], 'normal', 'core' );
		add_meta_box( 'avhfdasBoxCron', __( 'Cron', 'avhfdas' ), array (&$this, 'metaboxCron' ), $this->hooks['avhfdas_menu_general'], 'normal', 'core' );
		add_meta_box( 'avhfdasBoxBlackList', __( 'Blacklist', 'avhfdas' ), array (&$this, 'metaboxBlackList' ), $this->hooks['avhfdas_menu_general'], 'side', 'core' );
		add_meta_box( 'avhfdasBoxWhiteList', __( 'Whitelist', 'avhfdas' ), array (&$this, 'metaboxWhiteList' ), $this->hooks['avhfdas_menu_general'], 'side', 'core' );

		add_filter( 'screen_layout_columns', array (&$this, 'filterScreenLayoutColumns' ), 10, 2 );
		
		add_action('admin_print_styles', array($this, 'load_styles'));
		add_action('admin_print_scripts', array($this, 'load_scripts'));
	}

	/**
	 * Menu Page general options
	 *
	 * @return none
	 */
	function doMenuGeneralOptions () {
		global $screen_layout_columns;

		$options_general[] = array ('avhfdas[general][diewithmessage]', __( 'Show message', 'avhfdas' ), 'checkbox', 1, __( 'Show a message when the connection has been terminated.', 'avhfdas' ) );
		$options_general[] = array ('avhfdas[general][emailsecuritycheck]', __( 'Email on failed security check:', 'avhfdas' ), 'checkbox', 1, __( 'Receive an email when a comment is posted and the security check failed.', 'avhfdas' ) );

		$options_cron[] = array ('avhfdas[general][cron_nonces_email]', __( 'Email result of nonces clean up', 'avhfdas' ), 'checkbox', 1, __( 'Receive an email with the total number of nonces that are deleted. The nonces are used to secure the links found in the emails.', 'avhfdas' ) );
		$options_cron[] = array ('avhfdas[general][cron_ipcache_email]', __( 'Email result of IP cache clean up', 'avhfdas' ), 'checkbox', 1, __( 'Receive an email with the total number of IP\'s that are deleted from the IP caching system.', 'avhfdas' ) );

		$options_blacklist[] = array ('avhfdas[general][useblacklist]', __( 'Use internal blacklist', 'avhfdas' ), 'checkbox', 1, __( 'Check the internal blacklist first. If the IP is found terminate the connection, even when the Termination threshold is a negative number.', 'avhfdas' ) );
		$options_blacklist[] = array ('avhfdas[lists][blacklist]', __( 'Blacklist IP\'s:', 'avhfdas' ), 'textarea', 15, __( 'Each IP should be on a separate line<br />Ranges can be defines as well in the following two formats<br />IP to IP. i.e. 192.168.1.100-192.168.1.105<br />Network in CIDR format. i.e. 192.168.1.0/24', 'avhfdas' ), 15 );

		$options_whitelist[] = array ('avhfdas[general][usewhitelist]', __( 'Use internal whitelist', 'avhfdas' ), 'checkbox', 1, __( 'Check the internal whitelist first. If the IP is found don\t do any further checking.', 'avhfdas' ) );
		$options_whitelist[] = array ('avhfdas[lists][whitelist]', __( 'Whitelist IP\'s', 'avhfdas' ), 'textarea', 15, __( 'Each IP should be on a seperate line<br />Ranges can be defines as well in the following two formats<br />IP to IP. i.e. 192.168.1.100-192.168.1.105<br />Network in CIDR format. i.e. 192.168.1.0/24', 'avhfdas' ), 15 );

		$options_ipcache[] = array ('avhfdas[general][useipcache]', __( 'Use IP Caching', 'avhfdas' ), 'checkbox', 1, __( 'Cache the IP\'s that meet the 3rd party termination threshold and the IP\'s that are not detected by the 3rd party. The connection will be terminated if an IP is found in the cache that was perviously determined to be a spammer', 'avhfdas' ) );
		$options_ipcache[] = array ('avhfdas[ipcache][email]', __( 'Email ', 'avhfdas' ), 'checkbox', 1, __( 'Send an email when a connection is terminate based on the IP found in the cache', 'avhfdas' ) );
		$options_ipcache[] = array ('avhfdas[ipcache][daystokeep]', __( 'Days to keep in cache', 'avhfdas' ), 'text', 3, __( 'Keep the IP in cache for the selected days.', 'avhfdas' ) );

		if ( isset( $_POST['updateoptions'] ) ) {
			check_admin_referer( 'avh_fdas_generaloptions' );

			$formoptions = $_POST['avhfdas'];
			$options = $this->core->getOptions();
			$data = $this->core->getData();

			$all_data = array_merge( $options_general, $options_blacklist, $options_whitelist, $options_ipcache, $options_cron );
			foreach ( $all_data as $option ) {
				$section = substr( $option[0], strpos( $option[0], '[' ) + 1 );
				$section = substr( $section, 0, strpos( $section, '][' ) );
				$option_key = rtrim( $option[0], ']' );
				$option_key = substr( $option_key, strpos( $option_key, '][' ) + 2 );

				switch ( $section )
				{
					case 'general' :
					case 'ipcache' :
						$current_value = $options[$section][$option_key];
						break;
					case 'lists' :
						$current_value = $data[$section][$option_key];
						break;
				}
				// Every field in a form is set except unchecked checkboxes. Set an unchecked checkbox to 0.


				$newval = (isset( $formoptions[$section][$option_key] ) ? esc_attr( $formoptions[$section][$option_key] ) : 0);
				if ( $newval != $current_value ) { // Only process changed fields.
					// Sort the lists
					if ( 'blacklist' == $option_key || 'whitelist' == $option_key ) {
						$b = explode( "\r\n", $newval );
						natsort( $b );
						$newval = implode( "\r\n", $b );
						unset( $b );
					}
					switch ( $section )
					{
						case 'general' :
						case 'ipcache' :
							$options[$section][$option_key] = $newval;
							break;
						case 'lists' :
							$data[$section][$option_key] = $newval;
							break;
					}
				}
			}
			// Add or remove the Cron Job: avhfdas_clean_ipcache - defined in Public Class
			if ( $options['general']['useipcache'] ) {
				// Add Cron Job if it's not scheduled
				if ( ! wp_next_scheduled( 'avhfdas_clean_ipcache' ) ) {
					wp_schedule_single_event( time() + 86400, 'avhfdas_clean_ipcache' );
				}
			} else {
				// Remove Cron Job if it's scheduled
				if ( wp_next_scheduled( 'avhfdas_clean_ipcache' ) ) {
					wp_clear_scheduled_hook( 'avhfdas_clean_ipcache' );
				}
			}
			$this->core->saveOptions( $options );
			$this->core->saveData( $data );
			$this->message = __( 'Options saved', 'avhfdas' );
			$this->status = 'updated fade';
		}
		// Show messages if needed.
		if ( isset( $_REQUEST['m'] ) ) {
			switch ( $_REQUEST['m'] )
			{
				case AVHFDAS_REPORTED_DELETED :
					$this->status = 'updated fade';
					$this->message = sprintf( __( 'IP [%s] Reported and deleted', 'avhfdas' ), esc_attr( $_REQUEST['i'] ) );
					break;
				case AVHFDAS_ADDED_BLACKLIST :
					$this->status = 'updated fade';
					$this->message = sprintf( __( 'IP [%s] has been added to the blacklist', 'avhfdas' ), esc_attr( $_REQUEST['i'] ) );
					break;
				case AVHFDAS_REPORTED :
					$this->status = 'updated fade';
					$this->message = sprintf( __( 'IP [%s] reported.', 'avhfdas' ), esc_attr( $_REQUEST['i'] ) );
					break;
				case AVHFDAS_ERROR_INVALID_REQUEST :
					$this->status = 'error';
					$this->message = sprintf( __( 'Invalid request.', 'avhfdas' ) );
					break;
				case AVHFDAS_ERROR_NOT_REPORTED :
					$this->status = 'error';
					$this->message = sprintf( __( 'IP [%s] not reported. Probably already processed.', 'avhfdas' ), esc_attr( $_REQUEST['i'] ) );
					break;
				case AVHFDAS_ERROR_EXISTS_IN_BLACKLIST :
					$this->status = 'error';
					$this->message = sprintf( __( 'IP [%s] already exists in the blacklist.', 'avhfdas' ), esc_attr( $_REQUEST['i'] ) );
					break;
				default :
					$this->status = 'error';
					$this->message = 'Unknown message request';
			}
		}

		$this->displayMessage();

		$actual_options = array_merge( $this->core->getOptions(), $this->core->getData() );

		$hide2 = '';
		switch ( $screen_layout_columns )
		{
			case 2 :
				$width = 'width:49%;';
				break;
			default :
				//$width = 'width:98%;';
				//$hide2 = 'display:none;';
				$width = 'width:49%;';
				$hide2 = '';
		}
		$data['options_general'] = $options_general;
		$data['options_cron'] = $options_cron;
		$data['options_blacklist'] = $options_blacklist;
		$data['options_whitelist'] = $options_whitelist;
		$data['options_ipcache'] = $options_ipcache;
		$data['actual_options'] = $actual_options;

		echo '<div class="wrap avhfdas-wrap">';
		echo '<div class="wrap">';
		echo $this->displayIcon( 'options-general' );
		echo '<h2>' . __( 'General Options', 'avhfdas' ) . '</h2>';
		echo '<form name="avhfdas-generaloptions" id="avhfdas-generaloptions" method="POST" action="admin.php?page=avh-fdas-general" accept-charset="utf-8" >';
		wp_nonce_field( 'avh_fdas_generaloptions' );
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );

		echo '	<div id="dashboard-widgets-wrap">';
		echo '		<div id="dashboard-widgets" class="metabox-holder">';
		echo '			<div class="postbox-container" style="' . $width . '">' . "\n";
		do_meta_boxes( $this->hooks['avhfdas_menu_general'], 'normal', $data );
		echo '			</div>';
		echo '			<div class="postbox-container" style="' . $hide2 . $width . '">' . "\n";
		do_meta_boxes( $this->hooks['avhfdas_menu_general'], 'side', $data );
		echo '			</div>';
		echo '		</div>';

		echo '<br class="clear"/>';
		echo '	</div>'; //dashboard-widgets-wrap
		echo '</div>'; // wrap


		echo '<p class="submit"><input	class="button-primary"	type="submit" name="updateoptions" value="' . __( 'Save Changes', 'avhfdas' ) . '" /></p>';
		echo '</form>';
	}

	/**
	 * Metabox Display the General Options
	 * @param $data array The data is filled menu setup
	 * @return none
	 */
	function metaboxGeneral ( $data ) {
		echo $this->printOptions( $data['options_general'], $data['actual_options'] );
	}

	/**
	 * Metabox Display the Blacklist Options
	 * @param $data array The data is filled menu setup
	 * @return none
	 */
	function metaboxBlackList ( $data ) {
		echo $this->printOptions( $data['options_blacklist'], $data['actual_options'] );
	}

	/**
	 * Metabox Display the Whitelist Options
	 * @param $data array The data is filled menu setup
	 * @return none
	 */
	function metaboxWhiteList ( $data ) {
		echo $this->printOptions( $data['options_whitelist'], $data['actual_options'] );
	}

	/**
	 * Metabox Display the IP cache Options
	 * @param $data array The data is filled menu setup
	 * @return none
	 */
	function metaboxIPCache ( $data ) {
		echo '<p>' . __( 'To use IP caching you must enable it below and set the options. IP\'s are stored in the database so if you have a high traffic website the database can grow quickly', 'avhfdas' );
		echo $this->printOptions( $data['options_ipcache'], $data['actual_options'] );
	}

	/**
	 * Metabox Display the cron Options
	 * @param $data array The data is filled menu setup
	 * @return none
	 */
	function metaboxCron ( $data ) {
		echo '<p>' . __( 'Once a day cron jobs of this plugin run. You can select to receive an email with additional information about the jobs that ran.', 'avhfdas' );
		echo $this->printOptions( $data['options_cron'], $data['actual_options'] );
	}

	/**
	 * Setup everything needed for the 3rd party menu
	 *
	 */
	function actionLoadPageHook_3rd_party () {

		add_meta_box( 'avhfdasBoxSFS', 'Stop Forum Spam', array (&$this, 'metaboxMenu3rdParty_SFS' ), $this->hooks['avhfdas_menu_3rd_party'], 'normal', 'core' );
		add_meta_box( 'avhfdasBoxPHP', 'Project Honey Pot', array (&$this, 'metaboxMenu3rdParty_PHP' ), $this->hooks['avhfdas_menu_3rd_party'], 'side', 'core' );

		add_filter( 'screen_layout_columns', array (&$this, 'filterScreenLayoutColumns' ), 10, 2 );
		
		add_action('admin_print_styles', array($this, 'load_styles'));
		add_action('admin_print_scripts', array($this, 'load_scripts'));
	}

	/**
	 * Menu Page Third Party Options
	 *
	 * @return none
	 */
	function doMenu3rdPartyOptions () {
		global $screen_layout_columns;

		$options_sfs[] = array ('avhfdas[general][use_sfs]', __( 'Check with Stop Forum Spam', 'avhfdas' ), 'checkbox', 1, __( 'If checked, the visitor\'s IP will be checked with Stop Forum Spam', 'avhfdas' ) );
		$options_sfs[] = array ('avhfdas[sfs][whentoemail]', __( 'Email threshold', 'avhfdas' ), 'text', 3, __( 'When the frequency of the spammer in the stopforumspam database equals or exceeds this threshold an email is send.<BR />A negative number means an email will never be send.', 'avhfdas' ) );
		$options_sfs[] = array ('avhfdas[sfs][emailphp]', __( 'Email Project Honey Pot Info', 'avhfdas' ), 'checkbox', 1, __( 'Always email Project Honey Pot info when Stop Forum Spam email threshold is reached, disregarding the email threshold set for Project Honey Pot. This only works when you select to check with Project Honey Pot as well.', 'avhfdas' ) );
		$options_sfs[] = array ('avhfdas[sfs][whentodie]', __( 'Termination threshold', 'avhfdas' ), 'text', 3, __( 'When the frequency of the spammer in the stopforumspam database equals or exceeds this threshold the connection is terminated.<BR />A negative number means the connection will never be terminated.<BR /><strong>This option will always be the last one checked.</strong>', 'avhfdas' ) );
		$options_sfs[] = array ('avhfdas[sfs][sfsapikey]', __( 'API Key', 'avhfdas' ), 'text', 15, __( 'You need a Stop Forum Spam API key to report spam.', 'avhfdas' ) );
		$options_sfs[] = array ('avhfdas[sfs][error]', __( 'Email error', 'avhfdas' ), 'checkbox', 1, __( 'Receive an email when the call to Stop Forum Spam Fails', 'avhfdas' ) );

		$options_php[] = array ('avhfdas[general][use_php]', __( 'Check with Honey Pot Project', 'avhfdas' ), 'checkbox', 1, __( 'If checked, the visitor\'s IP will be checked with Honey Pot Project', 'avhfdas' ) );
		$options_php[] = array ('avhfdas[php][phpapikey]', __( 'API Key:', 'avhfdas' ), 'text', 15, __( 'You need a Project Honey Pot API key to check the Honey Pot Project database.', 'avhfdas' ) );
		$options_php[] = array ('avhfdas[php][whentoemailtype]', __( 'Email type threshold:', 'avhfdas' ), 'dropdown', '0/1/2/3/4/5/6/7', __( 'Search Engine/Suspicious/Harvester/Suspicious & Harvester/Comment Spammer/Suspicious & Comment Spammer/Harvester & Comment Spammer/Suspicious & Harvester & Comment Spammer', 'avhfdas' ), __( 'When the type of the spammer in the Project Honey Pot database equals or exceeds this threshold an email is send.<BR />Both the type threshold and the score threshold have to be reached in order to receive an email.', 'avhfdas' ) );
		$options_php[] = array ('avhfdas[php][whentoemail]', __( 'Email score threshold', 'avhfdas' ), 'text', 3, __( 'When the score of the spammer in the Project Honey Pot database equals or exceeds this threshold an email is send.<BR />A negative number means an email will never be send.', 'avhfdas' ) );
		$options_php[] = array ('avhfdas[php][whentodietype]', __( 'Termination type threshold', 'avhfdas' ), 'dropdown', '-1/0/1/2/3/4/5/6/7', __( 'Never/Search Engine/Suspicious/Harvester/Suspicious & Harvester/Comment Spammer/Suspicious & Comment Spammer/Harvester & Comment Spammer/Suspicious & Harvester & Comment Spammer', 'avhfdas' ), __( 'When the type of the spammer in the Project Honey Pot database equals or exceeds this threshold an email is send.<br />Both the type threshold and the score threshold have to be reached in order to termnate the connection. ', 'avhfdas' ) );
		$options_php[] = array ('avhfdas[php][whentodie]', __( 'Termination score threshold', 'avhfdas' ), 'text', 3, __( 'When the score of the spammer in the Project Honey Pot database equals or exceeds this threshold the connection is terminated.<BR />A negative number means the connection will never be terminated.<BR /><strong>This option will always be the last one checked.</strong>', 'avhfdas' ) );
		$options_php[] = array ('avhfdas[php][usehoneypot]', __( 'Use Honey Pot', 'avhfdas' ), 'checkbox', 1, __( 'If you have set up a Honey Pot you can select to have the text below to be added to the message when terminating the connection.<BR />You have to select <em>Show Message</em> in the General Options for this to work.', 'avhfdas' ) );
		$options_php[] = array ('avhfdas[php][honeypoturl]', __( 'Honey Pot URL', 'avhfdas' ), 'text', 30, __( 'The link to the Honey Pot as suggested by Project Honey Pot.', 'avhfdas' ) );

		if ( isset( $_POST['updateoptions'] ) ) {
			check_admin_referer( 'avh_fdas_options' );

			$formoptions = $_POST['avhfdas'];
			$options = $this->core->getOptions();

			$all_data = array_merge( $options_sfs, $options_php );
			foreach ( $all_data as $option ) {
				$section = substr( $option[0], strpos( $option[0], '[' ) + 1 );
				$section = substr( $section, 0, strpos( $section, '][' ) );
				$option_key = rtrim( $option[0], ']' );
				$option_key = substr( $option_key, strpos( $option_key, '][' ) + 2 );

				$current_value = $options[$section][$option_key];
				// Every field in a form is set except unchecked checkboxes. Set an unchecked checkbox to 0.


				$newval = (isset( $formoptions[$section][$option_key] ) ? $formoptions[$section][$option_key] : 0);
				if ( 'sfs' == $section && ('whentoemail' == $option_key || 'whentodie' == $option_key) ) {
					$newval = ( int ) $newval;
				}

				if ( 'php' == $section && ('whentoemail' == $option_key || 'whentodie' == $option_key) ) {
					$newval = ( int ) $newval;
				}

				if ( $newval != $current_value ) { // Only process changed fields
					$options[$section][$option_key] = $newval;
				}

			}
			$note = '';
			if ( empty( $options['php']['phpapikey'] ) ) {
				$options['general']['use_php'] = 0;
				$note = '<br \><br \>' . __( 'You can not use Project Honey Pot without an API key. Use of Project Honey Pot has been disabled', 'avhfdas' );
			}
			$this->core->saveOptions( $options );
			$this->message = __( 'Options saved', 'avhfdas' );
			$this->message .= $note;
			$this->status = 'updated fade';
			$this->displayMessage();
		}

		$actual_options = array_merge( $this->core->getOptions(), $this->core->getData() );

		$hide2 = '';
		switch ( $screen_layout_columns )
		{
			case 2 :
				$width = 'width:49%;';
				break;
			default :
				/*
				$width = 'width:98%;';
				$hide2 = 'display:none;';
				*/
				$width = 'width:49%;';
				$hide2 = '';
		}
		$data['options_sfs'] = $options_sfs;
		$data['options_php'] = $options_php;
		$data['actual_options'] = $actual_options;

		echo '<div class="wrap avhfdas-wrap">';
		echo '<div class="wrap">';
		echo $this->displayIcon( 'options-general' );
		echo '<h2>' . __( '3rd Party Options', 'avhfdas' ) . '</h2>';
		echo '<form name="avhfdas-options" id="avhfdas-options" method="POST" action="admin.php?page=avh-fdas-3rd-party" accept-charset="utf-8" >';
		wp_nonce_field( 'avh_fdas_options' );
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );

		echo '	<div id="dashboard-widgets-wrap">';
		echo '		<div id="dashboard-widgets" class="metabox-holder">';
		echo '			<div class="postbox-container" style="' . $width . '">' . "\n";
		do_meta_boxes( $this->hooks['avhfdas_menu_3rd_party'], 'normal', $data );
		echo '			</div>';
		echo '			<div class="postbox-container" style="' . $hide2 . $width . '">' . "\n";
		do_meta_boxes( $this->hooks['avhfdas_menu_3rd_party'], 'side', $data );
		echo '			</div>';
		echo '		</div>';

		echo '<br class="clear"/>';
		echo '	</div>'; //dashboard-widgets-wrap
		echo '</div>'; // wrap


		echo '<p class="submit"><input class="button-primary" type="submit" name="updateoptions" value="' . __( 'Save Changes', 'avhfdas' ) . '" /></p>';
		echo '</form>';
	}

	/**
	 * Metabox Display the 3rd Party Stop Forum Spam Options
	 * @param $data array The data is filled menu setup
	 * @return none
	 */

	function metaboxMenu3rdParty_SFS ( $data ) {
		echo '<p>' . __( 'To check a visitor at Stop Forum Spam you must enable it below. Set the options to your own liking.', 'avhfdas' );
		echo $this->printOptions( $data['options_sfs'], $data['actual_options'] );
	}

	/**
	 * Metabox Display the 3rd Party Project Honey Pot Options
	 * @param $data array The data is filled menu setup
	 * @return none
	 */
	function metaboxMenu3rdParty_PHP ( $data )
	{
		echo '<p>' . __( 'To check a visitor at Project Honey Pot you must enable it below, you must also have an API key. You can get an API key by signing up for free at the <a href="http://www.projecthoneypot.org/create_account.php" target="_blank">Honey Pot Project</a>. Set the options to your own liking.', 'avhfdas' );
		echo $this->printOptions( $data['options_php'], $data['actual_options'] );

	}

	/**
	 * Metabox Display the FAQ
	 * @return none
	 */
	function pluginFAQ ( $contextual_help, $screen_id, $screen ) {

		if ( in_array( $screen_id, $this->hooks ) ) {
			$contextual_help = __( '
<p><b>Why is there an IP caching system?</b></p>
<p>Stop Forum spam has set a limit on the amount of API calls you can make a day, currently it is set at 5000 calls a day.<br>
This means that if you don\'t use the Blacklist and/or Whitelist you are limited to 5000 visits/day on your site.</p>
<p>The following IP\'s are cached locally:</p>
<ul>
	<li>Every IP identified as spam and triggering the terminate-the-connection threshold.</li>
	<li>Every clean IP.</li>
</ul>
<p>Only returning IP\'s that were previously identified as spammer and who\'s connection was terminated will update their last seen date in the caching system.<br>
Every day, once a day, a routine runs to remove the IP\'s who\'s last seen date is X amount of days older than the date the routine runs. You can set the days in the adminstration section of the plugin.<br>You can check the statistics to see how many IP\'s are in the database. If you have a busy site, with a lot of unique visitors, you might have to play with the "Days to keep in cache" setting to keep the size under control.</p>

<p><b>In what order is an IP checked and what action is taken?</b></p>
<p>The plugin checks the visiting IP in the following order, only if that feature is enabled of course.</p>
<ul>
	<li>Whitelist - If found skip the rest of the checks.</li>
	<li>Blacklist - If found terminate the connection.</li>
	<li>IP Caching - If found and spam terminate connection, if found and clean skip the rest of the checks.</li>
	<li>3rd Parties - If found determine action based on result.</li>
</ul>

<p><b>How do I define a range in the blacklist or white list?</b></p>
<p>You can define two sorts of ranges:</p>
<ul>
	<li>From IP to IP. i.e. 192.168.1.100-192.168.1.105</li>
	<li>A network in CIDR format. i.e. 192.168.1.0/24</li>
</ul>

<p><b>How do I report a spammer to Stop Forum Spam?</b></p>
<p>You need to have an API key from Stop Forum Spam. If you do on the Edit Comments pages there is an extra option called, Report &amp; Delete, in the messages identified as spam.</p>

<p><b>How do I get a Stop Forum Spam API key?</b></p>
<p>You will have to sign up on their site, <a href="http://www.stopforumspam.com/signup" target="_blank">http://www.stopforumspam.com/signup</a>.</p>

<p><b>How do I get a Project Honey Pot API key?</b></p>
<p>You will have to sign up on their site, <a href="http://www.projecthoneypot.org/create_account.php" target="_blank">http://www.projecthoneypot.org/create_account.php</a>.</p>

<p><b>What are some score examples for Project Honey Pot?</b></p>
<p>The Threat Rating is a logarithmic score -- much like the Richter\'s scale for measuring earthquakes.A Threat Rating of 25 can be interpreted as the equivalent of sending 100 spam messages to a honey pot trap.</p>
<table class="widefat">
	<tbody>
		<tr>
			<th>Threat Rating</th>
			<th>IP that is as threatening as one that has sent</th>
		</tr>
		<tr>
			<td>25</td>
			<td>100 spam messages</td>
		</tr>
		<tr>
			<td>50</td>
			<td>10,000 spam messages</td>
		</tr>
		<tr>
			<td>75</td>
			<td>1,000,000 spam messages</td>
		</tr>
	</tbody>
</table>', 'avhfdas' );
		}

		return $contextual_help;
	}

	/**
	 * Sets the amount of columns wanted for a particuler screen
	 *
	 * @WordPress filter screen_meta_screen
	 * @param $screen
	 * @return strings
	 */

	function filterScreenLayoutColumns ( $columns, $screen ) {
		die(var_export("HLARGH"));
		switch ( $screen )
		{
			case $this->hooks['avhfdas_menu_overview'] :
				$columns[$this->hooks['avhfdas_menu_overview']] = 2;
				break;
			case $this->hooks['avhfdas_menu_general'] :
				$columns[$this->hooks['avhfdas_menu_general']] = 2;
				break;
			case $this->hooks['avhfdas_menu_3rd_party'] :
				$columns[$this->hooks['avhfdas_menu_3rd_party']] = 2;
				break;

		}
		return $columns;

	}

	/**
	 * Adds Settings next to the plugin actions
	 *
	 * @WordPress Filter plugin_action_links_avh-first-defense-against-spam/avh-fdas.php
	 * @param array $links
	 * @return array
	 *
	 * @since 1.0
	 */
	function filterPluginActions ( $links ) {
		$folder = plugin_basename( $this->core->info['plugin_dir'] );
		$settings_link = '<a href="admin.php?page=' . $folder . '">' . __( 'Settings', 'avhfdas' ) . '</a>';
		array_unshift( $links, $settings_link ); // before other links
		return $links;
	}

	/**
	 * Adds an extra option on the comment row
	 *
	 * @WordPress Filter comment_row_actions
	 * @param array $actions
	 * @param class $comment
	 * @return array
	 * @since 1.0
	 */
	function filterCommentRowActions ( $actions, $comment ) {
		if ( (! empty( $this->core->options['sfs']['sfsapikey'] )) && isset( $comment->comment_approved ) && 'spam' == $comment->comment_approved ) {
			$report_url = clean_url( wp_nonce_url( "admin.php?avhfdas_ajax_action=avh-fdas-reportcomment&id=$comment->comment_ID", "report-comment_$comment->comment_ID" ) );
			$actions['report'] = '<a class=\'delete:the-comment-list:comment-' . $comment->comment_ID . ':e7e7d3:action=avh-fdas-reportcomment vim-d vim-destructive\' href="' . $report_url . '">' . __( 'Report & Delete', 'avhfdas' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Checks if the user clicked on the Report & Delete link.
	 *
	 * @WordPress Action wp_ajax_avh-fdas-reportcomment
	 *
	 */
	function actionAjaxReportComment () {
		global $wpdb;

		if ( 'avh-fdas-reportcomment' == $_POST['action'] ) {
			$comment_id = absint( $_REQUEST['id'] );
			check_ajax_referer( 'report-comment_' . $comment_id );
			if ( ! $comment = get_comment( $comment_id ) ) {
				$this->comment_footer_die( __( 'Oops, no comment with this ID.', 'avhfdas' ) . sprintf( ' <a href="%s">' . __( 'Go back', 'avhfdas' ) . '</a>!', 'edit-comments.php' ) );
			}
			if ( ! current_user_can( 'edit_post', $comment->comment_post_ID ) ) {
				$this->comment_footer_die( __( 'You are not allowed to edit comments on this post.', 'avhfdas' ) );
			}
			$options = $this->core->getOptions();
			// If we use IP Cache and the Reported IP isn't spam, delete it from the IP cache.
			if ( 1 == $options['general']['useipcache'] ) {
				$ip_info = $this->_db->getIP( $comment->comment_author_IP );
				if ( is_object( $ip_info ) && 0 == $ip_info->spam ) {
					$result = $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->avhfdasipcache WHERE ip=INET_ATON(%s)", $comment->comment_author_IP ) );
				}
			}
			$this->handleReportSpammer( $comment->comment_author, $comment->comment_author_email, $comment->comment_author_IP );
			// Delete the comment
			$r = wp_delete_comment( $comment->comment_ID );
			die( $r ? '1' : '0' );
		}
	}

	/**
	 * Handles the admin_action emailreportspammer call.
	 *
	 * @WordPress Action admin_action_emailreportspammer
	 * @since 1.2
	 *
	 */
	function actionHandleEmailReportingUrl() {
		if ( ! (isset( $_REQUEST['action'] ) && 'emailreportspammer' == $_REQUEST['action']) ) {
			return;
		}
		$a = wp_specialchars( $_REQUEST['a'] );
		$e = wp_specialchars( $_REQUEST['e'] );
		$i = wp_specialchars( $_REQUEST['i'] );
		$extra = '&m=' . AVHFDAS_ERROR_INVALID_REQUEST . '&i=' . $i;
		if ( $this->core->avh_verify_nonce( $_REQUEST['_avhnonce'], $a . $e . $i ) ) {
			$all = get_site_option( $this->core->db_options_nonces );
			$extra = '&m=' . AVHFDAS_ERROR_NOT_REPORTED . '&i=' . $i;
			if ( isset( $all[$_REQUEST['_avhnonce']] ) ) {
				$this->handleReportSpammer( $a, $e, $i );
				unset( $all[$_REQUEST['_avhnonce']] );
				update_site_option( $this->core->db_nonce, $all );
				$extra = '&m=' . AVHFDAS_REPORTED . '&i=' . $i;
			}
			unset( $all );
		}
		wp_redirect( admin_url( 'admin.php?page=avh-fdas-general' . $extra ) );
	}

	/**
	 * Do the HTTP call to and report the spammer
	 *
	 * @param unknown_type $username
	 * @param unknown_type $email
	 * @param unknown_type $ip_addr
	 */
	function handleReportSpammer ( $username, $email, $ip_addr ) {
		$email = empty( $email ) ? 'meseaffibia@gmail.com' : $email;
		$url = 'http://www.stopforumspam.com/post.php';
		wp_remote_post( $url, array ('body' => array ('username' => $username, 'ip_addr' => $ip_addr, 'email' => $email, 'api_key' => $this->core->options['sfs']['sfsapikey'] ) ) );
	}

	/**
	 * Handles the admin_action_blacklist call
	 *
	 * @WordPress Action admin_action_blacklist
	 *
	 */
	function actionHandleBlacklistUrl () {
		if ( ! (isset( $_REQUEST['action'] ) && 'blacklist' == $_REQUEST['action']) ) {
			return;
		}
		$ip = $_REQUEST['i'];

		if ( $this->core->avh_verify_nonce( $_REQUEST['_avhnonce'], $ip ) ) {
			$blacklist = $this->core->data['lists']['blacklist'];
			if ( ! empty( $blacklist ) ) {
				$b = explode( "\r\n", $blacklist );
			} else {
				$b = array ();
			}
			if ( ! (in_array( $ip, $b )) ) {
				array_push( $b, $ip );
				$this->setBlacklistOption( $b );
				wp_redirect( admin_url( 'admin.php?page=avh-fdas-general&m=' . AVHFDAS_ADDED_BLACKLIST . '&i=' . $ip ) );
			} else {
				wp_redirect( admin_url( 'admin.php?page=avh-fdas-general&m=' . AVHFDAS_ERROR_EXISTS_IN_BLACKLIST . '&i=' . $ip ) );
			}
		} else {
			wp_redirect( admin_url( 'admin.php?page=avh-fdas-general&m=' . AVHFDAS_ERROR_INVALID_REQUEST ) );
		}
	}

	/**
	 * Update the blacklist in the proper format
	 *
	 * @param array $b
	 */
	function setBlacklistOption ( $b ) {
		$data = $this->core->getData();
		natsort( $b );
		$x = implode( "\r\n", $b );
		$data['lists']['blacklist'] = $x;
		$this->core->saveData( $data );
	}

	/**
	 * Update the whitelist in the proper format
	 *
	 * @param array $b
	 */
	function setWhitelistOption ( $b ) {
		$data = $this->core->getData();
		natsort( $b );
		$x = implode( "\r\n", $b );
		$data['lists']['whitelist'] = $x;
		$this->core->saveData( $data );
	}

	/**
	 * Called on activation of the plugin.
	 *
	 */
	function installPlugin () {

		global $wpdb;

		if ( $wpdb->get_var( 'show tables like \'' . $wpdb->avhfdasipcache . '\'' ) != $wpdb->avhfdasipcache ) {

			require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

			// Add Cron Job, the action is added in the Public class.
			if ( ! wp_next_scheduled( 'avhfdas_clean_nonce' ) ) {
				wp_schedule_single_event( time() + 86400, 'avhfdas_clean_nonce' );
			}

			// Load up variables
			$this->core->loadOptions(); // Options will be created if not in DB
			$this->core->loadData(); // Data will be created if not in DB


			// Setup nonces db in options
			if ( ! (get_site_option( $this->core->db_options_nonces )) ) {
				get_site_option( $this->core->db_options_nonces, $this->core->default_nonces );
				wp_cache_flush(); // Delete cache
			}

			// Setup the DB Tables
			$charset_collate = '';

			if ( version_compare( mysql_get_server_info(), '4.1.0', '>=' ) ) {
				if ( ! empty( $wpdb->charset ) )
					$charset_collate = 'DEFAULT CHARACTER SET ' . $wpdb->charset;
				if ( ! empty( $wpdb->collate ) )
					$charset_collate .= ' COLLATE ' . $wpdb->collate;
			}

			$sql = 'CREATE TABLE `' . $wpdb->avhfdasipcache . '` (
  					`ip` int(10) unsigned NOT NULL,
  					`added` datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\',
  					`lastseen` datetime NOT NULL DEFAULT \'0000-00-00 00:00:00\',
  					`spam` tinyint(1) NOT NULL,
  					PRIMARY KEY (`ip`),
  					KEY `added` (`added`),
  					KEY `lastseen` (`lastseen`)
					) ' . $charset_collate . ';';

			$result = $wpdb->query( $sql );
		}

	}

	/**
	 * Update an option value  -- note that this will NOT save the options.
	 *
	 * @param array $optkeys
	 * @param string $optval
	 */
	function setOption ( $optkeys, $optval ) {
		$key1 = $optkeys[0];
		$key2 = $optkeys[1];
		$this->core->options[$key1][$key2] = $optval;
	}

	/**
	 * Delete all options from DB.
	 *
	 */
	function deleteAllOptions () {
		delete_site_option( $this->core->db_options_core, $this->core->default_options );
		wp_cache_flush(); // Delete cache
	}

	############## Admin WP Helper ##############

	/**
	 * Display WP alert
	 *
	 */
	function displayMessage () {
		if ( $this->message != '' ) {
			$message = $this->message;
			$status = $this->status;
			$this->message = $this->status = ''; // Reset
		}
		if ( !empty( $message ) ) {
			$status = ($status != '') ? $status : 'updated fade';
			echo '<div id="message"	class="' . $status . '">';
			echo '<p><strong>' . $message . '</strong></p></div>';
		}
	}

	/**
	 * Displays the icon needed. Using this instead of core in case we ever want to show our own icons
	 * @param $icon strings
	 * @return string
	 */
	function displayIcon ( $icon ) {
		return ('<div class="icon32" id="icon-' . $icon . '"><br/></div>');
	}

	/**
	 * Ouput formatted options
	 *
	 * @param array $option_data
	 * @return string
	 */
	function printOptions ( $option_data, $option_actual ) {
		// Generate output
		$output = '';
		$output .= "\n" . '<table class="form-table avhfdas-options">' . "\n";
		foreach ( $option_data as $option ) {
			$section = substr( $option[0], strpos( $option[0], '[' ) + 1 );
			$section = substr( $section, 0, strpos( $section, '][' ) );
			$option_key = rtrim( $option[0], ']' );
			$option_key = substr( $option_key, strpos( $option_key, '][' ) + 2 );
			// Helper
			if ( $option[2] == 'helper' ) {
				$output .= '<tr style="vertical-align: top;"><td class="helper" colspan="2">' . $option[4] . '</td></tr>' . "\n";
				continue;
			}
			switch ( $option[2] )
			{
				case 'checkbox' :
					$input_type = '<input type="checkbox" id="' . $option[0] . '" name="' . $option[0] . '" value="' . esc_attr( $option[3] ) . '" ' . $this->isChecked( '1', $option_actual[$section][$option_key] ) . ' />' . "\n";
					$explanation = $option[4];
					break;
				case 'dropdown' :
					$selvalue = explode( '/', $option[3] );
					$seltext = explode( '/', $option[4] );
					$seldata = '';
					foreach ( ( array ) $selvalue as $key => $sel ) {
						$seldata .= '<option value="' . $sel . '" ' . (($option_actual[$section][$option_key] == $sel) ? 'selected="selected"' : '') . ' >' . ucfirst( $seltext[$key] ) . '</option>' . "\n";
					}
					$input_type = '<select id="' . $option[0] . '" name="' . $option[0] . '">' . $seldata . '</select>' . "\n";
					$explanation = $option[5];
					break;
				case 'text-color' :
					$input_type = '<input type="text" ' . (($option[3] > 50) ? ' style="width: 95%" ' : '') . 'id="' . $option[0] . '" name="' . $option[0] . '" value="' . esc_attr( stripcslashes( $option_actual[$section][$option_key] ) ) . '" size="' . $option[3] . '" /><div class="box_color ' . $option[0] . '"></div>' . "\n";
					$explanation = $option[4];
					break;
				case 'textarea' :
					$input_type = '<textarea rows="' . $option[5] . '" ' . (($option[3] > 50) ? ' style="width: 95%" ' : '') . 'id="' . $option[0] . '" name="' . $option[0] . '" size="' . $option[3] . '" />' . esc_attr( stripcslashes( $option_actual[$section][$option_key] ) ) . '</textarea>';
					$explanation = $option[4];
					break;
				case 'text' :
				default :
					$input_type = '<input type="text" ' . (($option[3] > 50) ? ' style="width: 95%" ' : '') . 'id="' . $option[0] . '" name="' . $option[0] . '" value="' . esc_attr( stripcslashes( $option_actual[$section][$option_key] ) ) . '" size="' . $option[3] . '" />' . "\n";
					$explanation = $option[4];
					break;
			}
			// Additional Information
			$extra = '';
			if ( $explanation ) {
				$extra = '<br /><span class="description">' . $explanation . '</span>' . "\n";
			}
			// Output
			$output .= '<tr style="vertical-align: top;"><th align="left" scope="row"><label for="' . $option[0] . '">' . $option[1] . '</label></th><td>' . $input_type . '	' . $extra . '</td></tr>' . "\n";
		}
		$output .= '</table>' . "\n";
		return $output;
	}

	/**
	 * Used in forms to set an option checked
	 *
	 * @param mixed $checked
	 * @param mixed $current
	 * @return strings
	 */
	function isChecked ( $checked, $current ) {
		$return = '';
		if ( $checked == $current ) {
			$return = ' checked="checked"';
		}
		return $return;
	}

	/**
	 * Display error message at bottom of comments.
	 *
	 * @param string $msg Error Message. Assumed to contain HTML and be sanitized.
	 */
	function comment_footer_die ( $msg ) {
		echo "<div class='wrap'><p>$msg</p></div>";
		die();
	}

}
?>
