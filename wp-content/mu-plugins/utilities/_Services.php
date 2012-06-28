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
class Services extends QuickPlugin
{
	/**
	 * What to name this post type
	 * 
	 * @var string
	 */
	var $single = 'Service';
	var $plural = 'Services';
	
	/**
	 * Post Type String
	 * @var string
	 */
	var $post_type = 'services';
	
	/**
	 * Metabox fields
	 * 
	 * @var array
	 */
	var $fields = array(
	/*
		array(
			'name' 	=> 'Job Title',
			'id' 	=> 'jobtitle',
			'type' 	=> 'text',
		)*/
	);
	
	/**
	 * Columns to display on post type listing page
	 * 
	 * @var array
	 */
	var $columns = array(
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
		$this->icon = WPMU_PLUGIN_URL.'/utilities/services_icon.jpg';
		$this->icon32 = WPMU_PLUGIN_URL.'/utilities/home-services.jpg';
		
		parent::__construct();
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
add_action( 'widgets_init', create_function( '', 'register_widget("Services_Widget");' ) );

/**
 * This is the class that you'll be working with. Duplicate this class as many times as you want. Make sure
 * to include an add_action call to each class, like the line above.
 *
 * @author byrd
 *
 */
class Services_Widget extends Empty_Widget_Abstract
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
		'name' => 'Services Listings',

		// this description will display within the administrative widgets area
		// when a user is deciding which widget to use.
		'description' => 'This widget is designed to display your services list. Red Rokk',

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
		'thumbnail' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD//gAxSW1hZ2UgUmVzaXplZCBhdCBodHRwOi8vd3d3LnNocmlua3BpY3R1cmVzLmNvbQr/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCAAUABQDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9IfFvj6y0T7Rbx3QTUrd0P2dkyZFIzx7e/qKo+LviZZeFfsM731vIJlR3swymRUYHawGcgEgjJ71wn7Vvxd8DfCHwbeX+vx2d34hmtHSxtG/17g5UN8vzBdxwMcliFXk15V8DNHdPFHhXx/4/8F6noXiS40aWFI7mZitrAzANJLEDjO1VzkbkVgD7eNjsxeX1aXtYfupaOS+zLpddntfo7XPQw2E+tQn7OXvx15e662812Pr7Rtbtdd0u1v7Vw8FxGsiHvgjPP50UzRdLsdJ02G309AloBuQBi2Qec5OSaK9hST1TPPOP+Jvwh8I/Em/8O6t4i0W31LUfDt4uoabcSr80Ey5AP+0vOdrZGQDjIFR6jo0Xi4Cz1aWW7t1cOoJClT7MoBHHB55BINFFZ1qcKtOUKiTTWqeqNKc5U5xlB2afQ7uziW3tYoo1CxxqFVQOgHQUUUVaSikkZvV3Z//Z',
				
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
				'default' => 'Service Listing'
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
		
		?>
		<div class="clear"><br/><br/></div>
		<?php 
		
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
new Services();

