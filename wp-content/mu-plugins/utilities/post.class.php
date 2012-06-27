<?php 
/**
 * @Author	Anonymous
 * @link http://www.redrokk.com
 * @Package Wordpress
 * @SubPackage RedRokk Library
 * @copyright  Copyright (C) 2011+ Redrokk Interactive Media
 * 
 * @version 2.0
 */

//security
defined('ABSPATH') or die('You\'re not supposed to be here.');
 
/**
 * 
 * 
 * @author Anonymous
 * @example

	$gallery = redrokk_post_class::getInstance('gallery');
	$gallery->set('_single', 'Image');
	$gallery->set('_plural', 'Gallery Images');

 */
class redrokk_post_class
{
	var $_post_type;
	var $_single;
	var $_plural;
	
	/**
	 * (optional) Meta argument used to define default values for 
	 * publicly_queriable, show_ui, show_in_nav_menus and exclude_from_search.
	 * Default: false
	 * 
	 * 'false' - do not display a user-interface for this post type (show_ui=false), 
	 * post_type queries can not be performed from the front end (publicly_queryable=false), 
	 * exclude posts with this post type from search results (exclude_from_search=true), 
	 * hide post_type for selection in navigation menus (show_in_nav_menus=false)
	 * 
	 * 'true' - show_ui=true, publicly_queryable=true, exclude_from_search=false, 
	 * show_in_menu=true
	 * 
	 * @var boolean
	 */
	var $public = true;
	
	/**
	 * (optional) Whether post_type queries can be performed from the front end.
	 * 
	 * @var boolean
	 */
	var $publicly_queryable = true;
	
	/**
	 * (importance) Whether to exclude posts with this post type from search results.
	 * 
	 * @var boolean
	 */
	var $exclude_from_search;
	
	/**
	 * (optional) Whether to generate a default UI for managing this post type. Note that 
	 * _built-in post types, such as post and page, are intentionally set to false.
	 * Default: value of public argument
	 *  
	 * 'false' - do not display a user-interface for this post type
	 * 'true' - display a user-interface (admin panel) for this post type
	 *  
	 * @var boolean
	 */
	var $show_ui = true;
	
	/**
	 * (optional) Whether to show the post type in the admin menu and 
	 * where to show that menu. Note that show_ui must be true.
	 * 
	 * 'false' - do not display in the admin menu
	 * 'true' - display as a top level menu
	 * 'some string' - a top level page like 'tools.php' or 'edit.php?post_type=page'
	 * 
	 * Note: When using 'some string' to show as a submenu of a menu page created by 
	 * a plugin, this item will become the first submenu item, and replace the location 
	 * of the top level link. If this isn't desired, the plugin that creates the menu 
	 * page needs to set the add_action priority for admin_menu to 9 or lower.
	 * 
	 * @var boolean|string
	 */
	var $show_in_menu = true;
	
	/**
	 * (optional) The url to the icon to be used for this menu.
	 * Default: null - defaults to the posts icon
	 * 
	 * @var string
	 */
	var $menu_icon;
	
	/**
	 * (optional) The string to use to build the read, edit, 
	 * and delete capabilities. May be passed as an array to allow for alternative 
	 * plurals when using this argument as a base to construct the capabilities, 
	 * e.g. array('story', 'stories'). By default the capability_type is used as a 
	 * base to construct capabilities. It seems that `map_meta_cap` needs to be set 
	 * to true, to make this work.
	 * Default: "post"
	 * 
	 * @var string|array
	 */
	var $capability_type = 'post';
	
	/**
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type
	 * @var array
	 */
	var $capabilities = array();
	
	/**
	 * (optional) False to prevent queries, or string value of the query var to 
	 * use for this post type.
	 * 
	 * @var boolean|string
	 */
	var $query_var = true;
	
	/**
	 * (optional) Whether the post type is hierarchical. Allows Parent to be specified.
	 * 
	 * @var boolean
	 */
	var $hierarchical = false;
	
	/**
	 * (optional) The position in the menu order the post type should appear.
	 * Default: null - defaults to below Comments
	 * 
	 * 5 - below Posts
	 * 10 - below Media
	 * 15 - below Links
	 * 20 - below Pages
	 * 25 - below comments
	 * 60 - below first separator
	 * 65 - below Plugins
	 * 70 - below Users
	 * 75 - below Tools
	 * 80 - below Settings
	 * 100 - below second separator
	 * 
	 * @var integer
	 */
	var $menu_position;
	
	/**
	 * (optional) An alias for calling add_post_type_support() directly.
	 * Default: title and editor
	 * 
	 * 'title'
	 * 'editor' (content)
	 * 'author'
	 * 'thumbnail' (featured image, current theme must also support post-thumbnails)
	 * 'excerpt'
	 * 'trackbacks'
	 * 'custom-fields'
	 * 'comments' (also will see comment count balloon on edit screen)
	 * 'revisions' (will store revisions)
	 * 'page-attributes' (menu order, hierarchical must be true to show Parent option)
	 * 'post-formats' add post formats, @see http://codex.wordpress.org/Post_Formats
	 * 
	 * @var array
	 */
	var $supports = array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' );
	
	/**
	 * A short descriptive summary of what the post type is.
	 * 
	 * @var unknown_type
	 */
	var $description;
	
	/**
	 * Contains the help message to display to the administrator
	 * 
	 * @var string
	 */
	var $_help;
	var $_help_edit;
	
	/**
	 * The labels to display to the administrators
	 * 
	 * @var array
	 */
	var $_labels = array(
		'name' 				=> '%2$s',
		'singular_name' 	=> '%1$s',
		'add_new' 			=> 'Add New',
		'add_new_item' 		=> 'Add New %2$s',
		'edit_item' 		=> 'Edit %1$s',
		'new_item' 			=> 'New %1$s',
		'all_items' 		=> 'All %2$s',
		'view_item' 		=> 'View %1$s',
		'search_items' 		=> 'Search %2$s',
		'not_found' 		=> 'No %2$s found',
		'not_found_in_trash'=> 'No %2$s found in Trash', 
		'parent_item_colon' => '',
		'menu_name' 		=> '%2$s'
	);
	
	/**
	 * Contains the messages to display to the administrator upon
	 * confirmation of actions
	 * 
	 * @var array
	 */
	var $_messages = array(
		'', // Unused. Messages start at index 1.
		'%1$s updated. <a href="%2$s">View %1$s</a>',
		'Custom field updated.',
		'Custom field deleted.',
		'%1$s updated.',
		'%1$s restored to revision from %2$s',
		'%1$s published. <a href="%2$s">View %1$s</a>',
		'%1$s saved.',
		'%1$s submitted. <a target="_blank" href="%2$s">Preview %1$s</a>',
		'%1$s scheduled for: <strong>%2$s</strong>. <a target="_blank" href="%3$s">Preview %1$s</a>',
		'%1$s draft updated. <a target="_blank" href="%2$s">Preview %1$s</a>',
	);
	
	/**
	 * Method returns properly formed labels
	 * 
	 */
	function getLabels()
	{
		$labels = array();
		foreach ((array)$this->_labels as $k => $label) {
			$labels[$k] = sprintf( __($label), $this->_single, $this->_plural );
		}
		return $labels;
	}
	
	/**
	 * Method provides updates messages to notify the user about what they have just
	 * done to the custom post type.
	 * 
	 * @param array $messages
	 */
	function _update_messages( $messages )
	{
		global $post, $post_ID;
		
		$messages[$this->_post_type] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __($this->_messages[1]),
					$this->_single, 
					esc_url( get_permalink($post_ID) ) 
				),
			2 => __($this->_messages[2]),
			3 => __($this->_messages[3]),
			4 => sprintf( __($this->_messages[4]), $this->_single ),
			5 => isset($_GET['revision']) ? sprintf( __($this->_messages[5]), $this->_single, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __($this->_messages[6]), 
					$this->_single, 
					esc_url( get_permalink($post_ID) ) 
				),
			7 => sprintf( __($this->_messages[7]), $this->_single ),
			8 => sprintf( __($this->_messages[8]), 
					$this->_single, 
					esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) 
				),
			9 => sprintf( __($this->_messages[9]), 
					$this->_single, 
					date_i18n( __( 'M j, Y @ G:i' ), 
					strtotime( $post->post_date ) ), 
					esc_url( get_permalink($post_ID) ) 
				),
			10 => sprintf( __($this->_messages[10]), 
					$this->_single, 
					esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) )
				),
		);
		
		return $messages;
	}
	
	/**
	 * Method displays the help text in the drop down
	 * 
	 * @param string $contextual_help
	 * @param string $screen_id
	 * @param string $screen
	 */
	function _help_text( $contextual_help, $screen_id, $screen )
	{
		$url = 'http://www.redrokk.com';
		$urle = urlencode($url);
		
		$title = 'RedRokk Interactive Media';
		
		$description = 'Are you using RedRokk Interactive Media for your software development?';
		$desc = urlencode($description);
		
		ob_start(); 
		?>
		<style>
		.twc-hr {margin: 20px 0 10px;border: none;border-bottom: 1px dashed #CCC;}
		.twc-share {position:relative;float:right;width:200px;}
		.twc-avatar {margin-right:20px;position:relative;float:left;width:100px;}
		</style>
		<hr class="twc-hr"/>
		<div class="twc-share">
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $urle; ?>&amp;layout=box_count&amp;show_faces=false&amp;width=50&amp;action=like&amp;colorscheme=light&amp;height=65" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:65px;margin-bottom: -5px;" allowTransparency="true"></iframe>
			
			<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo $url; ?>" data-text="<?php echo $description; ?>" data-count="vertical">Tweet</a>
			<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			
			<a class="DiggThisButton DiggMedium" href="http://digg.com/submit?url=<?php echo $urle; ?>&bodytext=<?php echo $description; ?>">
				<img src="http://developers.diggstatic.com/sites/all/themes/about/img/digg-btn.jpg" alt="<?php echo $description; ?>" title="<?php echo $title; ?>" />
				<?php echo $title; ?>
			</a>
			<script type="text/javascript">
			(function() {
			var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
			s.type = 'text/javascript';
			s.async = true;
			s.src = 'http://widgets.digg.com/buttons.js';
			s1.parentNode.insertBefore(s, s1);
			})();
			</script>
		</div>
		
		<img class="twc-avatar" style="margin:10px 20px;" src="<?php echo $url; ?>/logo.png" />
		<h4 style="margin:0px;"><?php echo $title; ?></h4>
		<p><?php _e('<a href="'.$url.'">This Plugin</a> is provided by '.$title.'. Please support us, <br/>so that we can continue to support you.','twc');?></p>
		<?php 
		$_help = ob_get_clean();
		
		//$contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
		if ( $this->_post_type == $screen->id ) {
			$contextual_help = $this->_help.$_help;
		} 
		elseif ( 'edit-'.$this->_post_type == $screen->id ) {
			$contextual_help = $this->_help_edit.$_help;
		}
		return $contextual_help;
	}
	
	/**
	 * To get permalinks to work when you activate the plugin use the 
	 * following example, paying attention to how my_cpt_init is called 
	 * in the register_activation_hook callback
	 * 
	 */
	function _rewrite_flush()
	{
		// First, we "add" the custom post type via the above written function.
		// Note: "add" is written with quotes, as CPTs don't get added to the DB,
		// They are only referenced in the post_type column with a post entry, 
		// when you add a post of this CPT.
		$this->_register_post_type();
	
		// ATTENTION: This is *only* done during plugin activation hook in this example!
		// You should *NEVER EVER* do this on every page load!!
		flush_rewrite_rules();
	}
	
	/**
	 * Method registers this post type with WordPress
	 * 
	 */
	function _register_post_type()
	{
		$options = $this->getProperties();
		$options['labels'] = $this->getLabels();
		
		register_post_type( $this->_post_type, $options );
	}
	
	/**
	 * Constructor.
	 * 
	 */
	function __construct( $options = array() )
	{
		//initializing
		$this->setProperties($options);
		if (!$this->_single) {
			$this->_single = ucfirst($this->_post_type);
		}
		if (!$this->_plural) {
			$this->_plural = ucfirst($this->_post_type).'s';
		}
		
		//registration actions
		add_action( 'init', array($this, '_register_post_type') );
		add_filter( 'post_updated_messages', array($this, '_update_messages') );
		add_action( 'contextual_help', array($this, '_help_text'), 10, 3 );
		add_action( 'update_option_recently_activated', array($this, '_rewrite_flush') );
	}
	
	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTable/bind
	 * @since   11.1
	 */
	public function bind($src, $ignore = array())
	{
		// If the source value is not an array or object return false.
		if (!is_object($src) && !is_array($src))
		{
			trigger_error('Bind failed as the provided source is not an array.');
			return false;
		}

		// If the source value is an object, get its accessible properties.
		if (is_object($src))
		{
			$src = get_object_vars($src);
		}

		// If the ignore value is a string, explode it over spaces.
		if (!is_array($ignore))
		{
			$ignore = explode(' ', $ignore);
		}

		// Bind the source value, excluding the ignored fields.
		foreach ($this->getProperties() as $k => $v)
		{
			// Only process fields not in the ignore array.
			if (!in_array($k, $ignore))
			{
				if (isset($src[$k]))
				{
					$this->$k = $src[$k];
				}
			}
		}

		return true;
	}
	
	/**
	 * Set the object properties based on a named array/hash.
	 *
	 * @param   mixed  $properties  Either an associative array or another object.
	 *
	 * @return  boolean
	 *
	 * @since   11.1
	 *
	 * @see     set() 
	 */
	public function setProperties($properties)
	{
		if (is_array($properties) || is_object($properties))
		{
			foreach ((array) $properties as $k => $v)
			{
				// Use the set function which might be overridden.
				$this->set($k, $v);
			}
			return true;
		}

		return false;
	}
	
	/**
	 * Modifies a property of the object, creating it if it does not already exist.
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $value     The value of the property to set.
	 *
	 * @return  mixed  Previous value of the property.
	 *
	 * @since   11.1
	 */
	public function set($property, $value = null)
	{
		$previous = isset($this->$property) ? $this->$property : null;
		$this->$property = $value;
		return $previous;
	}
	
	/**
	 * Returns an associative array of object properties.
	 *
	 * @param   boolean  $public  If true, returns only the public properties.
	 *
	 * @return  array 
	 *
	 * @see     get()
	 */
	public function getProperties($public = true)
	{
		$vars = get_object_vars($this);
		if ($public)
		{
			foreach ($vars as $key => $value)
			{
				if ('_' == substr($key, 0, 1))
				{
					unset($vars[$key]);
				}
			}
		}

		return $vars;
	}
	
	/**
	 * 
	 * contains the current instance of this class
	 * @var object
	 */
	static $_instances = null;
	
	/**
	 * Method is called when we need to instantiate this class
	 * 
	 * @param array $options
	 */
	public static function getInstance( $_post_type, $options = array() )
	{
		if (!isset(self::$_instances[$_post_type]))
		{
			$options['_post_type'] = $_post_type;
			$class = get_class();
			self::$_instances[$_post_type] =& new $class($options);
		}
		return self::$_instances[$_post_type];
	}
}