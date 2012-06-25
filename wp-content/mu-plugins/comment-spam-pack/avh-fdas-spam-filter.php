<?php
/*
Plugin Name: Spam Filter (AVH F.D.A.S Component)
Plugin URI: http://premium.wpmudev.org/
Description: Protects your comments by filtering out obvious spam
Version: 0.1
Author: Ve Bailovity (Incsub)
Author URI: http://premium.wpmudev.org

Inspired and heavily influenced by early version of
TanTanNoodles Simple Spam Filter (http://tantannoodles.com/toolkit/spam-filter/)
by Joe Tan (http://tantannoodles.com/)

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

class AVH_SpamFilter {

	private $_data;

	public function __construct () {
		if (!class_exists('AVH_FormsRenderer')) require_once (dirname(__FILE__) . '/avh-files/plugins/avh-fdas.forms.php');
		require_once (dirname(__FILE__) . '/avh-files/plugins/avh-fdas.spam_filter.forms.php');
		$this->_data = get_site_option('avhfdas-spam_filter');
	}

	public static function serve () {
		$me = new AVH_SpamFilter;
		$me->_add_hooks();
	}

	public static function get_default_stop_words () {
		return 'cialis, ebony, nude, porn, porno, pussy, upskirt, ringtones, phentermine, viagra, rape, casino, poker, cunt, funcking, fuck';
	}

	public function register_settings () {
		
		if (isset($_GET['action']) && isset($_GET['word'])) {
			$word = esc_attr($_GET['word']);
			if ('stop_word' == $_GET['action']) $this->_add_to_stop_words($word);
			else if ('kill_spam' == $_GET['action']) $this->_kill_spam($word);
			$goback = add_query_arg('success', 'true',  admin_url('edit-comments.php?page=avh-fdas_spam_filter'));
			wp_redirect($goback);
			die;
		}
		
		
		if (class_exists('TanTanSpamFilter')) return false;
		$form = new AVH_SpamFilter_AdminFormRenderer;

		register_setting('avh-fdas-spam_filter', 'avh-fdas-spam_filter');
		add_settings_section('avh-fdas_settings', __('Settings', 'avh-fdas'), create_function('', ''), 'avh-fdas_spam_filter_page');
		add_settings_field('avh-fdas_disable', __('Disable Spam Filter', 'avh-fdas'), array($form, 'create_disable_box'), 'avh-fdas_spam_filter_page', 'avh-fdas_settings');
		add_settings_field('avh-fdas_comments_page', __('Restrict Comments Page', 'avh-fdas'), array($form, 'create_comments_page_box'), 'avh-fdas_spam_filter_page', 'avh-fdas_settings');
		add_settings_field('avh-fdas_stop_words', __('Words Blacklist', 'avh-fdas'), array($form, 'create_stop_words_box'), 'avh-fdas_spam_filter_page', 'avh-fdas_settings');
		add_settings_field('avh-fdas_link_count', __('Link Count', 'avh-fdas'), array($form, 'create_link_count_box'), 'avh-fdas_spam_filter_page', 'avh-fdas_settings');
	}

	public function create_menu_entry () {
		if (class_exists('TanTanSpamFilter')) return false;
		if (@$_POST && isset($_POST['option_page'])) {
			$changed = false;
			if('avh-fdas-spam_filter' == @$_POST['option_page']) {
				update_site_option('avhfdas-spam_filter', $_POST['avh-spam_filter']);
				$changed = true;
			}

			if ($changed) {
				$goback = add_query_arg('settings-updated', 'true',  wp_get_referer());
				wp_redirect($goback);
				die;
			}
		}
		add_submenu_page('avh-settings', 'AVH First Defense Against Spam - WPMU DEV Version: ' . __( 'Spam Filter', 'avh-fdas' ), __( 'Spam Filter', 'avh-fdas' ), 'manage_network_options', 'avh-fdas-spam_filter', array ($this, 'create_settings_page'));
	}

	public function create_settings_page () {
		include (dirname(__FILE__) . '/avh-files/plugins/forms/spam_filter_settings.php');
	}

	public function create_comments_entry () {
		$perm = @$this->_data['disable_spam_filter'] ? 'manage_network_options' : 'manage_options';
		add_comments_page(__('Spam Filter', 'avh-fdas'), __('Spam Filter', 'avh-fdas'), $perm, 'avh-fdas_spam_filter', array($this, 'create_comments_page'));
	}

	public function create_comments_page () {
		global $wpdb;

		if (isset($_GET['action']) && isset($_GET['word'])) {
			$word = esc_attr($_GET['word']);
			if ('stop_word' == $_GET['action']) $this->_add_to_stop_words($word);
			else if ('kill_spam' == $_GET['action']) $this->_kill_spam($word);
			$goback = add_query_arg('success', 'true',  admin_url('edit-comments.php?page=avh-fdas_spam_filter'));
			wp_redirect($goback);
			die;
		}

		$deleted_count = $this->_get_rejected_count();
		$stop_words = $this->_stop_words_to_array();

		$spams = $wpdb->get_col("SELECT comment_content FROM {$wpdb->comments} WHERE comment_approved = 'spam'");
		$spams = is_array($spams) ? $spams : array();

		$hams = $wpdb->get_col("SELECT comment_content FROM {$wpdb->comments} WHERE comment_approved <> 'spam'");
		$hams = is_array($hams) ? $hams : array();

		$words = array();
		foreach($spams as $spam) {
			$spam = strtolower(strip_tags($spam));
			$spam_words = array_filter(array_map('trim', preg_split('/[\W]+/', $spam)));
			foreach ($spam_words as $swrd) @$words[$swrd]++;
		}
		foreach($hams as $ham) {
			$ham = strtolower(strip_tags($ham));
			$ham_words = array_filter(array_map('trim', preg_split('/[\W]+/', $ham)));
			foreach ($ham_words as $hwrd) unset($words[$hwrd]);
		}
		arsort($words);

		include (dirname(__FILE__) . '/avh-files/plugins/forms/spam_filter_comments.php');
	}

	public function process_comment ($comment) {
		if (class_exists('TanTanSpamFilter')) return false;
		$this->_check_stop_words($comment['comment_content']);
		$this->_check_link_count($comment['comment_content']);
		return $comment;
	}

	private function _add_to_stop_words ($word) {
		$stop_words = $this->_stop_words_to_array();
		$stop_words[] = $word;
		$this->_data['stop_words'] = join(', ', array_unique($stop_words));
		return update_site_option('avhfdas-spam_filter', $this->_data);
	}

	private function _kill_spam ($word) {
		global $wpdb;
		if (!$word) return false;
		$word = '%' . esc_sql($word) . '%';
		return $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam' AND comment_content LIKE '$word'");
	}

	private function _check_stop_words ($txt) {
		if (!$txt) return false;
		if (@$this->_data['no_stop_words']) return false;

		$stop_words = $this->_stop_words_to_array();
		if (!$stop_words) return false;

		$result = array();
		$txt = strip_tags($txt);
		foreach($stop_words as $word) {
			if (preg_match('/\b' . preg_quote($word) . '\b/iU', $txt)) $result[] = $word;
		}
		if (!$result) return true;

		$this->_track_rejected_comment();
		wp_die(
			sprintf(
				__('Sorry, your comment has been rejected because it contains one or more of the following words: <strong>%s</strong>.<br /><br />Please try posting your comment again, but without these words.', 'avh-fdas'),
				join(', ', $result)
			)
		);
	}

	private function _check_link_count ($txt) {
		if (!$txt) return false;
		if (@$this->_data['no_link_count']) return false;

		$allowed = @$this->_data['link_count'] ? $this->_data['link_count'] : 3;
		$matches = array();
		preg_match_all('!https?:\/\/!i', $txt, $matches);
		$count = count($matches[0]);
		if ($count <= $allowed) return true;

		$this->_track_rejected_comment();
		wp_die(
			__('Sorry, your comment has been rejected because it contained several links starting with with http:// - this is a measure to protect users from comment spam, we apologies for the inconvenience. <br /><br />Please click back and delete the http:// elements of your comments.<br /><br />For example: "http://edublogs.org" should simply be "edublogs.org".', 'avh-fdas')
		);
	}

	private function _track_rejected_comment () {
		$count = (int)$this->_get_rejected_count();
		$count += 1;
		update_option('avhfdas-spam_filter-count', $count);
	}

	private function _get_rejected_count () {
		$tcount = (int)get_option('tantan-spam-count');
		if ($tcount) { // Inherit and override TanTan options
			delete_option('tantan-spam-count');
		}
		$count = (int)get_option('avhfdas-spam_filter-count');
		return $tcount + $count;
	}

	private function _stop_words_to_array () {
		$stop_words = @$this->_data['stop_words'];
		$stop_words = $stop_words ? $stop_words : self::get_default_stop_words();
		$res = explode(',', $stop_words);
		return array_unique(array_filter(array_map('trim', $res)));
	}

	private function _add_hooks () {
		add_action('admin_init', array($this, 'register_settings'));
		add_action('network_admin_menu', array($this, 'create_menu_entry'), 35);

		add_action('admin_menu', array($this, 'create_comments_entry'));

		if (!@$this->_data['disable_spam_filter']) {
			add_action('preprocess_comment', array($this, 'process_comment'));
		}
	}
}

AVH_SpamFilter::serve();