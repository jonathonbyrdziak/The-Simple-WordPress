<?php
/*
Plugin Name: ReCAPTCHA (AVH F.D.A.S Component)
Plugin URI: http://premium.wpmudev.org/
Description: Protects your comments from spam by adding a CAPTCHA challenge
Version: 0.1
Author: Ve Bailovity (Incsub)
Author URI: http://premium.wpmudev.org

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

class AVH_ReCaptcha {

	private static $_public_key = '6LcHObsSAAAAAIfHD_wQ92EWasdfasdfasdfasdfdOV0JTcIN8tYxN07';
	private static $_private_key = '6LcHObsSAAAAAJpasdfasdfasdfasdfBq0g501raPqH7koKyU-Po8RLL';
	private $_data;

	public function __construct () {
		if (!function_exists('recaptcha_check_answer')) { // Don't include ReCAPTCHA lib if we already have it
			require_once (dirname(__FILE__) . '/avh-files/plugins/external/recaptchalib.php');
		}
		if (!class_exists('AVH_FormsRenderer')) require_once (dirname(__FILE__) . '/avh-files/plugins/avh-fdas.forms.php');
		require_once (dirname(__FILE__) . '/avh-files/plugins/avh-fdas.recaptcha.forms.php');
		$this->_data = get_site_option('avhfdas-recaptcha');
	}

	public static function serve () {
		$me = new AVH_ReCaptcha;
		$me->_add_hooks();
	}

	public function register_settings () {
		$form = new AVH_ReCaptcha_AdminFormRenderer;

		register_setting('avh-fdas-recaptcha', 'avh-fdas-recaptcha');
		add_settings_section('avh-fdas_settings', __('Settings', 'avh-fdas'), create_function('', ''), 'avh-fdas_recaptcha_page');
		add_settings_field('avh-fdas_disable', __('Disable ReCaptcha', 'avh-fdas'), array($form, 'create_disable_box'), 'avh-fdas_recaptcha_page', 'avh-fdas_settings');
		add_settings_field('avh-fdas_display', __('Display options', 'avh-fdas'), array($form, 'create_display_box'), 'avh-fdas_recaptcha_page', 'avh-fdas_settings');
	}

	public function create_menu_entry () {
		if (@$_POST && isset($_POST['option_page'])) {
			$changed = false;
			if('avh-fdas-recaptcha' == @$_POST['option_page']) {
				update_site_option('avhfdas-recaptcha', $_POST['avh-recaptcha']);
				$changed = true;
			}

			if ($changed) {
				$goback = add_query_arg('settings-updated', 'true',  wp_get_referer());
				wp_redirect($goback);
				die;
			}
		}
		add_submenu_page('avh-settings', 'AVH First Defense Against Spam - WPMU DEV Version: ' . __( 'ReCAPTCHA', 'avh-fdas' ), __( 'ReCAPTCHA', 'avh-fdas' ), 'manage_network_options', 'avh-fdas-recaptcha', array ($this, 'create_settings_page'));
	}

	public function create_settings_page () {
		include (dirname(__FILE__) . '/avh-files/plugins/forms/recaptcha_settings.php');
	}

	public function place_captcha () {
		global $user_ID;
		if ($user_ID) return false;

		$appearance = @$this->_data['appearance'] ? $this->_data['appearance'] : 'full';
		switch (@$this->_data['appearance']) {
			case "compact":
				return $this->_place_compact_captcha();
			case "full":
			default:
				return $this->_place_full_captcha();
		}
	}

	public function process_captcha_challenge ($comment) {
		global $user_ID;
		if ($user_ID) return $comment;
		if (in_array($comment['comment_type'], array('trackback', 'pingback'))) return $comment; // Only process comments

		$resp = recaptcha_check_answer(
			self::$_private_key,
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]
		);
		if (!$resp->is_valid) wp_die(__('Error: Please enter the correct anti-spam word. Press the back button and try again.', 'avh-fdas'));
		return $comment;
	}

	private function _place_compact_captcha () {
		$pkey = self::$_public_key;
		$label = __('Click to reload', 'avh-fdas');
		echo <<<EOCompact
<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'custom',
    custom_theme_widget: 'recaptcha_widget'
 };
</script>
<div id="recaptcha_widget" style="display:none">

   <div title="{$label}" onclick="javascript:Recaptcha.reload()" id="recaptcha_image"></div>
   <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
</div>

 <script type="text/javascript"
    src="http://www.google.com/recaptcha/api/challenge?k={$pkey}">
 </script>
EOCompact;
	}

	private function _place_full_captcha () {
		if (@$this->_data['theme']) {
			echo '<script type="text/javascript">' .
				'var RecaptchaOptions = {' .
    				'theme : "' . $this->_data['theme'] . '"' .
 				'};' .
			'</script>';
		}
		echo recaptcha_get_html(self::$_public_key);
	}

	private function _add_hooks () {
		add_action('admin_init', array($this, 'register_settings'));
		add_action('network_admin_menu', array($this, 'create_menu_entry'), 25);

		if (!@$this->_data['disable_recaptcha']) {
			add_action('comment_form', array($this, 'place_captcha'));
			add_action('preprocess_comment', array($this, 'process_captcha_challenge'));
		}
	}
}

AVH_ReCaptcha::serve();