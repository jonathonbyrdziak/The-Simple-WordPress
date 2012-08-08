<?php
/**
 * Renders form elements for admin settings pages.
 */
class AVH_TrackbackValidator_AdminFormRenderer extends AVH_FormsRenderer {
	protected $_option_name;
	protected $_pfx;
	protected $_opts;

	public function __construct () {
		$this->_option_name = 'avhfdas-trackback';
		$this->_pfx = 'avh-trackback';
		parent::__construct();
	}

	function create_disable_box () {
		echo $this->_create_checkbox('disable_trackback');
		echo '<div><small>' . __('Trackback Validation protection is enabled by default. If you set this option, your trackbacks will <b>NOT</b> be checked.', 'avh-fdas') . '</small></div>';
	}

	function create_ip_box () {
		echo $this->_create_checkbox('no_ip_check');
		echo '<div><small>' . __('If you set this option, the IPs of trackback sender and trackback webserver will <b>NOT</b> be checked against each other.', 'avh-fdas') . '</small></div>';
	}

	function create_remote_page_box () {
		echo
			'<label>' . __('Strict check:', 'avh-fdas') . '</label> ' .
			$this->_create_checkbox('strict_checks') .
			'<div><small>' . __('If you set this option, the trackback source page will have to include the actual permalink to your post.', 'avh-fdas') . '</small></div>'
		;
		$allowed_urls = $this->_get_option('allowed_urls');
		$allowed_urls = $allowed_urls ? $allowed_urls : AVH_TrackbackValidator::get_default_allowed_urls();
		echo
			'<label for="allowed_urls">' . __('List of allowed URLs:', 'avh-fdas') . '</label>' .
			'<textarea name="avh-trackback[allowed_urls]" id="allowed_urls" class="widefat" cols="48" rows="4">' . $allowed_urls . '</textarea>' .
			'<div><small>' . __('If you did not enable strict checks, you can enter a list of accepted (&quot;close enough&quot;) URLs here.', 'avh-fdas') . '</small></div>' .
			'<div><small>' . __('E.g. if you enter <code>http://www.example.com</code>, the trackback source page can include e.g. <code>http://www.example.com</code> or <code>http://www.example.com/my-page</code>.', 'avh-fdas') . '</small></div>' .
			'<div><small>' . __('Separate multiple URLs with a comma.', 'avh-fdas') . '</small></div>'
		;
		echo
			'<label>' . __('Disable remote checks:', 'avh-fdas') . '</label> ' .
			$this->_create_checkbox('disable_remote') .
			'<div><small>' . __('If you set this option, the trackback source page will <b>NOT</b> be checked at all.', 'avh-fdas') . '</small></div>'
		;
	}

	function create_postprocess_box () {
		$opts = array (
			'reject' => __('Reject them entirely', 'avh-fdas'),
			'spam' => __('Flag them as spam', 'avh-fdas'),
			'moderate' => __('Hold them for moderation', 'avh-fdas'),
		);
		echo $this->_create_keyval_selection_box('postprocess', $opts);
	}
}