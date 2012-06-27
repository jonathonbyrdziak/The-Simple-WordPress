<?php 
/**
 * 
 * 
 */

/**
 * 
 * @author byrd
 *
 */
class Employees extends QuickPlugin
{
	/**
	 * What to name this post type
	 * 
	 * @var string
	 */
	var $single = 'Employee';
	var $plural = 'Employees';
	
	/**
	 * Post Type String
	 * @var string
	 */
	var $post_type = 'employee';
	
	/**
	 * Metabox fields
	 * 
	 * @var array
	 */
	var $fields = array(
		array(
			'name' 	=> 'Job Title',
			'id' 	=> 'jobtitle',
			'type' 	=> 'text',
		)
	);
	
	/**
	 * Columns to display on post type listing page
	 * 
	 * @var array
	 */
	var $columns = array(
		'jobtitle' 		=> 'Job Title',
		'description'	=> 'Description'
	);
	
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
		$this->icon = WPMU_PLUGIN_URL.'/utilities/emp.png';
		$this->icon32 = WPMU_PLUGIN_URL.'/utilities/emp32.gif';
		
		parent::__construct();
		
		// Hooking
		add_filter('manage_'.$this->post_type.'_posts_columns' , array(&$this, 'add_columns'));
		
	}
	
	/**
	 * 
	 * @params array $columns
	 */
	function add_columns($columns) 
	{
		$n = array();
	    foreach ($columns as $key => $column)
	    {
	    	if ($key == 'title') {
	    		$n[$key] = 'Full Name';
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
	 * @params string $post_id
	 */
	function custom_columns( $column, $post_id )
	{
		switch ( $column ) {
		case 'jobtitle':
			echo get_post_meta($post_id, 'jobtitle', true);
			break;
		}
	}
	
}

/**
 * Actions and Filters
 *
 * Register any and all actions here. Nothing should actually be called
 * directly, the entire system will be based on these actions and hooks.
 */
add_action( 'widgets_init', create_function( '', 'register_widget("Employee_Widget");' ) );

/**
 * This is the class that you'll be working with. Duplicate this class as many times as you want. Make sure
 * to include an add_action call to each class, like the line above.
 *
 * @author byrd
 *
 */
class Employee_Widget extends Empty_Widget_Abstract
{
	/**
	 * Widget settings
	 *
	 * Simply use the following field examples to create the WordPress Widget options that
	 * will display to administrators. These options can then be found in the $params
	 * variable within the widget method.
	 *
	 *
	 */
	protected $widget = array(
		// you can give it a name here, otherwise it will default
		// to the classes name. BTW, you should change the class
		// name each time you create a new widget. Just use find
		// and replace!
		'name' => 'Employee Listings',

		// this description will display within the administrative widgets area
		// when a user is deciding which widget to use.
		'description' => 'This widget is designed to display your employee list. Red Rokk',

		// determines whether or not to use the sidebar _before and _after html
		'do_wrapper' => true,

		// determines whether or not to display the widgets title on the frontend
		'do_title'	=> true,

		// string : if you set a filename here, it will be loaded as the view
		// when using a file the following array will be given to the file :
		// array('widget'=>array(),'params'=>array(),'sidebar'=>array(),
		// alternatively, you can return an html string here that will be used
		'view' => false,
	
		// If you desire to change the size of the widget administrative options
		// area
		'width'	=> 650,
		'height' => 450,
	
		// Shortcode button row
		'buttonrow' => 4,
	
		// The image to use as a representation of your widget.
		// Whatever you place here will be used as the img src
		// so we have opted to use a basencoded image.
		'thumbnail' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAADAElEQVR42rWUfyzUYRzHz64MjaaQptMMFfmxoUI3v4bKxq44d52l0bmOonIt5NoSnTqU34mMVEyxVa65zGShlea3ToVqN/NjaFFtNc27nucPf9RJ0zzbZ5/n2Xd7Pe/n/X3eD4Ox0mNq9D06Wx9gqK8b/w2rK4nF9+kkzH2T40O9LQpjjKCsylwe+FldIRoqAzHaHUKBpLfdMEaWyGJ5wMZSV3wdOYb8k2Yg85wYB7RdC4fs4JoFoKJeCWmyFHl5+Utv0lfnSFVVJrmiKsUXXfckmGiJR3uBJ4bVo1D1qxAljACPx4OLszNOx0v+Du2p3gQCHVLEY7rnMqZepkGtEGKgzB8isQjrjU0pqKS4BBzOAWgxGDC3tF4c+viSFrpKmVDKd2JEEU1hQ7cC8TzdEjr6JtixZSPiwn3g5xcEH98gaOvqUeiiwJokLbRfZ6I81hAtuWwM3uFAVeRO13wfZ2oDUU3AFiYGSI/ajeD9BtQOjUByPQrEq0GUEogi2RbNRUcxoO5FdeI2jDfEUSu+vM2lYLIm/vYPDC2uknvGA+LjpjgrC4H6tQqzM5/wcXYG8oR1GKvlY7w1EZMvztGfNXY/gm66qEKvMhdcmYtE2mc+RMO+VBnmAUn1CRjLdFGeYIZ3d7kYrhHQTryV8Ow1w8YnJygkfiSA9sODnrAr3owMZRZWFTOht28tLK0sUf8L2pu5nXbiK8vcRjOQqCGQiDceELxig9vhBvenVjDKNoR2BhMPq7txUVYMkibiJ4no3oAQBAYJ6BXSCN2aZg0z+YaFIsfUZ+siK6MCjxp70Nw8CIEgDDM/5qlvwkgJDoXFwMt7Hy6kpv4J7e/oQ0XFTUhlybRSclIQGhyN2pq2hQrmCzEHIEkqR7Q4EVzuEbDZvrCxcaBJWjKO51PyqMKr2bdpkbkZy4qCSJFjOzm5QltbR7PK3wfJrZ29E1Xh5emPPX4cuO3yhqOjG1gsc1rkO4njP79CTU1P6AsTG3cKfF4o9ZFkmygir05nRydW9MX/CTgcIg5yWWrXAAAAAElFTkSuQmCC',
			
		/* The field options that you have available to you. Please
		 * contribute additional field options if you create any.
		 *
		 */
		'fields' => array(
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'default' => 'Employee Listing'
			),
			array(
			    'name'    => 'List Style',
				'desc' => '',
			    'id'      => 'liststyle',
			    'type'    => 'select',
			    'options' => array( 
				    'thumbnail' => 'List With Thumbnail', 
				    'list' => 'Simple List', 
				    'grid' => 'Grid'
				)
			),
			
			array(
					'name' 		=> 'Thumbnail Size',
					'desc' 		=> '',
					'id' 		=> 'size',
					'type'		=> 'text',
					'desc' 		=> 'Must be an integer',
					'default'	=> 92
			),
		)
	);

	/**
	 * Widget HTML
	 *
	 * If you want to have an all inclusive single widget file, you can do so by
	 * dumping your css styles with base_encoded images along with all of your
	 * html string, right into this method.
	 *
	 * @param array $widget
	 * @param array $params
	 * @param array $sidebar
	 */
	function html($widget, $params, $sidebar)
	{
		global $post;
		$emp = new WP_Query(array(
			'post_type'		=> 'employee',
			'post_status'	=> 'publish',
		));
		if (!$emp->have_posts()) return;
		
		while ($emp->have_posts()): $emp->the_post();
			switch ($params['liststyle']) :
				default:
				case 'thumbnail':
				?>
				<div id="entry-id-<?php echo $post->ID; ?>" class="cn-entry">
					<div class="alignleft"><?php the_post_thumbnail( array($params['size'],$params['size']) ); ?></div>
					<h3 class="cn-accordion-item" id="cn-accordion-item-<?php echo $post->ID; ?>" style="clear:none;"><?php the_title(); ?></h3>
					<h4 class="title" style="clear:none;"><?php echo get_post_meta(get_the_ID(), 'jobtitle', true) ?></h4>
					<div class="cn-excerpt"><?php the_content(); ?></div>
				</div>
				<?php 
				break;
				case 'list':
				?>
				<div id="entry-id-<?php echo $post->ID; ?>" class="cn-entry">
					<h3 class="cn-accordion-item" id="cn-accordion-item-<?php echo $post->ID; ?>"><?php the_title(); ?></h3>
					<h4 class="title"><?php echo get_post_meta(get_the_ID(), 'jobtitle', true) ?></h4>
					<div class="cn-excerpt"><?php the_content(); ?></div>
				</div>
				<?php 
				break; 
				case 'grid':
				?>
				<div id="entry-id-<?php echo $post->ID; ?>" class="alignleft">
					<div><?php the_post_thumbnail( array($params['size'],$params['size']) ); ?></div>
					<h3 class="cn-accordion-item" id="cn-accordion-item-<?php echo $post->ID; ?>"><?php the_title(); ?></h3>
					<h4 class="title"><?php echo get_post_meta(get_the_ID(), 'jobtitle', true) ?></h4>
				</div>
				<div class="clear"></div>
				<?php 
				break;
			endswitch;
		endwhile;
	}
}
new Employees();

