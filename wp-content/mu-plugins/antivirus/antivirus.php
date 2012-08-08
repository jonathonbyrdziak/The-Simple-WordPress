<?php
/*
Plugin Name: AntiVirus
Text Domain: antivirus
Domain Path: /lang
Description: Security solution as a smart, effectively plugin to protect your blog against exploits and spam injections.
Author: Sergej M&uuml;ller
Author URI: http://wpseo.de
Plugin URI: http://wpantivirus.com
Version: 1.3.1
*/


/* Sicherheitsabfrage */
if ( !class_exists('WP') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}


/**
* AntiVirus
*
* @since  0.1
*/

class AntiVirus {


	/* Save me! */
	private static $base;


	/**
	* Konstruktor der Klasse
	*
	* @since   0.1
	* @change  1.2
	*/

	public static function init()
	{
		/* AUTOSAVE */
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) or (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) ) {
			return;
		}
		
		/* Plugin-Base */
		self::$base = plugin_basename(__FILE__);

		/* Cronjob */
		if ( defined('DOING_CRON') ) {
			add_action(
				'antivirus_daily_cronjob',
				array(
					__CLASS__,
					'exe_daily_cronjob'
				)
			);

		/* Admin */
		} elseif ( is_admin() ) {
			/* AJAX */
			if ( defined('DOING_AJAX') ) {
				add_action(
					'wp_ajax_get_ajax_response',
					array(
						__CLASS__,
						'get_ajax_response'
					)
				);

			/* Backend */
			} else {
				/* Actions */
				add_action(
					'init',
					array(
						__CLASS__,
						'load_plugin_lang'
					)
				);
				add_action(
					'admin_menu',
					array(
						__CLASS__,
						'add_sidebar_menu'
					)
				);
				add_action(
					'admin_bar_menu',
					array(
						__CLASS__,
						'add_adminbar_menu'
					),
					91
				);
				add_action(
					'admin_notices',
					array(
						__CLASS__,
						'show_dashboard_notice'
					)
				);
				add_action(
					'admin_print_styles',
					array(
						__CLASS__,
						'add_enqueue_style'
					)
				);
				
				/* GUI */
				if ( self::is_current_page('home') ) {
					add_action(
						'admin_print_scripts',
						array(
							__CLASS__,
							'add_enqueue_script'
						)
					);

				/* Plugins */
				} else if ( self::is_current_page('plugins') ) {
					add_action(
						'deactivate_' .self::$base,
						array(
							__CLASS__,
							'clear_scheduled_hook'
						)
					);
					add_filter(
						'plugin_row_meta',
						array(
							__CLASS__,
							'init_row_meta'
						),
						10,
						2
					);
					add_filter(
						'plugin_action_links_' .self::$base,
						array(
							__CLASS__,
							'init_action_links'
						)
					);
				}
			}
		}
	}


	/**
	* Einbindung der Sprache
	*
	* @since   0.8
	* @change  0.8
	*/

	public static function load_plugin_lang()
	{
		load_plugin_textdomain(
			'antivirus',
			false,
			'antivirus/lang'
		);
	}
	
	
	/**
	* Hinzufügen der Action-Links (Einstellungen links)
	*
	* @since   1.1
	* @change  1.1
	*/

	public static function init_action_links($data)
	{
		/* Rechte? */
		if ( !current_user_can('manage_options') ) {
			return $data;
		}

		return array_merge(
			$data,
			array(
				sprintf(
					'<a href="%s">%s</a>',
					add_query_arg(
						array(
							'page' => 'antivirus'
						),
						admin_url('options-general.php')
					),
					__('Settings')
				)
			)
		);
	}


	/**
	* Links in der Plugins-Verwaltung
	*
	* @since   0.1
	* @change  1.1
	*
	* @param   array   $links  Array mit Links
	* @param   string  $file   Name des Plugins
	* @return  array   $links  Array mit erweitertem Link
	*/
	
	public static function init_row_meta($data, $page)
	{
		if ( $page == self::$base ) {
			$data = array_merge(
				$data,
				array(
					sprintf(
						'<a href="https://flattr.com/thing/58179/Sicherheit-in-WordPress-Das-erste-AntiVirus-Plugin-fur-WordPress" target="_blank">%s</a>',
						esc_html__('Flattr plugin', 'antivirus')
					),
					sprintf(
						'<a href="https://plus.google.com/110569673423509816572" target="_blank">%s</a>',
						esc_html__('Follow on Google+', 'antivirus')
					)
				)
			);
		}

		return $data;
	}


	/**
	* Aktion bei Aktivierung des Plugins
	*
	* @since   0.1
	* @change  0.8
	*/

	public static function install()
	{
		/* Option anlegen */
		add_option(
			'antivirus',
			array(),
			'',
			'no'
		);

		/* Cron aktivieren */
		if ( self::get_option('cronjob_enable') ) {
			self::init_scheduled_hook();
		}
	}
	
	
	/**
	* Uninstallation des Plugins pro MU-Blog
	*
	* @since   1.1
	* @change  1.1
	*/

	public static function uninstall()
	{
		/* Global */
		global $wpdb;
		
		/* Remove settings */
		delete_option('antivirus');
		
		/* Clean DB */
		$wpdb->query("OPTIMIZE TABLE `" .$wpdb->options. "`");
	}


	/**
	* Rückgabe eines Optionsfeldes
	*
	* @since   0.1
	* @change  0.8
	*
	* @param   string  $field  Name des Feldes
	* @return  mixed           Wert des Feldes
	*/

	private static function get_option($field)
	{
		if ( !$options = wp_cache_get('antivirus') ) {
			$options = get_option('antivirus');
			wp_cache_set(
				'antivirus',
				$options
			);
		}

		return @$options[$field];
	}


	/**
	* Aktualisiert ein Optionsfeld
	*
	* @since   0.1
	* @change  0.8
	*
	* @param   string  $field  Name des Feldes
	* @param   mixed           Wert des Feldes
	*/

	private static function update_option($field, $value)
	{
		self::update_options(
			array(
				$field => $value
			)
		);
	}


	/**
	* Aktualisiert mehrere Optionsfelder
	*
	* @since   0.1
	* @change  0.8
	*
	* @param   array  $data  Array mit Feldern
	*/

	private static function update_options($data)
	{
		/* Option zuweisen */
		$options = array_merge(
			(array)get_option('antivirus'),
			$data
		);

		/* DB updaten */
		update_option(
			'antivirus',
			$options
		);

		/* Cache updaten */
		wp_cache_set(
			'antivirus',
			$options
		);
	}


	/**
	* Initialisierung des Cronjobs
	*
	* @since   0.1
	* @change  0.8
	*/

	private static function init_scheduled_hook()
	{
		if ( !wp_next_scheduled('antivirus_daily_cronjob') ) {
			wp_schedule_event(
				time(),
				'daily',
				'antivirus_daily_cronjob'
			);
		}
	}


	/**
	* Beendigung des Cronjobs
	*
	* @since   0.1
	* @change  0.8
	*/

	public static function clear_scheduled_hook()
	{
		if ( wp_next_scheduled('antivirus_daily_cronjob') ) {
			wp_clear_scheduled_hook('antivirus_daily_cronjob');
		}
	}


	/**
	* Ausführung des Cronjobs
	*
	* @since   0.1
	* @change  1.0
	*/

	public static function exe_daily_cronjob()
	{
		/* Kein Cronjob? */
		if ( !self::get_option('cronjob_enable') ) {
			return;
		}

		/* Timestamp updaten */
		self::update_option(
			'cronjob_timestamp',
			time()
		);

		/* Files prüfen */
		if ( self::check_theme_files() or self::check_permalink_structure() ) {
			/* Sprache laden */
			self::load_plugin_lang();
			
			/* E-Mail-Adresse */
			$email = sanitize_email(self::get_option('notify_email'));
			$email = ( (!empty($email) && is_email($email)) ? $email : get_bloginfo('admin_email') );

			/* Send it! */
			wp_mail(
				$email,
				sprintf(
					'[%s] %s',
					get_bloginfo('name'),
					esc_html__('Virus suspected', 'antivirus')
				),
				sprintf(
					"%s\r\n%s\r\n\r\n\r\n%s\r\n%s\r\n",
					esc_html__('The daily antivirus scan of your blog suggests alarm.', 'antivirus'),
					get_bloginfo('url'),
					esc_html__('Notify message by AntiVirus for WordPress', 'antivirus'),
					esc_html__('http://wpantivirus.com', 'antivirus')
				)
			);

			/* Alert speichern */
			self::update_option(
				'cronjob_alert',
				1
			);
		}
	}


	/**
	* Initialisierung der GUI
	*
	* @since   0.1
	* @change  1.3.1
	*/

	public static function add_sidebar_menu()
	{
		/* Menü anlegen */
		add_submenu_page(
			'tools.php',
			'AntiVirus',
			'AntiVirus',
			'manage_options',
			'antivirus',
			array(
				__CLASS__,
				'show_admin_menu'
			)
		);
	}


	/**
	* Initialisierung von JavaScript
	*
	* @since   0.8
	* @change  1.1
	*/

	public static function add_enqueue_script()
	{
		/* Infos auslesen */
		$data = get_plugin_data(__FILE__);

		/* JS einbinden */
		wp_register_script(
			'av_script',
			plugins_url('js/script.js', __FILE__),
			array('jquery'),
			$data['Version']
		);
		
		/* Script einbinden */
		wp_enqueue_script('av_script');
		
		/* Script lokalisieren */
		wp_localize_script(
			'av_script',
			'av_settings',
			array(
				'nonce' => wp_create_nonce('av_ajax_nonce'),
				'ajax' 	=> admin_url('admin-ajax.php'),
				'theme'	=> urlencode(self::get_theme_name()),
				'msg_1'	=> esc_html__('There is no virus', 'antivirus'),
				'msg_2' => esc_html__('View line', 'antivirus'),
				'msg_3' => esc_html__('Scan finished', 'antivirus')
			)
		);
	}


	/**
	* Initialisierung von Stylesheets
	*
	* @since   0.8
	* @change  1.1
	*/

	public static function add_enqueue_style()
	{
		/* Infos auslesen */
		$data = get_plugin_data(__FILE__);
		
		/* CSS registrieren */
		wp_register_style(
			'av_css',
			plugins_url('css/style.css', __FILE__),
			array(),
			$data['Version']
		);

		/* CSS einbinden */
		wp_enqueue_style('av_css');
	}


	/**
	* Rückgabe des aktuellen Theme
	*
	* @since   0.1
	* @change  1.3.1
	*
	* @return  array  $themes  Array mit Theme-Eigenschaften
	*/

	private static function get_current_theme()
	{
		/* Ab WP 3.4 */
		if ( function_exists('wp_get_theme') ) {
			/* Init */
			$theme = wp_get_theme();
			$name = $theme->get('Name');
			$slug = $theme->get_stylesheet();
			$files = $theme->get_files('php', 1);

			/* Leer? */
			if ( empty($name) or empty($files) ) {
				return false;
			}
			
			/* Rückgabe */
			return array(
				'Name' => $name,
				'Slug' => $slug,
				'Template Files' => $files
			);
		/* Bis WP 3.4 */
		} else {
			if ( $themes = get_themes() ) {
				/* Aktuelles Theme */
				if ( $theme = get_current_theme() ) {
					if ( array_key_exists((string)$theme, $themes) ) {
						return $themes[$theme];
					}
				}
			}
			
		}
		
		return false;
	}


	/**
	* Rückgabe von Dateien des aktuellen Theme
	*
	* @since   0.1
	* @change  0.8
	*
	* @return  array  $files  Array mit Dateien
	*/

	private static function get_theme_files()
	{
		/* Theme vorhanden? */
		if ( !$theme = self::get_current_theme() ) {
			return false;
		}

		/* Keine Files? */
		if ( empty($theme['Template Files']) ) {
			return false;
		}

		/* Zurückgeben */
		return array_unique(
			array_map(
				create_function(
					'$v',
					'return str_replace(array(WP_CONTENT_DIR, "wp-content"), "", $v);'
				),
				$theme['Template Files']
			)
		);
	}


	/**
	* Rückgabe des Namen des aktuellen Theme
	*
	* @since   0.1
	* @change  1.3.1
	*
	* @return  string  $theme  Name des aktuellen Theme
	*/

	private static function get_theme_name()
	{
		if ( $theme = self::get_current_theme() ) {
			if ( !empty($theme['Slug']) ) {
				return $theme['Slug'];
			}
			if ( !empty($theme['Name']) ) {
				return $theme['Name'];
			}
		}

		return false;
	}


	/**
	* Rückgabe der WhiteList
	*
	* @since   0.1
	* @change  0.8
	*
	* @return  array  return  Array mit MD5-Werten
	*/

	private static function get_white_list()
	{
		return explode(
			':',
			self::get_option('white_list')
		);
	}


	/**
	* Ausführung von AJAX
	*
	* @since   0.1
	* @change  0.8
	*/

	public static function get_ajax_response()
	{
		/* Referer prüfen */
		check_ajax_referer('av_ajax_nonce');

		/* Zusätzliche Prüfung */
		if ( empty($_POST['_action_request']) ) {
			exit();
		}

		/* Init */
		$values = array();
		$output = '';

		/* Ausgabe starten */
		switch ($_POST['_action_request']) {
			case 'get_theme_files':
				self::update_option(
					'cronjob_alert',
					0
				);

				$values = self::get_theme_files();
			break;

			case 'check_theme_file':
				if ( !empty($_POST['_theme_file']) && $lines = self::check_theme_file($_POST['_theme_file']) ) {
					foreach ($lines as $num => $line) {
						foreach ($line as $string) {
							$values[] = $num;
							$values[] = htmlentities($string, ENT_QUOTES);
							$values[] = md5($num . $string);
						}
					}
				}
			break;

			case 'update_white_list':
				if ( !empty($_POST['_file_md5']) ) {
					self::update_option(
						'white_list',
						implode(
							':',
							array_unique(
								array_merge(
									self::get_white_list(),
									array($_POST['_file_md5'])
								)
							)
						)
					);

					$values = array($_POST['_file_md5']);
				}
				break;

			default:
				break;
		}

		/* Ausgabe starten */
		if ($values) {
			$output = sprintf(
				"['%s']",
				implode("', '", $values)
			);

			/* Header senden */
			header('Content-Type: plain/text');

			/* Ausgeben */
			echo sprintf(
				'{data:%s, nonce:"%s"}',
				$output,
				$_POST['_ajax_nonce']
			);
		}

		/* Raus! */
		exit();
	}


	/**
	* Rückgabe des Dateiinhaltes
	*
	* @since   0.1
	* @change  0.8
	*
	* @return  array  $file  Array mit Dateizeilen
	*/

	private static function get_file_content($file)
	{
		return file(WP_CONTENT_DIR . $file);
	}


	/**
	* Kürzung eines Strings
	*
	* @since   0.1
	* @change  0.1
	*
	* @param   string   $line    Eigenetliche Zeile als String
	* @param   string   $tag     Gesuchtes Tag
	* @param   integer  $max     Anzahl der Zeichen rechts und links
	* @return  string   $output  Gekürzter String
	*/

	public static function get_dotted_line($line, $tag, $max = 100)
	{
		/* Keine Werte? */
		if ( !$line or !$tag ) {
			return false;
		}

		/* Differenz ermitteln */
		if ( strlen($tag) > $max ) {
			return $tag;
		}

		/* Differenz ermitteln */
		$left = round(($max - strlen($tag)) / 2);

		/* Wert konvertieren */
		$tag = preg_quote($tag);

		/* String kürzen */
		$output = preg_replace(
			'/(' .$tag. ')(.{' .$left. '}).{0,}$/',
			'$1$2 ...',
			$line
		);
		$output = preg_replace(
			'/^.{0,}(.{' .$left. ',})(' .$tag. ')/',
			'... $1$2',
			$output
		);

		return $output;
	}


	/**
	* Definition des Regexp
	*
	* @since   0.1
	* @change  1.2
	*
	* @return  string  return  Regulärer Ausdruck
	*/

	private static function get_preg_match()
	{
		return '/(assert|file_get_contents|curl_exec|popen|proc_open|unserialize|eval|base64_encode|base64_decode|create_function|exec|shell_exec|system|passthru|ob_get_contents|file|curl_init|readfile|fopen|fsockopen|pfsockopen|fclose|fread|include|include_once|require|require_once|file_put_contents|iframe)\s*?\(/';
	}


	/**
	* Prüfung einer Zeile
	*
	* @since   0.1
	* @change  1.1
	*
	* @param   string   $line  Zeile zur Prüfung
	* @param   integer  $num   Nummer zur Prüfung
	* @return  string   $line  Zeile mit Resultaten
	*/

	private static function check_file_line($line = '', $num)
	{
		/* Wert trimmen */
		$line = trim((string)$line);

		/* Leere Werte? */
		if ( !$line or !isset($num) ) {
			return false;
		}

		/* Werte initialisieren */
		$results = array();
		$output = array();

		/* Befehle suchen */
		preg_match_all(
			self::get_preg_match(),
			$line,
			$matches
		);

		/* Ergebnis speichern */
		if ( $matches[1] ) {
			$results = $matches[1];
		}

		/* Base64 suchen */
		preg_match_all(
			'/[\'\"\$\\ \/]*?([a-zA-Z0-9]{' .strlen(base64_encode('sergej + swetlana = love.')). ',})/',
			$line,
			$matches
		);

		/* Ergebnis speichern */
		if ( $matches[1] ) {
			$results = array_merge($results, $matches[1]);
		}

		/* Frames suchen */
		preg_match_all(
			'/<\s*?(frame)/',
			$line,
			$matches
		);

		/* Ergebnis speichern */
		if ( $matches[1] ) {
			$results = array_merge($results, $matches[1]);
		}

		/* Option suchen */
		preg_match(
			'/get_option\s*\(\s*[\'"](.*?)[\'"]\s*\)/',
			$line,
			$matches
		);

		/* Option prüfen */
		if ( $matches && $matches[1] && self::check_file_line(get_option($matches[1]), $num) ) {
			array_push($results, 'get_option');
		}

		/* Ergebnisse? */
		if ( $results ) {
			/* Keine Duplikate */
			$results = array_unique($results);

			/* White-Liste */
			$md5 = self::get_white_list();

			/* Resultate loopen */
			foreach ($results as $tag) {
				$string = str_replace(
					$tag,
					'@span@' .$tag. '@/span@',
					self::get_dotted_line($line, $tag)
				);

				/* In der Whitelist? */
				if (!in_array(md5($num . $string), $md5)) {
					$output[] = $string;
				}
			}

			return $output;
		}

		return false;
	}


	/**
	* Prüfung der Dateien des aktuellen Theme
	*
	* @since   0.1
	* @change  0.8
	*
	* @return  array  $results  Array mit Ergebnissen
	*/

	private static function check_theme_files()
	{
		/* Files vorhanden? */
		if ( !$files = self::get_theme_files() ) {
			return false;
		}

		/* Init */
		$results = array();

		/* Files loopen */
		foreach($files as $file) {
			if ($result = self::check_theme_file($file)) {
				$results[$file] = $result;
			}
		}

		/* Werte vorhanden? */
		if ( !empty($results) ) {
			return $results;
		}

		return false;
	}


	/**
	* Prüfung einer Datei
	*
	* @since   0.1
	* @change  0.8
	*
	* @param   string  $file     Datei zur Prüfung
	* @return  array   $results  Array mit Ergebnissen
	*/

	private static function check_theme_file($file)
	{
		/* Kein File? */
		if ( !$file ) {
			return false;
		}

		/* Inhalt auslesen */
		if ( !$content = self::get_file_content($file) ) {
			return false;
		}

		/* Init */
		$results = array();

		/* Zeilen loopen */
		foreach($content as $num => $line) {
			if ($result = self::check_file_line($line, $num)) {
				$results[$num] = $result;
			}
		}

		/* Werte vorhanden? */
		if ( !empty($results) ) {
			return $results;
		}

		return false;
	}


	/**
	* Prüfung des Permalinks
	*
	* @since   0.1
	* @change  0.8
	*
	* @return  mixed  $matches  FALSE, wenn kein Fund
	*/

	private static function check_permalink_structure()
	{
		if ( $structure = get_option('permalink_structure') ) {
			/* Befehle suchen */
			preg_match_all(
				self::get_preg_match(),
				$structure,
				$matches
			);

			/* Ergebnis speichern */
			if ( $matches[1] ) {
				return $matches[1];
			}
		}

		return false;
	}


	/**
	* Prüfung der Admin-Seite
	*
	* @since   0.1
	* @change  0.8
	*
	* @param   integer  $page  Gesuchte Seite
	* @return  boolean         TRUE, wenn die aktuelle auch die gesuchte Seite ist
	*/

	private static function is_current_page($page)
	{
		switch($page) {
			case 'home':
				return ( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'antivirus' );

			case 'index':
			case 'plugins':
				return (!empty($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == sprintf('%s.php', $page));

			default:
				return false;
		}
	}


	/**
	* Anzeige des Dashboard-Hinweises
	*
	* @since   0.1
	* @change  1.2
	*/

	public static function show_dashboard_notice() {
		/* Kein Alert? */
		if ( !self::get_option('cronjob_alert') ) {
			return;
		}
		
		/* Bereits in der Adminbar */
		if ( function_exists('is_admin_bar_showing') && is_admin_bar_showing() ) {
			return;
		}

		/* Warnung */
		echo sprintf(
			'<div class="updated fade"><p><strong>%1$s:</strong> %2$s <a href="%3$s">%4$s &rarr;</a></p></div>',
			esc_html__('Virus suspected', 'antivirus'),
			esc_html__('The daily antivirus scan of your blog suggests alarm.', 'antivirus'),
			add_query_arg(
				array(
					'page' => 'antivirus'
				),
				admin_url('options-general.php')
			),
			esc_html__('Manual scan', 'antivirus')
		);
	}
	
	
	/**
	* Anzeige des Menüs in der Adminbar
	*
	* @since   1.2
	* @change  1.2
	*/
	
	public static function add_adminbar_menu( $wp_admin_bar ) {
		/* Kein Alert? */
		if ( !self::get_option('cronjob_alert') ) {
			return;
		}
		
		/* Keine Adminbar? */
		if ( !function_exists('is_admin_bar_showing') or !is_admin_bar_showing() ) {
			return;
		}
		
		/* Hinzufügen */
		$wp_admin_bar->add_menu(
			array(
				'id' 	=> 'antivirus',
				'title' => '<span class="ab-icon"></span><span class="ab-label">' .esc_html__('Virus suspected', 'antivirus'). '</span>',
				'href'  => add_query_arg(
					array(
						'page' => 'antivirus'
					),
					admin_url('options-general.php')
				)
			)
		);
	}


	/**
	* Anzeige der GUI
	*
	* @since   0.1
	* @change  1.2
	*/

	public static function show_admin_menu() {
		/* Updates speichern */
		if ( !empty($_POST) ) {
			/* Referer prüfen */
			check_admin_referer('antivirus');

			/* Werte zuweisen */
			$options = array(
				'cronjob_enable' => (int)(!empty($_POST['av_cronjob_enable'])),
				'notify_email'	 => sanitize_email(@$_POST['av_notify_email'])
			);

			/* Kein Cronjob? */
			if (empty($options['cronjob_enable'])) {
				$options['notify_email'] = '';
			}

			/* Cron stoppen? */
			if ($options['cronjob_enable'] && !self::get_option('cronjob_enable')) {
				self::init_scheduled_hook();
			} else if (!$options['cronjob_enable'] && self::get_option('cronjob_enable')) {
				self::clear_scheduled_hook();
			}

			/* Optionen speichern */
			self::update_options($options); ?>

			<div id="message" class="updated fade">
				<p>
					<strong>
						<?php _e('Settings saved.') ?>
					</strong>
				</p>
			</div>
		<?php } ?>

		<div class="wrap" id="av_main">
			<div class="icon32"></div>

			<h2>
				AntiVirus
			</h2>

			<form method="post" action="">
				<?php wp_nonce_field('antivirus') ?>

				<div id="poststuff">
					<div class="postbox">
						<h3>
							<?php esc_html_e('Completed scan', 'antivirus') ?>
						</h3>

						<div class="inside" id="av_completed">
							<div class="output">
								<div class="<?php echo (self::check_permalink_structure() ? 'danger' : 'done') ?>"><?php esc_html_e('Permalink back door check', 'antivirus') ?> <a href="<?php esc_html_e('http://mashable.com/2009/09/05/wordpress-attack/', 'antivirus') ?>" target="_blank">Info</a></div>
							</div>

							<ul class="agenda">
								<li>
									<p></p>
									<span>
										<?php esc_html_e('All clear', 'antivirus') ?>
									</span>
								</li>
								<li class="danger">
									<p></p>
									<span>
										<?php esc_html_e('Danger', 'antivirus') ?>
									</span>
								</li>
							</ul>
						</div>
					</div>

					<div class="postbox">
						<h3>
							<?php esc_html_e('Manual scan', 'antivirus') ?>
						</h3>

						<div class="inside" id="av_manual">
							<p>
								<a href="#" class="button rbutton"><?php esc_html_e('Scan the theme templates now', 'antivirus') ?></a>
								<span class="alert"></span>
							</p>
							<div class="output"></div>
						</div>
					</div>

					<div class="postbox">
						<h3>
							<?php _e('Settings') ?>
						</h3>

						<div class="inside">
							<table class="form-table">
								<tr>
									<td>
										<label for="av_cronjob_enable">
											<input type="checkbox" name="av_cronjob_enable" id="av_cronjob_enable" value="1" <?php checked(self::get_option('cronjob_enable'), 1) ?> />
											<?php esc_html_e('Enable the daily antivirus scan', 'antivirus') ?>
											<?php if (self::get_option('cronjob_enable') && self::get_option('cronjob_timestamp')) {
												echo sprintf(
													'&nbsp;(%s @ %s)',
													esc_html__('Last check', 'antivirus'),
													date_i18n('d.m.Y H:i:s', (self::get_option('cronjob_timestamp') + get_option('gmt_offset') * 3600))
												);
											} ?>
										</label>
										<span class="shift">
											<?php esc_html_e('Alternate email address', 'antivirus') ?>:&nbsp;<input type="text" name="av_notify_email" value="<?php esc_attr_e(self::get_option('notify_email')) ?>" class="regular-text" />
										</span>
									</td>
								</tr>
							</table>

							<p>
								<input type="submit" name="av_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
							</p>
						</div>
					</div>
				</div>
			</form>
		</div>
	<?php }
}


/* Fire */
add_action(
	'plugins_loaded',
	array(
		'AntiVirus',
		'init'
	),
	99
);


/* Install */
register_activation_hook(
	__FILE__,
	array(
		'AntiVirus',
		'install'
	)
);


/* Uninstall */
register_uninstall_hook(
	__FILE__,
	array(
		'AntiVirus',
		'uninstall'
	)
);