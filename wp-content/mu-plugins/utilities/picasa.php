<?php 

/**
 * Method displays the albums for this user
 * 
 */
function picasa_display_albums( $post, $metabox )
{
	require_once (ABSPATH . WPINC . '/class-feed.php');

	$url = 'http://picasaweb.google.com/data/feed/api/user/'.urlencode(get_option('picasa_username')).'/';
	$feed = new SimplePie();
	
	$feed->set_feed_url( $url );
	$feed->set_stupidly_fast(true);
	$feed->enable_cache(false);
	$feed->set_cache_duration(0);
	$feed->set_file_class('WP_SimplePie_File');
	
	$feed->init();
	$feed->handle_content_type();
	
	if ($feed->error()) {
		echo $feed->error();
		return;
	}
	
	$maxitems = $feed->get_item_quantity(); 
	$items = $feed->get_items(0, $maxitems);
	
	$urls = get_post_meta($post->ID, 'picasa_sync');
	
	$i=0;
	echo '<ul style="width:100%;">';
	foreach ((array)$items as $item)
	{
		$i = $i>0 ?0 :++$i;
		
		//get image
		$media_group = $item->get_item_tags(SIMPLEPIE_NAMESPACE_MEDIARSS, 'group');
		$media_content = $media_group[0]['child'][SIMPLEPIE_NAMESPACE_MEDIARSS]['content'];
		$icon = $media_content[0]['attribs']['']['url'];
		
		$isActive = false;
		$link = $item->get_link() ?$item->get_link() :$item->get_permalink();
		if (in_array($link, $urls))
			$isActive = true;
		?>
		<li data="<?php echo urlencode($link) ?>" class="picasa_list_item <?php echo $isActive? 'picasa_active' :'' ?>" style="width:40%;margin-left:2%;margin-right:<?php echo $i ?'10%' :'0'; ?>;float:left;border:10px solid #ddd;">
			<h3><?php echo $item->get_title() ?></h3>
			<img src="<?php echo $icon ?>" style="width:100%;" />
		</li>
		<?php 
	}
	?>
	<div style="width:100%;clear:both;"></div></ul>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('.picasa_list_item').bind('click', function(e){
				if (jQuery(this).hasClass('picasa_active'))
				{
					jQuery(this).removeClass('picasa_active');
					jQuery.ajax({
						url: ajaxurl, 
						data: {
							action:'picasa_unsync',
							postid: <?php echo $post->ID ?>,
							url:jQuery(this).attr('data')
						},
						success:function(response){
							console.log(response);
						}
					});
				}
				else
				{
					jQuery(this).addClass('picasa_active');
					jQuery.ajax({
						url: ajaxurl,
						data: {
							action:'picasa_sync',
							postid: <?php echo $post->ID ?>,
							url:jQuery(this).attr('data')
						},
						success:function(response){
							console.log(response);
						}
					});
				}
			});
		});
	</script>
	<style type="text/css">ul li.picasa_active{border:10px solid #627db4!important;}</style>
	<?php 
}

/**
 * Simple recording of the albums
 * 
 */
add_action('wp_ajax_picasa_sync', 'picasa_sync');
function picasa_sync()
{
	update_post_meta($_REQUEST['postid'], 'picasa_sync', urldecode($_REQUEST['url']));
	die('picasa_sync');
}

add_action('wp_ajax_picasa_unsync', 'picasa_unsync');
function picasa_unsync()
{
	delete_post_meta($_REQUEST['postid'], 'picasa_sync', urldecode($_REQUEST['url']));
	die('picasa_unsync');
}

/**
 * Administrative Settings
 * 
 * Creating admin pages, is easy as that. There's a ton of work already completed for you, which will
 * allow you to offer seamless and stable administrative options pages to your users.
 */
$picasa = redrokk_admin_class::getInstance('picasa-admin', array(
	'page_title'	=> 'Picasa Synchronization',
	'menu_title'	=> 'Picasa Sync',
	'parent_menu'	=> 'media',
	'screen_icon'	=> WPMU_PLUGIN_URL.'/utilities/PicasaIcon.jpg',
));

/**
 * Administrative Metaboxes
 * 
 * Make quick use of creating new metaboxes by using the metabox class
 * 
 * @see https://gist.github.com/1880770
 */
redrokk_metabox_class::getInstance('picasa-username', array(
	'title'			=> 'Picasa Setup',
	'_object_types'	=> $picasa,
	'priority'		=> 'high',
	'_fields'		=> array(
		array(
			'name' 	=> 'Picasa Username',
			'id' 	=> 'picasa_username',
			'type' 	=> 'text',
			'class'	=> 'regular-text',
			'desc'	=> "<br/>Please enter your Picasa username. If you don't know it, then you can enter your Google Email",
		),
	)
));

//function picasa_albums
if (get_option('picasa_username', false)) {
	redrokk_metabox_class::getInstance('picasa-albums', array(
		'title'			=> 'Picasa Albums',
		'_object_types'	=> $picasa,
		'priority'		=> 'high',
		'callback'		=> 'picasa_display_albums'
	));
	$sync = redrokk_metabox_class::getInstance('picasa-sync', array(
		'title'			=> 'Synchronization',
		'_object_types'	=> $picasa,
		'context'		=> 'side',
		'priority'		=> 'default',
		'_fields'		=> array(
			array(
				'name' 	=> 'Sync Regularly',
				'id' 	=> 'picasa_sync_regularly',
				'type' 	=> 'checkbox',
				'options'	=> array(
					'true'	=> "Yes",
				),
			),
		)
	));
}

/**
 * Delcaring my new cron
 * 
 */
$args = array(
	'callback' => 'picasa_cron',
	'schedule' => 10, // minutes
);
if (get_option('picasa_sync_regularly', 'false') !== 'true') {
	
	// Saves once
	add_action('metabox-save-'.$sync->_id, 'picasa_cron');
	
	// kills all future cron events for this slug
	$args['stop'] = 'true'; 
}
redrokk_cron_class::getInstance('picasa-cron', $args);

function picasa_cron()
{
	$post = redrokk_admin_class::getInstance('picasa-admin')->getPost();
	$urls = get_post_meta($post->ID, 'picasa_sync');
	
	require_once (ABSPATH . WPINC . '/class-feed.php');
	
	foreach ((array)$urls as $url) {
		$feed = new SimplePie();
		
		$feed->set_feed_url( $url );
		$feed->set_stupidly_fast(true);
		$feed->enable_cache(false);
		$feed->set_cache_duration(0);
		$feed->set_file_class('WP_SimplePie_File');
		
		$feed->init();
		$feed->handle_content_type();
		
		if ($feed->error()) {
			echo $feed->error();
			continue;
		}
		
		$maxitems = $feed->get_item_quantity(); 
		$items = $feed->get_items(0, $maxitems);
		
		foreach ((array)$items as $item) {
			
			//get image
			$media_group = $item->get_item_tags(SIMPLEPIE_NAMESPACE_MEDIARSS, 'group');
			$media_content = $media_group[0]['child'][SIMPLEPIE_NAMESPACE_MEDIARSS]['content'];
			
			$url  = $media_content[0]['attribs']['']['url'];
			$link = $item->get_link() ?$item->get_link() :$item->get_permalink();
			$title = $item->get_title();
			
			if (picasa_does_file_exist( $url )) continue;
			
			$file_array = picasa_save_file( $url );
			if (is_wp_error($file_array))
				continue;
			
			$post_data = array();
			$post_data['post_title'] = $title;
			
			$id = media_handle_sideload($file_array, $post->ID, $post_data);
			add_post_meta($id, 'imageurl', $url);
		}
		
	}
}

/**
 * Method determines if the file already exists in the system
 * 
 * @param string $url
 * @return bool
 */
function picasa_does_file_exist( $url )
{
	$posts = get_posts(array ( 
		'post_type' 	=> 'attachment', 
		'meta_value' 	=> $url, 
	));
	
	return ($posts) ?true :false;
}

/**
 * 
 * @param string $url
 */
function picasa_save_file( $url )
{
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	
	if (!preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches))
		return false;
		
	$tmp = download_url( $url );
	
	if ( is_wp_error( $tmp ) ) 
		return $tmp;
	
	
	$file_array = array();
	$file_array['name'] = basename($matches[0]);
	$file_array['tmp_name'] = $tmp;
	
	return $file_array;
}



/**
 * Actions and Filters
 *
 * Register any and all actions here. Nothing should actually be called
 * directly, the entire system will be based on these actions and hooks.
 */
add_action( 'widgets_init', create_function( '', 'register_widget("Picasa_Widget");' ) );

/**
 * This is the class that you'll be working with. Duplicate this class as many times as you want. Make sure
 * to include an add_action call to each class, like the line above.
 *
 * @author byrd
 *
 */
class Picasa_Widget extends Empty_Widget_Abstract
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
		'name' => 'Picasa Album',

		// this description will display within the administrative widgets area
		// when a user is deciding which widget to use.
		'description' => 'Widget displays your synchronized picasa photos. Red Rokk',

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
		'thumbnail' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAANbY1E9YMgAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAEhSURBVHjaYvj//z8DOv7IwJwGxP/R8GZsalkYoKDRdpIxkJoJxFL1//9IfWIES81kQAAfEPE9gfEMkPLjXPD/GYjPhKYZREsC+c/4/v+ZBWSnM2ACkJozQIOk4AYgaYYBQoZIAvEmsAFAhb5ommEArBGfS4CuiAR5dCGaxC0gjqo/nHcWJgAyBBom6KAY5AVBfJqRDcHmCmRjTwA1WjKQCGCBOAukmdGip4EcA2Y1/P6VDtS8GciuJ9UARlBqgmoGJ5T/J0oY8WkAhvx+JO4FFqBmX5hmYgAwBTpic8EZWFoAuYCxnvE/EWYd+N/43xEWiMHQKCQWHADiJHgsAG19CKTcgPgssZqBtt+HewEjZOvBAeWAJvwWiC+DnI0sCBBgACgUlKfkR6d3AAAAAElFTkSuQmCC',
					
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
				'default' => 'Portfolio'
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
	function html($widget = array(), $params = array(), $sidebar = array())
	{
		$post = redrokk_admin_class::getInstance('picasa-admin')->getPost();
		$urls = get_post_meta($post->ID, 'picasa_sync');
		
		$attachments = get_posts(array(
			'post_type' => 'attachment',
			'numberposts' => 0,
			//'post_status' => 'inherit',
			'post_parent' => $post->ID
		));
		
		if (!$attachments) return;
		
		?>
		<div class="content-wrapper nosidebars table clearfix"><div class="content clearfix">
    		<div class="article-portfolio">
    	<?php 
		foreach ((array)$attachments as $attachment)
		{
			//print_r($attachment);
			?>
			<div class="article-block-portfolio" style="z-index: 983; ">
				<div class="image-med" style="z-index: 982; ">
					<div class="preload" style="z-index: 981; ">
					<a class="prettyPhoto[pp_gal]" rel="prettyPhoto[pp_gal]" href="<?php echo simpolio_image(array('type'=>'med','url'=>$attachment->guid)) ?>" style="">
						<span class="hover"></span>
						<span class="zoom" style="opacity: 0; "></span>
					</a>
					</div>
				</div>
			</div>
			<?php 
		}
		?>
		</div></div></div>
  		<?php 
	}
}