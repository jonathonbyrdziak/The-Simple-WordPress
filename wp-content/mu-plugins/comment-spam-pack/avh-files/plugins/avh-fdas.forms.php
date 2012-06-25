<?php
class AVH_FormsRenderer {
	protected $_option_name;
	protected $_pfx;
	protected $_opts;

	public function __construct () {
		$this->_opts = get_site_option($this->_option_name);
	}

	function _get_option ($name) {
		return @$this->_opts[$name];
	}

	function _create_checkbox ($name) {
		$value = $this->_get_option($name);
		return
			"<input type='radio' name='{$this->_pfx}[{$name}]' id='{$name}-yes' value='1' " . ((int)$value ? 'checked="checked" ' : '') . " /> " .
				"<label for='{$name}-yes'>" . __('Yes', 'avh-fdas') . "</label>" .
			'&nbsp;' .
			"<input type='radio' name='{$this->_pfx}[{$name}]' id='{$name}-no' value='0' " . (!(int)$value ? 'checked="checked" ' : '') . " /> " .
				"<label for='{$name}-no'>" . __('No', 'avh-fdas') . "</label>" .
		"";
	}

	function _create_radiobox ($name, $value) {
		$opt = $this->_get_option($name);
		$checked = (@$opt == $value) ? true : false;
		return "<input type='radio' name='{$this->_pfx}[{$name}]' id='{$name}-{$value}' value='{$value}' " . ($checked ? 'checked="checked" ' : '') . " /> ";
	}

	function _create_key_selection_box ($name, $values, $default=false) {
		$opt = $this->_get_option($name);
		if ($default) {
			$opt = $opt ? $opt : $default;
		}
		$ret = "<select name='{$this->_pfx}[{$name}]' id='{$name}'>";
		foreach ($values as $key) {
			$selected = ($opt == $key) ? "selected='selected'" : '';
			$ret .= "<option value='{$key}' {$selected}>{$key}</option>";
		}
		$ret .= "</select>";
		return $ret;
	}

	function _create_keyval_selection_box ($name, $values, $default=false) {
		$opt = $this->_get_option($name);
		if ($default) {
			$opt = $opt ? $opt : $default;
		}
		$ret = "<select name='{$this->_pfx}[{$name}]' id='{$name}'>";
		foreach ($values as $key => $label) {
			$selected = ($opt == $key) ? "selected='selected'" : '';
			$ret .= "<option value='{$key}' {$selected}>{$label}</option>";
		}
		$ret .= "</select>";
		return $ret;
	}
}