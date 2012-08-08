<?php
/**
 * Renders form elements for admin settings pages.
 */
class AVH_SpamFilter_AdminFormRenderer extends AVH_FormsRenderer {
	protected $_option_name;
	protected $_pfx;
	protected $_opts;

	public function __construct () {
		$this->_option_name = 'avhfdas-spam_filter';
		$this->_pfx = 'avh-spam_filter';
		parent::__construct();
	}

	function create_disable_box () {
		echo $this->_create_checkbox('disable_spam_filter');
		echo '<div><small>' . __('Spam Filter protection is enabled by default. If you set this option, your comments will <b>NOT</b> be protected by Spam Filter.', 'avh-fdas') . '</small></div>';
	}

	function create_comments_page_box () {
		echo $this->_create_checkbox('restrict_comments_page');
		echo '<div><small>' . __('Enabling this option will restrict access to per-blog Spam Filter comments page to Super Admins', 'avh-fdas') . '</small></div>';
	}

	function create_stop_words_box () {
		$stop_words = $this->_get_option('stop_words');
		$stop_words = $stop_words ? $stop_words : AVH_SpamFilter::get_default_stop_words();
		echo "<textarea name='avh-spam_filter[stop_words]' class='widefat' cols='48' rows='8'>{$stop_words}</textarea>";
		echo '<div><small>' . __('Separate words by comma. You can add as many as you want.', 'avh-fdas') . '</small></div>';
		echo __('Disable words blacklist protection:', 'avh-fdas') .
			'<br />' .
			$this->_create_checkbox('no_stop_words') .
			'<br />' .
			'<div><small>' .
				__('By default, comments that contain one or more words listed above will be rejected.', 'avh-fdas') .
			'</small></div>'
		;
	}

	function create_link_count_box () {
		echo $this->_create_key_selection_box('link_count', range(1, 10), 3);
		echo '<div><small>' . __('Allow this many links in your comments', 'avh-fdas') . '</small></div>';
		echo __('Disable link count protection:', 'avh-fdas') .
			'<br />' .
			$this->_create_checkbox('no_link_count') .
			'<br />' .
			'<div><small>' .
				__('By default, comments that contain more links then set above will be rejected.', 'avh-fdas') .
			'</small></div>'
		;
	}
}