<?php 
/**
 * @Author	Anonymous
 * @link http://www.redrokk.com
 * @Package Wordpress
 * @SubPackage RedRokk Library QuickPlugin
 * @copyright  Copyright (C) 2011+ Redrokk Interactive Media
 * 
 * @version 2.0
 */

//security
defined('ABSPATH') or die('You\'re not supposed to be here.');

/**
 * 
 * @author byrd
 *
 */
abstract class QuickPlugin
{
	/**
	 * What to name this post type
	 * 
	 * @var string
	 */
	var $single = 'QuickPlugin';
	var $plural = 'QuickPlugins';
	
	/**
	 * Post Type String
	 * @var string
	 */
	var $post_type = 'QuickPlugin';
	
	/**
	 * Metabox fields
	 * 
	 * @var array
	 */
	var $fields = array();
	
	/**
	 * Columns to display on post type listing page
	 * 
	 * @var array
	 */
	var $columns = array();
	
	/**
	 * 
	 * @var string
	 */
	var $icon = '';
	var $icon32 = '';
	
	/**
	 * Whether or not this includes thumbnails
	 * 
	 * @var bool
	 */
	var $thumbnail = true;
	
	/**
	 * Constructor.
	 * 
	 */
	function __construct()
	{
		// Initializing
		$supports = array( 'title', 'editor' );
		if ($this->thumbnail) {
			$supports[] = 'thumbnail';
		}
		
		// Hooking
		add_filter('manage_'.$this->post_type.'_posts_columns' , array(&$this, '_add_columns'));
		add_filter('wdeb_theme_header_partial', array(&$this, 'plugin_header'));
		add_filter('easy_mode_menu', array(&$this, 'easy_mode_menu'));
		
		add_action('admin_head', array(&$this, 'plugin_header'));
		add_action('manage_'.$this->post_type.'_posts_custom_column' , array(&$this, '_custom_columns'), 10, 2 );
		
		if (method_exists($this, 'custom_columns'))
			add_action('manage_'.$this->post_type.'_posts_custom_column' , array(&$this, 'custom_columns'), 10, 2 );
		
		// Declaring
		$this->i = redrokk_post_class::getInstance($this->post_type, array(
			'menu_icon' => $this->icon,
			'_single' 	=> $this->single,
			'_plural' 	=> $this->plural,
			'supports' 	=> $supports,
		));
		
		if (!empty($this->fields)) {
			redrokk_metabox_class::getInstance("{$this->post_type}_details", array(
				'title'			=> "$this->single Details",
				'priority'		=> 'high',
				'_fields'		=> $this->fields,
				'_object_types'	=> $this->i,
				)
			);
		}
	}
	
	/**
	 * 
	 * @params array $column
	 * @params string $post_id
	 */
	function _custom_columns( $column, $post_id )
	{
		switch ( $column ) {
		case 'description':
			$p = get_post($post_id);
			echo crop($p->post_content, 200);
			break;
			
		case 'thumbnail':
			the_post_thumbnail( array(64,64) );
			break;
		}
	}
	
	/**
	 * 
	 * @params array $columns
	 */
	function _add_columns($columns) 
	{
	    unset($columns['author']);
	    unset($columns['date']);
	    unset($columns['tag']);
	   
	    $n = array();
	    foreach ($columns as $key => $column)
	    {
	    	if ($key == 'cb' && $this->thumbnail) {
	    		$n[$key] = $column;
	   			$n['thumbnail'] = '<span>Photo</span>';
	    	} elseif ($key == 'title') {
	    		$n[$key] = $column;
	    		foreach ((array)$this->columns as $k => $v)
	    			$n[$k] = "<span>$v</span>";
	    	}
	    	else
	    	{
	    		$n[$key] = $column;
	    	}
	    }
	    return $n;
	}
	
	/**
	 * 
	 * @params array $column
	 */
	function easy_mode_menu( $menu = array() )
	{
		$menu[] = array (
			'check_callback' 	=> false,
			'capability' 		=> false,
			'url' 				=> 'edit.php?post_type='.$this->post_type,
			'icon' 				=> $this->icon,
			'title' 			=> __($this->single),
			'help' 				=> __('Edit your '.strtolower($this->single).' list'),
		);
		return $menu;
	}
	
	/**
	 * 
	 * @params bool $content
	 */
	function plugin_header( $content = false ) 
	{
		global $post_type;
		if ($post_type !== $this->post_type && (!isset($_REQUEST['post_type']) || $_REQUEST['post_type'] != $this->post_type))
		?>
		<style type="text/css">
		#icon-edit.icon32.icon32-posts-<?php echo $this->post_type ?> { background:transparent url('<?php echo $this->icon32;?>') 0 0 no-repeat !important; }		
		</style>
		<?php 
		return $content;
	}
	
}