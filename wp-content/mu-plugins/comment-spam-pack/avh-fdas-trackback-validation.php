<?php
/*
Plugin Name: Trackback Validation (AVH F.D.A.S Component)
Plugin URI: http://premium.wpmudev.org/
Description: Validates your trackbacks as a protection against spam
Version: 0.1
Author: Ve Bailovity (Incsub)
Author URI: http://premium.wpmudev.org

Inspired and heavily influenced by early version of
Simple Trackback Validation (http://sw-guide.de/wordpress/plugins/simple-trackback-validation/)
by Michael Woehrer (http://sw-guide.de)

Copyright 2009-2011 Incsub (http://incsub.com)

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

class AVH_TrackbackValidator {

	private $_data;

	public function __construct () {
		if (!class_exists('AVH_FormsRenderer')) require_once (dirname(__FILE__) . '/avh-files/plugins/avh-fdas.forms.php');
		require_once (dirname(__FILE__) . '/avh-files/plugins/avh-fdas.trackback.forms.php');
		$this->_data = get_site_option('avhfdas-trackback');
	}

	public static function serve () {
		$me = new AVH_TrackbackValidator;
		$me->_add_hooks();
	}

	public function register_settings () {
		$form = new AVH_TrackbackValidator_AdminFormRenderer;

		register_setting('avh-fdas-trackback', 'avh-fdas-trackback');
		add_settings_section('avh-fdas_settings', __('Settings', 'avh-fdas'), create_function('', ''), 'avh-fdas_trackback_page');
		add_settings_field('avh-fdas_disable', __('Disable Trackback Validation', 'avh-fdas'), array($form, 'create_disable_box'), 'avh-fdas_trackback_page', 'avh-fdas_settings');
		add_settings_field('avh-fdas_ip', __('Disable IP checks', 'avh-fdas'), array($form, 'create_ip_box'), 'avh-fdas_trackback_page', 'avh-fdas_settings');
		add_settings_field('avh-fdas_remote', __('Remote checks', 'avh-fdas'), array($form, 'create_remote_page_box'), 'avh-fdas_trackback_page', 'avh-fdas_settings');
		add_settings_field('avh-fdas_postprocess', __('What to do with suspected spam trackbacks?', 'avh-fdas'), array($form, 'create_postprocess_box'), 'avh-fdas_trackback_page', 'avh-fdas_settings');
	}

	public function create_menu_entry () {
		if (@$_POST && isset($_POST['option_page'])) {
			$changed = false;
			if('avh-fdas-trackback' == @$_POST['option_page']) {
				update_site_option('avhfdas-trackback', $_POST['avh-trackback']);
				$changed = true;
			}

			if ($changed) {
				$goback = add_query_arg('settings-updated', 'true',  wp_get_referer());
				wp_redirect($goback);
				die;
			}
		}
		add_submenu_page('avh-settings', 'AVH First Defense Against Spam - WPMU DEV Version: ' . __( 'Trackback Validation', 'avh-fdas' ), __( 'Trackback Validation', 'avh-fdas' ), 'manage_network_options', 'avh-fdas-trackback', array ($this, 'create_settings_page'));
	}

	public function create_settings_page () {
		include (dirname(__FILE__) . '/avh-files/plugins/forms/trackback_settings.php');
	}

	public function process_trackback ($comment) {
		if ('trackback' != $comment['comment_type']) return $comment;
		$permalink = get_permalink($comment['comment_post_ID']);
		$valid =
			$this->_check_author_url($comment['comment_author_url'])
			&&
			$this->_check_author_ip($comment['comment_author_url'])
			&&
			$this->_check_links($comment['comment_author_url'], $permalink)
		;
		if ($valid) return $comment;

		switch (@$this->_data['postprocess']) {
			case 'moderate':
				add_filter('pre_comment_approved', create_function('$a', 'return "0";'));
				break;
			case 'spam':
				add_filter('pre_comment_approved', create_function('$a', 'return "spam";'));
				break;
			case 'reject':
			default:
				wp_die(__("Your trackback has been rejected", 'afv-fdas'));
				break;
		}
		return $comment;
	}

	private function _check_author_url ($url) {
		return (substr($url, 0, 4) == 'http');
	}

	private function _check_author_ip ($url) {
		if (@$this->_data['no_ip_check']) return true;
		$agent_ip = long2ip(ip2long($_SERVER['REMOTE_ADDR']));
		$domain = parse_url($url, PHP_URL_HOST);
		$domain_ip = gethostbyname($domain);
		return ($agent_ip == $domain_ip);
	}

	private function _check_links ($url, $permalink) {
		if (@$this->_data['disable_remote']) return true;
		$urls = $this->_get_remote_url_list($url);
		if (!$urls) return false;
		if (@$this->_data['strict_checks']) {
			return (in_array($permalink, $urls) || in_array(rtrim('/', $permalink), $urls));
		}
		$locals = $this->_allowed_urls_to_array();
		$locals = $locals ? $locals : array();
		foreach ($locals as $local) {
			foreach ($urls as $remote) {
				if (preg_match('!^' . preg_quote($local) . '!i', $remote)) return true;
			}
		}
		return false;
	}


	private function _get_remote_url_list ($url) {
		if (!$url) return false;
		$req = new WP_Http;
		$result = $req->request($url);
		if (isset($result->errors)) return false;
		return $this->_get_urls_from_body(@$result['body']);
	}

	private function _get_urls_from_body ($txt) {
		preg_match_all('!\bhttps?:' . preg_quote('//') . '[^"\'\s]+!i', $txt, $matches);
		return $matches[0];
	}

	private function _allowed_urls_to_array () {
		$allowed_urls = @$this->_data['allowed_urls'];
		$allowed_urls = $allowed_urls ? $allowed_urls : self::get_default_allowed_urls();
		$res = explode(',', $allowed_urls);
		return array_unique(array_filter(array_map('trim', $res)));
	}

	public static function get_default_allowed_urls () {
		return site_url();
	}

	private function _add_hooks () {
		add_action('admin_init', array($this, 'register_settings'));
		add_action('network_admin_menu', array($this, 'create_menu_entry'), 35);

		if (!@$this->_data['disable_trackback']) {
			add_action('preprocess_comment', array($this, 'process_trackback'), 1, 1);
		}
	}
}

AVH_TrackbackValidator::serve();