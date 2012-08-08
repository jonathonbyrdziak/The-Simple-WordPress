<?php
/**
 * Renders form elements for admin settings pages.
 */
class AVH_ReCaptcha_AdminFormRenderer extends AVH_FormsRenderer {
	protected $_option_name;
	protected $_pfx;
	protected $_opts;

	public function __construct () {
		$this->_option_name = 'avhfdas-recaptcha';
		$this->_pfx = 'avh-recaptcha';
		parent::__construct();
	}

	function create_display_box () {
		$opts = array(
			"full" => __('Full', 'avh-fdas'),
			"compact" => __('Compact', 'avh-fdas'),
		);
		$themes = array(
			"red" => __('Red', 'avh-fdas'),
			"white" => __('White', 'avh-fdas'),
			"blackglass" => __('Black glass', 'avh-fdas'),
			"clean" => __('Clean', 'avh-fdas'),
		);

		echo "<label for='appearance'>" . __('Appearance:', 'avh-fdas') . '</label> ' .
			$this->_create_keyval_selection_box('appearance', $opts) .
			'<br />' .
		'';

		echo '<div id="avh-recaptcha-themes">';
		foreach ($themes as $key=>$lbl) {
			if ('blackglass' == $key) $img = 'Black';
			else $img = ucfirst($key);
			echo "<label for='theme-{$key}'>" .
				$this->_create_radiobox('theme', $key) .
				"<img src='http://code.google.com/apis/recaptcha/images/reCAPTCHA_Sample_{$img}.png' />" .
			"</label><br />";
		}
		echo '</div>';
	}

	function create_disable_box () {
		echo $this->_create_checkbox('disable_recaptcha');
		echo '<div><small>' . __('ReCAPTCHA protection is enabled by default. If you set this option, your comments will <b>NOT</b> be protected by ReCAPTCHA challenge.', 'avh-fdas') . '</small></div>';
	}

}